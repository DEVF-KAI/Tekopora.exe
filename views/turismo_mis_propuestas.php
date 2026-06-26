<div class="container-fluid py-5 bg-light" style="min-height: 80vh;">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
            <h2 class="mb-0 font-weight-bold" style="color: #1A6A6D;">
                <i class="fas fa-history text-info mr-2"></i> Mis Propuestas Turísticas
            </h2>
            <a href="<?= url('/turismo') ?>" class="btn btn-outline-secondary rounded-pill btn-sm font-weight-bold">
                <i class="fas fa-arrow-left mr-1"></i> Volver al Mapa
            </a>
        </div>

        <div class="row">
            <?php if (empty($misSitios)): ?>
                <div class="col-12 text-center py-5">
                    <i class="fas fa-folder-open fa-4x text-muted mb-3" style="opacity: 0.3;"></i>
                    <h4 class="text-muted">Aún no has propuesto ningún destino.</h4>
                    <a href="<?= url('/turismo/crear') ?>" class="btn btn-success rounded-pill mt-3 px-4 shadow">
                        <i class="fas fa-plus mr-2"></i> Haz tu primera propuesta
                    </a>
                </div>
            <?php else: ?>
                <?php foreach ($misSitios as $sitio): ?>
                    <?php 
                        // Lógica de colores según estado
                        if ($sitio['estado'] === 'Aprobado') {
                            $badgeClass = 'badge-success';
                            $icon = 'fa-check-circle';
                            $border = 'border-success';
                        } elseif ($sitio['estado'] === 'Rechazado') {
                            $badgeClass = 'badge-danger';
                            $icon = 'fa-times-circle';
                            $border = 'border-danger';
                        } else {
                            $badgeClass = 'badge-warning text-dark';
                            $icon = 'fa-clock';
                            $border = 'border-warning';
                        }
                    ?>

                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card shadow-sm h-100" style="border-radius: 15px; overflow: hidden; border-top: 4px solid; border-top-color: var(--<?= str_replace('border-', '', $border) ?>);">
                            <div style="height: 180px; background-image: url('<?= !empty($sitio['imagen_url']) ? asset($sitio['imagen_url']) : asset('imgs/default.jpg') ?>'); background-size: cover; background-position: center;">
                                <div class="p-2">
                                    <span class="badge <?= $badgeClass ?> shadow" style="font-size: 0.85rem;">
                                        <i class="fas <?= $icon ?> mr-1"></i> <?= strtoupper($sitio['estado']) ?>
                                    </span>
                                </div>
                            </div>
                            
                            <div class="card-body">
                                <h5 class="font-weight-bold text-dark mb-1"><?= htmlspecialchars($sitio['nombre']) ?></h5>
                                <p class="small text-muted mb-3">
                                    <i class="far fa-calendar-alt mr-1"></i> Propuesto el <?= date('d/m/Y', strtotime($sitio['fechaRegistro'])) ?>
                                </p>
                                <p class="card-text small text-muted" style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
                                    "<?= htmlspecialchars($sitio['descripcion']) ?>"
                                </p>
                            </div>
                            <div class="card-footer bg-white border-0 pb-3 small text-muted text-center">
                                <i class="fas fa-map-marker-alt text-danger mr-1"></i> <?= $sitio['latitud'] ?>, <?= $sitio['longitud'] ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>