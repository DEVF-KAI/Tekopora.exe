<?php
class EmpresasController {
    public function empresas() {
       require __DIR__ . '/../../config/database.php';
       $stmt = $conn->query("SELECT * FROM empresaConstructora ORDER BY nombreEmpresa ASC");
    
    // 3. Guardarlos en la variable que la vista espera
    $empresas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 4. Cargar la vista (pasándole los datos)
    $title = 'Empresas Constructoras - TekoPorã';
       // 1. Captura la vista
    ob_start();
    require __DIR__ . '/../../views/empresas.php';
    $content = ob_get_clean();

    // 2. Carga el layout
   require __DIR__ . '/../../views/layouts/app_layout.php';
    }

    public function login() {
        require __DIR__ . '/../../views/login.php';
    }
}
