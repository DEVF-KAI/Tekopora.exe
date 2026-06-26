<?php

require_once __DIR__ . '/../helpers/auth.php';

class AdminPanelController
{

    public function index()
    {
        require_admin(); // 🔥 SOLO ADMIN ENTRA
        require __DIR__ . '/../../config/database.php';

        // 🔥 CONSULTA CON ROL
        $stmt = $conn->query("
            SELECT 
                u.*, 
                r.nombre AS rol
            FROM usuario u
            LEFT JOIN usuario_rol ur ON u.idUsuario = ur.idUsuario_FK
            LEFT JOIN rol r ON ur.idRol_FK = r.idRol
        ");

        $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

        ob_start();
        include __DIR__ . '/../../views/admin_perfil.php';
        $content = ob_get_clean();

        $title = "Tekopora - Admin";

        include __DIR__ . '/../../views/layouts/app_layout.php';
    }

    // 🔥 MÉTODO PARA SUSPENDER USUARIO
    public function suspenderUsuario()
    {
        require_admin();
        require __DIR__ . '/../../config/database.php';

        //  CAMBIO: Buscamos 'codigo' que es lo que manda la vista
        if (isset($_GET['codigo'])) {
            $codigo = $_GET['codigo'];

            //  CAMBIO: UPDATE usando codigoUsuario para mantener la seguridad
            $stmt = $conn->prepare("UPDATE usuario SET estado = 'Suspendido' WHERE codigoUsuario = :codigo");
            $stmt->bindParam(':codigo', $codigo, PDO::PARAM_STR);
            $stmt->execute();
        }

        header("Location: " . url('adminpanel'));
        exit;
    }

    // 🔥 MÉTODO PARA ACTIVAR USUARIO
    public function activarUsuario()
    {
        require_admin();
        require __DIR__ . '/../../config/database.php';

        // ✅ CAMBIO: Buscamos 'codigo'
        if (isset($_GET['codigo'])) {
            $codigo = $_GET['codigo'];

            // ✅ CAMBIO: UPDATE usando codigoUsuario
            $stmt = $conn->prepare("UPDATE usuario SET estado = 'Activo' WHERE codigoUsuario = :codigo");
            $stmt->bindParam(':codigo', $codigo, PDO::PARAM_STR);
            $stmt->execute();
        }

        header("Location: " . url('adminpanel'));
        exit;
    }
    // Dentro de AdminPanelController.php

    // 🔥 MÉTODO PARA MOSTRAR LA VISTA DE EDICIÓN
    public function editarUsuario() {
        require_admin();
        require __DIR__ . '/../../config/database.php';

        $codigo = $_GET['codigo'] ?? null;

        if (!$codigo) {
            header("Location: " . url('adminpanel?error=Falta el código'));
            exit;
        }

        // Buscamos al usuario y su ID de rol actual
        $stmt = $conn->prepare("
            SELECT u.*, r.idRol 
            FROM usuario u
            LEFT JOIN usuario_rol ur ON u.idUsuario = ur.idUsuario_FK
            LEFT JOIN rol r ON ur.idRol_FK = r.idRol
            WHERE u.codigoUsuario = :codigo
        ");
        $stmt->bindParam(':codigo', $codigo);
        $stmt->execute();
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario) {
            
            $rolActual = $usuario['idRol']; 

            // Traemos todos los roles para el select
            $stmtRoles = $conn->query("SELECT * FROM rol");
            $roles = $stmtRoles->fetchAll(PDO::FETCH_ASSOC);

            $title = "Editar Usuario - TekoPorã";
            ob_start();
            include __DIR__ . '/../../views/editar_usuario.php'; 
            $content = ob_get_clean();

            include __DIR__ . '/../../views/layouts/app_layout.php';
        } else {
            header("Location: " . url('adminpanel?error=Usuario no encontrado'));
            exit;
        }
    }
    public function actualizarUsuario()
    {
        require_admin();
        require __DIR__ . '/../../config/database.php';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $codigo = $_POST['codigoUsuario'];
            $nombre = $_POST['nombre'];
            $email = $_POST['email'];
            $rol = $_POST['rol'];

            try {
                $conn->beginTransaction();

                // 1. Actualizar datos básicos
                $stmt = $conn->prepare("UPDATE usuario SET nombre = ?, email = ? WHERE codigoUsuario = ?");
                $stmt->execute([$nombre, $email, $codigo]);

                // 2. Actualizar el rol (necesitamos el ID interno para la tabla pivote)
                $userStmt = $conn->prepare("SELECT idUsuario FROM usuario WHERE codigoUsuario = ?");
                $userStmt->execute([$codigo]);
                $idUsuario = $userStmt->fetchColumn();

                $rolUpdate = $conn->prepare("UPDATE usuario_rol SET idRol_FK = ? WHERE idUsuario_FK = ?");
                $rolUpdate->execute([$rol, $idUsuario]);

                $conn->commit();
                header("Location: " . url('adminpanel?success=Usuario actualizado'));
            } catch (Exception $e) {
                $conn->rollBack();
                header("Location: " . url('adminpanel?error=Error al actualizar'));
            }
        }
    }
}