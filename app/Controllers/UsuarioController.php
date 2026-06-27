<?php

require_once __DIR__ . '/../helpers/auth.php';

class UsuarioController
{
    /**
     * Mostrar tabla de usuarios bloqueados (solo para administrador)
     */
    public function index()
    {
        require_admin();
        require __DIR__ . '/../../config/database.php';

        // Traer usuarios con bloqueo temporal o permanente
        $stmt = $conn->query("
            SELECT 
                u.*, 
                r.nombre AS rol
            FROM usuario u
            LEFT JOIN usuario_rol ur ON u.idUsuario = ur.idUsuario_FK
            LEFT JOIN rol r ON ur.idRol_FK = r.idRol
            WHERE u.bloqueado = 1 OR u.locked_until IS NOT NULL
            ORDER BY u.locked_until DESC, u.bloqueado DESC
        ");

        $usuariosBloqueados = $stmt->fetchAll(PDO::FETCH_ASSOC);

        ob_start();
        include __DIR__ . '/../../views/usuarios_bloqueados.php';
        $content = ob_get_clean();

        $title = "Tekopora - Usuarios Bloqueados";

        include __DIR__ . '/../../views/layouts/app_layout.php';
    }

    /**
     * Bloquear permanentemente un usuario
     */
    public function bloquear()
    {
        require_admin();
        require __DIR__ . '/../../config/database.php';

        if (isset($_GET['codigo']) && !empty($_GET['codigo'])) {
            $codigo = htmlspecialchars($_GET['codigo'], ENT_QUOTES, 'UTF-8');

            $stmt = $conn->prepare("UPDATE usuario SET bloqueado = 1 WHERE codigoUsuario = :codigo AND codigoUsuario != 'SYS_ADMIN'");
            $stmt->bindParam(':codigo', $codigo, PDO::PARAM_STR);
            $stmt->execute();

            if (function_exists('registrarActividad') && isset($_SESSION['usuario']) && is_array($_SESSION['usuario'])) {
                $idUsr = isset($_SESSION['usuario']['idUsuario']) ? $_SESSION['usuario']['idUsuario'] : (isset($_SESSION['usuario']['id']) ? $_SESSION['usuario']['id'] : null);
                if ($idUsr) {
                    registrarActividad($idUsr, "Bloqueó permanentemente el acceso del usuario con código: " . htmlspecialchars($codigo, ENT_QUOTES, 'UTF-8'));
                }
            }

            $_SESSION['success'] = "Usuario bloqueado permanentemente.";
        }

        header("Location: " . url('usuarios/bloqueados'));
        exit;
    }

    /**
     * Desbloquear usuario (levantar restricción temporal o permanente)
     */
    public function desbloquear()
    {
        require_admin();
        require __DIR__ . '/../../config/database.php';

        if (isset($_GET['codigo']) && !empty($_GET['codigo'])) {
            $codigo = htmlspecialchars($_GET['codigo'], ENT_QUOTES, 'UTF-8');

            $stmt = $conn->prepare("
                UPDATE usuario 
                SET bloqueado = 0, 
                    failedLoginAttempts = 0, 
                    locked_until = NULL 
                WHERE codigoUsuario = :codigo
            ");
            $stmt->bindParam(':codigo', $codigo, PDO::PARAM_STR);
            $stmt->execute();

            if (function_exists('registrarActividad') && isset($_SESSION['usuario']) && is_array($_SESSION['usuario'])) {
                $idUsr = isset($_SESSION['usuario']['idUsuario']) ? $_SESSION['usuario']['idUsuario'] : (isset($_SESSION['usuario']['id']) ? $_SESSION['usuario']['id'] : null);
                if ($idUsr) {
                    registrarActividad($idUsr, "Desbloqueó el acceso del usuario con código: " . htmlspecialchars($codigo, ENT_QUOTES, 'UTF-8'));
                }
            }

            $_SESSION['success'] = "Usuario desbloqueado. Podrá intentar ingresar nuevamente.";
        }

        header("Location: " . url('usuarios/bloqueados'));
        exit;
    }

    /**
     * Obtener tabla de usuarios bloqueados en JSON (para filtrado dinámico)
     */
    public function obtenerBloqueados()
    {
        require_admin();
        require __DIR__ . '/../../config/database.php';

        $stmt = $conn->query("
            SELECT 
                u.idUsuario,
                u.codigoUsuario,
                u.nombre,
                u.appPaterno,
                u.email,
                u.bloqueado,
                u.failedLoginAttempts,
                u.locked_until,
                r.nombre AS rol
            FROM usuario u
            LEFT JOIN usuario_rol ur ON u.idUsuario = ur.idUsuario_FK
            LEFT JOIN rol r ON ur.idRol_FK = r.idRol
            WHERE u.bloqueado = 1 OR u.locked_until IS NOT NULL
            ORDER BY u.locked_until DESC, u.bloqueado DESC
        ");

        $usuariosBloqueados = $stmt->fetchAll(PDO::FETCH_ASSOC);

        header('Content-Type: application/json');
        echo json_encode($usuariosBloqueados);
        exit;
    }
}
