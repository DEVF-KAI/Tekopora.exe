<?php 
// Validación de sesión
if (session_status() === PHP_SESSION_NONE) { 
    session_start(); 
}

// Validar que $usuariosBloqueados existe y es array
if (!isset($usuariosBloqueados) || !is_array($usuariosBloqueados)) {
    $usuariosBloqueados = [];
}
?>

<div class="container mt-4 mb-5 layout-anim-enter" id="tablaContenedorBloqueados">

    <!-- CABECERA -->
    <div class="d-flex justify-content-between align-items-center mb-5 flex-wrap">
        <div>
            <h2 class="fw-bold mb-0 title-gradient">
                <i class="fa fa-ban me-2"></i> Usuarios Bloqueados
            </h2>
            <p class="text-muted small mt-1 mb-0">Gestiona cuentas con bloqueo temporal o permanente.</p>
        </div>

        <!-- CONTENEDOR DE BOTONES -->
        <div class="d-flex align-items-center gap-2 mt-3 mt-md-0">
            <a href="<?= url('/adminpanel') ?>" class="btn btn-outline-secondary shadow-sm btn-header">
                <i class="fa fa-arrow-left me-1"></i> Volver a Usuarios
            </a>
        </div>
    </div>

    <!-- ALERTAS DE SESIÓN -->
    <?php if (isset($_SESSION['error']) && !empty($_SESSION['error'])): ?>
        <div class="alert alert-danger py-3 px-4 text-start shadow-sm mb-4" style="border-radius: 10px; border-left: 4px solid #e74c3c;">
            <i class="fa fa-exclamation-triangle me-2"></i> <?= htmlspecialchars($_SESSION['error'], ENT_QUOTES, 'UTF-8') ?>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['success']) && !empty($_SESSION['success'])): ?>
        <div class="alert alert-success py-3 px-4 text-start shadow-sm mb-4" style="border-radius: 10px; border-left: 4px solid #27ae60;">
            <i class="fa fa-check-circle me-2"></i> <?= htmlspecialchars($_SESSION['success'], ENT_QUOTES, 'UTF-8') ?>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <!-- TABLA DE USUARIOS BLOQUEADOS -->
    <div class="table-responsive px-2 pb-5">
        <?php if (empty($usuariosBloqueados) || count($usuariosBloqueados) === 0): ?>
            <div class="alert alert-info py-4 text-center" style="border-radius: 10px;">
                <i class="fa fa-info-circle me-2"></i> No hay usuarios bloqueados en este momento.
            </div>
        <?php else: ?>
            <table class="table table-borderless table-spaced align-middle text-center mb-0 w-100">
                <thead>
                    <tr class="header-row text-muted text-uppercase small fw-bold">
                        <th class="text-start pb-3" style="width: 28%;">Ciudadano e Insignia</th>
                        <th class="pb-3">Contacto</th>
                        <th class="pb-3">Tipo de Bloqueo</th>
                        <th class="pb-3">Duración / Estado</th>
                        <th class="pb-3">Rol</th>
                        <th class="pe-4 pb-3">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    <?php 
                    $delay = 0; 
                    foreach ($usuariosBloqueados as $u): 
                        $rolUser = isset($u['rol']) ? $u['rol'] : 'Sin rol';
                        $karmaUser = isset($u['karmatotal']) ? $u['karmatotal'] : (isset($u['karma']) ? $u['karma'] : 0);
                        
                        // Determinar tipo de bloqueo
                        $esBloqueoPermanente = isset($u['bloqueado']) && $u['bloqueado'] == 1;
                        $tieneBloqueoTemporal = isset($u['locked_until']) && $u['locked_until'];
                        
                        if ($esBloqueoPermanente) {
                            $tipoBloqueo = 'Permanente';
                            $tipoBloqueoClass = 'badge-danger';
                            $tipoBloqueoIcon = 'fa-lock';
                            $duracion = 'Requiere Admin';
                        } else {
                            $tipoBloqueo = 'Temporal (10 min)';
                            $tipoBloqueoClass = 'badge-warning';
                            $tipoBloqueoIcon = 'fa-hourglass-end';
                            
                            // Validar y parsear DateTime de forma segura
                            try {
                                if (!empty($u['locked_until'])) {
                                    $locked_until = new DateTime($u['locked_until']);
                                    $ahora = new DateTime();
                                    $diferencia = $locked_until->getTimestamp() - $ahora->getTimestamp();
                                    
                                    if ($diferencia > 0) {
                                        $minutos = ceil($diferencia / 60);
                                        $duracion = $minutos . ' min restante(s)';
                                    } else {
                                        $duracion = 'Expirado (puede ingresar)';
                                    }
                                } else {
                                    $duracion = 'Información no disponible';
                                }
                            } catch (Exception $e) {
                                $duracion = 'Error en fecha';
                            }
                        }

                        // Insignias - Validar valores
                        $karmaUser = isset($u['karmatotal']) ? $u['karmatotal'] : (isset($u['karma']) ? $u['karma'] : 0);
                        $karmaUser = (int)$karmaUser; // Asegurar que es un número
                        
                        if ($karmaUser < 0) { $textoKarma = 'Riesgo'; $badgeIcon = 'fa-exclamation-triangle'; $badgeClass = 'insignia-riesgo'; } 
                        elseif ($karmaUser < 50) { $textoKarma = 'Novato'; $badgeIcon = 'fa-leaf'; $badgeClass = 'insignia-novato'; } 
                        elseif ($karmaUser < 200) { $textoKarma = 'Activo'; $badgeIcon = 'fa-fire'; $badgeClass = 'insignia-activo'; } 
                        elseif ($karmaUser < 500) { $textoKarma = 'Destacado'; $badgeIcon = 'fa-star'; $badgeClass = 'insignia-destacado'; } 
                        else { $textoKarma = 'Leyenda'; $badgeIcon = 'fa-trophy'; $badgeClass = 'insignia-leyenda'; }

                        $imgInsignia = 'cobre.png'; 
                        if ($rolUser === 'Administrador') { $imgInsignia = 'superadmin.png'; $textoKarma = 'Supremo'; $badgeIcon = 'fa-crown'; $badgeClass = 'insignia-leyenda'; } 
                        elseif (in_array($rolUser, ['Moderador Turismo', 'Moderador Obra', 'Personal Alcaldia'], true)) { $imgInsignia = 'constructora.png'; } 
                        else {
                            if ($karmaUser >= 500) { $imgInsignia = 'morado.png'; } elseif ($karmaUser >= 300) { $imgInsignia = 'oro.png'; }
                            elseif ($karmaUser >= 100) { $imgInsignia = 'azul.png'; } elseif ($karmaUser >= 50) { $imgInsignia = 'plata.png'; }
                            else { $imgInsignia = 'cobre.png'; }
                        }
                    ?>
                        <tr class="custom-row fade-in-row" style="animation-delay: <?= $delay ?>s;">

                            <td class="text-start py-3">
                                <div class="d-flex align-items-center">
                                    <div class="me-3 flex-shrink-0">
                                        <img src="<?= asset('imgs/' . htmlspecialchars($imgInsignia, ENT_QUOTES, 'UTF-8')) ?>" alt="Insignia" style="width: 55px; height: 55px; object-fit: contain; filter: drop-shadow(0 4px 6px rgba(0,0,0,0.15));">
                                    </div>
                                    <div class="d-flex flex-column justify-content-center">
                                        <h6 class="mb-1 fw-bold text-dark" style="line-height: 1.2;">
                                            <?= htmlspecialchars(trim((isset($u['nombre']) ? $u['nombre'] : '') . ' ' . (isset($u['appPaterno']) ? $u['appPaterno'] : '')), ENT_QUOTES, 'UTF-8') ?>
                                        </h6>
                                        <div class="mb-1">
                                            <span class="badge badge-karma border <?= htmlspecialchars($badgeClass, ENT_QUOTES, 'UTF-8') ?>">
                                                <i class="fa <?= htmlspecialchars($badgeIcon, ENT_QUOTES, 'UTF-8') ?>"></i> <?= htmlspecialchars($textoKarma, ENT_QUOTES, 'UTF-8') ?>
                                            </span>
                                        </div>
                                        <small class="text-muted m-0 p-0" style="font-size: 0.70rem; line-height: 1;">
                                            Cód: <?= htmlspecialchars(isset($u['codigoUsuario']) ? $u['codigoUsuario'] : 'N/A', ENT_QUOTES, 'UTF-8') ?>
                                        </small>
                                    </div>
                                </div>
                            </td>
                            
                            <td class="py-3 text-muted small fw-bold">
                                <i class="fa fa-envelope me-1 opacity-50"></i> <?= htmlspecialchars(isset($u['email']) ? $u['email'] : 'Sin correo', ENT_QUOTES, 'UTF-8') ?>
                            </td>

                            <td class="py-3">
                                <span class="badge rounded-pill text-white px-3 py-2 fw-bold shadow-sm <?= htmlspecialchars($tipoBloqueoClass, ENT_QUOTES, 'UTF-8') ?>">
                                    <i class="fa <?= htmlspecialchars($tipoBloqueoIcon, ENT_QUOTES, 'UTF-8') ?> me-1 opacity-75"></i> <?= htmlspecialchars($tipoBloqueo, ENT_QUOTES, 'UTF-8') ?>
                                </span>
                            </td>

                            <td class="py-3">
                                <span class="fw-bold small <?= $esBloqueoPermanente ? 'text-danger' : 'text-warning' ?>">
                                    <?= htmlspecialchars($duracion, ENT_QUOTES, 'UTF-8') ?>
                                </span>
                            </td>

                            <td class="py-3">
                                <?php 
                                    $rol = isset($u['rol']) ? $u['rol'] : 'Sin rol';
                                    if ($rol === 'Administrador') { $rolClass = 'bg-gradient-admin'; $icon = 'fa-star'; } 
                                    elseif ($rol === 'Moderador Turismo') { $rolClass = 'bg-gradient-turismo'; $icon = 'fa-map-marker'; } 
                                    elseif ($rol === 'Moderador Obra') { $rolClass = 'bg-gradient-obra'; $icon = 'fa-building'; } 
                                    elseif ($rol === 'Personal Alcaldia') { $rolClass = 'bg-gradient-alcaldia'; $icon = 'fa-university'; } 
                                    elseif ($rol === 'Ciudadano') { $rolClass = 'bg-gradient-ciudadano'; $icon = 'fa-user'; } 
                                    else { $rolClass = 'bg-gradient-default'; $icon = 'fa-id-badge'; }
                                ?>
                                <span class="badge rounded-pill text-white px-3 py-2 fw-bold shadow-sm <?= htmlspecialchars($rolClass, ENT_QUOTES, 'UTF-8') ?>">
                                    <i class="fa <?= htmlspecialchars($icon, ENT_QUOTES, 'UTF-8') ?> me-1 opacity-75"></i> <?= htmlspecialchars($rol, ENT_QUOTES, 'UTF-8') ?>
                                </span>
                            </td>

                            <td class="pe-4 py-3">
                                <div class="d-flex justify-content-center gap-2">
                                    <?php if (!$esBloqueoPermanente && isset($u['codigoUsuario'])): ?>
                                        <!-- Bloquear permanentemente -->
                                        <a href="<?= url('usuarios/bloquear?codigo=' . urlencode($u['codigoUsuario'])) ?>" class="action-btn btn-block shadow-sm" title="Bloquear permanentemente" onclick="return confirm('¿Bloquear permanentemente a este usuario?');"><i class="fa fa-lock"></i></a>
                                    <?php endif; ?>
                                    
                                    <?php if (isset($u['codigoUsuario'])): ?>
                                        <!-- Desbloquear (levantar restricción) -->
                                        <a href="<?= url('usuarios/desbloquear?codigo=' . urlencode($u['codigoUsuario'])) ?>" class="action-btn btn-unblock shadow-sm" title="Desbloquear y permitir intentar nuevamente" onclick="return confirm('¿Desbloquear a este usuario?');"><i class="fa fa-unlock"></i></a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php 
                        $delay += 0.05; 
                    endforeach; 
                    ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<style>
    /* Botón de bloqueo permanente */
    .btn-block {
        background: #fff5f5;
        color: #e74c3c;
        border: 1px solid #fce4e4;
        width: 38px;
        height: 38px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .btn-block:hover {
        background: #e74c3c;
        color: white;
        transform: scale(1.1);
        box-shadow: 0 5px 15px rgba(231, 76, 60, 0.4);
    }

    /* Botón de desbloqueo */
    .btn-unblock {
        background: #f0fff4;
        color: #27ae60;
        border: 1px solid #d5f4e6;
        width: 38px;
        height: 38px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .btn-unblock:hover {
        background: #27ae60;
        color: white;
        transform: scale(1.1);
        box-shadow: 0 5px 15px rgba(39, 174, 96, 0.4);
    }

    /* Estilos reutilizados del admin_perfil.php */
    .btn-header {
        border-radius: 50px;
        font-weight: 700;
        padding: 8px 18px;
        font-size: 0.9rem;
    }

    body { background-color: #f0f4f8; font-family: 'Inter', system-ui, sans-serif; }
    .title-gradient { background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
    .text-primary-custom { color: #217F82; }
    .table-spaced { border-collapse: separate; border-spacing: 0 14px; }
    .custom-row { background: #ffffff; transition: all 0.3s ease; box-shadow: 0 5px 15px rgba(0,0,0,0.03); }
    .custom-row td { border: none !important; }
    .custom-row td:first-child { border-top-left-radius: 16px; border-bottom-left-radius: 16px; }
    .custom-row td:last-child { border-top-right-radius: 16px; border-bottom-right-radius: 16px; }
    .custom-row:hover { transform: translateY(-5px); box-shadow: 0 15px 30px rgba(33, 127, 130, 0.12); z-index: 10; position: relative; }
    
    .badge-karma { font-size: 0.70rem; padding: 4px 10px; border-radius: 6px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; }
    .insignia-riesgo { background-color: #ffebee !important; color: #c62828 !important; border-color: #ffcdd2 !important; }
    .insignia-novato { background-color: #f5f5f5 !important; color: #757575 !important; border-color: #e0e0e0 !important; }
    .insignia-activo { background-color: #e8f5e9 !important; color: #2e7d32 !important; border-color: #c8e6c9 !important; }
    .insignia-destacado { background-color: #e3f2fd !important; color: #1565c0 !important; border-color: #bbdefb !important; }
    .insignia-leyenda { background-color: #fff8e1 !important; color: #f57f17 !important; border-color: #ffecb3 !important; }

    .bg-gradient-admin { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); } 
    .bg-gradient-turismo { background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); } 
    .bg-gradient-obra { background: linear-gradient(135deg, #f2994a 0%, #f2c94c 100%); } 
    .bg-gradient-alcaldia { background: linear-gradient(135deg, #cb2d3e 0%, #ef473a 100%); } 
    .bg-gradient-ciudadano { background: linear-gradient(135deg, #20bf55 0%, #01baef 100%); } 
    .bg-gradient-default { background: linear-gradient(135deg, #434343 0%, #000000 100%); }

    .fade-in-row { opacity: 0; transform: translateY(20px); animation: rowEntrance 0.6s ease-out forwards; }
    @keyframes rowEntrance { to { opacity: 1; transform: translateY(0); } }

    .header-row { background: rgba(255, 255, 255, 0.5); }
</style>

<script>
    // Auto-refresh cada 30 segundos para actualizar estados de bloqueo temporal
    setTimeout(() => {
        location.reload();
    }, 30000);
</script>
