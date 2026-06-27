<?php
// app/Controllers/ChatboyController.php

class ChatboyController
{
    public function index()
    {
        // 1. Capturamos la entrada de forma segura
        $pregunta = filter_input(INPUT_GET, 'pregunta', FILTER_SANITIZE_SPECIAL_CHARS);
        // Capturamos si el usuario usó el micrófono (1 = sí, 0 = no)
        $vocalizar = filter_input(INPUT_GET, 'vocalizar', FILTER_SANITIZE_SPECIAL_CHARS); 

        // 2. Si hay una pregunta real, procesamos la lógica con Python
        if (!empty($pregunta)) {
            $respuesta_ia = $this->consultarProcesador($pregunta);
        }

        // 3. DETECCIÓN DE AJAX: Si la petición viene de JS (fetch), devolvemos solo JSON
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            header('Content-Type: application/json');
            echo json_encode([
                'pregunta' => $pregunta,
                'respuesta' => $respuesta_ia ?? ''
            ]);
            exit;
        }

        // 4. Carga normal de la vista
        $viewPath = __DIR__ . '/../../views/chatbot_views.php';
        if (file_exists($viewPath)) {
            require_once $viewPath;
        } else {
            die("Error Crítico: No se encontró la vista.");
        }
    }

    private function consultarProcesador($mensaje) {
        $mensajeEscapado = escapeshellarg($mensaje);
        
        // Asegúrate de que la ruta al script sea correcta según tu nueva estructura
        $scriptPath = __DIR__ . '/../Scripts/processor.py';
        
        $command = "python $scriptPath $mensajeEscapado 2>&1"; 
        
        $output = shell_exec($command);
        
        // Limpieza de codificación para evitar caracteres raros
        $respuestaCruda = mb_convert_encoding(trim($output), 'UTF-8', 'UTF-8');

        // Lógica de procesamiento de imágenes
        $respuestaProcesada = preg_replace_callback(
            '/!\[.*?\]\((.*?)\)/s', 
            function($matches) {
                $url = trim($matches[1]);
                
                if (empty($url) || strpos($url, 'http') === false || $url === 'None' || strpos($url, 'no se proporciona') !== false) {
                    return ''; 
                }

                return '<br><div class="flex justify-center mt-4">' .
                       '<img src="' . $url . '" ' .
                       'onerror="this.parentElement.style.display=\'none\'" ' . 
                       'class="rounded-2xl shadow-lg border-2 border-emerald-100 max-w-full h-auto" ' .
                       'style="max-width: 600px; width: 100%; height: auto; object-fit: contain;" alt="Imagen Turística">' .
                       '</div>';
            }, 
            $respuestaCruda
        );

        return $respuestaProcesada;
    }
}