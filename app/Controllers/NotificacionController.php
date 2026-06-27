<?php
require_once __DIR__ . '/../helpers/auth.php';

class NotificacionController {
    
    // Obtener notificaciones del usuario logueado
    public function obtener() {
        header('Content-Type: application/json');
        
        if (empty($_SESSION['usuario'])) {
            echo json_encode(['count' => 0, 'data' => []]);
            exit;
        }

        $idUsuario = $_SESSION['usuario']['id'] ?? $_SESSION['usuario']['idUsuario'];

        try {
            $pdo = new PDO("mysql:host=db;dbname=tekopora_db;charset=utf8", 'root', '');
            
            $sql = "SELECT n.idNotificacion, n.titulo, n.mensaje, n.fechaCreacion, un.leida 
                    FROM notificacion n
                    JOIN usuario_notificacion un ON n.idNotificacion = un.idNotificacion_FK
                    WHERE un.idUsuario_FK = ? 
                    ORDER BY n.fechaCreacion DESC LIMIT 10";
                    
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$idUsuario]);
            $notificaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Contamos cuántas NO han sido leídas
            $unreadCount = 0;
            foreach ($notificaciones as $n) {
                if (!$n['leida']) $unreadCount++;
            }

            echo json_encode(['count' => $unreadCount, 'data' => $notificaciones]);
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    // Marcar TODAS las notificaciones del usuario como leídas al abrir la campana
    public function marcarLeidas() {
        header('Content-Type: application/json');
        if (empty($_SESSION['usuario'])) {
            echo json_encode(['error' => 'No autorizado']);
            exit;
        }

        $idUsuario = $_SESSION['usuario']['id'] ?? $_SESSION['usuario']['idUsuario'];
        
        try {
            $pdo = new PDO("mysql:host=db;dbname=tekopora_db;charset=utf8", 'root', '');
            // Actualizamos la tabla pivote
            $sql = "UPDATE usuario_notificacion 
                    SET leida = 1, fechaLectura = NOW() 
                    WHERE idUsuario_FK = ? AND leida = 0";
            $pdo->prepare($sql)->execute([$idUsuario]);
            
            echo json_encode(['success' => true]);
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}
?>