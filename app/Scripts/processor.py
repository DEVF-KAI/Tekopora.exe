import sys
import json
import requests
import mysql.connector
import re

def buscar_en_base_de_datos(mensaje):
    conn = None
    try:
        config = {'host': '127.0.0.1', 'user': 'root', 'password': '', 'database': 'tekopora_db'}
        conn = mysql.connector.connect(**config)
        cursor = conn.cursor(dictionary=True, buffered=True)

        # 1. ¿El mensaje contiene algo que parece un código?
        match = re.search(r'([A-Z]+-[A-Z0-9-]+)', mensaje.upper())
        
        if match:
            criterio = match.group(1)
            sql = """
                SELECT p.titulo, p.contenido, m.nombreMunicipio, multi.urlArchivo
                FROM publicacion p
                JOIN municipio m ON p.idMunicipio_FK = m.idMunicipio
                LEFT JOIN multimedia multi ON p.idPublicacion = multi.idPublicacion_FK
                WHERE p.codigoPublicacion = %s LIMIT 1
            """
            cursor.execute(sql, (criterio,))
        else:
            # 2. BÚSQUEDA AUTOMÁTICA MEJORADA
            # Tomamos las palabras más importantes (quitamos "de", "el", "del")
            palabras = [p for p in mensaje.split() if len(p) > 2]
            criterio_busqueda = f"%{palabras[-1]}%" if palabras else f"%{mensaje}%"

            sql = """
                SELECT p.titulo, p.contenido, m.nombreMunicipio, multi.urlArchivo
                FROM publicacion p
                JOIN municipio m ON p.idMunicipio_FK = m.idMunicipio
                LEFT JOIN multimedia multi ON p.idPublicacion = multi.idPublicacion_FK
                WHERE p.titulo LIKE %s OR p.contenido LIKE %s LIMIT 1
            """
            # Buscamos usando la palabra clave más fuerte
            cursor.execute(sql, (criterio_busqueda, criterio_busqueda))

        resultado = cursor.fetchone()
        cursor.close()
        return resultado
    except Exception as e:
        # Ahora sí veremos si hay un error de conexión
        return {"error": str(e)}
    finally:
        if conn and conn.is_connected():
            conn.close()

def consultar_n8n(mensaje_final):
    url = "http://127.0.0.1:5678/webhook/chat-tekopora"
    payload = {"chatInput": mensaje_final}
    try:
        response = requests.post(url, json=payload, timeout=60)
        if response.status_code != 200:
            return f"Error de n8n: {response.status_code}"
        try:
            data = response.json()
            if isinstance(data, list) and len(data) > 0:
                item = data[0]
                return item.get('output', item.get('text', str(item)))
            if isinstance(data, dict):
                return data.get('output', data.get('text', str(data)))
            return response.text.strip()
        except:
            return response.text.strip()
    except Exception as e:
        return f"Error de conexión: {str(e)}"

def main():
    sys.stdout.reconfigure(encoding='utf-8')
    
    if len(sys.argv) > 1:
        entrada_usuario = sys.argv[1]
    else:
        entrada_usuario = "hola"

    contexto = buscar_en_base_de_datos(entrada_usuario)

    # --- EN TU ARCHIVO processor.py ---
    if contexto and "titulo" in contexto:
    # Si hay datos, los empaquetamos como una instrucción obligatoria
        url_img = contexto.get('urlArchivo') if contexto.get('urlArchivo') else "None"
        mensaje_final = (
        f"### CONTEXTO OFICIAL TEKOPORÃ ###\n"
        f"NOMBRE: {contexto['titulo']}\n"
        f"DESCRIPCIÓN: {contexto['contenido']}\n"
        f"MUNICIPIO: {contexto['nombreMunicipio']}\n"
        f"URL_IMAGEN: {url_img}\n"
        f"### FIN DEL CONTEXTO ###\n"
        f"Instrucción: Usa los datos anteriores para responder a: {entrada_usuario}"
    )
    else:
    # Si no hay nada, activamos la cultura general
        mensaje_final = f"CULTURA_GENERAL: No hay datos en la DB. Responde brevemente sobre: {entrada_usuario}"

    print(consultar_n8n(mensaje_final))
if __name__ == "__main__":
    main()