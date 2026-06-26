<div class="container-fluid py-5 bg-light" style="min-height: 80vh;">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
            <h2 class="mb-0 font-weight-bold" style="color: #1A6A6D;">
                <i class="fas fa-clipboard-check text-warning mr-2"></i> Bandeja de Revisión Turística
            </h2>
            <span class="badge badge-info p-2" style="font-size: 1rem;">
                <?= count($sitiosPendientes) ?> Pendientes
            </span>
        </div>

        <div class="row">
            <?php if (empty($sitiosPendientes)): ?>
                <div class="col-12 text-center py-5">
                    <i class="fas fa-check-circle fa-4x text-success mb-3" style="opacity: 0.5;"></i>
                    <h4 class="text-muted">¡Todo al día! No hay propuestas pendientes.</h4>
                </div>
            <?php else: ?>
                <?php foreach ($sitiosPendientes as $sitio): ?>
                    <div class="col-lg-6 mb-4">
                        <div class="card shadow-sm border-0 h-100" style="border-radius: 15px; overflow: hidden;">
                            <div class="row no-gutters h-100">
                                <div class="col-md-5">
                                    <img src="<?= !empty($sitio['imagen_url']) ? asset($sitio['imagen_url']) : asset('imgs/default.jpg') ?>" 
                                         class="h-100 w-100" style="object-fit: cover; min-height: 250px;">
                                </div>
                                <div class="col-md-7">
                                    <div class="card-body d-flex flex-column h-100 p-4">
                                        <div class="mb-2">
                                            <span class="badge badge-warning">PROPUESTA CIUDADANA</span>
                                            <small class="text-muted float-right"><?= date('d/m/Y', strtotime($sitio['fechaRegistro'])) ?></small>
                                        </div>
                                        
                                        <h5 class="font-weight-bold text-dark"><?= htmlspecialchars($sitio['nombre']) ?></h5>
                                        <p class="small text-muted mb-2">
                                            <i class="fas fa-user-circle mr-1"></i> Propuesto por: <b><?= htmlspecialchars($sitio['proponente']) ?></b>
                                        </p>
                                        
                                        <p class="card-text small text-muted flex-grow-1" style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
                                            "<?= htmlspecialchars($sitio['descripcion']) ?>"
                                        </p>
                                        
                                        <p class="small mb-3"><i class="fas fa-map-marker-alt text-danger mr-1"></i> Coordenadas: <?= $sitio['latitud'] ?>, <?= $sitio['longitud'] ?></p>

                                        <form action="<?= url('/turismo/procesar') ?>" method="POST" class="mt-auto d-flex justify-content-between">
                                            <input type="hidden" name="idSitio" value="<?= $sitio['idSitio'] ?>">
                                            
                                            <button type="submit" name="accion" value="Rechazar" class="btn btn-outline-danger btn-sm rounded-pill px-4 font-weight-bold" onclick="return confirm('¿Seguro que deseas rechazar esta propuesta?')">
                                                <i class="fas fa-times mr-1"></i> Rechazar
                                            </button>
                                            
                                            <button type="submit" name="accion" value="Aprobar" class="btn btn-success btn-sm rounded-pill px-4 shadow-sm font-weight-bold">
                                                <i class="fas fa-check mr-1"></i> Aprobar
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>