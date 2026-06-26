<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 1. Incluir helpers básicos
require_once __DIR__ . '/../app/helpers.php';
require_once __DIR__ . '/../app/helpers/auth.php';

// 2. AUTO-CARGA DE TODOS LOS CONTROLADORES
// Esto evita el error "Class not found" cuando un controlador llama a otro
$controllersDir = __DIR__ . '/../app/Controllers/';
foreach (glob($controllersDir . "*.php") as $filename) {
    require_once $filename;
}

// 3. Cargar rutas
$routes = require __DIR__ . '/../routes/web.php';

// Método y path actuales
$method = $_SERVER['REQUEST_METHOD'];
$path   = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Detectar basePath automático
$basePath = str_replace('\\', '/', dirname(dirname($_SERVER['SCRIPT_NAME'])));

// Quitar el basePath de la ruta solicitada
if ($basePath !== '/' && stripos($path, $basePath) === 0) {
    $path = substr($path, strlen($basePath));
}

// Limpiar "/public" si el navegador lo incluye en la URL
$path = str_ireplace(['/public/index.php', '/public'], '', $path);

// Quitar la barra al final si existe
$path = rtrim($path, '/');
if ($path === '' || $path === false) { $path = '/'; }

// 4. Buscar coincidencia en rutas
$routeFound = false;
foreach ($routes as $r) {
    if ($r['method'] === $method && $r['path'] === $path) {
        $routeFound = true;
        [$controller, $action] = explode('@', $r['target']);
        
        // Como ya cargamos todos los controladores arriba, solo instanciamos
        if (class_exists($controller)) {
            $c = new $controller();
            $c->$action();
            exit;
        } else {
            http_response_code(500);
            echo "Error 500: La clase controlador {$controller} no existe, aunque el archivo fue cargado.";
            exit;
        }
    }
}   

// Si no encuentra ruta
if (!$routeFound) {
    http_response_code(404);
    echo "404 - Página no encontrada. (Ruta limpia: '{$path}')";
}