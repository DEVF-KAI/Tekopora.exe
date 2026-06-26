<?php
// Evitamos iniciar la sesión si ya está activa en otro lado (ej: app_layout.php)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function require_login() {
    if (empty($_SESSION['usuario'])) {
        header("Location: " . url('/login'));
        exit();
    }
}

function tiene_permiso($roles_permitidos = []) {
    // 1. Primero verificamos que esté logueado
    require_login(); 

    // 2. Obtenemos el rol actual
    $rol_usuario = $_SESSION['usuario']['rol'] ?? '';

    // 3. Si el rol NO está en la lista de permitidos, lo pateamos al inicio
    if (!in_array($rol_usuario, $roles_permitidos)) {
        // Opcional: Podrías redirigir a un '/acceso-denegado' en el futuro
        header("Location: " . url('/')); 
        exit();
    }
}

/**
 * Atajo rápido solo para el Panel de Control principal
 */
function require_admin() {
    tiene_permiso(['Administrador']);
}
?>