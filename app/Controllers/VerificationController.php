<?php
// Importamos las clases de PHPMailer que descargaste con Composer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Cargamos el "Autoloader" mágico de Composer
require __DIR__ . '/../../vendor/autoload.php';

class VerificationController {
    
    // 1. Mostrar la pantalla para escribir el código
    public function index() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        
        if (empty($_SESSION['usuario'])) {
            header("Location: " . url('/login'));
            exit();
        }
        // Llamaremos a esta vista en el próximo paso
        require __DIR__ . '/../../views/verificar_codigo.php';
    }

    // 2. Generar el número y enviarlo por correo
    public function enviar() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        require __DIR__ . '/../../config/database.php';

        $idUsuario = $_SESSION['usuario']['idUsuario'] ?? null;
        $emailDestino = $_SESSION['usuario']['email'] ?? null;

        if (!$idUsuario || !$emailDestino) {
            header("Location: " . url('/login'));
            exit();
        }

        // Generar código de 6 dígitos (ej: 045892) y fecha de expiración (15 min)
        $codigo = sprintf("%06d", mt_rand(1, 999999));
        $expiracion = date('Y-m-d H:i:s', strtotime('+15 minutes'));

        try {
            // Guardar en la Base de Datos
            $stmt = $conn->prepare("UPDATE usuario SET codigo_verificacion = ?, expiracion_codigo = ? WHERE idUsuario = ?");
            $stmt->execute([$codigo, $expiracion, $idUsuario]);

            // ==========================================
            // CONFIGURACIÓN DEL CARTERO (PHPMailer)
            // ==========================================
            $mail = new PHPMailer(true);

            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com'; // Servidor de Google
            $mail->SMTPAuth   = true;
            
            // DATOS DE GOOGLE CLOUD
            $mail->Username   = 'e1000villca@gmail.com';  // Tu correo
            $mail->Password   = 'jlmg oduv pwze kfuy';   // La Contraseña de Aplicación de Google (sin espacios)
            
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;
            $mail->CharSet    = 'UTF-8';

            // Quién lo envía y a quién va dirigido
            $mail->setFrom('tekoporainfo@gmail.com', 'TekoPorã Bolivia'); // Pon tu correo de nuevo aquí
            $mail->addAddress($emailDestino);

            // Diseño HTML del correo
            $mail->isHTML(true);
            $mail->Subject = 'Tu Código de Acceso - TekoPorã';
            $mail->Body    = "
                <div style='font-family: Arial, sans-serif; max-width: 500px; margin: auto; padding: 30px; border-radius: 15px; background: #f4f7f6; text-align: center; border: 1px solid #e0e0e0;'>
                    <h2 style='color: #217F82; margin-top: 0;'>TekoPorã Bolivia</h2>
                    <p style='color: #2c3e50;'>Hola,</p>
                    <p style='color: #2c3e50;'>Tu código de verificación de 6 dígitos es:</p>
                    <div style='margin: 30px 0;'>
                        <span style='font-size: 32px; font-weight: bold; background: #ffffff; padding: 15px 30px; border-radius: 10px; color: #2A6F97; letter-spacing: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);'>{$codigo}</span>
                    </div>
                    <p style='color: #e74c3c; font-size: 0.85em; font-weight: bold;'>⚠️ Este código expirará en 15 minutos.</p>
                    <hr style='border: none; border-top: 1px solid #ddd; margin: 25px 0;' />
                    <p style='font-size: 0.75em; color: #95a5a6;'>Si tú no solicitaste este código, ignora este correo.</p>
                </div>
            ";

            // Enviar!
            $mail->send();
            $_SESSION['success'] = "Te hemos enviado un código a tu correo.";
            
        } catch (Exception $e) {
            error_log("Error de PHPMailer: {$mail->ErrorInfo}");
            $_SESSION['error'] = "Error al enviar el correo. Intenta de nuevo.";
        } catch (PDOException $e) {
            $_SESSION['error'] = "Error al guardar el código en el sistema.";
        }

        header("Location: " . url('/verificar'));
        exit();
    }

    // 3. Validar el código que el usuario escribió
    public function validar() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        require __DIR__ . '/../../config/database.php';

        $idUsuario = $_SESSION['usuario']['idUsuario'] ?? null;
        $codigoIngresado = trim($_POST['codigo'] ?? '');

        if (!$idUsuario) {
            header("Location: " . url('/login'));
            exit();
        }

        try {
            $stmt = $conn->prepare("SELECT codigo_verificacion, expiracion_codigo FROM usuario WHERE idUsuario = ?");
            $stmt->execute([$idUsuario]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Validaciones de seguridad
            if (!$user || !$user['codigo_verificacion']) {
                $_SESSION['error'] = "No has solicitado ningún código.";
                header("Location: " . url('/verificar'));
                exit();
            }

            if (strtotime($user['expiracion_codigo']) < time()) {
                $_SESSION['error'] = "El código ha expirado. Solicita uno nuevo.";
                header("Location: " . url('/verificar'));
                exit();
            }

            if ($codigoIngresado === $user['codigo_verificacion']) {
                // ¡ÉXITO! Destruimos el código usado por seguridad
                $stmtUpdate = $conn->prepare("UPDATE usuario SET codigo_verificacion = NULL, expiracion_codigo = NULL WHERE idUsuario = ?");
                $stmtUpdate->execute([$idUsuario]);

                $_SESSION['success'] = "¡Identidad verificada exitosamente!";
                header("Location: " . url('/')); // O mándalo al dashboard, donde quieras.
                exit();
            } else {
                $_SESSION['error'] = "El código es incorrecto.";
                header("Location: " . url('/verificar'));
                exit();
            }

        } catch (PDOException $e) {
            $_SESSION['error'] = "Error en el servidor al validar.";
            header("Location: " . url('/verificar'));
            exit();
        }
    }
}