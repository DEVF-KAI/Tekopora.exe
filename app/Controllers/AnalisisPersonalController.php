<?php

require_once __DIR__ . '/../helpers/auth.php';

class AnalisisPersonalController {

    public function perfil() {

        require_login(); // 🔒 debe estar logueado

        require __DIR__ . '/../../config/database.php';

        // 🔥 Usuario en sesión
        $idUsuario = $_SESSION['usuario']['id'];

        // =========================
        // 👤 DATOS DEL USUARIO
        // =========================
        $stmt = $conn->prepare("
            SELECT nombre, email, telefono, fechaRegistro
            FROM usuario
            WHERE idUsuario = ?
        ");
        $stmt->execute([$idUsuario]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        // =========================
        // 🏝️ SITIOS (PUBLICACIONES)
        // =========================
        $stmt = $conn->prepare("
            SELECT 
                titulo AS nombre,
                fechaPublicacion AS fecha,
                estado
            FROM publicacion
            WHERE idUsuario_FK = ?
            ORDER BY fechaPublicacion DESC
        ");
        $stmt->execute([$idUsuario]);
        $sitios = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // =========================
        // 🏗️ REPORTES DE PROYECTO
        // =========================
        $stmt = $conn->prepare("
            SELECT 
                p.nombreProyecto AS proyecto,
                r.porcentajeAvance AS avance,
                p.estado
            FROM reporteProyecto r
            INNER JOIN proyecto p ON r.idProyecto_FK = p.idProyecto
            WHERE r.idUsuario_FK = ?
            ORDER BY r.fechaReporte DESC
        ");
        $stmt->execute([$idUsuario]);
        $reportes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // =========================
        // 📜 ACTIVIDAD (BITÁCORA)
        // =========================
        $stmt = $conn->prepare("
            SELECT 
                accion AS descripcion,
                fechaHora AS fecha
            FROM bitacora
            WHERE idUsuario_FK = ?
            ORDER BY fechaHora DESC
            LIMIT 10
        ");
        $stmt->execute([$idUsuario]);
        $actividad = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // =========================
        // 🎯 CARGAR VISTA
        // =========================
        ob_start();
        include __DIR__ . '/../../views/analisis-personal.php';
        $content = ob_get_clean();

        $title = "Mi Perfil";

        include __DIR__ . '/../../views/layouts/app_layout.php';
    }
}