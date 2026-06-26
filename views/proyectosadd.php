<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Seguridad: Solo Admin o Personal Alcaldia
if (!isset($_SESSION['usuario']) || !in_array($_SESSION['usuario']['rol'], ['Administrador', 'Personal Alcaldia'])) {
    header("Location: " . url('/?error=Acceso Denegado'));
    exit();
}
$title = 'Nuevo Proyecto - TekoPorã';
ob_start();
?>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    .projects-header {
        background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.7)), url("<?= asset('imgs/lago.jpeg') ?>");
        background-size: cover; background-position: center; color: white; padding: 6rem 0; text-align: center; margin-bottom: -4rem;
    }
    .projects-header h1 { font-size: 3.5rem; font-weight: 700; text-transform: uppercase; font-family: 'Times New Roman', Times, serif; }
    .form-container { position: relative; z-index: 10; margin-bottom: 4rem; }
    .card-form { border-radius: 15px; border: none; overflow: hidden; }
    #mapa-selector { height: 350px; border-radius: 10px; border: 1px solid #ddd; }
    .img-preview { width: 100%; max-height: 250px; object-fit: cover; border-radius: 10px; display: none; border: 2px dashed #1A6A6D; margin-top: 10px; }
</style>

<div class="projects-header">
    <div class="container">
        <h1>Registrar Obra</h1>
        <div class="small text-uppercase" style="letter-spacing: 2px;">Gestión de Proyectos Municipales</div>
    </div>
</div>

<div class="container form-container">
    <div class="card card-form shadow-lg">
        <div class="card-body p-4 p-md-5">
            <form action="<?= url('/proyectosadd') ?>" method="POST" enctype="multipart/form-data">
                
                <div class="row">
                    <div class="col-lg-7">
                        <h5 class="font-weight-bold text-dark mb-4 border-bottom pb-2">1. Detalles del Proyecto</h5>
                        
                        <div class="form-group mb-3">
                            <label class="small font-weight-bold text-muted">NOMBRE DEL PROYECTO</label>
                            <input type="text" name="nombreProyecto" class="form-control" placeholder="Ej: Viaducto Belisario Salinas" required>
                        </div>

                        <div class="form-group mb-3">
                            <label class="small font-weight-bold text-muted">ASIGNAR MODERADOR RESPONSABLE</label>
                            <select name="idModerador" class="form-control" required>
                                <option value="" selected disabled>Seleccione al encargado de esta obra...</option>
                                <?php foreach ($moderadores as $m): ?>
                                    <option value="<?= $m['idUsuario'] ?>"><?= htmlspecialchars($m['nombre'] . ' ' . $m['appPaterno']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label class="small font-weight-bold text-muted">DESCRIPCIÓN</label>
                            <textarea name="descripcion" class="form-control" rows="3" placeholder="Impacto de la obra..."></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label class="small font-weight-bold text-muted">MACRODISTRITO</label>
                                <select name="idMacrodistrito" class="form-control" required>
                                    <?php foreach ($macrodistritos as $m): ?>
                                        <option value="<?= $m['idMacrodistrito'] ?>"><?= $m['nombreMacrodistrito'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6 form-group">
                                <label class="small font-weight-bold text-muted">EMPRESA CONSTRUCTORA</label>
                                <select name="idEmpresa" class="form-control" required>
                                    <?php foreach ($empresas as $e): ?>
                                        <option value="<?= $e['idEmpresa'] ?>"><?= $e['nombreEmpresa'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label class="small font-weight-bold text-muted">PRESUPUESTO (Bs.)</label>
                                <input type="number" step="0.01" name="presupuesto" class="form-control" required>
                            </div>
                            <div class="col-md-6 form-group">
                                <label class="small font-weight-bold text-muted">ESTADO INICIAL</label>
                                <select name="estado" class="form-control">
                                    <option value="Planificado">Planificado</option>
                                    <option value="En ejecución" selected>En ejecución</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label class="small font-weight-bold text-muted">FECHA DE INICIO</label>
                                <input type="date" name="fechaInicio" class="form-control" required>
                            </div>
                            <div class="col-md-6 form-group">
                                <label class="small font-weight-bold text-muted">ENTREGA ESTIMADA</label>
                                <input type="date" name="fechaEntregaEstimada" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">

                            <label class="small font-weight-bold text-muted">AVANCE ACTUAL (%)</label>

                            <input type="number" step="0.1" name="avancePorcentaje" class="form-control w-50" value="0">

                        </div>

                
                    </div>

                    <div class="col-lg-5 border-left pl-lg-4">
                        <h5 class="font-weight-bold text-dark mb-4 border-bottom pb-2">2. Multimedia y Ubicación</h5>
                        
                        <div class="form-group mb-4">
                            <label class="small font-weight-bold text-muted">IMAGEN DEL PROYECTO</label>
                            <input type="file" name="imagenProyecto" id="imgInput" class="form-control-file" accept="image/*">
                            <img id="imgPreview" class="img-preview shadow-sm">
                        </div>

                        <div id="mapa-selector" class="mb-3"></div>
                        
                        <div class="row">
                            <div class="col-6">
                                <input type="text" id="lat" name="latitud" class="form-control bg-light small" placeholder="Latitud" readonly required>
                            </div>
                            <div class="col-6">
                                <input type="text" id="lng" name="longitud" class="form-control bg-light small" placeholder="Longitud" readonly required>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-5">
                    <button type="submit" class="btn btn-save px-5 py-3 shadow-lg font-weight-bold">
                        <i class="fa fa-save mr-2"></i> REGISTRAR OBRA PÚBLICA
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    // Preview de Imagen
    document.getElementById('imgInput').onchange = evt => {
        const [file] = evt.target.files;
        if (file) {
            const preview = document.getElementById('imgPreview');
            preview.src = URL.createObjectURL(file);
            preview.style.display = 'block';
        }
    }

    // Mapa Leaflet
    document.addEventListener('DOMContentLoaded', function () {
        var centroLaPaz = [-16.4955, -68.1336];
        var mapa = L.map('mapa-selector').setView(centroLaPaz, 14);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(mapa);
        var marcador = L.marker(centroLaPaz, { draggable: true }).addTo(mapa);

        function actualizarInputs(lat, lng) {
            document.getElementById('lat').value = lat.toFixed(7);
            document.getElementById('lng').value = lng.toFixed(7);
        }
        actualizarInputs(centroLaPaz[0], centroLaPaz[1]);
        mapa.on('click', e => { marcador.setLatLng(e.latlng); actualizarInputs(e.latlng.lat, e.latlng.lng); });
        marcador.on('dragend', e => { var pos = marcador.getLatLng(); actualizarInputs(pos.lat, pos.lng); });
    });
</script>

