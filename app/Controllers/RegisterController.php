<?php
// Importamos PHPMailer al inicio para que el controlador pueda usarlo
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Asegúrate de que la ruta al autoload de Composer sea la correcta según tu estructura
require __DIR__ . '/../../vendor/autoload.php';

class RegisterController {
    
    public function register() {
        require __DIR__ . '/../../config/database.php';

        // FILTRAMOS EL ROL DE CIUDADANO
        $stmt = $conn->prepare("SELECT idRol, nombre FROM rol WHERE nombre = ? LIMIT 1");
        $stmt->execute(['Ciudadano']);
        $roles = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // CARGAMOS LA VISTA
        require __DIR__ . '/../../views/register.php';
    }

    public function login() {
        require __DIR__ . '/../../views/login.php';
    }
   
    public function store() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        require __DIR__ . '/../../config/database.php';

        $nombre = $_POST['nombre'] ?? '';
        $appPaterno = $_POST['appPaterno'] ?? '';
        $appMaterno = $_POST['appMaterno'] ?? '';
        $ci = $_POST['ci'] ?? '';
        $email = $_POST['email'] ?? '';
        $telefono = $_POST['telefono'] ?? '';
        $password = $_POST['password'] ?? '';
        $rol = $_POST['rol'] ?? '';

        // 1. Validación de campos vacíos
        if (empty($nombre) || empty($appPaterno) || empty($ci) || empty($email) || empty($password) || empty($rol)) {
            $_SESSION['error'] = "Campos obligatorios faltantes";
            header("Location: " . url('register'));
            exit();
        }

        
        //  2. VALIDACIÓN DE CONTRASEÑA SEGURA
        if (!preg_match('/(?=.*\d)(?=.*[A-Z])(?=.*[\W_]).{8,}/', $password)) {
            $_SESSION['error'] = "La contraseña debe tener al menos 8 caracteres, 1 mayúscula, 1 número y 1 carácter especial.";
            header("Location: " . url('register'));
            exit();
        }
        

        try {
            
            //  3. SEGURIDAD EXTRA: FORZAR ROL CIUDADANO
            // Buscamos el ID real en la BD e ignoramos el POST
            $stmtId = $conn->prepare("SELECT idRol FROM rol WHERE nombre = 'Ciudadano' LIMIT 1");
            $stmtId->execute();
            $rol = $stmtId->fetchColumn(); 

            // 4. Si pasa todo, hasheamos y guardamos
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            $codigoUsuario = 'USR-' . strtoupper(substr(uniqid(), -8));

            // Insertar usuario
            $stmt = $conn->prepare("
                INSERT INTO usuario 
                (codigoUsuario, ci, nombre, appPaterno, appMaterno, email, telefono, passwordHash)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)
            ");

            $stmt->execute([
                $codigoUsuario,
                $ci,
                $nombre,
                $appPaterno,
                $appMaterno,
                $email,
                $telefono,
                $passwordHash
            ]);

            $idUsuario = $conn->lastInsertId();

            // Asignar rol (ahora usando el ID 100% seguro de Ciudadano)
            $stmtRol = $conn->prepare("
                INSERT INTO usuario_rol (idUsuario_FK, idRol_FK)
                VALUES (?, ?)
            ");

            $stmtRol->execute([$idUsuario, $rol]);

            // REGISTRO EN BITÁCORA (Registro de Nuevo Usuario)
            if (function_exists('registrarActividad')) {
                registrarActividad($idUsuario, "Se registró en la plataforma como nuevo usuario ciudadano");
            }

            // ==========================================
            // CONFIGURACIÓN DEL CARTERO: CORREO DE BIENVENIDA
            // ==========================================
            try {
                $mail = new PHPMailer(true);

                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com'; 
                $mail->SMTPAuth   = true;
                
                // TUS DATOS DE GOOGLE CLOUD (Pon tus credenciales reales aquí)
                $mail->Username   = 'e1000villca@gmail.com';  
                $mail->Password   = 'skar csie qydc fsvx';   
                
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = 587;
                $mail->CharSet    = 'UTF-8';

                // Remitente y Destinatario
                $mail->setFrom('e1000villca@gmail.com', 'TekoPorã Bolivia'); 
                $mail->addAddress($email, $nombre); 

                // Diseño HTML del correo de Bienvenida
                $mail->isHTML(true);
                $mail->Subject = '¡Bienvenido a la comunidad TekoPorã!';
                $mail->Body    = "
                    <div style='font-family: Arial, sans-serif; max-width: 500px; margin: auto; padding: 30px; border-radius: 15px; background: #f4f7f6; text-align: center; border: 1px solid #e0e0e0;'>
                        <h2 style='color: #217F82; margin-top: 0;'>¡Hola, {$nombre}!</h2>
                        <p style='color: #2c3e50; font-size: 16px;'>Gracias por registrarte en <strong>TekoPorã Bolivia</strong>.</p>
                        <p style='color: #2c3e50; font-size: 15px;'>Tu cuenta ha sido creada con éxito. Ahora eres parte de nuestra comunidad ciudadana. Ya puedes participar en nuestro foro, explorar los proyectos municipales y utilizar a nuestra Inteligencia Artificial.</p>
                        <div style='margin: 30px 0;'>
                            <a href='http://localhost/Tekopora_F/login' style='background-color: #2A6F97; color: #ffffff; padding: 12px 25px; border-radius: 8px; text-decoration: none; font-weight: bold; box-shadow: 0 4px 6px rgba(0,0,0,0.1);'>Iniciar Sesión</a>
                        </div>
                        <hr style='border: none; border-top: 1px solid #ddd; margin: 25px 0;' />
                        <p style='font-size: 0.75em; color: #95a5a6;'>Este es un correo generado automáticamente. Por favor, no respondas a este mensaje.</p>
                    </div>
                ";

                $mail->send();

            } catch (Exception $e) {
                // Si el correo falla, no detenemos el registro, solo lo anotamos en el log de PHP
                error_log("Error enviando correo de bienvenida: {$mail->ErrorInfo}");
            }
            // ==========================================

            $_SESSION['success'] = "Usuario registrado correctamente. Revisa tu correo de bienvenida.";
            header("Location: " . url('login'));
            exit();

        } catch (PDOException $e) {
            $_SESSION['error'] = "Error al registrar usuario";
            error_log($e->getMessage());
            header("Location: " . url('register'));
            exit();
        }
    }
}