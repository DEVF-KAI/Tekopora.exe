<?php

class RegisterController {
    
    public function register() {
        // 1. Captura la vista
        ob_start();
        require __DIR__ . '/../../config/database.php';

        // 🔥 FILTRAMOS: Solo traemos el rol de Ciudadano
        $stmt = $conn->prepare("SELECT idRol, nombre FROM rol WHERE nombre = ? LIMIT 1");
        $stmt->execute(['Ciudadano']);
        $roles = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        require __DIR__ . '/../../views/register.php';
        $content = ob_get_clean();

        // 2. Carga el layout
        require __DIR__ . '/../../views/layouts/app_layout.php';
    }

    public function login() {
        require __DIR__ . '/../../views/login.php';
    }
   
    public function store() {
        session_start();
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
            $rol = $stmtId->fetchColumn(); // Reemplaza la variable $rol por el ID seguro
            


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

            $_SESSION['success'] = "Usuario registrado correctamente";
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