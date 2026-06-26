<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Seguridad estricta: Solo Admin o Moderador Obra (Constructora)
if (!isset($_SESSION['usuario']) || !in_array($_SESSION['usuario']['rol'], ['Administrador', 'Moderador Obra'])) {
    header("Location: " . url('/?error=Acceso Denegado'));
    exit();
}

$title = 'Panel de Constructora - TekoPorã';
ob_start();
?>

<style>
    .card-hover {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border-radius: 15px;
        border: none;
    }

    .card-hover:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
    }

    .progress-bar-animated {
        transition: width 1.5s ease-in-out;
    }

    .btn-update {
        background-color: #F2B705;
        color: #333;
        font-weight: bold;
        border-radius: 50px;
    }

    .btn-update:hover {
        background-color: #e5ad04;
        color: #000;
        transform: scale(1.05);
    }
</style>

<div class="container-fluid py-5" style="background-color: #f4f6f9; min-height: 100vh;">
    <div class="container py-4">

        <div class="row mb-5 align-items-center">
            <div class="col-md-8">
                <h2 class="font-weight-bold" style="color: #1A6A6D; font-family: 'Times New Roman', Times, serif;">
                    <i class="fa fa-hard-hat text-warning mr-2"></i> Mis Obras Asignadas
                </h2>
                <p class="text-muted mb-0">Panel de actualización de progreso técnico y reportes de campo.</p>
            </div>
            <div class="col-md-4 text-md-right mt-3 mt-md-0">
                <div class="bg-white p-3 rounded shadow-sm border-left"
                    style="border-width: 4px !important; border-color: #217F82 !important;">
                    <span class="d-block text-muted small font-weight-bold">CONSTRUCTORA / RESPONSABLE</span>
                    <span class="text-dark font-weight-bold"><i class="fa fa-user-tie text-info mr-1"></i>
                        <?= htmlspecialchars($_SESSION['usuario']['nombre']) ?></span>
                </div>
            </div>
        </div>

        <div class="row">
            <?php if (empty($proyectosAsignados)): ?>
                <div class="col-12 text-center py-5">
                    <img src="<?= asset('imgs/constructora.png') ?>" style="width: 100px; opacity: 0.5;" class="mb-3">
                    <h5 class="text-muted">No tienes obras asignadas en este momento.</h5>
                    <p class="text-muted small">Contacta con la Alcaldía para que te vinculen a un proyecto.</p>
                </div>
            <?php else: ?>
                <?php foreach ($proyectosAsignados as $obra): ?>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card shadow-sm card-hover h-100">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span
                                        class="badge <?= $obra['estado'] == 'En ejecución' ? 'badge-success' : 'badge-secondary' ?> px-2 py-1">
                                        <?= htmlspecialchars($obra['estado']) ?>
                                    </span>
                                    <small class="text-muted"><i class="fa fa-calendar-alt"></i> Inicio:
                                        <?= date('d/m/Y', strtotime($obra['fechaInicio'])) ?></small>
                                </div>

                                <h5 class="font-weight-bold text-dark mb-4" style="line-height: 1.4;">
                                    <?= htmlspecialchars($obra['nombreProyecto']) ?>
                                </h5>

                                <div class="mb-3">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span class="small font-weight-bold text-muted">AVANCE FÍSICO</span>
                                        <span class="small font-weight-bold"
                                            style="color: #217F82;"><?= $obra['avancePorcentaje'] ?>%</span>
                                    </div>
                                    <div class="progress" style="height: 10px; border-radius: 10px; background-color: #e9ecef;">
                                        <div class="progress-bar progress-bar-animated" role="progressbar"
                                            style="width: <?= $obra['avancePorcentaje'] ?>%; background-color: #217F82;"
                                            aria-valuenow="<?= $obra['avancePorcentaje'] ?>" aria-valuemin="0"
                                            aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer bg-white border-top-0 p-4 text-center">
                                <button type="button" class="btn btn-update w-100 py-2 shadow-sm" data-toggle="modal"
                                    data-target="#modalReporte<?= $obra['idProyecto'] ?>">
                                    <i class="fa fa-upload mr-1"></i> Actualizar Avance
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="modalReporte<?= $obra['idProyecto'] ?>" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content" style="border-radius: 15px; border: none;">
                                <div class="modal-header text-white"
                                    style="background-color: #1A6A6D; border-radius: 15px 15px 0 0;">
                                    <h5 class="modal-title font-weight-bold"><i class="fa fa-clipboard-check mr-2"></i> Nuevo
                                        Reporte de Avance</h5>
                                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form action="<?= url('/proyectos/reportar') ?>" method="POST" enctype="multipart/form-data">
                                    <div class="modal-body p-4">

                                        <div class="alert alert-info small rounded p-2 mb-4">
                                            <strong>Obra:</strong> <?= htmlspecialchars($obra['nombreProyecto']) ?>
                                        </div>

                                        <input type="hidden" name="codigoProyecto" value="<?= $obra['codigoProyecto'] ?>">

                                        <div class="form-group mb-4">
                                            <label class="font-weight-bold text-muted small">NUEVO PORCENTAJE DE AVANCE
                                                (%)</label>
                                            <div class="d-flex align-items-center">
                                                <input type="range" class="custom-range w-75 mr-3"
                                                    min="<?= $obra['avancePorcentaje'] ?>" max="100" step="0.1"
                                                    id="slider<?= $obra['idProyecto'] ?>" name="porcentajeAvance"
                                                    value="<?= $obra['avancePorcentaje'] ?>"
                                                    oninput="document.getElementById('val<?= $obra['idProyecto'] ?>').innerText = this.value + '%'">
                                                <h4 class="mb-0 font-weight-bold text-success"
                                                    id="val<?= $obra['idProyecto'] ?>"><?= $obra['avancePorcentaje'] ?>%</h4>
                                            </div>
                                            <small class="text-muted mt-1 d-block">No puede ser menor al avance actual
                                                (<?= $obra['avancePorcentaje'] ?>%).</small>
                                        </div>

                                        <div class="form-group mb-2">
                                            <label class="font-weight-bold text-muted small">DESCRIPCIÓN DEL REPORTE <span
                                                    class="text-danger">*</span></label>
                                            <textarea name="descripcion" class="form-control rounded" rows="3"
                                                placeholder="Ej: Se completó el vaciado de losas del segundo nivel..."
                                                required></textarea>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label class="small font-weight-bold text-muted">FOTOGRAFÍA DE AVANCE
                                                (Opcional)</label>
                                            <input type="file" name="imagenReporte" class="form-control-file" accept="image/*">
                                            <small class="text-muted">Adjunte una foto para evidenciar el progreso. Se mostrará
                                                en la galería pública.</small>
                                        </div>
                                    </div>
                                    <div class="modal-footer bg-light" style="border-radius: 0 0 15px 15px;">
                                        <button type="button" class="btn btn-secondary font-weight-bold rounded-pill px-4"
                                            data-dismiss="modal">Cancelar</button>
                                        <button type="submit" class="btn text-white font-weight-bold rounded-pill px-4"
                                            style="background-color: #217F82;">Guardar Reporte</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>