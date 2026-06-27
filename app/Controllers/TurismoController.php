<?php
class TurismoController
{

    // ===================================================================
    // 1. VISTAS PÚBLICAS (Para cualquier usuario o visitante)
    // ===================================================================

   public function turismo() {
    try {
        if (session_status() === PHP_SESSION_NONE) session_start();
        
        $pdo = new PDO("mysql:host=db;dbname=tekopora_db;charset=utf8", 'root', '');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Traemos solo los sitios aprobados y sus fotos
        $sql = "
            SELECT s.*, 
                   m.urlArchivo AS imagen_url
            FROM sitioturistico s
            LEFT JOIN multimedia m ON s.idSitio = m.idSitio_FK
            WHERE s.estado = 'Aprobado' 
            ORDER BY s.fechaRegistro DESC
        ";

        $stmt = $pdo->query($sql);
        $sitiosAprobados = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $title = "Turismo La Paz - TekoPorã";
        
        // Cargamos la vista pasándole los datos
        ob_start();
        require __DIR__ . '/../../views/turismo.php';
        $content = ob_get_clean();
        require __DIR__ . '/../../views/layouts/app_layout.php';

    } catch (PDOException $e) {
        die("Error en el servidor: " . $e->getMessage());
    }
}

    public function destinos()
    {
        ob_start();
        require __DIR__ . '/../../views/destinos.php';
        $content = ob_get_clean();
        require __DIR__ . '/../../views/layouts/app_layout.php';
    }

    
    // 2. VISTAS DE MODERADOR (Solo Admin o Moderador Turismo)
   

