<?php
class AuthController
{
    // Mostrar formulario
    public function login()
    {
        require __DIR__ . '/../../views/login.php';

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // Procesar login
    public function authenticate()
    {

        require __DIR__ . '/../../config/database.php';

        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        try {
            //  Traer usuario + rol
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

            // ==========================================
            //  VALIDACIÓN DE BLOQUEO POR INTENTOS
            // ==========================================
            $ahora = new DateTime();
            $locked_until = null;
            
            try {
                if (isset($usuario['locked_until']) && !empty($usuario['locked_until'])) {
                    $locked_until = new DateTime($usuario['locked_until']);
                }
            } catch (Exception $e) {
                // Si hay error al parsear la fecha, resetear el bloqueo
                $stmt_reset = $conn->prepare("UPDATE usuario SET failedLoginAttempts = 0, locked_until = NULL WHERE idUsuario = ?");
                $stmt_reset->execute([$usuario['idUsuario']]);
                $locked_until = null;
            }

            if ($locked_until && $ahora < $locked_until) {
                $minutos_restantes = ceil(($locked_until->getTimestamp() - $ahora->getTimestamp()) / 60);
                $_SESSION['error'] = "Cuenta temporalmente bloqueada. Intentos fallidos registrados. Intenta nuevamente en {$minutos_restantes} minuto(s).";
                header("Location: " . url('login'));
                exit();
            }

            // Si el bloqueo temporal ha vencido, resetear intentos
            if ($locked_until && $ahora >= $locked_until) {
                $stmt_reset = $conn->prepare("UPDATE usuario SET failedLoginAttempts = 0, locked_until = NULL WHERE idUsuario = ?");
                $stmt_reset->execute([$usuario['idUsuario']]);
                $usuario['failedLoginAttempts'] = 0;
            }

            // Validar si la cuenta está bloqueada permanentemente
            if (isset($usuario['bloqueado']) && $usuario['bloqueado'] == 1) {
                $_SESSION['error'] = "DEMASIADOS INTENTOS FALLIDOS. COMUNIQUESE CON UN ADMINISTRADOR PARA DESBLOQUEAR SU CUENTA.";
                header("Location: " . url('login'));
                exit();
            }

            if (!password_verify($password, $usuario['passwordHash'])) {
                // Incrementar intentos fallidos
                $intentos_actuales = ($usuario['failedLoginAttempts'] ?? 0) + 1;
                $locked_until_new = null;

                if ($intentos_actuales >= 3) {
                    // Bloquear por 10 minutos
                    $fecha_bloqueo = new DateTime();
                    $fecha_bloqueo->add(new DateInterval('PT10M'));
                    $locked_until_new = $fecha_bloqueo->format('Y-m-d H:i:s');

                    $stmt_block = $conn->prepare("UPDATE usuario SET failedLoginAttempts = ?, locked_until = ? WHERE idUsuario = ?");
                    $stmt_block->execute([$intentos_actuales, $locked_until_new, $usuario['idUsuario']]);

                    $_SESSION['error'] = "DEMASIADOS INTENTOS FALLIDOS. COMUNIQUESE CON UN ADMINISTRADOR PARA DESBLOQUEAR SU CUENTA.";
                } else {
                    // Guardar intento fallido
                    $stmt_fail = $conn->prepare("UPDATE usuario SET failedLoginAttempts = ? WHERE idUsuario = ?");
                    $stmt_fail->execute([$intentos_actuales, $usuario['idUsuario']]);

                    $intentos_restantes = 3 - $intentos_actuales;
                    $_SESSION['error'] = "La contraseña es incorrecta. Te quedan {$intentos_restantes} intento(s).";
                }

                header("Location: " . url('login'));
                exit();
            }

            // Login exitoso: resetear intentos fallidos
            $stmt_success = $conn->prepare("UPDATE usuario SET failedLoginAttempts = 0, locked_until = NULL WHERE idUsuario = ?");
            $stmt_success->execute([$usuario['idUsuario']]);

            // ==========================================
            //  VALIDACIÓN DE ESTADO DEL USUARIO
            // ==========================================
            if (isset($usuario['estado']) && in_array($usuario['estado'], ['Inactivo', 'Suspendido'], true)) {
                $estadoLower = htmlspecialchars(strtolower($usuario['estado']), ENT_QUOTES, 'UTF-8');
                $_SESSION['error'] = "No puedes acceder. Tu cuenta está " . $estadoLower . ". Contacta al administrador.";
                header("Location: " . url('login'));
                exit();
            }

            // Verificar nuevamente si está bloqueado (para el login exitoso)
            if (isset($usuario['bloqueado']) && $usuario['bloqueado'] == 1) {
                $_SESSION['error'] = "DEMASIADOS INTENTOS FALLIDOS. COMUNIQUESE CON UN ADMINISTRADOR PARA DESBLOQUEAR SU CUENTA.";
                header("Location: " . url('login'));
                exit();
            }

            //  Guardar sesión con rol
            $_SESSION['usuario'] = [
                'idUsuario' => $usuario['idUsuario'],
                'codigo'=> $usuario['codigoUsuario'],
                'nombre' => $usuario['nombre'],
                'email' => $usuario['email'],
                'rol' => $usuario['rol'],
                'karma' => $usuario['karmatotal'] ?? 0
            ];

            //  REGISTRO EN BITÁCORA (Login Exitoso)
            if (function_exists('registrarActividad')) {
                registrarActividad($usuario['idUsuario'], "Inició sesión en el sistema mediante correo y contraseña");
            }

            //  Redirección por rol
            if ($usuario['rol'] === 'Administrador') {
                header("Location: " . url('adminpanel'));
            } else {
                header("Location: " . url('/')); 
            }
            exit();

        } catch (PDOException $e) {
            $_SESSION['error'] = "Error de base de datos: " . $e->getMessage();
            error_log("Auth Error: " . $e->getMessage());
            header("Location: " . url('login'));
            exit();
        }
    }
    
    // Cerrar sesión
    public function logout()
    {
        // 1. Iniciamos sesión solo si es estrictamente necesario
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }


        // 2. REGISTRO EN BITÁCORA (Logout)
        // Se hace antes de destruir los datos para no perder la referencia del usuario
        if (isset($_SESSION['usuario']) && function_exists('registrarActividad')) {
            $idUsr = $_SESSION['usuario']['idUsuario'] ?? $_SESSION['usuario']['id'];
            registrarActividad($idUsr, "Cerró su sesión de forma manual");
        }

        // 3. Limpieza total de la sesión
        $_SESSION = array(); // Vaciamos los datos en memoria

        // Destruimos la cookie de sesión en el navegador
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        session_destroy(); // Matamos la sesión en el servidor

        // 4. Redirección final
        // Ahora funcionará porque ya no hay avisos de error imprimiendo texto antes
        header("Location: " . url('login'));
        exit();
    }
}