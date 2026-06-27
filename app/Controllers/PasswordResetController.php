<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../../vendor/autoload.php';

class PasswordResetController {

    // 1. Mostrar pantalla para pedir el correo
    public function index() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        require __DIR__ . '/../../views/recuperar_password.php';
    }

    // 2. Procesar el correo y enviar el código
    public function enviarCodigo() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        require __DIR__ . '/../../config/database.php';

        $email = trim($_POST['email'] ?? '');

        try {
            // Verificar si el correo existe
            $stmt = $conn->prepare("SELECT idUsuario, nombre FROM usuario WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$user) {
                $_SESSION['error'] = "Si el correo existe, se ha enviado un código."; // Mensaje genérico por seguridad
                header("Location: " . url('/recuperar/verificar'));
                exit();
            }

            // Generar código
            $codigo = sprintf("%06d", mt_rand(1, 999999));
            $expiracion = date('Y-m-d H:i:s', strtotime('+15 minutes'));

            // Guardar en BD
            $stmtUpdate = $conn->prepare("UPDATE usuario SET codigo_verificacion = ?, expiracion_codigo = ? WHERE idUsuario = ?");
            $stmtUpdate->execute([$codigo, $expiracion, $user['idUsuario']]);

            // Enviar Correo
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'e1000villca@gmail.com'; 
            $mail->Password   = 'jlmg oduv pwze kfuy';  
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;
            $mail->CharSet    = 'UTF-8';

            $mail->setFrom('e1000villca@gmail.com', 'TekoPorã Bolivia'); 
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = 'Recuperacion de Contrasena - TekoPora';
            $mail->Body    = "
                <div style='font-family: Arial, sans-serif; text-align: center; padding: 20px;'>
                    <h2 style='color: #2A6F97;'>Recuperación de Contraseña</h2>
                    <p>Usa este código para restablecer tu contraseña. Expira en 15 minutos:</p>
                    <h1 style='background: #f4f7f6; padding: 15px; letter-spacing: 5px; color: #217F82;'>{$codigo}</h1>
                </div>
            ";
            $mail->send();

            // Guardar el email en sesión temporalmente para saber a quién le cambiamos la contraseña
            $_SESSION['reset_email'] = $email;
            $_SESSION['success'] = "Código enviado exitosamente.";
            header("Location: " . url('/recuperar/verificar'));
            exit();

        } catch (Exception $e) {
            $_SESSION['error'] = "Hubo un problema enviando el correo.";
            header("Location: " . url('/recuperar'));
            exit();
        }
    }

    // 3. Mostrar pantalla para ingresar el código
    public function mostrarVerificacion() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        require __DIR__ . '/../../views/recuperar_verificar.php';
    }

    // 4. Validar el código ingresado
    public function verificarCodigo() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        require __DIR__ . '/../../config/database.php';

        $codigoIngresado = trim($_POST['codigo'] ?? '');
        $email = $_SESSION['reset_email'] ?? '';

        if (!$email) {
            header("Location: " . url('/recuperar'));
            exit();
        }

        $stmt = $conn->prepare("SELECT codigo_verificacion, expiracion_codigo FROM usuario WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && $user['codigo_verificacion'] === $codigoIngresado && strtotime($user['expiracion_codigo']) > time()) {
            $_SESSION['reset_verified'] = true; // Permiso para cambiar la contraseña
            header("Location: " . url('/recuperar/nueva'));
            exit();
        } else {
            $_SESSION['error'] = "El código es incorrecto o ha expirado.";
            header("Location: " . url('/recuperar/verificar'));
            exit();
        }
    }

    // 5. Mostrar pantalla de nueva contraseña
    public function mostrarNueva() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (empty($_SESSION['reset_verified'])) {
            header("Location: " . url('/recuperar'));
            exit();
        }
        require __DIR__ . '/../../views/recuperar_nueva.php';
    }

    // 6. Guardar la nueva contraseña
    public function actualizarPassword() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        require __DIR__ . '/../../config/database.php';

        $password = $_POST['password'] ?? '';
        $email = $_SESSION['reset_email'] ?? '';

        if (!empty($password) && !empty($email) && !empty($_SESSION['reset_verified'])) {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            
            $stmt = $conn->prepare("UPDATE usuario SET passwordHash = ?, codigo_verificacion = NULL, expiracion_codigo = NULL WHERE email = ?");
            $stmt->execute([$hash, $email]);

            // REGISTRO EN BITÁCORA (Recuperación Exitosa)
            $stmtUser = $conn->prepare("SELECT idUsuario FROM usuario WHERE email = ?");
            $stmtUser->execute([$email]);
            $idUsuario = $stmtUser->fetchColumn();

            if ($idUsuario && function_exists('registrarActividad')) {
                registrarActividad($idUsuario, "Restableció su contraseña mediante código de verificación al correo");
            }

            // Limpiar variables de sesión de recuperación
            unset($_SESSION['reset_email'], $_SESSION['reset_verified']);

            $_SESSION['success'] = "Tu contraseña ha sido actualizada. Ya puedes iniciar sesión.";
            header("Location: " . url('/login'));
            exit();
        } else {
            $_SESSION['error'] = "Error al procesar la solicitud.";
            header("Location: " . url('/recuperar/nueva'));
            exit();
        }
    }
}