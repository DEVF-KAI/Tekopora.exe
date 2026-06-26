<?php
if (session_status() === PHP_SESSION_NONE) session_start();
// Solo usuarios logueados pueden proponer sitios
if (!isset($_SESSION['usuario'])) {
    header("Location: " . url('/login?error=Debes iniciar sesión para proponer un sitio'));
    exit();
}
$title = 'Proponer Sitio Turístico - TekoPorã';
ob_start();
?>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    .tourism-header { background-image: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.7)), url("<?= asset('imgs/valle.jpeg') ?>"); background-size: cover; background-position: center; color: white; padding: 6rem 0; text-align: center; margin-bottom: -3rem; }
    #mapa-turismo { height: 350px; border-radius: 15px; border: 2px solid #ddd; z-index: 1; }
    .card-custom { border-radius: 20px; border: none; }
    .img-preview { width: 100%; max-height: 250px; object-fit: cover; border-radius: 10px; display: none; margin-top: 10px; border: 2px dashed #28a745; }
</style>

<div class="tourism-header">
    <div class="container">
        <h1 class="display-4 font-weight-bold" style="font-family: 'Times New Roman', Times, serif;">Descubre La Paz</h1>
        <p class="text-uppercase" style="letter-spacing: 2px;">Propón un nuevo destino turístico</p>
    </div>
</div>

<div class="container mb-5" style="position: relative; z-index: 10;">
    <div class="card shadow-lg card-custom">
        <div class="card-header text-white p-4" style="background: linear-gradient(135deg, #28a745 0%, #218838 100%);">
            <h4 class="mb-0 font-weight-bold"><i class="fa fa-map-marked-alt mr-2 text-warning"></i> Formulario de Propuesta</h4>
            <small class="text-white-50">Tu propuesta será revisada por un Moderador de Turismo antes de hacerse pública.</small>
        </div>

        <div class="card-body p-4 p-md-5">
            <form action="<?= url('/turismo/guardar') ?>" method="POST" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-lg-6 pr-lg-4">
                        <h5 class="font-weight-bold text-dark border-bottom pb-2 mb-4">Datos del Lugar</h5>
                        
                        <div class="form-group mb-3">
                            <label class="small font-weight-bold text-muted">NOMBRE DEL SITIO TURÍSTICO</label>
                            <input type="text" name="nombre" class="form-control" placeholder="Ej: Valle de las Ánimas" required>
                        </div>

                        <div class="form-group mb-3">
                            <label class="small font-weight-bold text-muted">¿POR QUÉ DEBERÍAN VISITARLO?</label>
                            <textarea name="descripcion" class="form-control" rows="4" placeholder="Describe la magia de este lugar..." required></textarea>
                        </div>

                        <div class="form-group mb-4">
                            <label class="small font-weight-bold text-muted">FOTOGRAFÍA DEL LUGAR</label>
                            <input type="file" name="imagenSitio" id="imgInput" class="form-control-file" accept="image/*" required>
                            <img id="imgPreview" class="img-preview shadow-sm">
                        </div>
                    </div>

                    <div class="col-lg-6 border-left pl-lg-4">
                        <h5 class="font-weight-bold text-dark border-bottom pb-2 mb-4">Ubicación Exacta</h5>
                        <p class="small text-muted mb-2">Marca en el mapa dónde se encuentra exactamente este lugar.</p>
                        
                        <div id="mapa-turismo" class="mb-3 shadow-sm"></div>
                        
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
                    <button type="submit" class="btn btn-success px-5 py-3 shadow-lg font-weight-bold" style="border-radius: 50px;">
                        <i class="fa fa-paper-plane mr-2"></i> ENVIAR A REVISIÓN
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

    // Mapa Leaflet (Centrado en La Paz)
    document.addEventListener('DOMContentLoaded', function () {
        var centro = [-16.4955, -68.1336];
        var mapa = L.map('mapa-turismo').setView(centro, 12);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(mapa);
        var marcador = L.marker(centro, { draggable: true }).addTo(mapa);

        function actualizarCoords(lat, lng) {
            document.getElementById('lat').value = lat.toFixed(7);
            document.getElementById('lng').value = lng.toFixed(7);
        }
        actualizarCoords(centro[0], centro[1]);
        mapa.on('click', e => { marcador.setLatLng(e.latlng); actualizarCoords(e.latlng.lat, e.latlng.lng); });
        marcador.on('dragend', e => { var pos = marcador.getLatLng(); actualizarCoords(pos.lat, pos.lng); });
    });
</script>
