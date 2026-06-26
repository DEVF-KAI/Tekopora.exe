<?php
require_once __DIR__ . '/../helpers/auth.php';
require_once __DIR__ . '/../../app/helpers.php'; 

class ForoController {
    
    // vista inicial del foro
    public function index() {
        try {
            $dsn = "mysql:host=localhost;dbname=tekopora_db;charset=utf8";
            $pdo = new PDO($dsn, 'root', '');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // calculo de la aceptacion de un post, ya sea +1 o -1
            $sql = "
                SELECT 
                    p.idPublicacion AS id,
                    p.codigoPublicacion, --  Este sí se queda
                    p.titulo,
                    p.contenido,
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
                    multimedia m ON m.idPublicacion_FK = p.idPublicacion
                ORDER BY 
                    p.fechaPublicacion DESC
            ";

            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Obtener los comentarios para cada post
            $stmtComentarios = $pdo->prepare("
                SELECT c.idComentario, c.contenido, c.fecha, CONCAT(u.nombre, ' ', u.appPaterno) AS autor 
                FROM comentario c 
                JOIN usuario u ON c.idUsuario_FK = u.idUsuario 
                WHERE c.idPublicacion_FK = ? 
                ORDER BY c.fecha ASC
            ");

            foreach ($posts as &$post) {
                $post['fecha'] = date('d/m/Y H:i', strtotime($post['fecha']));
                
                // Cargamos los comentarios de este post en un array interno
                $stmtComentarios->execute([$post['id']]); 
                $post['comentarios'] = $stmtComentarios->fetchAll(PDO::FETCH_ASSOC);
            }
            unset($post);

            require_once __DIR__ . '/../../views/foro.php';

        } catch (PDOException $e) {
            die("Error de BD: " . $e->getMessage());
        }
    }

    // posteos con imagenes
    public function store() {
        require_login(); 
        $titulo = $_POST['titulo'] ?? '';
        $contenido = $_POST['contenido'] ?? '';
        $idUsuario = $_SESSION['usuario']['id'] ?? $_SESSION['usuario']['idUsuario']; 
        $codigo = 'PUB-' . strtoupper(substr(uniqid(), -8)); 

        try {
            $dsn = "mysql:host=localhost;dbname=tekopora_db;charset=utf8";
            $pdo = new PDO($dsn, 'root', '');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();

            $sqlPost = "INSERT INTO publicacion (codigoPublicacion, titulo, contenido, idUsuario_FK, idMunicipio_FK, idCategoria_FK, estado) 
                        VALUES (:codigo, :titulo, :contenido, :user, 1, 1, 'Activo')";
            $stmt = $pdo->prepare($sqlPost);
            $stmt->execute([':codigo' => $codigo, ':titulo' => $titulo, ':contenido' => $contenido, ':user' => $idUsuario]);
            $idPublicacion = $pdo->lastInsertId();

            if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
                // Limpiamos el nombre
                $nombreArchivo = time() . '_' . preg_replace("/[^a-zA-Z0-9.]/", "", basename($_FILES['imagen']['name']));
                
                // Definimos la estructura: Foro_imgs / Usuario_ID /
                $carpetaUsuario = 'Foro_imgs/Usuario_' . $idUsuario;
                $rutaCarpeta = __DIR__ . '/../../public/imgs/' . $carpetaUsuario . '/';
                
                // Si la carpeta del usuario no existe, la creamos
                if (!file_exists($rutaCarpeta)) {
                    mkdir($rutaCarpeta, 0777, true);
                }

                $rutaFinal = $rutaCarpeta . $nombreArchivo;

                // Movemos el archivo y guardamos la ruta en la base de datos
                if (move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaFinal)) {
                    $urlBD = 'imgs/' . $carpetaUsuario . '/' . $nombreArchivo;
                    $stmtMulti = $pdo->prepare("INSERT INTO multimedia (urlArchivo, tipo, idPublicacion_FK) VALUES (:url, 'imagen', :idPub)");
                    $stmtMulti->execute([':url' => $urlBD, ':idPub' => $idPublicacion]);
                }
            }

            $pdo->commit();
            header("Location: " . url('/foro?success=1'));
            exit();

        } catch (Exception $e) {
            if (isset($pdo)) $pdo->rollBack();
            die("Ocurrió un error: " . $e->getMessage());
        }
    }

    // ELIMINAR POSTEOS (Usa Código)
    public function eliminar() {
        tiene_permiso(['Administrador', 'Moderador Turismo', 'Moderador Obras']); 
        $codigoPost = $_GET['codigo'] ?? null; 

        if ($codigoPost) {
            try {
                $pdo = new PDO("mysql:host=localhost;dbname=tekopora_db;charset=utf8", 'root', '');
                $pdo->prepare("DELETE FROM publicacion WHERE codigoPublicacion = ?")->execute([$codigoPost]);
            } catch (PDOException $e) {}
        }
        header("Location: " . url('/foro'));
        exit();
    }

    // GUARDAR COMENTARIO (Vuelto a la normalidad, sin código)
    public function comentar() {
        require_login();
        $idPublicacion = $_POST['idPublicacion'] ?? null; 
        $contenido = trim($_POST['contenido'] ?? '');
        $idUsuario = $_SESSION['usuario']['id'];

        if ($idPublicacion && $contenido) {
            try {
                $pdo = new PDO("mysql:host=localhost;dbname=tekopora_db;charset=utf8", 'root', '');
                $stmt = $pdo->prepare("INSERT INTO comentario (contenido, idUsuario_FK, idPublicacion_FK) VALUES (?, ?, ?)");
                $stmt->execute([$contenido, $idUsuario, $idPublicacion]);
            } catch (PDOException $e) { die($e->getMessage()); }
        }
        header("Location: " . url('/foro'));
        exit();
    }

    // sistema de votacion karma
    public function votar() {
        if (empty($_SESSION['usuario'])) {
            echo json_encode(['error' => 'Debes iniciar sesión']); exit;
        }

        $idUsuario = $_SESSION['usuario']['id'];
        $idPublicacion = $_POST['idPublicacion']; 
        $tipoVoto = (int)$_POST['tipoVoto'];

        try {
            $pdo = new PDO("mysql:host=localhost;dbname=tekopora_db;charset=utf8", 'root', '');
            
            // Buscar al autor del post para actualizar su Karma
            $stmtAutor = $pdo->prepare("SELECT idUsuario_FK FROM publicacion WHERE idPublicacion = ?");
            $stmtAutor->execute([$idPublicacion]);
            $idAutor = $stmtAutor->fetchColumn();

            // Ver si ya votó antes
            $stmt = $pdo->prepare("SELECT idVoto, tipoVoto FROM voto WHERE idUsuario_FK = ? AND idPublicacion_FK = ?");
            $stmt->execute([$idUsuario, $idPublicacion]);
            $votoPrevio = $stmt->fetch();

            if ($votoPrevio) {
                if ($votoPrevio['tipoVoto'] == $tipoVoto) {
                    $pdo->prepare("DELETE FROM voto WHERE idVoto = ?")->execute([$votoPrevio['idVoto']]);
                    $pdo->prepare("UPDATE usuario SET karmaTotal = karmaTotal - ? WHERE idUsuario = ?")->execute([$tipoVoto, $idAutor]);
                } else {
                    $pdo->prepare("UPDATE voto SET tipoVoto = ? WHERE idVoto = ?")->execute([$tipoVoto, $votoPrevio['idVoto']]);
                    $pdo->prepare("UPDATE usuario SET karmaTotal = karmaTotal + ? WHERE idUsuario = ?")->execute([$tipoVoto * 2, $idAutor]);
                }
            } else {
                $pdo->prepare("INSERT INTO voto (tipoVoto, idUsuario_FK, idPublicacion_FK) VALUES (?, ?, ?)")->execute([$tipoVoto, $idUsuario, $idPublicacion]);
                $pdo->prepare("UPDATE usuario SET karmaTotal = karmaTotal + ? WHERE idUsuario = ?")->execute([$tipoVoto, $idAutor]);
            }

            $suma = $pdo->prepare("SELECT COALESCE(SUM(tipoVoto), 0) FROM voto WHERE idPublicacion_FK = ?");
            $suma->execute([$idPublicacion]);
            echo json_encode(['success' => true, 'votos' => $suma->fetchColumn()]);

        } catch (PDOException $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
        exit();
    }

    // ELIMINAR COMENTARIOS (Vuelto a usar ID)
    public function eliminarComentario() {
        tiene_permiso(['Administrador', 'Moderador Turismo', 'Moderador Obras']); 
        
        $idComentario = $_GET['id'] ?? null; // Volvemos a recibir ID

        if ($idComentario) {
            try {
                $pdo = new PDO("mysql:host=localhost;dbname=tekopora_db;charset=utf8", 'root', '');
                $stmt = $pdo->prepare("DELETE FROM comentario WHERE idComentario = ?");
                $stmt->execute([$idComentario]);
            } catch (PDOException $e) {
            }
        }

        // Devolvemos al foro
        header("Location: " . url('/foro'));
        exit();
    }
}
?>