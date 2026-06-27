import sys
import json
import requests
import mysql.connector
import re

# Configuración de salida para evitar errores de codificación en Windows
sys.stdout.reconfigure(encoding='utf-8')

def buscar_en_base_de_datos(mensaje):
    conn = None
    try:
        config = {'host': 'db', 'user': 'root', 'password': '', 'database': 'tekopora_db'}
        conn = mysql.connector.connect(**config)
        cursor = conn.cursor(dictionary=True, buffered=True)

        # 1. Búsqueda por código (Ej: OBRA-123)
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
            # 2. Búsqueda por palabras clave
            palabras_ignoradas = {"quiero", "saber", "sobre", "hola", "dime", "informacion", "que", "como", "donde"}
            palabras = [p for p in re.sub(r'[^\w\s]', '', mensaje).split() if len(p) > 2 and p.lower() not in palabras_ignoradas]
            
            if not palabras:
                return None 

            criterio_busqueda = f"%{palabras[-1]}%"
            sql = """
                SELECT p.titulo, p.contenido, m.nombreMunicipio, multi.urlArchivo
                FROM publicacion p
                JOIN municipio m ON p.idMunicipio_FK = m.idMunicipio
                LEFT JOIN multimedia multi ON p.idPublicacion = multi.idPublicacion_FK
                WHERE p.titulo LIKE %s OR p.contenido LIKE %s LIMIT 1
            """
            cursor.execute(sql, (criterio_busqueda, criterio_busqueda))

        resultado = cursor.fetchone()
        cursor.close()
        return resultado
    except Exception:
        return None
    finally:
        if conn and conn.is_connected():
            conn.close()

def consultar_n8n(mensaje_final):
    # Usamos n8n para evitar problemas de resolución de nombres en Docker
    url = "http://n8n:5678/webhook/chat-tekopora"
    payload = {"chatInput": mensaje_final}
    
    try:
        response = requests.post(url, json=payload, timeout=25) 
        
        if response.status_code != 200:
            return f"Error HTTP {response.status_code} en n8n."
            
        try:
            data = response.json()

            if isinstance(data, list):
                if len(data) > 0:
                    data = data[0]
                else:
                    return "n8n devolvió una lista vacía."

            return data.get('output', data.get('text', str(data)))
            
        except (ValueError, AttributeError):
            return response.text
            
    except Exception as e:
        return f"Error de conexión: {str(e)}"

def main():
    if len(sys.argv) > 1:
        entrada_usuario = sys.argv[1]
    else:
        entrada_usuario = "hola"

    entrada_limpia = re.sub(r'[^\w\s]', '', entrada_usuario.lower().strip())
    saludos = ["hola", "buenas", "buenos dias", "hey", "saludos"]
    
    if entrada_limpia in saludos:
        print(consultar_n8n(entrada_usuario))
        return 

    contexto = buscar_en_base_de_datos(entrada_usuario)

    if contexto:
        url_img_db = contexto.get('urlArchivo')
        
        # 🟢 CORRECCIÓN PARA DOCKER: Eliminamos la carpeta /Tekopora_F/
        if url_img_db and not str(url_img_db).startswith('http'):
            url_img = f"http://localhost/public/{url_img_db}"
        else:
            url_img = url_img_db if url_img_db else "None"

        mensaje_final = (
            f"### CONTEXTO OFICIAL ###\n"
            f"NOMBRE: {contexto['titulo']}\n"
            f"DESCRIPCIÓN: {contexto['contenido']}\n"
            f"MUNICIPIO: {contexto['nombreMunicipio']}\n"
            f"URL_IMAGEN: {url_img}\n"
            f"### FIN DEL CONTEXTO ###\n\n"
            f"Consulta: {entrada_usuario}"
        )
    else:
        mensaje_final = entrada_usuario

    print(consultar_n8n(mensaje_final))

if __name__ == "__main__":
    main()