<?php
// app/Controllers/ChatboyController.php

class ChatboyController
{
    public function index()
    {
        // 1. Capturamos la entrada de forma segura
        $pregunta = filter_input(INPUT_GET, 'pregunta', FILTER_SANITIZE_SPECIAL_CHARS);
        
        // 2. Definimos valores por defecto
        $titulo = $pregunta ? "Consulta: " . $pregunta : "Asistente Virtual WYRM";
        $respuesta_ia = "Bienvenido a Tekoporã. ¿En qué puedo ayudarte hoy?";

        // 3. Si hay una pregunta, procesamos la lógica
        if (!empty($pregunta)) {
            $respuesta_ia = $this->consultarProcesador($pregunta);
        } else {
            $pregunta = "¡Hola! Soy tu asistente de Tekoporã.";
        }

        // 4. DETECCIÓN DE AJAX: Si la petición viene de JS (fetch), devolvemos solo JSON
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            header('Content-Type: application/json');
            echo json_encode([
                'pregunta' => $pregunta,
                'respuesta' => $respuesta_ia
            ]);
            exit;
        }

        // 5. Carga normal de la vista (Carga inicial)
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
        
        // Recomendación: Usa el path completo de tu venv si estás en local
        // $pythonPath = "C:/ruta/a/tu/venv/Scripts/python.exe"; 
        $command = "python $scriptPath $mensajeEscapado 2>&1"; 
        
        $output = shell_exec($command);
        
        // Limpieza de codificación para evitar caracteres raros
        $respuestaCruda = mb_convert_encoding(trim($output), 'UTF-8', 'UTF-8');

        // Lógica de procesamiento de imágenes (Mantenemos tu lógica que ya funciona)
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
                       'style="max-height: 400px;" alt="Imagen Turística">' .
                       '</div>';
            }, 
            $respuestaCruda
        );

        return $respuestaProcesada;
    }
}