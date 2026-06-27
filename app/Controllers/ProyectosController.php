<?php

class ProyectosController
{
    public function proyectos()
    {
        if (session_status() === PHP_SESSION_NONE)
            session_start();

        try {
            $pdo = new PDO("mysql:host=db;dbname=tekopora_db;charset=utf8", 'root', '');

            $stmtMacro = $pdo->query("SELECT idMacrodistrito, nombreMacrodistrito FROM macrodistrito");
            $macrodistritos = $stmtMacro->fetchAll(PDO::FETCH_ASSOC);

            $sqlProyectos = "
                SELECT p.*, 
                       (SELECT urlArchivo FROM multimedia WHERE idProyecto_FK = p.idProyecto ORDER BY idMultimedia DESC LIMIT 1) AS imagen_url,
                       CONCAT(u.nombre, ' ', u.appPaterno) as moderador
                FROM proyecto p
                LEFT JOIN usuario u ON p.idUsuario_FK = u.idUsuario
                ORDER BY p.fechaInicio DESC
            ";

            $stmtProy = $pdo->query($sqlProyectos);
            $proyectos = $stmtProy->fetchAll(PDO::FETCH_ASSOC);

            $title = "Catálogo de Obras - TekoPorã";

            ob_start();
            require __DIR__ . '/../../views/proyectos.php';
            $content = ob_get_clean();

            require __DIR__ . '/../../views/layouts/app_layout.php';

        } catch (PDOException $e) {
            die("Error en la base de datos: " . $e->getMessage());
        }
    }

    public function login()
    {
        require __DIR__ . '/../../views/login.php';
    }

    public function misObras()
    {
        if (session_status() === PHP_SESSION_NONE)
            session_start();

        if (!isset($_SESSION['usuario']) || !in_array($_SESSION['usuario']['rol'], ['Administrador', 'Moderador Obra'])) {
            header("Location: " . url('/?error=Acceso Denegado'));
            exit();
        }

        $idUsuario = $_SESSION['usuario']['idUsuario'] ?? $_SESSION['usuario']['id'];

        try {
            $pdo = new PDO("mysql:host=db;dbname=tekopora_db;charset=utf8", 'root', '');

            $stmt = $pdo->prepare("
                SELECT idProyecto, codigoProyecto, nombreProyecto, avancePorcentaje, estado, fechaInicio 
                FROM proyecto 
                WHERE idUsuario_FK = ?
                ORDER BY fechaInicio DESC
            ");
            $stmt->execute([$idUsuario]);
            $proyectosAsignados = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $title = "Mis Obras Asignadas - TekoPorã";

            ob_start();
            require __DIR__ . '/../../views/obras_moderador.php';
            $content = ob_get_clean();
            require __DIR__ . '/../../views/layouts/app_layout.php';

        } catch (PDOException $e) {
            header("Location: " . url('/?error=Error en el servidor'));
        }
    }

    public function guardarReporte()
    {
        if (session_status() === PHP_SESSION_NONE)
            session_start();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_SESSION['usuario']) || !in_array($_SESSION['usuario']['rol'], ['Administrador', 'Moderador Obra'])) {
                header("Location: " . url('/?error=Acceso Denegado'));
                exit();
            }

            $codigoProyecto = $_POST['codigoProyecto'] ?? null;
            $nuevoAvance = $_POST['porcentajeAvance'] ?? 0;
            $descripcion = $_POST['descripcion'] ?? '';
            $idUsuario = $_SESSION['usuario']['idUsuario'] ?? $_SESSION['usuario']['id'];

