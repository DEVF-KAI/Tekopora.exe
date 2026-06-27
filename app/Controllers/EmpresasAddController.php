<?php

class EmpresasAddController {

    // 1. Mostrar el formulario de registro
    public function empresasadd() {
        // Título de la página
        $title = "Registrar Empresa - TekoPorã";

        // Capturamos la vista
        ob_start();
        require __DIR__ . '/../../views/empresas_add.php';
        $content = ob_get_clean();

        // Cargamos el layout global
        require __DIR__ . '/../../views/layouts/app_layout.php';
    }

    // 2. Procesar el guardado de la empresa
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

        require __DIR__ . '/../../config/database.php';
        
        // Iniciamos sesión si no está iniciada para verificar permisos
        if (session_status() === PHP_SESSION_NONE) session_start();

        // Captura de datos del formulario
        $nombre = $_POST['nombreEmpresa'] ?? '';
        $telefono = $_POST['telefono'] ?? '';
        $direccion = $_POST['direccion'] ?? '';
        $nit = $_POST['nit'] ?? ''; // El NIT que usaremos como código

        // Validar que no falten datos esenciales
        if (empty($nombre) || empty($nit)) {
            header("Location: " . url('/empresas/add?error=Nombre y NIT son obligatorios'));
            exit();
        }

        try {
            // Verificar si el NIT ya está registrado como código de empresa
            $check = $conn->prepare("SELECT idEmpresa FROM empresaConstructora WHERE codigoEmpresa = ?");
            $check->execute([$nit]);
            
            if ($check->rowCount() > 0) {
                header("Location: " . url('/empresas/add?error=El NIT ya se encuentra registrado'));
                exit();
            }

            // Inserción en la base de datos
            $sql = "INSERT INTO empresaConstructora (codigoEmpresa, nombreEmpresa, telefono, direccion) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            
            $stmt->execute([
                $nit,
                $nombre,
                $telefono,
                $direccion
            ]);

            // REGISTRO EN BITÁCORA (Nueva Empresa)
            if (isset($_SESSION['usuario']) && function_exists('registrarActividad')) {
                $idUsr = $_SESSION['usuario']['idUsuario'] ?? $_SESSION['usuario']['id'];
                registrarActividad($idUsr, "Registró en el sistema a la empresa constructora: " . $nombre . " (NIT: " . $nit . ")");
            }

            // Redirigir al listado con éxito
            header("Location: " . url('/empresas?success=Empresa registrada correctamente'));
            exit();

        } catch (Exception $e) {
            // Manejo de errores
            header("Location: " . url('/empresas/add?error=' . urlencode($e->getMessage())));
            exit();
        }
    }
}