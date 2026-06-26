<a href="chatboy" 
   id="btn-abrir-chat" 
   style="position: fixed; bottom: 20px; right: 20px; background-color: #217F82; color: white; padding: 12px 20px; border-radius: 50px; text-decoration: none; z-index: 99999; display: flex; align-items: center; font-weight: bold;">
    <i class="fas fa-comment-dots" style="margin-right: 10px;"></i>
    Hablar con WYRM
</a>

<link href="https://cdn.jsdelivr.net/npm/@n8n/chat/style.css" rel="stylesheet" />
<div id="n8n-chat"></div>

<script>
    // ESTA FUNCIÓN VA POR FUERA PARA QUE EL ONCLICK SIEMPRE LA ENCUENTRE
    window.chatInstance = null;// Variable global para almacenar la instancia del chat

    function triggerChat() {// Función para abrir el chat, se llama desde el botón
        if (window.chatInstance) {// Si la instancia ya está lista, la abrimos
            window.chatInstance.toggle();
        } else {// Si no, mostramos un mensaje de alerta (esto es temporal, se puede mejorar)
            alert("AHORA TOCA PONER EL N8N ACA PI PI PI");
            console.log("Esperando instancia de n8n...");
        }
    }
</script>

<script type="module">// Cargamos el módulo de n8n para crear el chat, esto se hace de forma asíncrona para no bloquear la carga de la página
    import { createChat } from 'https://cdn.jsdelivr.net/npm/@n8n/chat/index.current.js';

    // Intentamos cargar n8n sin bloquear nada
    createChat({// Configuración del chat, aquí se pueden agregar más opciones como estilos personalizados, mensajes de bienvenida, etc.    
        webhookUrl: 'http://localhost:5678/webhook-test/asistente',
        mode: 'window',
        showWelcomeScreen: true,
        i18n: { en: { title: 'Asistente WYRM' } }
    }).then(res => {
        window.chatInstance = res;
    }).catch(err => {
        console.warn("n8n no disponible.");
    });
</script>

<style>
    /* Ocultamos la burbuja que n8n crea por defecto */
    .n8n-chat-button { display: none !important; }
</style>