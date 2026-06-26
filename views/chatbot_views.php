<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tekoporã - IA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .bg-tekopora {
            background-color: #006b63;
        }

        .text-tekopora {
            color: #006b63;
        }

        /* Estilo para que las imágenes que vengan de la IA se vean bien */
        .respuesta-ia img {
            max-width: 100%;
            height: auto;
            border-radius: 1rem;
            margin-top: 1rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            border: 2px solid #e2e8f0;
        }

        /* Animación para el pulso del micrófono */
        .listening {
            animation: pulse-red 1.5s infinite;
        }

        @keyframes pulse-red {
            0% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7); }
            70% { box-shadow: 0 0 0 10px rgba(239, 68, 68, 0); }
            100% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); }
        }
    </style>
</head>

<body class="bg-gray-100 font-sans min-h-screen flex flex-col">

    <nav class="bg-tekopora p-4 shadow-lg">
        <div class="container mx-auto flex justify-between items-center">
            <span class="text-white font-bold uppercase italic tracking-wider">Tekoporã Bolivia</span>
            <a href="./" class="text-white text-sm hover:underline flex items-center">
                <i class="fas fa-home mr-2"></i> Volver al inicio
            </a>
        </div>
    </nav>

    <div class="container mx-auto mt-6 p-4 max-w-4xl flex-grow">
        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden border border-gray-200">

            <!-- Cabecera de la consulta actual -->
            <div class="p-6 bg-emerald-50 border-b border-emerald-100">
                <p class="text-xs font-bold text-emerald-600 uppercase tracking-widest mb-1">Tu Consulta:</p>
                <h1 class="text-xl font-medium text-gray-700">
                    <i class="fas fa-quote-left text-emerald-300 mr-2"></i>
                    <?php echo htmlspecialchars($pregunta); ?>
                </h1>
            </div>

            <!-- Cuerpo de la Respuesta de la IA -->
            <div class="p-8 md:p-12">
                <div class="flex items-start gap-6">
                    <div class="w-14 h-14 bg-tekopora rounded-2xl flex items-center justify-center text-white shadow-lg flex-shrink-0">
                        <i class="fas fa-robot text-2xl"></i>
                    </div>

                    <div class="flex-grow">
                        <div id="contenedor-respuesta" class="respuesta-ia text-gray-700 text-lg leading-relaxed">
                            <?php
                            // Imprime la respuesta procesada por el Controller (incluyendo el <img> si existe)
                            echo $respuesta_ia;
                            ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Formulario de Chat con Micrófono -->
            <div class="p-6 bg-gray-50 border-t border-gray-100">
                <form action="chatboy" method="GET" id="form-chat" class="flex flex-col md:flex-row gap-3">
                    <div class="relative flex-grow">
                        <i class="fas fa-comment-dots absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        
                        <input type="text" name="pregunta" id="user-input" placeholder="¿Quieres saber más sobre algún lugar?" required
                            class="w-full pl-12 pr-4 py-4 rounded-2xl border-2 border-gray-200 focus:border-emerald-500 focus:ring-0 outline-none transition-all">
                    </div>
                    
                    <button type="submit"
                        class="bg-tekopora hover:bg-emerald-700 text-white px-10 py-4 rounded-2xl font-bold shadow-lg transition-all transform hover:scale-105 active:scale-95">
                        CONSULTAR
                    </button>

                    <!-- Botón de Micrófono Mejorado -->
                    <button type="button" id="btn-micro" 
                        class="p-4 rounded-2xl bg-white border-2 border-gray-200 hover:border-red-400 hover:text-red-500 text-gray-500 transition-all duration-300 shadow-sm flex items-center justify-center">
                        <i id="micro-icon" class="fas fa-microphone text-xl"></i>
                    </button>
                </form>
            </div>

        </div>
    </div>

    <!-- Lógica de Voz (STT y TTS) -->
    <script>
        const recognition = new (window.SpeechRecognition || window.webkitSpeechRecognition)();
        recognition.lang = 'es-BO'; 
        recognition.interimResults = false;

        const btnMicro = document.getElementById('btn-micro');
        const microIcon = document.getElementById('micro-icon');
        const inputChat = document.getElementById('user-input');
        const formChat = document.getElementById('form-chat');

        // --- FUNCIÓN PARA ESCUCHAR ---
        btnMicro.addEventListener('click', () => {
            try {
                recognition.start();
                microIcon.classList.replace('fa-microphone', 'fa-circle');
                btnMicro.classList.add('listening', 'text-red-500', 'border-red-500');
            } catch (e) {
                console.log("Ya está escuchando...");
            }
        });

        recognition.onresult = (event) => {
            const texto = event.results[0][0].transcript;
            inputChat.value = texto;
            
            // Efecto visual de éxito
            microIcon.classList.replace('fa-circle', 'fa-check');
            
            // Envío automático tras un pequeño delay para que el usuario vea lo que escribió
            setTimeout(() => {
                formChat.submit();
            }, 600);
        };

        recognition.onend = () => {
            microIcon.classList.remove('fa-circle', 'fa-check');
            microIcon.classList.add('fa-microphone');
            btnMicro.classList.remove('listening', 'text-red-500', 'border-red-500');
        };

        // --- FUNCIÓN PARA HABLAR ---
        function hablar(mensaje) {
            // Limpiamos el texto para que no lea etiquetas de imagen o HTML
            const temporal = document.createElement("div");
            temporal.innerHTML = mensaje;
            const textoLimpio = temporal.textContent || temporal.innerText || "";

            const síntesis = window.speechSynthesis;
            // Cancelar cualquier lectura previa
            síntesis.cancel();

            const locución = new SpeechSynthesisUtterance(textoLimpio);
            locución.lang = 'es-ES'; 
            locución.rate = 1.1; 
            locución.pitch = 1.0;

            síntesis.speak(locución);
        }

        // --- DISPARO AL CARGAR LA PÁGINA ---
        window.addEventListener('load', () => {
            const respuesta = document.getElementById('contenedor-respuesta');
            if (respuesta && respuesta.innerText.trim() !== "") {
                // Pequeña pausa para que la carga visual no interrumpa el inicio del audio
                setTimeout(() => {
                    hablar(respuesta.innerText);
                }, 500);
            }
        });
    </script>

    <footer class="p-6 text-center text-gray-400 text-sm">
        &copy; 2026 Tekoporã - Proyecto de Ingeniería de Sistemas
    </footer>

</body>
</html>