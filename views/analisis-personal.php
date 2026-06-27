<style>
/* ==========================================
   ESTILOS MODERNOS DEL PERFIL CIUDADANO
   ========================================== */
.nav-bar, .navbar, .container-fluid.position-relative.nav-bar {
    margin-top: 0 !important;
    padding-top: 0 !important;
    border-top: none !important;
}

body {
    background-color: #f4f7f6;
}

/* Portada y superposición */
.profile-cover {
    height: 250px;
    object-fit: cover;
    border-bottom-left-radius: 20px;
    border-bottom-right-radius: 20px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.profile-card {
    margin-top: -80px; /* Superpone la tarjeta a la imagen de portada */
    border: none;
    border-radius: 15px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.05);
    background: #ffffff;
    z-index: 10;
}

/* Icono de perfil */
.profile-avatar-wrapper {
    margin-top: -50px;
    margin-bottom: 15px;
}
.profile-avatar {
    background: #ffffff;
    border-radius: 50%;
    padding: 5px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

/* Tarjetas generales */
.modern-card {
    border: none;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.04);
    margin-bottom: 25px;
    overflow: hidden;
}

.modern-card .card-header {
    background: #ffffff;
    border-bottom: 1px solid rgba(0,0,0,0.05);
    padding: 20px 25px;
    font-weight: 600;
    color: #2c3e50;
    display: flex;
    align-items: center;
    gap: 10px;
}

.modern-card .card-header i {
    color: #217F82;
    font-size: 1.2em;
}

/* Tablas */
.table-custom th {
    border-top: none;
    color: #7f8c8d;
    font-weight: 500;
    text-transform: uppercase;
    font-size: 0.85em;
    letter-spacing: 0.5px;
}
.table-custom td {
    vertical-align: middle;
    color: #34495e;
}

/* Inputs formales */
.form-modern {
    background-color: #f8f9fa !important;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    color: #495057;
    font-weight: 500;
}

/* Estados (Empty States) */
.empty-state {
    text-align: center;
    padding: 30px 10px;
    color: #95a5a6;
}
.empty-state i {
    font-size: 3em;
    margin-bottom: 15px;
    color: #bdc3c7;
}

/* Badges de estado */
.badge-custom {
    padding: 6px 12px;
    border-radius: 20px;
    font-weight: 500;
    font-size: 0.85em;
}
</style>

<div class="container-fluid p-0 px-lg-4">
    <img class="w-100 profile-cover" src="<?= asset('imgs/look.jpg') ?>" alt="Portada TekoPorã">
</div>

