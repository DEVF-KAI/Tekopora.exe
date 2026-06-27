<?php
if (session_status() === PHP_SESSION_NONE)
    session_start();
?>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    .detail-header {
        /* La imagen de fondo ahora es dinámica y toma la última subida, o la de por defecto si no hay */
        background-image: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.8)), url("<?= !empty($imagenes) ? asset($imagenes[count($imagenes) - 1]['urlArchivo']) : asset('imgs/lago.jpeg') ?>");
        background-size: cover;
        background-position: center;
        color: white;
        padding: 5rem 0;
    }

    .info-card {
        border: none;
        border-radius: 15px;
    }

    .status-badge {
        padding: 8px 20px;
        border-radius: 50px;
        font-weight: bold;
        font-size: 0.9rem;
    }

    #mapa-detalle {
        height: 400px;
        border-radius: 15px;
        border: 1px solid #eee;
    }

    .gallery-img {
        width: 100%;
        height: 200px;
        object-fit: cover;
        border-radius: 10px;
        cursor: pointer;
        transition: 0.3s;
    }

    .gallery-img:hover {
        transform: scale(1.03);
    }

    .section-title {
        color: #1A6A6D;
        font-weight: 800;
        border-left: 5px solid #F2B705;
        padding-left: 15px;
        margin-bottom: 20px;
    }

    /* Estrellas interactivas mágicas */
    .star-rating {
        direction: rtl;
        display: inline-block;
    }

    .star-rating input[type="radio"] {
        display: none;
    }

    .star-rating label {
        color: #ddd;
        font-size: 2rem;
        padding: 0 2px;
        cursor: pointer;
        transition: 0.2s;
    }

    .star-rating input[type="radio"]:checked~label,
    .star-rating label:hover,
    .star-rating label:hover~label {
        color: #F2B705;
    }
</style>

<div class="container-fluid p-0 detail-header text-center">
    <div class="container">
        <div class="mb-3">
            <span class="status-badge <?= $proyecto['estado'] == 'Completado' ? 'bg-success' : 'bg-warning text-dark' ?>">
                <?= strtoupper($proyecto['estado']) ?>
            </span>
        </div>
        <h1 class="display-4 font-weight-bold"><?= htmlspecialchars($proyecto['nombreProyecto']) ?></h1>
        <p class="lead">Código de Seguimiento: <span class="badge badge-light"><?= $proyecto['codigoProyecto'] ?></span></p>
    </div>
</div>

<div class="container py-5" style="margin-top: -3rem;">
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm info-card mb-4">
                <div class="card-body p-4">
                    <!-- CONTENEDOR FLEX PARA EL BOTÓN DE SUBIR FOTO -->
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="section-title mb-0">Descripción de la Obra</h4>
                        <?php if (isset($_SESSION['usuario']) && in_array($_SESSION['usuario']['rol'], ['Administrador', 'Moderador Obra'])): ?>
                            <button type="button" class="btn btn-sm btn-outline-primary rounded-pill font-weight-bold shadow-sm" data-toggle="modal" data-target="#modalSubirFoto">
                                <i class="fas fa-camera mr-1"></i> Añadir Foto
                            </button>
                        <?php endif; ?>
                    </div>

                    <p class="text-muted" style="font-size: 1.1rem; line-height: 1.8;">
                        <?= nl2br(htmlspecialchars($proyecto['descripcion'])) ?>
                    </p>

                    <div class="row mt-5">
                        <div class="col-md-6">
                            <h4 class="section-title">Ubicación Geográfica</h4>
                            <p class="small text-muted mb-3"><i class="fa fa-map-marker-alt mr-2 text-danger"></i><?= $proyecto['nombreMacrodistrito'] ?>, La Paz</p>
                        </div>
                        <div class="col-md-6 text-md-right">
                            <span class="text-muted small">Coordenadas: <?= $proyecto['latitud'] ?>, <?= $proyecto['longitud'] ?></span>
                        </div>
                    </div>
                    <div id="mapa-detalle" class="shadow-sm"></div>
                </div>
            </div>

            <!-- REGISTRO FOTOGRÁFICO -->
            <?php if (!empty($imagenes)): ?>
                <div class="card shadow-sm info-card">
                    <div class="card-body p-4">
                        <h4 class="section-title">Registro Fotográfico</h4>
                        <div class="row">
                            <?php foreach ($imagenes as $img): ?>
                                <div class="col-md-4 mb-3">
                                    <img src="<?= asset($img['urlArchivo']) ?>" class="gallery-img shadow-sm" data-toggle="modal" data-target="#modalGaleria" onclick="abrirImagen('<?= asset($img['urlArchivo']) ?>')">
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm info-card mb-4 bg-white">
                <div class="card-body text-center">
                    <h5 class="font-weight-bold text-muted small">AVANCE DE OBRA</h5>
                    <h1 class="display-4 font-weight-bold" style="color: #1A6A6D;"><?= $proyecto['avancePorcentaje'] ?>%</h1>
                    <div class="progress mb-3" style="height: 12px; border-radius: 10px;">
                        <div class="progress-bar bg-success" style="width: <?= $proyecto['avancePorcentaje'] ?>%"></div>
                    </div>
                    <p class="small text-muted mb-0">Última actualización por:
                        <b><?= $proyecto['moderadorResponsable'] ?></b>
                    </p>
                </div>
            </div>

            <div class="card shadow-sm info-card mb-4">
                <div class="card-body">
                    <h5 class="section-title" style="font-size: 1rem;">Inversión Municipal</h5>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted">Presupuesto Total:</span>
                        <span class="h5 mb-0 font-weight-bold">Bs. <?= number_format($proyecto['presupuesto'], 2) ?></span>
                    </div>
                    <hr>
                    <div class="small">
                        <p class="mb-1 text-muted"><b>Fecha de Inicio:</b> <?= date('d/m/Y', strtotime($proyecto['fechaInicio'])) ?></p>
                        <p class="mb-0 text-muted"><b>Entrega Estimada:</b> <?= $proyecto['fechaEntregaEstimada'] ? date('d/m/Y', strtotime($proyecto['fechaEntregaEstimada'])) : 'Pendiente' ?></p>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm info-card mt-4">
                <div class="card-body">
                    <h5 class="section-title" style="font-size: 1rem;">Empresa Adjudicada</h5>
                    <p class="font-weight-bold text-dark mb-1"><?= htmlspecialchars($proyecto['nombreEmpresa']) ?></p>
                    <p class="small text-muted mb-3">NIT: <?= $proyecto['codigoEmpresa'] ?></p>

                    <div class="small text-muted mb-4">
                        <p class="mb-1"><i class="fa fa-phone mr-2"></i> <?= $proyecto['telEmpresa'] ?></p>
                        <p class="mb-0"><i class="fa fa-building mr-2"></i> <?= htmlspecialchars($proyecto['dirEmpresa']) ?></p>
                    </div>

                    <?php if (isset($_SESSION['usuario'])): ?>
                        <hr>
                        <div class="text-center">
                            <h6 class="font-weight-bold text-muted small mb-2">CALIFICA A ESTA EMPRESA</h6>
                            <form action="<?= url('/proyectos/evaluar') ?>" method="POST">
                                <input type="hidden" name="codigoProyecto" value="<?= $proyecto['codigoProyecto'] ?>">

                                <div class="star-rating">
                                    <input type="radio" id="star5" name="puntaje" value="5"><label for="star5" class="fas fa-star"></label>
                                    <input type="radio" id="star4" name="puntaje" value="4"><label for="star4" class="fas fa-star"></label>
                                    <input type="radio" id="star3" name="puntaje" value="3"><label for="star3" class="fas fa-star"></label>
                                    <input type="radio" id="star2" name="puntaje" value="2"><label for="star2" class="fas fa-star"></label>
                                    <input type="radio" id="star1" name="puntaje" value="1" required><label for="star1" class="fas fa-star"></label>
                                </div>

                                <button type="submit" class="btn btn-sm btn-block text-white shadow-sm mt-2" style="background-color: #1A6A6D; border-radius: 50px;">
                                    Enviar Valoración
                                </button>
                            </form>
                        </div>
                    <?php else: ?>
                        <hr>
                        <p class="text-center small text-muted mb-0">Inicia sesión para calificar a esta empresa.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ==========================================
     MODALES AÑADIDOS
     ========================================== -->

