document.addEventListener("DOMContentLoaded", function() {
    
    // ==========================================
    // 1. LÓGICA DEL MICRÓFONO Y TRANSCRIPCIÓN
    // ==========================================
    const btnMicro = document.getElementById('btn-micro');
    const iconMicro = document.getElementById('micro-icon');
    const inputUsuario = document.getElementById('user-input');
    const inputVocalizar = document.getElementById('input-vocalizar');
    const formChat = document.getElementById('form-chat');

    let recognition;

    // Verificamos si el navegador soporta el dictado por voz
    if ('webkitSpeechRecognition' in window || 'SpeechRecognition' in window) {
        const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
        recognition = new SpeechRecognition();
        recognition.lang = 'es-MX'; // Idioma base para la transcripción
        recognition.continuous = false;
        recognition.interimResults = false;

        recognition.onstart = () => {
            iconMicro.classList.remove('fa-microphone');
            iconMicro.classList.add('fa-microphone-slash', 'text-danger');
            inputUsuario.placeholder = "Escuchando...";
        };

        recognition.onresult = (event) => {
            const transcript = event.results[0][0].transcript;
            inputUsuario.value = transcript; // Transcribe al input
            
            // INDICAMOS AL SISTEMA QUE DEBE RESPONDER CON VOZ
            if(inputVocalizar) inputVocalizar.value = "1";
            
            // Enviamos la pregunta automáticamente
            formChat.submit();
        };

        recognition.onerror = (event) => {
            console.error("Error en el micrófono:", event.error);
            iconMicro.classList.add('fa-microphone');
            iconMicro.classList.remove('fa-microphone-slash', 'text-danger');
            inputUsuario.placeholder = "Inicia transmisión de datos...";
        };

        recognition.onend = () => {
            iconMicro.classList.add('fa-microphone');
            iconMicro.classList.remove('fa-microphone-slash', 'text-danger');
        };
    } else {
        console.warn("Tu navegador no soporta reconocimiento de voz.");
        if (btnMicro) btnMicro.style.display = 'none';
    }

    if (btnMicro) {
        btnMicro.addEventListener('click', () => {
            if (recognition) recognition.start();
        });
    }

    // Si el usuario empieza a escribir manualmente, apagamos la voz
    if (inputUsuario) {
        inputUsuario.addEventListener('input', () => {
            if (inputVocalizar) inputVocalizar.value = "0";
        });
    }

    // ==========================================
    // 2. AUTO-SCROLL Y DETECCIÓN DE VOZ AL CARGAR
    // ==========================================
    const chatHistory = document.getElementById("chat-history");
    if(chatHistory) chatHistory.scrollTop = chatHistory.scrollHeight;

    const respuesta = document.getElementById('contenedor-respuesta');
    // Solo habla si el atributo data-vocalizar es true
    if (respuesta && respuesta.dataset.vocalizar === 'true' && respuesta.innerText.trim() !== "") {
        setTimeout(() => {
            hablar(respuesta.innerText);
        }, 500);
    }
});

// ==========================================
// 3. SÍNTESIS DE VOZ (Acento Neutro Obligatorio)
// ==========================================
function hablar(mensaje) {
    const temporal = document.createElement("div");
    temporal.innerHTML = mensaje;
    const textoLimpio = temporal.textContent || temporal.innerText || "";

    if (!textoLimpio) return;

    const síntesis = window.speechSynthesis;
    síntesis.cancel(); // Callamos a la IA si estaba diciendo otra cosa

    const locución = new SpeechSynthesisUtterance(textoLimpio);
    locución.rate = 1.0; 
    locución.pitch = 1.0;

    const asignarVozYHablar = () => {
        const voces = síntesis.getVoices();
        
        // 1. Buscamos voz Natural en Español Neutro (México o España), IGNORANDO Argentina
        let vozPremium = voces.find(voz => voz.name.includes('Natural') && (voz.lang.includes('es-MX') || voz.lang.includes('es-ES')));
        
        // 2. Plan B: Voz de Google neutra
        if (!vozPremium) vozPremium = voces.find(voz => voz.name.includes('Google') && voz.lang.startsWith('es'));
        
        // 3. Plan C: Cualquier voz en español que NO sea argentina ('es-AR')
        if (!vozPremium) vozPremium = voces.find(voz => voz.lang.startsWith('es') && !voz.lang.includes('es-AR'));

        if (vozPremium) {
            locución.voice = vozPremium;
            console.log("🗣️ IA hablando con acento neutro:", vozPremium.name);
        } else {
            locución.lang = 'es-MX'; // Fallback forzado a neutro
        }

        síntesis.speak(locución);
    };

    if (síntesis.getVoices().length > 0) {
        asignarVozYHablar();
    } else {
        síntesis.onvoiceschanged = () => {
            asignarVozYHablar();
            síntesis.onvoiceschanged = null; 
        };
    }
}