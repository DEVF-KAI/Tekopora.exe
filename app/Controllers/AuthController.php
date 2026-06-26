<?php
class AuthController
{
    // Mostrar formulario
    public function login()
    {
        require __DIR__ . '/../../views/login.php';
    }

    // Procesar login
    public function authenticate()
    {
        session_start();
        require __DIR__ . '/../../config/database.php';

        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        try {
            // 🔥 Traer usuario + rol
            $stmt = $conn->prepare("
                SELECT u.*, r.nombre AS rol
                FROM usuario u
                LEFT JOIN usuario_rol ur ON u.idUsuario = ur.idUsuario_FK
                LEFT JOIN rol r ON ur.idRol_FK = r.idRol
                WHERE u.email = ?
                LIMIT 1
            ");
            $stmt->execute([$email]);
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$usuario) {
                $_SESSION['error'] = "El correo no está registrado";
                header("Location: " . url('login'));
                exit();
            }

            if (!password_verify($password, $usuario['passwordHash'])) {
                $_SESSION['error'] = "La contraseña es incorrecta";
                header("Location: " . url('login'));
                exit();
            }

            // ==========================================
            // 🔥 NUEVA VALIDACIÓN DE ESTADO DEL USUARIO
            // ==========================================
            // Verificamos si la columna 'estado' existe y si es Inactivo o Suspendido
            if (isset($usuario['estado']) && in_array($usuario['estado'], ['Inactivo', 'Suspendido'])) {
                // Creamos el mensaje dinámico ("Tu cuenta está inactivo/suspendido")
                $_SESSION['error'] = "No puedes acceder. Tu cuenta está " . strtolower($usuario['estado']) . ". Contacta al administrador.";
                header("Location: " . url('login'));
                exit();
            }
            // ==========================================

            // 🔥 Guardar sesión con rol (ESTANDARIZADO: idUsuario)
            $_SESSION['usuario'] = [
                'idUsuario' => $usuario['idUsuario'], // <-- EL FIX ESTÁ AQUÍ
                'codigo'=> $usuario['codigoUsuario'],
                'nombre' => $usuario['nombre'],
                'email' => $usuario['email'],
                'rol' => $usuario['rol'],
                'karma' => $usuario['karmatotal'] ?? 0
            ];

            // 🔥 Redirección por rol
            if ($usuario['rol'] === 'Administrador') {
                header("Location: " . url('adminpanel'));
            } else {
                header("Location: " . url('/')); // home
            }
            exit();

        } catch (PDOException $e) {
            $_SESSION['error'] = "Error de base de datos";
            header("Location: " . url('login'));
            exit();
        }
    }
    
    // Cerrar sesión
    public function logout()
    {
        session_start();
        session_destroy();
        header("Location: " . url('login'));
    }
}