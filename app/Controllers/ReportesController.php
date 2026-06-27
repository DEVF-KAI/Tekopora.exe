<?php
use Dompdf\Dompdf;
use Dompdf\Options;

require __DIR__ . '/../../vendor/autoload.php';

class ReportesController {

    public function generarAuditoria() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        require __DIR__ . '/../../config/database.php';

        if (empty($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'Administrador') {
            die("Acceso denegado. Solo el administrador puede generar auditorías.");
        }

        $area = $_GET['area'] ?? 'general';
        $html = '';
        
        date_default_timezone_set('America/La_Paz');
        $fechaActual = date('d/m/Y H:i');

        if ($area === 'usuarios') {
            $sql = "SELECT u.*, r.nombre AS nombre_rol 
                    FROM usuario u
                    LEFT JOIN usuario_rol ur ON u.idUsuario = ur.idUsuario_FK
                    LEFT JOIN rol r ON ur.idRol_FK = r.idRol
                    ORDER BY u.idUsuario DESC";
            
            $stmt = $conn->query($sql);
            $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $totalUsuarios = count($usuarios);
            
            $html = "
            <html>
            <head>
                <style>
                    body { font-family: 'Helvetica', sans-serif; color: #333; }
                    .header { text-align: center; border-bottom: 2px solid #217F82; padding-bottom: 10px; margin-bottom: 20px; }
                    .header h1 { color: #1e3c72; margin: 0; }
                    .header p { margin: 5px 0; color: #666; font-size: 12px; }
                    .stats-box { background: #f4f7f6; padding: 15px; border-radius: 8px; margin-bottom: 20px; text-align: center;}
                    table { width: 100%; border-collapse: collapse; font-size: 12px; margin-top: 15px; }
                    th { background-color: #217F82; color: white; padding: 10px; text-align: left; }
                    td { padding: 8px; border-bottom: 1px solid #ddd; }
                    .badge { padding: 3px 6px; border-radius: 4px; font-size: 10px; color: white; font-weight: bold;}
                    .bg-admin { background: #667eea; }
                    .bg-activo { background: #20bf55; }
                    .bg-suspendido { background: #e74c3c; }
                    .bg-default { background: #95a5a6; }
                </style>
            </head>
            <body>
                <div class='header'>
                    <h1>Auditoría de Usuarios - TekoPorã</h1>
                    <p>Documento generado automáticamente el: {$fechaActual}</p>
                    <p>Generado por: " . ($_SESSION['usuario']['nombre'] ?? 'Administrador') . "</p>
                </div>

                <div class='stats-box'>
                    <strong>Total de Usuarios Registrados en el Sistema:</strong> {$totalUsuarios}
                </div>

                <table>
                    <thead>
                        <tr>
                            <th>Ciudadano</th>
                            <th>Correo Electrónico</th>
                            <th>Rol</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>";

            foreach ($usuarios as $u) {
                $nombreCompleto = htmlspecialchars(trim(($u['nombre'] ?? 'Usuario') . ' ' . ($u['appPaterno'] ?? '')));
                $email = htmlspecialchars($u['email'] ?? 'Sin correo');
                $rolStr = $u['nombre_rol'] ?? 'Ciudadano'; 
                $estadoStr = $u['estado'] ?? 'Activo';

                $rolClase = ($rolStr === 'Administrador') ? 'bg-admin' : 'bg-default';
                $estadoClase = ($estadoStr === 'Activo') ? 'bg-activo' : 'bg-suspendido';

                $html .= "
                    <tr>
                        <td>{$nombreCompleto}</td>
                        <td>{$email}</td>
                        <td><span class='badge {$rolClase}'>{$rolStr}</span></td>
                        <td><span class='badge {$estadoClase}'>{$estadoStr}</span></td>
                    </tr>";
            }

            $html .= "
                    </tbody>
                </table>
            </body>
            </html>";
            
        } elseif ($area === 'empresas') {
            $stmt = $conn->query("SELECT nombre_empresa, nit, representante, email_contacto, estado FROM empresas ORDER BY idEmpresa DESC");
            $empresas = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $totalEmpresas = count($empresas);
            
            $html = "
            <html>
            <head>
                <style>
                    body { font-family: 'Helvetica', sans-serif; color: #333; }
                    .header { text-align: center; border-bottom: 2px solid #217F82; padding-bottom: 10px; margin-bottom: 20px; }
                    .header h1 { color: #1e3c72; margin: 0; }
                    .header p { margin: 5px 0; color: #666; font-size: 12px; }
                    .stats-box { background: #f4f7f6; padding: 15px; border-radius: 8px; margin-bottom: 20px; text-align: center;}
                    table { width: 100%; border-collapse: collapse; font-size: 12px; margin-top: 15px; }
                    th { background-color: #217F82; color: white; padding: 10px; text-align: left; }
                    td { padding: 8px; border-bottom: 1px solid #ddd; }
                    .badge { padding: 3px 6px; border-radius: 4px; font-size: 10px; color: white; font-weight: bold;}
                    .bg-activo { background: #20bf55; }
                    .bg-suspendido { background: #e74c3c; }
                </style>
            </head>
            <body>
                <div class='header'>
                    <h1>Auditoría de Empresas - TekoPorã</h1>
                    <p>Documento generado automáticamente el: {$fechaActual}</p>
                    <p>Generado por: " . ($_SESSION['usuario']['nombre'] ?? 'Administrador') . "</p>
                </div>

                <div class='stats-box'>
                    <strong>Total de Empresas Constructoras Registradas:</strong> {$totalEmpresas}
                </div>

                <table>
                    <thead>
                        <tr>
                            <th>Razón Social</th>
                            <th>NIT</th>
                            <th>Representante Legal</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>";

            foreach ($empresas as $e) {
                $nombreEmp = htmlspecialchars($e['nombre_empresa'] ?? 'N/A');
                $nit = htmlspecialchars($e['nit'] ?? 'N/A');
                $representante = htmlspecialchars($e['representante'] ?? 'N/A');
                $estado = $e['estado'] ?? 'Activo';

                $estadoClase = ($estado === 'Activo') ? 'bg-activo' : 'bg-suspendido';

                $html .= "
                    <tr>
                        <td><strong>{$nombreEmp}</strong></td>
                        <td>{$nit}</td>
                        <td>{$representante}</td>
                        <td><span class='badge {$estadoClase}'>{$estado}</span></td>
                    </tr>";
            }

            $html .= "
                    </tbody>
                </table>
            </body>
            </html>";
        
        } elseif ($area === 'proyectos') {
            $sql = "
                SELECT p.codigoProyecto, p.nombreProyecto, p.presupuesto, p.avancePorcentaje, p.estado, 
                       e.nombreEmpresa, m.nombreMacrodistrito
                FROM proyecto p
                LEFT JOIN proyecto_empresa pe ON p.idProyecto = pe.idProyecto_FK
                LEFT JOIN empresaconstructora e ON pe.idEmpresa_FK = e.idEmpresa 
                LEFT JOIN macrodistrito_proyecto mp ON p.idProyecto = mp.idProyecto_FK
                LEFT JOIN macrodistrito m ON mp.idMacrodistrito_FK = m.idMacrodistrito
                ORDER BY p.avancePorcentaje DESC
            ";
            
            $stmt = $conn->query($sql);
            $proyectos = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $totalProyectos = count($proyectos);
            $proyectosCompletados = 0;
            $sumaPresupuestos = 0;
            $sumaAvance = 0;

            foreach ($proyectos as $p) {
                if (($p['estado'] ?? '') === 'Completado' || ($p['avancePorcentaje'] ?? 0) >= 100) {
                    $proyectosCompletados++;
                }
                $sumaPresupuestos += floatval($p['presupuesto'] ?? 0); 
                $sumaAvance += floatval($p['avancePorcentaje'] ?? 0);
            }

            $promedioAvance = $totalProyectos > 0 ? round($sumaAvance / $totalProyectos, 2) : 0;
            $presupuestoTotalFormat = number_format($sumaPresupuestos, 2, '.', ',');

            $html = "
            <html>
            <head>
                <style>
                    body { font-family: 'Helvetica', sans-serif; color: #333; }
                    .header { text-align: center; border-bottom: 2px solid #217F82; padding-bottom: 10px; margin-bottom: 20px; }
                    .header h1 { color: #1e3c72; margin: 0; font-size: 24px; }
                    .header p { margin: 5px 0; color: #666; font-size: 12px; }
                    
                    .dashboard { width: 100%; margin-bottom: 20px; text-align: center; }
                    .box { display: inline-block; width: 30%; background: #f4f7f6; padding: 15px; border-radius: 8px; margin: 1%; box-sizing: border-box; }
                    .box-title { font-size: 10px; text-transform: uppercase; color: #7f8c8d; font-weight: bold; margin-bottom: 5px; }
                    .box-value { font-size: 18px; color: #217F82; font-weight: bold; }
                    
                    table { width: 100%; border-collapse: collapse; font-size: 10px; margin-top: 15px; }
                    th { background-color: #2c3e50; color: white; padding: 8px; text-align: left; }
                    td { padding: 8px; border-bottom: 1px solid #ddd; }
                    .badge { padding: 3px 6px; border-radius: 4px; font-size: 9px; color: white; font-weight: bold;}
                    .bg-completado { background: #27ae60; }
                    .bg-ejecucion { background: #f39c12; }
                    .bg-retrasado { background: #e74c3c; }
                    .progress-bg { background: #ecf0f1; border-radius: 10px; height: 8px; width: 100%; margin-top: 5px; }
                    .progress-bar { background: #2980b9; height: 8px; border-radius: 10px; }
                </style>
            </head>
            <body>
                <div class='header'>
                    <h1>Auditoría de Estado de Obras Públicas</h1>
                    <p>Documento generado automáticamente el: {$fechaActual}</p>
                    <p>Generador: " . ($_SESSION['usuario']['nombre'] ?? 'Administrador') . "</p>
                </div>

                <div class='dashboard'>
                    <div class='box'>
                        <div class='box-title'>Total de Obras</div>
                        <div class='box-value'>{$totalProyectos}</div>
                    </div>
                    <div class='box'>
                        <div class='box-title'>Avance General Ciudad</div>
                        <div class='box-value'>{$promedioAvance}%</div>
                    </div>
                    <div class='box'>
                        <div class='box-title'>Inversión Total (Bs.)</div>
                        <div class='box-value'>{$presupuestoTotalFormat}</div>
                    </div>
                </div>

                <table>
                    <thead>
                        <tr>
                            <th width='15%'>Código</th>
                            <th width='25%'>Nombre del Proyecto</th>
                            <th width='20%'>Empresa / Macrodistrito</th>
                            <th width='15%'>Estado</th>
                            <th width='25%'>Avance</th>
                        </tr>
                    </thead>
                    <tbody>";

            foreach ($proyectos as $p) {
                $codigo = htmlspecialchars($p['codigoProyecto'] ?? 'S/C');
                $nombreProj = htmlspecialchars($p['nombreProyecto'] ?? 'N/A');
                $empresa = htmlspecialchars($p['nombreEmpresa'] ?? 'Alcaldía (Directo)');
                $macro = htmlspecialchars($p['nombreMacrodistrito'] ?? 'No asignado');
                $estado = $p['estado'] ?? 'Pendiente';
                $avance = floatval($p['avancePorcentaje'] ?? 0);

                if ($avance == 100 || $estado == 'Completado') {
                    $estadoClase = 'bg-completado';
                } elseif ($avance < 20 && $estado == 'En ejecución') { 
                    $estadoClase = 'bg-retrasado';
                } else {
                    $estadoClase = 'bg-ejecucion';
                }

                $html .= "
                    <tr>
                        <td><strong>{$codigo}</strong></td>
                        <td>{$nombreProj}</td>
                        <td>
                            <strong>" . substr($empresa, 0, 20) . (strlen($empresa) > 20 ? '...' : '') . "</strong><br>
                            <span style='color: #7f8c8d; font-size: 8px;'>{$macro}</span>
                        </td>
                        <td><span class='badge {$estadoClase}'>{$estado}</span></td>
                        <td>
                            {$avance}%
                            <div class='progress-bg'>
                                <div class='progress-bar' style='width: {$avance}%;'></div>
                            </div>
                        </td>
                    </tr>";
            }

            $html .= "
                    </tbody>
                </table>
            </body>
            </html>";

        } elseif ($area === 'bitacora') {
            
            $usuariosSeleccionados = $_POST['usuarios_ids'] ?? []; 
            
            if (empty($usuariosSeleccionados)) {
                die("Debes seleccionar al menos un usuario para generar el reporte de bitácora.");
            }

            $placeholders = str_repeat('?,', count($usuariosSeleccionados) - 1) . '?';

            $sql = "
                SELECT 
                    b.accion, 
                    DATE_FORMAT(b.fechaHora, '%d/%m/%Y %H:%i:%s') as fecha_exacta, 
                    CONCAT(u.nombre, ' ', u.appPaterno) as nombre_completo,
                    r.nombre as rol
                FROM bitacora b
                JOIN usuario u ON b.idUsuario_FK = u.idUsuario
                LEFT JOIN usuario_rol ur ON u.idUsuario = ur.idUsuario_FK
                LEFT JOIN rol r ON ur.idRol_FK = r.idRol
                WHERE u.idUsuario IN ($placeholders)
                ORDER BY b.fechaHora DESC
            ";

            $stmt = $conn->prepare($sql);
            $stmt->execute($usuariosSeleccionados);
            $registros = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $totalRegistros = count($registros);

            $html = "
            <html>
            <head>
                <style>
                    body { font-family: 'Helvetica', sans-serif; color: #333; }
                    .header { text-align: center; border-bottom: 2px solid #217F82; padding-bottom: 10px; margin-bottom: 20px; }
                    .header h1 { color: #1e3c72; margin: 0; font-size: 24px; }
                    .header p { margin: 5px 0; color: #666; font-size: 12px; }
                    .stats-box { background: #f4f7f6; padding: 15px; border-radius: 8px; margin-bottom: 20px; text-align: center;}
                    table { width: 100%; border-collapse: collapse; font-size: 10px; margin-top: 15px; }
                    th { background-color: #2c3e50; color: white; padding: 8px; text-align: left; }
                    td { padding: 8px; border-bottom: 1px solid #ddd; }
                </style>
            </head>
            <body>
                <div class='header'>
                    <h1>Auditoría de Actividad (Bitácora)</h1>
                    <p>Documento generado automáticamente el: {$fechaActual}</p>
                    <p>Generado por: " . ($_SESSION['usuario']['nombre'] ?? 'Administrador') . "</p>
                </div>

                <div class='stats-box'>
                    <strong>Total de Acciones Registradas en el periodo consultado:</strong> {$totalRegistros}
                </div>

                <table>
                    <thead>
                        <tr>
                            <th width='30%'>Usuario / Rol</th>
                            <th width='45%'>Acción Realizada</th>
                            <th width='25%'>Fecha y Hora</th>
                        </tr>
                    </thead>
                    <tbody>";

            foreach ($registros as $row) {
                $nombre = htmlspecialchars($row['nombre_completo']);
                $rol = htmlspecialchars($row['rol'] ?? 'Ciudadano');
                $accion = htmlspecialchars($row['accion']);
                $fecha = $row['fecha_exacta'];

                $html .= "
                    <tr>
                        <td>
                            <strong>{$nombre}</strong><br>
                            <span style='color: #7f8c8d; font-size: 9px;'>{$rol}</span>
                        </td>
                        <td>{$accion}</td>
                        <td>{$fecha}</td>
                    </tr>";
            }

            $html .= "
                    </tbody>
                </table>
            </body>
            </html>";
        } else {
            die("Área de auditoría no válida.");
        }

        if (function_exists('registrarActividad')) {
            $idUsr = $_SESSION['usuario']['idUsuario'] ?? $_SESSION['usuario']['id'];
            registrarActividad($idUsr, "Generó un reporte PDF de auditoría del área: " . strtoupper($area));
        }

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true); 
        
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        
        $dompdf->setPaper('letter', 'portrait');
        $dompdf->render();
        
        if (ob_get_length()) {
            ob_end_clean();
        }
        
        $dompdf->stream("Auditoria_Tekopora_{$area}_{$fechaActual}.pdf", array("Attachment" => false));
    }
}