<!-- Modal para Subir Foto (Solo Admin/Mod) -->
<?php if (isset($_SESSION['usuario']) && in_array($_SESSION['usuario']['rol'], ['Administrador', 'Moderador Obra'])): ?>
<div class="modal fade" id="modalSubirFoto" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius: 15px; border: none;">
            <div class="modal-header bg-light border-0">
                <h5 class="modal-title font-weight-bold text-dark"><i class="fas fa-image mr-2 text-primary"></i>Añadir Foto al Registro</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4">
                <form action="<?= url('/proyectos/subir_foto') ?>" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="idProyecto" value="<?= $proyecto['idProyecto'] ?>">
                    <input type="hidden" name="codigoProyecto" value="<?= $proyecto['codigoProyecto'] ?>">
                    <div class="form-group text-center">
                        <label for="imagenRegistro" class="text-muted d-block mb-3">Selecciona una imagen clara del avance actual de la obra.</label>
                        <div class="custom-file mb-3 text-left">
                            <input type="file" class="custom-file-input" id="imagenRegistro" name="imagenRegistro" accept="image/*" required onchange="document.getElementById('file-label').innerHTML = this.files[0].name;">
                            <label class="custom-file-label" for="imagenRegistro" id="file-label" data-browse="Buscar">Elegir archivo...</label>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block rounded-pill font-weight-bold shadow-sm py-2">
                        <i class="fas fa-upload mr-2"></i>Subir Fotografía
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Modal Visor de Galería -->
<div class="modal fade" id="modalGaleria" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content bg-transparent border-0">
            <div class="modal-header border-0 pb-0">
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" style="opacity: 1; text-shadow: 0 2px 4px rgba(0,0,0,0.5);">
                    <span aria-hidden="true" style="font-size: 2rem;">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center pt-0">
                <img id="imagenExpandida" src="" class="img-fluid rounded shadow-lg" style="max-height: 80vh;">
            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var lat = <?= $proyecto['latitud'] ?>;
        var lng = <?= $proyecto['longitud'] ?>;

        var mapa = L.map('mapa-detalle').setView([lat, lng], 16);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(mapa);

        L.marker([lat, lng]).addTo(mapa)
            .bindPopup('<b><?= htmlspecialchars($proyecto['nombreProyecto']) ?></b><br>Obra en curso.')
            .openPopup();
    });

    // Función nueva para el Modal de Galería
    function abrirImagen(url) {
        document.getElementById('imagenExpandida').src = url;
    }
</script>