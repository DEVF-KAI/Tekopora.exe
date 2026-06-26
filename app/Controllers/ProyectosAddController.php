<?php

class ProyectosaddController
{

    /**
     * Muestra el formulario con los catálogos (Macrodistritos, Empresas y Moderadores)
     */
    public function proyectosadd()
    {
        if (session_status() === PHP_SESSION_NONE)
            session_start();

        require __DIR__ . '/../../config/database.php';

        // 1. Cargamos catálogos
        $macrodistritos = $conn->query("SELECT idMacrodistrito, nombreMacrodistrito FROM macrodistrito")->fetchAll(PDO::FETCH_ASSOC);
        $empresas = $conn->query("SELECT idEmpresa, nombreEmpresa FROM empresaConstructora")->fetchAll(PDO::FETCH_ASSOC);

        // 2. Cargamos moderadores (Usuarios con rol 'Moderador Obra')
        $sqlMod = "SELECT u.idUsuario, u.nombre, u.appPaterno 
                   FROM usuario u
                   JOIN usuario_rol ur ON u.idUsuario = ur.idUsuario_FK
                   JOIN rol r ON ur.idRol_FK = r.idRol
                   WHERE r.nombre = 'Moderador Obra'";
        $moderadores = $conn->query($sqlMod)->fetchAll(PDO::FETCH_ASSOC);

        $title = "Registrar Proyecto - TekoPorã";

        ob_start();
        require __DIR__ . '/../../views/proyectosadd.php';
        $content = ob_get_clean();
        require __DIR__ . '/../../views/layouts/app_layout.php';
    }

    /**
     * Procesa el guardado: Proyecto + Imagen (Estructura Foro) + Asignación
     */
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST')
            return;

        require __DIR__ . '/../../config/database.php';
        if (session_status() === PHP_SESSION_NONE)
            session_start();

        // El ID de quien opera y el moderador asignado
        $idAdmin = $_SESSION['usuario']['idUsuario'] ?? $_SESSION['usuario']['id'] ?? null;
        $idModeradorAsignado = $_POST['idModerador'];

        if (!$idAdmin) {
            header("Location: " . url('/login?error=Sesión expirada'));
            exit();
        }

        $codigoProyecto = "PRJ-" . strtoupper(substr(md5(uniqid()), 0, 8));

        try {
            $conn->beginTransaction();

            // 1. Insertar Proyecto (idUsuario_FK = Moderador Responsable)
            $sqlProj = "INSERT INTO proyecto (
                codigoProyecto, nombreProyecto, descripcion, presupuesto, 
                fechaInicio, fechaEntregaEstimada, avancePorcentaje, 
                estado, latitud, longitud, idUsuario_FK
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = $conn->prepare($sqlProj);
            $stmt->execute([
                $codigoProyecto,
                $_POST['nombreProyecto'],
                $_POST['descripcion'],
                $_POST['presupuesto'],
                $_POST['fechaInicio'],
                $_POST['fechaEntregaEstimada'] ?: null,
                $_POST['avancePorcentaje'] ?? 0,
                $_POST['estado'],
                $_POST['latitud'],
                $_POST['longitud'],
                $idModeradorAsignado
            ]);

            $idNuevoProyecto = $conn->lastInsertId();

            // 2. LÓGICA DE IMAGEN (Carpeta propia por proyecto)
            // 2. LÓGICA DE IMAGEN (Carpeta propia por proyecto en Proyectos_imgs)
            if (isset($_FILES['imagenProyecto']) && $_FILES['imagenProyecto']['error'] === UPLOAD_ERR_OK) {

                $ext = pathinfo($_FILES['imagenProyecto']['name'], PATHINFO_EXTENSION);
                $nombreArchivo = time() . '_inicio.' . $ext;

                //  CAMBIO 1: Añadimos Proyectos_imgs a la ruta física
                $rutaCarpeta = __DIR__ . '/../../public/imgs/Proyectos_imgs/' . $codigoProyecto . '/';

                if (!file_exists($rutaCarpeta)) {
                    mkdir($rutaCarpeta, 0777, true);
                }

                $rutaFinal = $rutaCarpeta . $nombreArchivo;

                if (move_uploaded_file($_FILES['imagenProyecto']['tmp_name'], $rutaFinal)) {
                    // CAMBIO 2: Añadimos Proyectos_imgs a la ruta de la base de datos
                    $urlBD = 'imgs/Proyectos_imgs/' . $codigoProyecto . '/' . $nombreArchivo;

                    $stmtMulti = $conn->prepare("INSERT INTO multimedia (urlArchivo, tipo, idProyecto_FK) VALUES (:url, 'imagen', :idProy)");
                    $stmtMulti->execute([
                        ':url' => $urlBD,
                        ':idProy' => $idNuevoProyecto
                    ]);
                }
            }

            // 3. Vínculos con Macrodistrito y Empresa
            $conn->prepare("INSERT INTO macrodistrito_proyecto (idMacrodistrito_FK, idProyecto_FK) VALUES (?, ?)")
                ->execute([$_POST['idMacrodistrito'], $idNuevoProyecto]);

            $conn->prepare("INSERT INTO proyecto_empresa (idProyecto_FK, idEmpresa_FK) VALUES (?, ?)")
                ->execute([$idNuevoProyecto, $_POST['idEmpresa']]);

            $conn->commit();
            header("Location: " . url('/proyectos?success=Proyecto registrado y asignado correctamente'));
            exit();

        } catch (Exception $e) {
            if ($conn->inTransaction())
                $conn->rollBack();
            header("Location: " . url('/proyectosadd?error=' . urlencode($e->getMessage())));
            exit();
        }
    }
}