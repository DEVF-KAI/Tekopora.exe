<?php
require_once __DIR__ . '/../helpers/auth.php';
require_once __DIR__ . '/../../app/helpers.php'; 

class ForoController {
    
    // VISTA INICIAL DEL FORO
    public function index() {
        try {
            $dsn = "mysql:host=db;dbname=tekopora_db;charset=utf8";
            $pdo = new PDO($dsn, 'root', '');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "
                SELECT 
                    p.idPublicacion AS id,
                    p.codigoPublicacion,
                    p.titulo,
                    p.contenido,
                    c.nombre AS nombre_categoria,
                    CONCAT(u.nombre, ' ', u.appPaterno) AS autor,
                    u.karmaTotal AS karma_autor,
                    p.fechaPublicacion AS fecha,
                    m.urlArchivo AS imagen_url,
                    COALESCE((SELECT SUM(tipoVoto) FROM voto v WHERE v.idPublicacion_FK = p.idPublicacion), 0) AS votos,
                    (SELECT COUNT(*) FROM comentario c WHERE c.idPublicacion_FK = p.idPublicacion) AS num_comentarios
                FROM 
                    publicacion p
                JOIN 
                    usuario u ON p.idUsuario_FK = u.idUsuario
                LEFT JOIN 
                    categoriaturistica c ON p.idCategoria_FK = c.idCategoria
                LEFT JOIN 
                    multimedia m ON m.idPublicacion_FK = p.idPublicacion
                ORDER BY 
                    p.fechaPublicacion DESC
            ";

            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $stmtComentarios = $pdo->prepare("
                SELECT 
                    c.idComentario, 
                    c.contenido, 
                    c.fecha, 
                    CONCAT(u.nombre, ' ', u.appPaterno) AS autor,
                    COALESCE((SELECT SUM(tipoVoto) FROM voto v WHERE v.idComentario_FK = c.idComentario), 0) AS votos
                FROM comentario c 
                JOIN usuario u ON c.idUsuario_FK = u.idUsuario 
                WHERE c.idPublicacion_FK = ? 
                ORDER BY c.fecha ASC
            ");

            foreach ($posts as &$post) {
                $post['fecha'] = date('d/m/Y H:i', strtotime($post['fecha']));
                $stmtComentarios->execute([$post['id']]); 
                $post['comentarios'] = $stmtComentarios->fetchAll(PDO::FETCH_ASSOC);
            }
            unset($post);

            require_once __DIR__ . '/../../views/foro.php';

        } catch (PDOException $e) {
            die("Error de BD: " . $e->getMessage());
        }
    }

    // CREAR PUBLICACIÓN
    public function store() {
        require_login(); 
        $titulo = $_POST['titulo'] ?? '';
        $contenido = $_POST['contenido'] ?? '';
        $idCategoria = $_POST['idCategoria_FK'] ?? 1; 
        
        $idUsuario = $_SESSION['usuario']['id'] ?? $_SESSION['usuario']['idUsuario']; 
        $codigo = 'PUB-' . strtoupper(substr(uniqid(), -8)); 

        try {
            $dsn = "mysql:host=db;dbname=tekopora_db;charset=utf8";
            $pdo = new PDO($dsn, 'root', '');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();

            $sqlPost = "INSERT INTO publicacion (codigoPublicacion, titulo, contenido, idUsuario_FK, idMunicipio_FK, idCategoria_FK, estado) 
                        VALUES (:codigo, :titulo, :contenido, :user, 1, :categoria, 'Activo')";
            $stmt = $pdo->prepare($sqlPost);
            $stmt->execute([
                ':codigo' => $codigo, 
                ':titulo' => $titulo, 
                ':contenido' => $contenido, 
                ':user' => $idUsuario,
                ':categoria' => $idCategoria
            ]);
            $idPublicacion = $pdo->lastInsertId();

            if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
                $nombreArchivo = time() . '_' . preg_replace("/[^a-zA-Z0-9.]/", "", basename($_FILES['imagen']['name']));
                $carpetaUsuario = 'Foro_imgs/Usuario_' . $idUsuario;
                $rutaCarpeta = __DIR__ . '/../../public/imgs/' . $carpetaUsuario . '/';
                
                if (!file_exists($rutaCarpeta)) {
                    mkdir($rutaCarpeta, 0777, true);
                }

                $rutaFinal = $rutaCarpeta . $nombreArchivo;

                if (move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaFinal)) {
                    $urlBD = 'imgs/' . $carpetaUsuario . '/' . $nombreArchivo;
                    $stmtMulti = $pdo->prepare("INSERT INTO multimedia (urlArchivo, tipo, idPublicacion_FK) VALUES (:url, 'imagen', :idPub)");
                    $stmtMulti->execute([':url' => $urlBD, ':idPub' => $idPublicacion]);
                }
            }

            $pdo->commit();

            // REGISTRO EN BITÁCORA (Publicar)
            if (function_exists('registrarActividad')) {
                registrarActividad($idUsuario, "Publicó en el foro: " . $titulo);
            }

            header("Location: " . url('/foro?success=1'));
            exit();

        } catch (Exception $e) {
            if (isset($pdo)) $pdo->rollBack();
            die("Ocurrió un error al publicar: " . $e->getMessage());
        }
    }

    // ELIMINAR POSTEOS
    public function eliminar() {
        tiene_permiso(['Administrador', 'Moderador Turismo']); 
        $codigoPost = $_GET['codigo'] ?? null; 

        if ($codigoPost) {
            try {
                $pdo = new PDO("mysql:host=db;dbname=tekopora_db;charset=utf8", 'root', '');
                $pdo->prepare("DELETE FROM publicacion WHERE codigoPublicacion = ?")->execute([$codigoPost]);
                
                // REGISTRO EN BITÁCORA (Eliminar Publicación)
                if (isset($_SESSION['usuario']) && function_exists('registrarActividad')) {
                    $idUsr = $_SESSION['usuario']['idUsuario'] ?? $_SESSION['usuario']['id'];
                    registrarActividad($idUsr, "Eliminó la publicación del foro con código: " . $codigoPost);
                }
            } catch (PDOException $e) {}
        }
        header("Location: " . url('/foro'));
        exit();
    }

    // GUARDAR COMENTARIO
    public function comentar() {
        require_login();
        $idPublicacion = $_POST['idPublicacion_FK'] ?? null; 
        $contenido = trim($_POST['contenido'] ?? '');
        $idUsuario = $_SESSION['usuario']['id'] ?? $_SESSION['usuario']['idUsuario'];

        if ($idPublicacion && $contenido) {
            try {
                $pdo = new PDO("mysql:host=db;dbname=tekopora_db;charset=utf8", 'root', '');
                $stmt = $pdo->prepare("INSERT INTO comentario (contenido, idUsuario_FK, idPublicacion_FK) VALUES (?, ?, ?)");
                $stmt->execute([$contenido, $idUsuario, $idPublicacion]);

                // REGISTRO EN BITÁCORA (Comentar)
                if (function_exists('registrarActividad')) {
                    registrarActividad($idUsuario, "Comentó en una publicación del foro");
                }
            } catch (PDOException $e) { die("Error al comentar: " . $e->getMessage()); }
        }
        header("Location: " . url('/foro'));
        exit();
    }

    // SISTEMA DE VOTACIÓN KARMA
    public function votar() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        
        header('Content-Type: application/json');

        if (empty($_SESSION['usuario'])) {
            echo json_encode(['error' => 'Debes iniciar sesión para votar.']); 
            exit;
        }

        $idUsuario = $_SESSION['usuario']['id'] ?? $_SESSION['usuario']['idUsuario'];
        
        $data = json_decode(file_get_contents('php://input'), true);
        $tipoEntidad = $_POST['tipoEntidad'] ?? $data['tipoEntidad'] ?? null;
        $idEntidad = (int)($_POST['idEntidad'] ?? $data['idEntidad'] ?? 0); 
        $tipoVoto = (int)($_POST['tipoVoto'] ?? $data['tipoVoto'] ?? 0);

        if (!$tipoEntidad || !$idEntidad) {
            echo json_encode(['error' => 'Faltan datos para procesar tu voto.']);
            exit;
        }

        try {
            $pdo = new PDO("mysql:host=db;dbname=tekopora_db;charset=utf8", 'root', '');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            if ($tipoEntidad === 'publicacion') {
                $stmtAutor = $pdo->prepare("SELECT idUsuario_FK FROM publicacion WHERE idPublicacion = ?");
                $stmtVoto = $pdo->prepare("SELECT idVoto, tipoVoto FROM voto WHERE idUsuario_FK = ? AND idPublicacion_FK = ?");
                $stmtVotoParams = [$idUsuario, $idEntidad];
                $sqlInsertVoto = "INSERT INTO voto (tipoVoto, idUsuario_FK, idPublicacion_FK) VALUES (?, ?, ?)";
                $sqlSuma = "SELECT COALESCE(SUM(tipoVoto), 0) FROM voto WHERE idPublicacion_FK = ?";
            } else {
                $stmtAutor = $pdo->prepare("SELECT idUsuario_FK FROM comentario WHERE idComentario = ?");
                $stmtVoto = $pdo->prepare("SELECT idVoto, tipoVoto FROM voto WHERE idUsuario_FK = ? AND idComentario_FK = ?");
                $stmtVotoParams = [$idUsuario, $idEntidad];
                $sqlInsertVoto = "INSERT INTO voto (tipoVoto, idUsuario_FK, idComentario_FK) VALUES (?, ?, ?)";
                $sqlSuma = "SELECT COALESCE(SUM(tipoVoto), 0) FROM voto WHERE idComentario_FK = ?";
            }

            $stmtAutor->execute([$idEntidad]);
            $idAutor = $stmtAutor->fetchColumn();

            $stmtVoto->execute($stmtVotoParams);
            $votoPrevio = $stmtVoto->fetch();

            if ($votoPrevio) {
                if ($votoPrevio['tipoVoto'] == $tipoVoto) {
                    $pdo->prepare("DELETE FROM voto WHERE idVoto = ?")->execute([$votoPrevio['idVoto']]);
                    $pdo->prepare("UPDATE usuario SET karmaTotal = karmaTotal - ? WHERE idUsuario = ?")->execute([$tipoVoto, $idAutor]);
                } else {
                    $pdo->prepare("UPDATE voto SET tipoVoto = ? WHERE idVoto = ?")->execute([$tipoVoto, $votoPrevio['idVoto']]);
                    $pdo->prepare("UPDATE usuario SET karmaTotal = karmaTotal + ? WHERE idUsuario = ?")->execute([$tipoVoto * 2, $idAutor]);
                }
            } else {
                $pdo->prepare($sqlInsertVoto)->execute([$tipoVoto, $idUsuario, $idEntidad]);
                $pdo->prepare("UPDATE usuario SET karmaTotal = karmaTotal + ? WHERE idUsuario = ?")->execute([$tipoVoto, $idAutor]);
            }

            // REGISTRO EN BITÁCORA (Votar)
            if (function_exists('registrarActividad')) {
                registrarActividad($idUsuario, "Votó (" . ($tipoVoto > 0 ? '+1' : '-1') . ") en una " . $tipoEntidad . " del foro");
            }

            $suma = $pdo->prepare($sqlSuma);
            $suma->execute([$idEntidad]);
            echo json_encode(['success' => true, 'votos' => $suma->fetchColumn()]);

        } catch (PDOException $e) {
            echo json_encode(['error' => 'Error BD: ' . $e->getMessage()]);
        }
        exit();
    }

    // ELIMINAR COMENTARIOS
    public function eliminarComentario() {
        // Asignamos permisos al administrador y al moderador de turismo
        tiene_permiso(['Administrador', 'Moderador Turismo']); 
        
        $idComentario = $_GET['id'] ?? null; 

        if ($idComentario) {
            try {
                $pdo = new PDO("mysql:host=db;dbname=tekopora_db;charset=utf8", 'root', '');
                $stmt = $pdo->prepare("DELETE FROM comentario WHERE idComentario = ?");
                $stmt->execute([$idComentario]);

                // Registro en bitácora: Eliminar Comentario
                if (isset($_SESSION['usuario']) && function_exists('registrarActividad')) {
                    $idUsr = $_SESSION['usuario']['idUsuario'] ?? $_SESSION['usuario']['id'];
                    registrarActividad($idUsr, "Eliminó un comentario del foro");
                }
            } catch (PDOException $e) {}
        }
        header("Location: " . url('/foro'));
        exit();
    }
}
?>