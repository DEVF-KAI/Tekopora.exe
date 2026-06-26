<?php
class DestinosController {
    public function destinos() {
       // 1. Captura la vista
    ob_start();
    require __DIR__ . '/../../views/destinos.php';
    $content = ob_get_clean();

    // 2. Carga el layout
   require __DIR__ . '/../../views/layouts/app_layout.php';
    }

    public function login() {
        require __DIR__ . '/../../views/login.php';
    }
}