            if ($codigoProyecto && $descripcion) {
                try {
                    $pdo = new PDO("mysql:host=db;dbname=tekopora_db;charset=utf8", 'root', '');
                    $pdo->beginTransaction();

                    $stmtId = $pdo->prepare("SELECT idProyecto FROM proyecto WHERE codigoProyecto = ? LIMIT 1");
                    $stmtId->execute([$codigoProyecto]);
                    $idProyecto = $stmtId->fetchColumn();

                    if (!$idProyecto)
                        throw new Exception("Proyecto no encontrado");

                    $stmtReporte = $pdo->prepare("INSERT INTO reporteProyecto (descripcion, porcentajeAvance, idProyecto_FK, idUsuario_FK) VALUES (?, ?, ?, ?)");
                    $stmtReporte->execute([$descripcion, $nuevoAvance, $idProyecto, $idUsuario]);

                    $estadoActualizado = ($nuevoAvance == 100) ? 'Completado' : 'En ejecución';
                    $stmtUpdate = $pdo->prepare("UPDATE proyecto SET avancePorcentaje = ?, estado = ? WHERE idProyecto = ?");
                    $stmtUpdate->execute([$nuevoAvance, $estadoActualizado, $idProyecto]);
                    
                    if (isset($_FILES['imagenReporte']) && $_FILES['imagenReporte']['error'] === UPLOAD_ERR_OK) {
                        $ext = pathinfo($_FILES['imagenReporte']['name'], PATHINFO_EXTENSION);
                        $nombreArchivo = time() . '_reporte.' . $ext;
                        $rutaCarpeta = __DIR__ . '/../../public/imgs/Proyectos_imgs/' . $codigoProyecto . '/';

                        if (!file_exists($rutaCarpeta))
                            mkdir($rutaCarpeta, 0777, true);

                        if (move_uploaded_file($_FILES['imagenReporte']['tmp_name'], $rutaCarpeta . $nombreArchivo)) {
                            $urlBD = 'imgs/Proyectos_imgs/' . $codigoProyecto . '/' . $nombreArchivo;
                            $stmtMulti = $pdo->prepare("INSERT INTO multimedia (urlArchivo, tipo, idProyecto_FK) VALUES (?, 'imagen', ?)");
                            $stmtMulti->execute([$urlBD, $idProyecto]);
                        }
                    }

                    $pdo->commit();

                    // 🌟 REGISTRO EN BITÁCORA (Avance de Obra)
                    if (function_exists('registrarActividad')) {
                        registrarActividad($idUsuario, "Reportó un avance del " . $nuevoAvance . "% en el proyecto: " . $codigoProyecto);
                    }

                    header("Location: " . url('/mis-obras?success=Reporte y fotografía guardados correctamente'));
                    exit();

                } catch (Exception $e) {
                    if (isset($pdo))
                        $pdo->rollBack();
                    header("Location: " . url('/mis-obras?error=Error al guardar el reporte'));
                    exit();
                }
            }
        }
        header("Location: " . url('/mis-obras'));
        exit();
    }
    
    public function detalleProyecto()
    {
        $codigo = $_GET['codigo'] ?? null;
        if (!$codigo) {
            header("Location: " . url('/proyectos'));
            exit();
        }

        try {
            $pdo = new PDO("mysql:host=db;dbname=tekopora_db;charset=utf8", 'root', '');

            $sql = "
            SELECT p.*, 
                   e.nombreEmpresa, e.telefono AS telEmpresa, e.direccion AS dirEmpresa, e.codigoEmpresa,
                   m.nombreMacrodistrito,
                   CONCAT(u.nombre, ' ', u.appPaterno) AS moderadorResponsable,
                   u.email AS emailModerador
            FROM proyecto p
            LEFT JOIN proyecto_empresa pe ON p.idProyecto = pe.idProyecto_FK
            LEFT JOIN empresaconstructora e ON pe.idEmpresa_FK = e.idEmpresa
            LEFT JOIN macrodistrito_proyecto mp ON p.idProyecto = mp.idProyecto_FK
            LEFT JOIN macrodistrito m ON mp.idMacrodistrito_FK = m.idMacrodistrito
            LEFT JOIN usuario u ON p.idUsuario_FK = u.idUsuario
            WHERE p.codigoProyecto = ?
        ";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([$codigo]);
            $proyecto = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$proyecto) {
                header("Location: " . url('/proyectos?error=Proyecto no encontrado'));
                exit();
            }

            $stmtImg = $pdo->prepare("SELECT urlArchivo FROM multimedia WHERE idProyecto_FK = ? ORDER BY idMultimedia ASC");
            $stmtImg->execute([$proyecto['idProyecto']]);
            $imagenes = $stmtImg->fetchAll(PDO::FETCH_ASSOC);

            $title = "Detalle: " . $proyecto['nombreProyecto'];

            ob_start();
            require __DIR__ . '/../../views/proyectos_detalle.php';
            $content = ob_get_clean();
            require __DIR__ . '/../../views/layouts/app_layout.php';

        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }

    public function subirFotoAdicional()
    {
        if (session_status() === PHP_SESSION_NONE)
            session_start();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_SESSION['usuario']) || !in_array($_SESSION['usuario']['rol'], ['Administrador', 'Moderador Obra'])) {
                header("Location: " . url('/?error=Acceso Denegado'));
                exit();
            }

            $idProyecto = $_POST['idProyecto'] ?? null;
            $codigoProyecto = $_POST['codigoProyecto'] ?? null;

            if ($idProyecto && $codigoProyecto && isset($_FILES['imagenRegistro']) && $_FILES['imagenRegistro']['error'] === UPLOAD_ERR_OK) {
                try {
                    $pdo = new PDO("mysql:host=db;dbname=tekopora_db;charset=utf8", 'root', '');
                    
                    $ext = pathinfo($_FILES['imagenRegistro']['name'], PATHINFO_EXTENSION);
                    $nombreArchivo = time() . '_registro.' . $ext;
                    $rutaCarpeta = __DIR__ . '/../../public/imgs/Proyectos_imgs/' . $codigoProyecto . '/';

                    if (!file_exists($rutaCarpeta)) {
                        mkdir($rutaCarpeta, 0777, true);
                    }

                    if (move_uploaded_file($_FILES['imagenRegistro']['tmp_name'], $rutaCarpeta . $nombreArchivo)) {
                        $urlBD = 'imgs/Proyectos_imgs/' . $codigoProyecto . '/' . $nombreArchivo;
                        $stmtMulti = $pdo->prepare("INSERT INTO multimedia (urlArchivo, tipo, idProyecto_FK) VALUES (?, 'imagen', ?)");
                        $stmtMulti->execute([$urlBD, $idProyecto]);
                        
                        if (function_exists('registrarActividad')) {
                            $idUsuario = $_SESSION['usuario']['idUsuario'] ?? $_SESSION['usuario']['id'];
                            registrarActividad($idUsuario, "Añadió una fotografía al proyecto " . $codigoProyecto);
                        }

                        header("Location: " . url('/proyectos/detalle?codigo=' . $codigoProyecto . '&success=Fotografía añadida al registro'));
                        exit();
                    }
                } catch (Exception $e) {
                    header("Location: " . url('/proyectos/detalle?codigo=' . $codigoProyecto . '&error=Error al procesar la imagen'));
                    exit();
                }
            }
            header("Location: " . url('/proyectos/detalle?codigo=' . $codigoProyecto . '&error=No se recibió ninguna imagen'));
            exit();
        }
    }

    public function evaluarEmpresa()
    {
        if (session_status() === PHP_SESSION_NONE)
            session_start();

        if (!isset($_SESSION['usuario'])) {
            header("Location: " . url('/login'));
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $codigoProyecto = $_POST['codigoProyecto'];
            $puntaje = (int) $_POST['puntaje'];
            $idUsuario = $_SESSION['usuario']['idUsuario'] ?? $_SESSION['usuario']['id'];

            if ($puntaje >= 1 && $puntaje <= 5) {
                try {
                    $pdo = new PDO("mysql:host=db;dbname=tekopora_db;charset=utf8", 'root', '');
                    $pdo->beginTransaction();

                    $stmtEmpresa = $pdo->prepare("
                    SELECT pe.idEmpresa_FK 
                    FROM proyecto p
                    JOIN proyecto_empresa pe ON p.idProyecto = pe.idProyecto_FK
                    WHERE p.codigoProyecto = ?
                ");
                    $stmtEmpresa->execute([$codigoProyecto]);
                    $idEmpresa = $stmtEmpresa->fetchColumn();

                    if ($idEmpresa) {
                        $stmtEval = $pdo->prepare("
                        INSERT INTO evaluacion_empresa (idUsuario_FK, idEmpresa_FK, puntaje) 
                        VALUES (?, ?, ?) 
                        ON DUPLICATE KEY UPDATE puntaje = ?
                    ");
                        $stmtEval->execute([$idUsuario, $idEmpresa, $puntaje, $puntaje]);

                        $stmtPromedio = $pdo->prepare("SELECT AVG(puntaje) FROM evaluacion_empresa WHERE idEmpresa_FK = ?");
                        $stmtPromedio->execute([$idEmpresa]);
                        $nuevoPromedio = $stmtPromedio->fetchColumn();

                        $stmtUpdate = $pdo->prepare("UPDATE empresaconstructora SET valoracionPromedio = ? WHERE idEmpresa = ?");
                        $stmtUpdate->execute([$nuevoPromedio, $idEmpresa]);
                        
                        if (function_exists('registrarActividad')) {
                            registrarActividad($idUsuario, "Evaluó con " . $puntaje . " estrellas a la empresa adjudicada del proyecto " . $codigoProyecto);
                        }

                        $pdo->commit();
                        header("Location: " . url('/proyectos/detalle?codigo=' . $codigoProyecto . '&success=Gracias por evaluar'));
                        exit();
                    }
                } catch (Exception $e) {
                    if (isset($pdo))
                        $pdo->rollBack();
                    header("Location: " . url('/proyectos/detalle?codigo=' . $codigoProyecto . '&error=Error al procesar tu voto'));
                    exit();
                }
            }
        }
    }
}