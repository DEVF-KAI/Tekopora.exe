<?php
$title = 'Asistente IA - TekoPorã';
$ocultar_footer = true; 

// Llamamos estilos y funcionalidades
$extraCss = '<link rel="stylesheet" href="' . asset('css/chatbot.css') . '">';
$extraJs = '<script src="' . asset('js/chatbot.js') . '"></script>';

// Leemos si el envío anterior usó el micrófono
$vocalizar_flag = (isset($_GET['vocalizar']) && $_GET['vocalizar'] == '1') ? 'true' : 'false';

ob_start(); 
?>

<style>
    #btn-abrir-chat, .btn-flotante-teko {
        display: none !important;
    }

    #btn-abrir-chat, .btn-flotante-teko, footer {
        display: none !important;
    }
</style>

<div class="chat-wrapper">
    
    <div class="chat-main" id="chat-history">
        
        <?php if (empty($pregunta)): ?>
        <div class="message-row ai-message">
            <div class="ai-avatar"><i class="fas fa-robot text-warning text-xl"></i></div>
            <div class="ai-bubble">
                <p>Saludos. Soy Teko, la Inteligencia Artificial de TekoPorã. Estoy sincronizado con los servidores del municipio.</p>
                <p>¿Donde quieres ir hoy?</p>
            </div>
        </div>
        <?php endif; ?>

        <?php if (!empty($pregunta)): ?>
            <div class="message-row user-message">
                <div class="user-bubble">
                    <?php echo htmlspecialchars($pregunta); ?>
                </div>
            </div>

            <div class="message-row ai-message">
                <div class="ai-avatar"><i class="fas fa-robot text-warning text-xl"></i></div>
                <div class="ai-bubble" id="contenedor-respuesta" data-vocalizar="<?= $vocalizar_flag ?>">
                    <?php 
                    echo $respuesta_ia ?? 'Tuve un pequeño corte de conexión con el cerebro principal. ¿Podrías repetirme la consulta?'; 
                    ?>
                </div>
            </div>
        <?php endif; ?>

    </div>

    <form action="chatboy" method="GET" id="form-chat" class="input-console">
        <input type="hidden" name="vocalizar" id="input-vocalizar" value="0">
        
        <input type="text" name="pregunta" id="user-input" autocomplete="off" placeholder="Inicia transmisión de datos..." required class="chat-input-3d">

        
        <button type="submit" class="btn-send-3d" title="Procesar">
            <i class="fas fa-paper-plane"></i>
        </button>
    </form>

</div>

<?php 
$content = ob_get_clean(); 
require __DIR__ . '/layouts/app_layout.php'; 
?>