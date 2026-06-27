<?php

class AnalisisPersonalController {
    
    public function perfil() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        require_login(); 

        $idUsuario = $_SESSION['usuario']['idUsuario'] ?? $_SESSION['usuario']['id'] ?? null;

        if (!$idUsuario) {
            header("Location: " . url('/login'));
            exit();
        }

        require __DIR__ . '/../../config/database.php';

        try {
            // 1. Obtener datos básicos del usuario
            $stmt = $conn->prepare("SELECT * FROM usuario WHERE idUsuario = ?");
            $stmt->execute([$idUsuario]);
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            // 2. Obtener sitios agregados por este usuario
            $stmtSitios = $conn->prepare("SELECT nombre, fechaRegistro as fecha, estado FROM sitioturistico WHERE idUsuario_FK = ?");
            $stmtSitios->execute([$idUsuario]); 
            $sitios = $stmtSitios->fetchAll(PDO::FETCH_ASSOC);

            // 3. Obtener reportes de proyectos
            $stmtReportes = $conn->prepare("
                SELECT p.nombreProyecto as proyecto, r.porcentajeAvance as avance, p.estado 
                FROM reporteproyecto r
                JOIN proyecto p ON r.idProyecto_FK = p.idProyecto
                WHERE r.idUsuario_FK = ?
            ");
            $stmtReportes->execute([$idUsuario]);
            $reportes = $stmtReportes->fetchAll(PDO::FETCH_ASSOC);

            // 4. Actividad reciente (bitácora ampliada a 15 registros)
            // NO traemos la IP aquí por seguridad, solo la accion y fecha
            $stmtActividad = $conn->prepare("SELECT accion as descripcion, fechaHora as fecha FROM bitacora WHERE idUsuario_FK = ? ORDER BY fechaHora DESC LIMIT 15");
            $stmtActividad->execute([$idUsuario]);
            $actividad = $stmtActividad->fetchAll(PDO::FETCH_ASSOC);

            // 5. Historial del Foro (Publicaciones + Comentarios)
            $stmtForo = $conn->prepare("
                SELECT titulo AS descripcion, fechaPublicacion AS fecha, 'Publicación' AS tipo 
                FROM publicacion WHERE idUsuario_FK = ? 
                UNION ALL 
                SELECT contenido AS descripcion, fecha AS fecha, 'Comentario' AS tipo 
                FROM comentario WHERE idUsuario_FK = ? 
                ORDER BY fecha DESC
            ");
            $stmtForo->execute([$idUsuario, $idUsuario]);
            $foroActividad = $stmtForo->fetchAll(PDO::FETCH_ASSOC);

            ob_start();
            require __DIR__ . '/../../views/analisis-personal.php';
            $content = ob_get_clean();

            require __DIR__ . '/../../views/layouts/app_layout.php';

        } catch (PDOException $e) {
            error_log($e->getMessage());
            die("Error al cargar el perfil de TekoPorã.");
        }
    }
}
?>