    // Mostrar la bandeja de entrada al moderador
    public function validarSitios()
    {
        if (!isset($_SESSION['usuario']) || !in_array($_SESSION['usuario']['rol'], ['Administrador', 'Moderador Turismo'])) {
            header("Location: " . url('/?error=Acceso Denegado'));
            exit();
        }

        $sitiosPendientes = [];

        try {
            $pdo = new PDO("mysql:host=db;dbname=tekopora_db;charset=utf8", 'root', '');

            // Añadimos codigoPublicacion a la consulta para enviarlo a la vista
            $stmt = $pdo->prepare("
                SELECT p.idPublicacion, p.codigoPublicacion, p.titulo, p.contenido, p.fechaPublicacion, CONCAT(u.nombre, ' ', u.appPaterno) AS autor
                FROM publicacion p
                INNER JOIN usuario u ON p.idUsuario_FK = u.idUsuario
                WHERE p.estado = 'Pendiente'
                ORDER BY p.fechaPublicacion ASC
            ");
            $stmt->execute();
            $sitiosPendientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            // Manejo de error silencioso
        }

        require __DIR__ . '/../../views/validar_sitios.php';
    }

    //  Procesar el clic en "Aprobar" o "Rechazar" (Cambiado a usar Código)
    public function procesarValidacion()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_SESSION['usuario']) || !in_array($_SESSION['usuario']['rol'], ['Administrador', 'Moderador Turismo'])) {
                header("Location: " . url('/?error=Acceso Denegado'));
                exit();
            }

            // Recibimos el código en vez del ID
            $codigoPublicacion = $_POST['codigoPublicacion'] ?? null;
            $accion = $_POST['accion'] ?? null; // 'Aprobado' o 'Rechazado'

            if ($codigoPublicacion && in_array($accion, ['Aprobado', 'Rechazado'])) {
                try {
                    $pdo = new PDO("mysql:host=db;dbname=tekopora_db;charset=utf8", 'root', '');

                    // Actualizamos usando codigoPublicacion
                    $stmt = $pdo->prepare("UPDATE publicacion SET estado = ? WHERE codigoPublicacion = ?");
                    $stmt->execute([$accion, $codigoPublicacion]);

                    header("Location: " . url('/validar-sitios?success=Sitio ' . strtolower($accion) . ' correctamente'));
                    exit();

                } catch (PDOException $e) {
                    header("Location: " . url('/validar-sitios?error=Error al procesar la solicitud'));
                    exit();
                }
            }
        }

        header("Location: " . url('/validar-sitios'));
        exit();
    }
    // Muestra el formulario
    public function crear()
    {
        ob_start();
        require __DIR__ . '/../../views/turismo_add.php';
         $content = ob_get_clean();
        require __DIR__ . '/../../views/layouts/app_layout.php';
    }

    // Recibe los datos y los manda a revisión
    public function store()
    {
        if (session_status() === PHP_SESSION_NONE)
            session_start();

        if (!isset($_SESSION['usuario']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: " . url('/login'));
            exit();
        }

        $idUsuario = $_SESSION['usuario']['idUsuario'] ?? $_SESSION['usuario']['id'];
        $codigoSitio = "TUR-" . strtoupper(substr(md5(uniqid()), 0, 8));

        try {
            $pdo = new PDO("mysql:host=db;dbname=tekopora_db;charset=utf8", 'root', '');
            $pdo->beginTransaction();

            // 1. Insertamos el sitio con estado 'Pendiente' por defecto (establecido en la BD)
            $stmt = $pdo->prepare("
                INSERT INTO sitioturistico (codigoSitio, nombre, descripcion, latitud, longitud, idUsuario_FK) 
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $codigoSitio,
                $_POST['nombre'],
                $_POST['descripcion'],
                $_POST['latitud'],
                $_POST['longitud'],
                $idUsuario
            ]);
            $idSitio = $pdo->lastInsertId();

            // 2. Guardamos la imagen en su propia carpeta
            if (isset($_FILES['imagenSitio']) && $_FILES['imagenSitio']['error'] === UPLOAD_ERR_OK) {
                $ext = pathinfo($_FILES['imagenSitio']['name'], PATHINFO_EXTENSION);
                $nombreArchivo = time() . '_portada.' . $ext;

                $rutaCarpeta = __DIR__ . '/../../public/imgs/Turismo_imgs/' . $codigoSitio . '/';
                if (!file_exists($rutaCarpeta))
                    mkdir($rutaCarpeta, 0777, true);

                $rutaFinal = $rutaCarpeta . $nombreArchivo;

                if (move_uploaded_file($_FILES['imagenSitio']['tmp_name'], $rutaFinal)) {
                    $urlBD = 'imgs/Turismo_imgs/' . $codigoSitio . '/' . $nombreArchivo;
                    $stmtMulti = $pdo->prepare("INSERT INTO multimedia (urlArchivo, tipo, idSitio_FK) VALUES (?, 'imagen', ?)");
                    $stmtMulti->execute([$urlBD, $idSitio]);
                }
            }

            $pdo->commit();

            // REGISTRO EN BITÁCORA (Propuesta Ciudadana)
            if (function_exists('registrarActividad')) {
                registrarActividad($idUsuario, "Propuso un nuevo sitio turístico para revisión: " . $_POST['nombre']);
            }

            header("Location: " . url('/turismo?success=Tu propuesta fue enviada. Un moderador la revisará pronto.'));
            exit();

        } catch (Exception $e) {
            if (isset($pdo))
                $pdo->rollBack();
            header("Location: " . url('/turismo/crear?error=Error al procesar la solicitud'));
            exit();
        }
    }
    // 1. Muestra el panel de revisión exclusivo para Moderadores
    public function panelRevision() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        
        // Seguridad: Solo el Administrador o Moderador de Turismo entran aquí
        if (!isset($_SESSION['usuario']) || !in_array($_SESSION['usuario']['rol'], ['Administrador', 'Moderador Turismo'])) {
            header("Location: " . url('/?error=Acceso Denegado'));
            exit();
        }

        try {
            $pdo = new PDO("mysql:host=db;dbname=tekopora_db;charset=utf8", 'root', '');
            
            // Buscamos los sitios pendientes, quién lo propuso y su foto
            $sql = "
                SELECT s.*, 
                       CONCAT(u.nombre, ' ', u.appPaterno) AS proponente,
                       m.urlArchivo AS imagen_url
                FROM sitioturistico s
                JOIN usuario u ON s.idUsuario_FK = u.idUsuario
                LEFT JOIN multimedia m ON s.idSitio = m.idSitio_FK
                WHERE s.estado = 'Pendiente'
                ORDER BY s.fechaRegistro ASC
            ";
            
            $stmt = $pdo->query($sql);
            $sitiosPendientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $title = "Revisión de Propuestas - TekoPorã";
            
            ob_start();
            require __DIR__ . '/../../views/turismo_revision.php';
            $content = ob_get_clean();
            require __DIR__ . '/../../views/layouts/app_layout.php';

        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }

    // 2. Procesa la decisión (Aprobar o Rechazar)
    public function procesarPropuesta() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['usuario']) || !in_array($_SESSION['usuario']['rol'], ['Administrador', 'Moderador Turismo'])) {
            exit("Acceso Denegado");
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $idSitio = $_POST['idSitio'];
            $accion = $_POST['accion']; // Vendrá como 'Aprobar' o 'Rechazar'
            
            $nuevoEstado = ($accion === 'Aprobar') ? 'Aprobado' : 'Rechazado';

            try {
                $pdo = new PDO("mysql:host=db;dbname=tekopora_db;charset=utf8", 'root', '');
                $stmt = $pdo->prepare("UPDATE sitioturistico SET estado = ? WHERE idSitio = ?");
                $stmt->execute([$nuevoEstado, $idSitio]);

                // 🌟 REGISTRO EN BITÁCORA (Moderación de Turismo)
                if (function_exists('registrarActividad')) {
                    $idUsr = $_SESSION['usuario']['idUsuario'] ?? $_SESSION['usuario']['id'];
                    registrarActividad($idUsr, "Revisó una propuesta turística y la marcó como: " . $nuevoEstado);
                }

                $mensaje = ($nuevoEstado === 'Aprobado') ? "Sitio aprobado y publicado en el mapa." : "Propuesta rechazada.";
                header("Location: " . url('/turismo/revision?success=' . urlencode($mensaje)));
                exit();

            } catch (PDOException $e) {
                header("Location: " . url('/turismo/revision?error=Error al procesar'));
                exit();
            }
        }
    }
    // Historial de propuestas del usuario
    public function misPropuestas() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        
        // Si no está logueado, lo mandamos al login
        if (!isset($_SESSION['usuario'])) {
            header("Location: " . url('/login'));
            exit();
        }

        $idUsuario = $_SESSION['usuario']['idUsuario'] ?? $_SESSION['usuario']['id'];

        try {
            $pdo = new PDO("mysql:host=db;dbname=tekopora_db;charset=utf8", 'root', '');
            
            // Buscamos TODOS los sitios de este usuario específico
            $sql = "
                SELECT s.*, 
                       m.urlArchivo AS imagen_url
                FROM sitioturistico s
                LEFT JOIN multimedia m ON s.idSitio = m.idSitio_FK
                WHERE s.idUsuario_FK = ?
                ORDER BY s.fechaRegistro DESC
            ";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$idUsuario]);
            $misSitios = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $title = "Mis Propuestas de Turismo - TekoPorã";
            
            ob_start();
            require __DIR__ . '/../../views/turismo_mis_propuestas.php';
            $content = ob_get_clean();
            require __DIR__ . '/../../views/layouts/app_layout.php';

        } catch (PDOException $e) {
            die("Error en la base de datos: " . $e->getMessage());
        }
    }
}