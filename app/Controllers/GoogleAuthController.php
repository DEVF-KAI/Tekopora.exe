<?php

class GoogleAuthController {
    
    // ==========================================
    // CONFIGURACIÓN DE CREDENCIALES
    // ==========================================
    private $clientId = '949490933707-dm44tpauiedn8bt4bk1uqid4me8hhvqr.apps.googleusercontent.com';
    private $clientSecret = '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';
    private $redirectUri = 'http://localhost/auth/google/callback';

    // ==========================================
    // REDIRIGIR A GOOGLE
    // ==========================================
    public function redirectToGoogle() {
        $url = "https://accounts.google.com/o/oauth2/v2/auth?" . http_build_query([
            'client_id' => $this->clientId,
            'redirect_uri' => $this->redirectUri,
            'response_type' => 'code',
            'scope' => 'email profile openid',
            'access_type' => 'online'
        ]);
        
        header("Location: $url");
        exit();
    }

    // ==========================================
    // RECIBIR RESPUESTA DE GOOGLE
    // ==========================================
    public function handleGoogleCallback() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_GET['code'])) {
            $_SESSION['error'] = "Autenticación cancelada por el usuario.";
            header("Location: " . url('/login'));
            exit();
        }

        // A. Intercambiar el código por un Token de Acceso
        $tokenData = $this->getAccessToken($_GET['code']);
        
        if (isset($tokenData['error'])) {
            $_SESSION['error'] = "Error al obtener el token de Google.";
            header("Location: " . url('/login'));
            exit();
        }

        // B. Obtener los datos del perfil del usuario usando el Token
        $userInfo = $this->getUserProfile($tokenData['access_token']);

        if (!$userInfo || !isset($userInfo['email'])) {
            $_SESSION['error'] = "No se pudieron obtener los datos de tu cuenta.";
            header("Location: " . url('/login'));
            exit();
        }

        // C. Lógica de Base de Datos (Iniciar sesión o Registrar)
        $this->loginOrRegisterUser($userInfo);
    }

    // ==========================================
    // MÉTODOS PRIVADOS DE PETICIONES cURL
    // ==========================================
    private function getAccessToken($code) {
        $ch = curl_init('https://oauth2.googleapis.com/token');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'redirect_uri' => $this->redirectUri,
            'grant_type' => 'authorization_code',
            'code' => $code
        ]));
        
        $response = curl_exec($ch);
        curl_close($ch);
        
        return json_decode($response, true);
    }

    private function getUserProfile($accessToken) {
        $ch = curl_init('https://www.googleapis.com/oauth2/v2/userinfo');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $accessToken
        ]);
        
        $response = curl_exec($ch);
        curl_close($ch);
        
        return json_decode($response, true);
    }

    // ==========================================
    // INTEGRACIÓN AL MODELO DE LA BASE DE DATOS
    // ==========================================
    private function loginOrRegisterUser($googleUser) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Conexión a la base de datos (ruta relativa desde la carpeta Controllers)
        require __DIR__ . '/../../config/database.php';

        $email = $googleUser['email'];
        $googleId = $googleUser['id'];
        $nombreCompleto = $googleUser['name'] ?? 'Usuario';
        
        // Separamos el nombre (asume que la primera palabra es el nombre)
        $partesNombre = explode(' ', $nombreCompleto, 2);
        $nombre = $partesNombre[0];
        $appMaterno = $partesNombre[1] ?? ''; 

        try {
            // Buscamos si el usuario ya existe en TekoPorã y traemos su rol
            $stmt = $conn->prepare("
                SELECT u.*, r.nombre as rol_nombre 
                FROM usuario u 
                LEFT JOIN usuario_rol ur ON u.idUsuario = ur.idUsuario_FK 
                LEFT JOIN rol r ON ur.idRol_FK = r.idRol 
                WHERE u.email = ? LIMIT 1
            ");
            $stmt->execute([$email]);
            $usuarioExiste = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($usuarioExiste) {
                // Si el correo existe pero no tiene google_id (se registró manual antes), lo vinculamos
                if (empty($usuarioExiste['google_id'])) {
                    $stmtUpdate = $conn->prepare("UPDATE usuario SET google_id = ? WHERE idUsuario = ?");
                    $stmtUpdate->execute([$googleId, $usuarioExiste['idUsuario']]);
                }
                
                // Creamos la sesión con el formato que espera tu auth.php
                $_SESSION['usuario'] = [
                    'idUsuario' => $usuarioExiste['idUsuario'],
                    'nombre' => $usuarioExiste['nombre'],
                    'email' => $usuarioExiste['email'],
                    'rol' => $usuarioExiste['rol_nombre'] ?? 'Ciudadano'
                ];

            } else {
                // REGISTRO DE USUARIO NUEVO
                $passwordFalsa = bin2hex(random_bytes(16));
                $hashArgon = password_hash($passwordFalsa, PASSWORD_ARGON2ID);
                $codigoUsuario = 'USR-' . strtoupper(substr(uniqid(), -8));

                // Insertamos en la tabla usuario (nota que omitimos CI y appPaterno)
                $stmtInsert = $conn->prepare("
                    INSERT INTO usuario 
                    (codigoUsuario, nombre, appMaterno, email, passwordHash, google_id)
                    VALUES (?, ?, ?, ?, ?, ?)
                ");
                $stmtInsert->execute([$codigoUsuario, $nombre, $appMaterno, $email, $hashArgon, $googleId]);
                $idNuevoUsuario = $conn->lastInsertId();

                // Buscamos el ID del rol Ciudadano
                $stmtIdRol = $conn->prepare("SELECT idRol FROM rol WHERE nombre = 'Ciudadano' LIMIT 1");
                $stmtIdRol->execute();
                $idRol = $stmtIdRol->fetchColumn();

                if ($idRol) {
                    $stmtRol = $conn->prepare("INSERT INTO usuario_rol (idUsuario_FK, idRol_FK) VALUES (?, ?)");
                    $stmtRol->execute([$idNuevoUsuario, $idRol]);
                }

                // Creamos la sesión para el usuario recién nacido
                $_SESSION['usuario'] = [
                    'idUsuario' => $idNuevoUsuario,
                    'nombre' => $nombre,
                    'email' => $email,
                    'rol' => 'Ciudadano'
                ];
            }

            // REGISTRO EN BITÁCORA (Login con Google exitoso)
            if (function_exists('registrarActividad')) {
                $idUsr = $_SESSION['usuario']['idUsuario'] ?? $_SESSION['usuario']['id'];
                registrarActividad($idUsr, "Inició sesión en el sistema mediante cuenta de Google");
            }

            // ¡Adentro! Redirigimos al inicio de la aplicación
            header("Location: " . url('/')); 
            exit();

        } catch (PDOException $e) {
            $_SESSION['error'] = "Error al autenticar con Google: " . $e->getMessage();
            error_log($e->getMessage());
            header("Location: " . url('/login'));
            exit();
        }
    }
}