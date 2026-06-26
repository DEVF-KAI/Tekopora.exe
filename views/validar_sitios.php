<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Seguridad: Solo Admin o Moderador Turismo
if (!isset($_SESSION['usuario']) || !in_array($_SESSION['usuario']['rol'], ['Administrador', 'Moderador Turismo'])) {
    header("Location: " . url('/?error=Acceso Denegado'));
    exit();
}

$title = 'Validar Sitios Turísticos - TekoPorã';
ob_start();

// Datos simulados por si el controlador aún no manda los reales
if (!isset($sitiosPendientes)) {
    $sitiosPendientes = [
        ['idPublicacion' => 1, 'titulo' => 'Mirador de Killi Killi', 'contenido' => 'Excelente vista panorámica de toda la ciudad de La Paz. Ideal para atardeceres.', 'autor' => 'Juan Perez', 'fechaPublicacion' => '2026-04-08'],
        ['idPublicacion' => 2, 'titulo' => 'Valle de las Ánimas', 'contenido' => 'Formaciones geológicas impresionantes en la zona sur.', 'autor' => 'Maria Gomez', 'fechaPublicacion' => '2026-04-09']
    ];
}
?>

<style>
    .table-hover tbody tr:hover {
        background-color: rgba(33, 127, 130, 0.05);
    }

    .btn-aprobar {
        background-color: #28a745;
        color: white;
        border-radius: 50px;
        padding: 5px 15px;
        font-weight: bold;
        transition: 0.3s;
        border: none;
    }

    .btn-aprobar:hover {
        background-color: #218838;
        transform: scale(1.05);
    }

    .btn-rechazar {
        background-color: #dc3545;
        color: white;
        border-radius: 50px;
        padding: 5px 15px;
        font-weight: bold;
        transition: 0.3s;
        border: none;
    }

    .btn-rechazar:hover {
        background-color: #c82333;
        transform: scale(1.05);
    }

    .badge-estado {
        font-size: 0.85rem;
        padding: 0.4em 0.8em;
        border-radius: 50px;
    }
</style>

<div class="container-fluid py-5" style="background-color: #f4f6f9; min-height: 100vh;">
    <div class="container py-4">

        <div class="row mb-4 align-items-center">
            <div class="col-md-8">
                <h2 class="font-weight-bold" style="color: #1A6A6D; font-family: 'Times New Roman', Times, serif;">
                    <i class="fa fa-check-circle text-warning mr-2"></i> Validación de Sitios Turísticos
                </h2>
                <p class="text-muted mb-0">Revisa y aprueba los destinos sugeridos por la comunidad.</p>
            </div>
        </div>

        <div class="card shadow-lg border-0 rounded-lg overflow-hidden">
            <div class="card-header text-white p-3"
                style="background: linear-gradient(135deg, #217F82 0%, #1A6A6D 100%);">
                <h5 class="mb-0 font-weight-bold"><i class="fa fa-inbox mr-2"></i> Bandeja de Pendientes</h5>
            </div>

            <div class="card-body p-0">
                <?php if (empty($sitiosPendientes)): ?>
                    <div class="text-center py-5">
                        <i class="fa fa-check-double fa-4x text-muted mb-3" style="opacity: 0.3;"></i>
                        <h5 class="text-muted font-weight-bold">¡Todo al día!</h5>
                        <p class="text-muted small">No hay sugerencias turísticas pendientes de revisión.</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="bg-light text-muted small font-weight-bold text-uppercase">
                                <tr>
                                    <th class="py-3 px-4 border-0">Fecha</th>
                                    <th class="py-3 border-0">Sugerencia Turística</th>
                                    <th class="py-3 border-0">Autor</th>
                                    <th class="py-3 px-4 border-0 text-right">Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($sitiosPendientes as $sitio): ?>
                                    <tr>
                                        <td class="px-4 text-muted small align-middle">
                                            <?= date('d/m/Y', strtotime($sitio['fechaPublicacion'])) ?>
                                        </td>
                                        <td class="align-middle py-3">
                                            <h6 class="font-weight-bold text-dark mb-1">
                                                <?= htmlspecialchars($sitio['titulo']) ?></h6>
                                            <p class="text-muted small mb-0"
                                                style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                                <?= htmlspecialchars($sitio['contenido']) ?>
                                            </p>
                                        </td>
                                        <td class="align-middle">
                                            <span class="badge badge-light border text-dark font-weight-bold px-2 py-1">
                                                <i class="fa fa-user text-info mr-1"></i>
                                                <?= htmlspecialchars($sitio['autor']) ?>
                                            </span>
                                        </td>
                                        <td class="px-4 align-middle text-right" style="white-space: nowrap;">
                                            <form action="<?= url('/turismo/procesar-validacion') ?>" method="POST"
                                                class="d-inline">
                                                <input type="hidden" name="codigoPublicacion"
                                                    value="<?= $sitio['codigoPublicacion'] ?>"> <input type="hidden"
                                                    name="accion" value="Aprobado">
                                                <button type="submit" class="btn btn-aprobar shadow-sm mr-2"
                                                    title="Aprobar Sitio">
                                                    <i class="fa fa-check"></i> Aprobar
                                                </button>
                                            </form>

                                            <form action="<?= url('/turismo/procesar-validacion') ?>" method="POST"
                                                class="d-inline"
                                                onsubmit="return confirm('¿Seguro que deseas rechazar este sitio?');">
                                                <input type="hidden" name="idPublicacion"
                                                    value="<?= $sitio['idPublicacion'] ?>">
                                                <input type="hidden" name="accion" value="Rechazado">
                                                <button type="submit" class="btn btn-rechazar shadow-sm" title="Rechazar Sitio">
                                                    <i class="fa fa-times"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/layouts/app_layout.php';
?>