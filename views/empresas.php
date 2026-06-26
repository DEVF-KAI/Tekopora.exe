<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Título de la página
$title = 'Empresas Constructoras - TekoPorã';
ob_start();
?>

<style>
    .empresa-card { transition: transform 0.3s ease, box-shadow 0.3s ease; border-radius: 15px; border: none; }
    .empresa-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important; }
    .btn-add-empresa { background-color: #F2B705; color: #333; font-weight: bold; border-radius: 50px; }
    .btn-add-empresa:hover { background-color: #e5ad04; transform: scale(1.05); color: #000; }
    .header-section { background: linear-gradient(rgba(26, 106, 109, 0.9), rgba(26, 106, 109, 0.9)), url('<?= asset("imgs/obras_header.jpg") ?>') center/cover; padding: 60px 0; border-radius: 0 0 30px 30px; }
</style>

<div class="header-section text-center text-white mb-5">
    <div class="container">
        <h1 class="display-4 font-weight-bold" style="font-family: 'Times New Roman', Times, serif;">Constructoras Aliadas</h1>
        <p class="lead">Transparencia en la ejecución de obras públicas para Bolivia</p>
    </div>
</div>

<div class="container mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="font-weight-bold text-dark mb-0">Listado de Empresas</h3>
            <p class="text-muted">Empresas adjudicadas y registradas en el sistema municipal</p>
        </div>

        <?php if (isset($_SESSION['usuario']) && in_array($_SESSION['usuario']['rol'], ['Administrador', 'Personal Alcaldia'])): ?>
            <a href="<?= url('/empresas/add') ?>" class="btn btn-add-empresa px-4 py-2 shadow-sm">
                <i class="fa fa-plus-circle mr-2"></i> Registrar Nueva Empresa
            </a>
        <?php endif; ?>
    </div>

    <div class="row">
        <?php if (empty($empresas)): ?>
            <div class="col-12 text-center py-5">
                <i class="fa fa-industry fa-4x text-muted mb-3" style="opacity: 0.2;"></i>
                <h5 class="text-muted">No se encontraron empresas registradas en la base de datos.</h5>
            </div>
        <?php else: ?>
            <?php foreach ($empresas as $e): ?>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100 shadow-sm empresa-card">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-light p-3 rounded-circle mr-3">
                                    <i class="fa fa-building fa-2x" style="color: #217F82;"></i>
                                </div>
                                <div>
                                    <h5 class="font-weight-bold mb-0 text-dark"><?= htmlspecialchars($e['nombreEmpresa']) ?></h5>
                                    <small class="text-muted font-weight-bold">NIT: <?= htmlspecialchars($e['codigoEmpresa']) ?></small>
                                </div>
                            </div>

                            <hr>

                            <div class="mb-2">
                                <i class="fa fa-phone-alt mr-2 text-info"></i>
                                <span class="small text-muted font-weight-bold">TELÉFONO:</span>
                                <p class="mb-0 ml-4"><?= htmlspecialchars($e['telefono'] ?? 'No disponible') ?></p>
                            </div>

                            <div class="mb-3">
                                <i class="fa fa-map-marker-alt mr-2 text-danger"></i>
                                <span class="small text-muted font-weight-bold">DIRECCIÓN:</span>
                                <p class="mb-0 ml-4 text-truncate"><?= htmlspecialchars($e['direccion'] ?? 'No disponible') ?></p>
                            </div>

                            <div class="bg-light p-2 rounded text-center">
                                <span class="small font-weight-bold text-dark">VALORACIÓN CIUDADANA</span>
                                <div class="text-warning">
                                    <?php 
                                    $rating = $e['valoracionPromedio'] ?? 0;
                                    for ($i = 1; $i <= 5; $i++) {
                                        echo $i <= $rating ? '<i class="fas fa-star"></i>' : '<i class="far fa-star"></i>';
                                    }
                                    ?>
                                    <span class="ml-1 text-dark small">(<?= $rating ?>/5)</span>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-white border-0 pb-4 text-center">
                            <a href="<?= url('/empresas/detalle?codigo=' . $e['codigoEmpresa']) ?>" class="btn btn-outline-info btn-sm rounded-pill px-4">
                                Ver Obras Adjudicadas
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