<div class="container pb-5">

    <div class="row">

        <div class="col-lg-4">
            <div class="card profile-card text-center text-lg-center">
                <div class="card-body px-4 pb-4">
                    
                    <div class="profile-avatar-wrapper">
                        <i class="fas fa-user-circle fa-6x profile-avatar" style="color:#217F82;"></i>
                    </div>

                    <h4 class="fw-bold mb-1" style="color: #2c3e50;">
                        <?= htmlspecialchars($usuario['nombre'] ?? 'Ciudadano') ?>
                    </h4>
                    <p class="text-muted small mb-4">
                        <i class="far fa-calendar-alt me-1"></i> Miembro desde <?= htmlspecialchars(date('d/m/Y', strtotime($usuario['fechaRegistro'] ?? 'now'))) ?>
                    </p>

                    <div class="d-flex justify-content-between text-center border-top pt-3">
                        <div class="flex-fill border-end">
                            <h4 class="mb-0 fw-bold" style="color: #217F82;"><?= count($sitios ?? []) ?></h4>
                            <small class="text-muted text-uppercase" style="font-size: 0.75em;">Sitios</small>
                        </div>
                        <div class="flex-fill border-end">
                            <h4 class="mb-0 fw-bold" style="color: #217F82;"><?= count($reportes ?? []) ?></h4>
                            <small class="text-muted text-uppercase" style="font-size: 0.75em;">Obras</small>
                        </div>
                        <div class="flex-fill">
                            <h4 class="mb-0 fw-bold" style="color: #217F82;"><?= count($actividad ?? []) ?></h4>
                            <small class="text-muted text-uppercase" style="font-size: 0.75em;">Acciones</small>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="col-lg-8 mt-4 mt-lg-0 position-relative" style="z-index: 10;">
            
            <div class="card modern-card">
                <div class="card-header">
                    <i class="fas fa-id-card"></i> Información Personal
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted small fw-bold">Nombre Completo</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fas fa-user text-muted"></i></span>
                                <input class="form-control form-modern border-start-0" value="<?= htmlspecialchars($usuario['nombre'] ?? 'Usuario') ?>" disabled>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-muted small fw-bold">Correo Electrónico</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fas fa-envelope text-muted"></i></span>
                                <input class="form-control form-modern border-start-0" value="<?= htmlspecialchars($usuario['email'] ?? 'Sin correo') ?>" disabled>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-muted small fw-bold">Teléfono de Contacto</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fas fa-phone text-muted"></i></span>
                                <input class="form-control form-modern border-start-0" value="<?= htmlspecialchars($usuario['telefono'] ?? 'Pendiente de actualización') ?>" disabled>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card modern-card">
                <div class="card-header">
                    <i class="fas fa-map-marked-alt"></i> Sitios Turísticos Propuestos
                </div>
                <div class="card-body p-0">
                    <?php if (!empty($sitios)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover table-custom mb-0 text-center">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="text-start ps-4">Sitio</th>
                                        <th>Fecha de Registro</th>
                                        <th class="pe-4">Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($sitios as $s): ?>
                                        <tr>
                                            <td class="text-start ps-4 fw-bold"><?= htmlspecialchars($s['nombre'] ?? '') ?></td>
                                            <td><?= htmlspecialchars(date('d/m/Y', strtotime($s['fecha'] ?? 'now'))) ?></td>
                                            <td class="pe-4">
                                                <?php 
                                                    $estado = strtolower($s['estado'] ?? 'pendiente');
                                                    $badgeClass = 'bg-secondary';
                                                    if ($estado === 'aprobado') $badgeClass = 'bg-success';
                                                    if ($estado === 'pendiente') $badgeClass = 'bg-warning text-dark';
                                                    if ($estado === 'rechazado') $badgeClass = 'bg-danger';
                                                ?>
                                                <span class="badge badge-custom <?= $badgeClass ?>">
                                                    <?= htmlspecialchars(ucfirst($s['estado'] ?? 'Pendiente')) ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="empty-state">
                            <i class="fas fa-mountain"></i>
                            <h6 class="fw-bold">No has propuesto sitios aún</h6>
                            <p class="small mb-0">Ayuda a promover el turismo registrando nuevos lugares.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card modern-card">
                <div class="card-header">
                    <i class="fas fa-hard-hat"></i> Mis Reportes de Obras Públicas
                </div>
                <div class="card-body p-0">
                    <?php if (!empty($reportes)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover table-custom mb-0 text-center">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="text-start ps-4">Proyecto</th>
                                        <th>Avance Reportado</th>
                                        <th class="pe-4">Estado del Proyecto</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($reportes as $r): ?>
                                        <tr>
                                            <td class="text-start ps-4 fw-bold">
                                                <i class="fas fa-building text-muted me-2"></i>
                                                <?= htmlspecialchars($r['proyecto'] ?? '') ?>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center justify-content-center">
                                                    <div class="progress w-50 me-2" style="height: 8px;">
                                                        <div class="progress-bar bg-info" role="progressbar" style="width: <?= htmlspecialchars($r['avance'] ?? 0) ?>%;"></div>
                                                    </div>
                                                    <span class="small fw-bold"><?= htmlspecialchars($r['avance'] ?? 0) ?>%</span>
                                                </div>
                                            </td>
                                            <td class="pe-4">
                                                <span class="badge badge-custom bg-secondary">
                                                    <?= htmlspecialchars($r['estado'] ?? 'En curso') ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="empty-state">
                            <i class="fas fa-clipboard-list"></i>
                            <h6 class="fw-bold">Sin reportes registrados</h6>
                            <p class="small mb-0">Tu participación es clave para el control social.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card modern-card mt-4">
                <div class="card-header">
                    <i class="fas fa-comments"></i> Participación en el Foro
                </div>
                <div class="card-body p-3">
                    <?php if (!empty($foroActividad)): ?>
                        <div class="list-group list-group-flush">
                            <?php 
                            $mostrarInicial = 3; // Cuántos mostrar antes del "Ver más"
                            $contador = 0;
                            foreach ($foroActividad as $f): 
                                $contador++;
                                $iconoForo = ($f['tipo'] == 'Publicación') ? 'fa-bullhorn text-warning' : 'fa-reply text-info';
                                
                                // Si pasamos el límite, abrimos el contenedor colapsable
                                if ($contador == $mostrarInicial + 1) {
                                    echo '<div class="collapse" id="collapseForo">';
                                }
                            ?>
                                <div class="list-group-item d-flex justify-content-between align-items-center border-0 mb-2 rounded shadow-sm" style="background-color: #fcfcfc; border-left: 3px solid #1A6A6D !important;">
                                    <div class="d-flex align-items-center">
                                        <div class="p-2 me-3" style="width: 40px; text-align: center;">
                                            <i class="fas <?= $iconoForo ?> fa-lg"></i>
                                        </div>
                                        <div>
                                            <span class="badge badge-light mb-1 border"><?= $f['tipo'] ?></span>
                                            <h6 class="mb-0 text-dark font-weight-bold" style="font-size: 0.9em;">
                                                "<?= htmlspecialchars(strlen($f['descripcion']) > 60 ? substr($f['descripcion'], 0, 60).'...' : $f['descripcion']) ?>"
                                            </h6>
                                        </div>
                                    </div>
                                    <span class="text-muted small fw-bold">
                                        <?= htmlspecialchars(date('d/m H:i', strtotime($f['fecha'] ?? 'now'))) ?>
                                    </span>
                                </div>
                            <?php endforeach; ?>
                            
                            <?php if ($contador > $mostrarInicial): ?>
                                </div> <!-- Cierra el collapse -->
                                <button class="btn btn-sm btn-outline-secondary mt-2 w-100 rounded-pill font-weight-bold" type="button" data-toggle="collapse" data-target="#collapseForo" aria-expanded="false" aria-controls="collapseForo" onclick="this.innerText = this.innerText === 'Ver más participaciones' ? 'Ocultar participaciones' : 'Ver más participaciones'">
                                    Ver más participaciones
                                </button>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <div class="empty-state py-4">
                            <i class="fas fa-comment-slash"></i>
                            <h6 class="fw-bold">Aún no hay participación</h6>
                            <p class="small mb-0">Tus aportes en el foro ciudadano aparecerán aquí.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card modern-card">
                <div class="card-header">
                    <i class="fas fa-history"></i> Historial de Actividad
                </div>
                <div class="card-body p-3" style="max-height: 400px; overflow-y: auto;">
                    <?php if (!empty($actividad)): ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($actividad as $a): ?>
                                <?php 
                                    // Lógica para asignar un icono dependiendo de la acción
                                    $desc = strtolower($a['descripcion']);
                                    $icon = 'fa-check text-success'; // Por defecto
                                    
                                    if (strpos($desc, 'sesión') !== false) $icon = 'fa-sign-in-alt text-primary';
                                    elseif (strpos($desc, 'publicación') !== false) $icon = 'fa-file-alt text-warning';
                                    elseif (strpos($desc, 'comentó') !== false) $icon = 'fa-comment-dots text-info';
                                    elseif (strpos($desc, 'reportó') !== false) $icon = 'fa-hard-hat text-danger';
                                    elseif (strpos($desc, 'voto') !== false) $icon = 'fa-star text-warning';
                                ?>
                                <div class="list-group-item d-flex justify-content-between align-items-center border-0 mb-2 rounded shadow-sm" style="background-color: #ffffff; border-left: 3px solid #217F82 !important;">
                                    <div class="d-flex align-items-center">
                                        <div class="p-2 me-3" style="width: 40px; text-align: center;">
                                            <i class="fas <?= $icon ?> fa-lg"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0 fw-bold text-dark" style="font-size: 0.9em;"><?= htmlspecialchars($a['descripcion'] ?? '') ?></h6>
                                        </div>
                                    </div>
                                    <span class="text-muted small fw-bold">
                                        <i class="far fa-clock"></i> <?= htmlspecialchars(date('d/m H:i', strtotime($a['fecha'] ?? 'now'))) ?>
                                    </span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="empty-state py-4">
                            <i class="fas fa-shoe-prints"></i>
                            <h6 class="fw-bold">No hay actividad reciente</h6>
                            <p class="small mb-0">Tus últimas acciones en la plataforma aparecerán aquí.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>
</div>