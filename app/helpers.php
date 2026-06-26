<?php
// Archivo: app/helpers.php

if (!function_exists('url')) {
    /**
     * Genera una URL absoluta para las rutas (ej: /proyectos)
     */
    function url($path = '') {
        // Detectar la ruta base dinámicamente
        $scriptName = dirname($_SERVER['SCRIPT_NAME']);
        $basePath = str_replace('\\', '/', $scriptName);
        $basePath = str_replace('/public', '', $basePath);
        
        if ($basePath === '/') {
            $basePath = '';
        }
        
        $path = ltrim($path, '/');
        return $basePath . '/' . $path;
    }
}

if (!function_exists('asset')) {
    /**
     * Genera la ruta correcta para los archivos estáticos (CSS, JS, imágenes)
     * que están dentro de la carpeta public/
     */
    function asset($path) {
        $scriptName = dirname($_SERVER['SCRIPT_NAME']);
        $basePath = str_replace('\\', '/', $scriptName);
        $basePath = str_replace('/public', '', $basePath);
        
        if ($basePath === '/') {
            $basePath = '';
        }
        
        $path = ltrim($path, '/');
        
        // Retorna la ruta apuntando a la carpeta public
        return $basePath . '/public/' . $path;
    }
}