<?php
if (session_status() === PHP_SESSION_NONE)
    session_start();

// Por si acaso el controlador no manda la variable, evitamos errores
if (!isset($sitiosAprobados)) {
    $sitiosAprobados = [];
}
?>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<style>
    /* Estilos para animaciones y tarjetas */
    .lugar-item {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .lugar-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15) !important;
    }

    .page-item.active .page-link {
        background-color: #217F82 !important;
        border-color: #217F82 !important;
        color: white !important;
    }

    .page-link {
        color: #217F82;
    }

    .page-link:hover {
        color: #145558;
    }

    /* Estilo moderno para los pines del mapa */
    .custom-pin {
        background-color: #F2B705;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        border: 3px solid #217F82;
        box-shadow: 0 0 8px rgba(0, 0, 0, 0.6);
        transition: transform 0.2s;
    }

    .custom-pin:hover {
        transform: scale(1.3);
        background-color: #ff3333;
    }

    /* Popups del mapa */
    .leaflet-popup-content-wrapper {
        border-radius: 12px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }

    .leaflet-popup-content h6 {
        font-weight: bold;
        color: #1A6A6D;
        margin-bottom: 3px;
    }
</style>

<div class="container-fluid p-0">
    <div id="header-carousel" class="carousel slide" data-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img class="w-100" src="<?= asset('imgs/salar.jpeg') ?>" alt="Turismo"
                    style="height: 400px; object-fit: cover; filter: brightness(0.7);">
                <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                    <div class="p-3" style="max-width: 900px;">
                        <h4 class="display-4 text-white text-uppercase font-weight-bold mb-md-3"
                            style="letter-spacing: 2px;">TURISMO LA PAZ</h4>
                        <p class="m-0 text-uppercase font-weight-bold text-warning">Inicio >> Descubre la ciudad
                            Maravilla</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid py-5 bg-white">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
            <h2 class="mb-0 font-weight-bold" style="color: #1A6A6D;"><i
                    class="fas fa-map-marked-alt text-warning mr-2"></i> Mapa Interactivo de Destinos</h2>
            <span class="badge text-white p-2 px-3 shadow-sm" style="background-color: #217F82; font-size: 1rem;"
                id="badgeMapaTotal">Cargando...</span>
        </div>

        <div class="card border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
            <div id="mapaLaPaz" style="height: 550px; width: 100%; z-index: 1;"></div>
        </div>
    </div>
</div>

<div class="container-fluid py-4 bg-light">
    <div class="container">
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-body p-4">
                <h5 class="font-weight-bold text-dark mb-3"><i class="fa fa-filter mr-2 text-info"></i> Filtra tu
                    próxima aventura</h5>
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <select class="form-control" id="filtroZona" style="border-radius: 10px;">
                            <option value="todos">Todas las zonas</option>
                            <option value="Mallasa">Mallasa</option>
                            <option value="Zona Sur">Zona Sur</option>
                            <option value="Centro">Centro</option>
                            <option value="Zongo">Zongo</option>
                            <option value="Hampaturi">Hampaturi</option>
                            <option value="Max Paredes">Max Paredes</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <select class="form-control" id="filtroTipo" style="border-radius: 10px;">
                            <option value="todos">Cualquier atractivo</option>
                            <option value="Mirador">Mirador</option>
                            <option value="Parque">Parque</option>
                            <option value="Formación natural">Formación natural</option>
                            <option value="Sitio histórico">Sitio histórico</option>
                            <option value="Destino">Destino General</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <select class="form-control" id="filtroRating" style="border-radius: 10px;">
                            <option value="todos">Cualquier Calificación</option>
                            <option value="4.5">⭐⭐⭐⭐ 4.5+</option>
                            <option value="4.0">⭐⭐⭐⭐ 4.0+</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="input-group">
                            <input type="text" class="form-control" id="buscarTexto" placeholder="Ej: Teleférico..."
                                style="border-radius: 10px 0 0 10px;">
                            <div class="input-group-append">
                                <button class="btn text-white"
                                    style="background-color: #217F82; border-radius: 0 10px 10px 0;"
                                    onclick="aplicarFiltros()">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid py-5 bg-white">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
            <h3 class="mb-0 font-weight-bold" style="color: #1A6A6D;">Catálogo de Destinos</h3>

            <div class="d-flex align-items-center mt-2 mt-md-0">
                <button class="btn btn-sm btn-outline-secondary rounded-pill mr-3" onclick="limpiarFiltros()">
                    <i class="fas fa-redo-alt mr-1"></i> Mostrar Todos
                </button>

                <?php if (isset($_SESSION['usuario'])): ?>

                    <?php if (in_array($_SESSION['usuario']['rol'], ['Administrador', 'Moderador Turismo'])): ?>
                        <a href="<?= url('/turismo/revision') ?>"
                            class="btn btn-warning rounded-pill px-4 shadow-sm font-weight-bold mr-2">
                            <i class="fas fa-clipboard-list text-dark mr-2"></i> Panel Moderador
                        </a>
                    <?php endif; ?>

                    <a href="<?= url('/turismo/mis-propuestas') ?>"
                        class="btn btn-info rounded-pill px-4 shadow-sm font-weight-bold mr-2 text-white">
                        <i class="fas fa-history mr-2"></i> Mis Propuestas
                    </a>

                    <a href="<?= url('/turismo/crear') ?>"
                        class="btn btn-success rounded-pill px-4 shadow-sm font-weight-bold">
                        <i class="fas fa-plus-circle mr-2"></i> Proponer
                    </a>

                <?php else: ?>
                    <a href="<?= url('/login') ?>"
                        class="btn btn-outline-success rounded-pill px-4 font-weight-bold btn-sm">
                        <i class="fas fa-sign-in-alt mr-2"></i> Loguéate para proponer
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <div class="row" id="listaLugares">
        </div>

        <nav aria-label="Page navigation" class="mt-5">
            <ul class="pagination justify-content-center" id="paginationControls">
            </ul>
        </nav>
    </div>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    // 1. Recibimos los datos con seguridad y logueamos para debugear 
    const datosBackend = <?= json_encode($sitiosAprobados) ?: '[]' ?>;
    console.log("Datos cargados desde la Base de Datos:", datosBackend);

    // Guardamos la ruta base de tus assets para las imágenes
    const baseUrl = "<?= asset('') ?>";

    // 2. Mapeamos los datos de la BD
    const lugaresDB = datosBackend.map(sitio => ({
        id: sitio.idSitio,
        nombre: sitio.nombre,
        macro: sitio.macrodistrito ?? "La Paz",
        tipo: "Destino Turístico", // Estandarizamos el tipo
        descripcion: sitio.descripcion,
        rating: "5.0", // Todos empiezan con 5 estrellas por defecto
        visitas: "Nuevo",
        lat: parseFloat(sitio.latitud),
        lng: parseFloat(sitio.longitud),
        // Le pasamos la URL de la imagen, o una por defecto si falló
        imagen: sitio.imagen_url ? sitio.imagen_url : 'imgs/obra_default.jpg'
    }));

    let lugaresFiltrados = [...lugaresDB];
    let paginaActual = 1;
    const lugaresPorPagina = 6;
    let mapa;
    let capaMarcadores;

    // Inicialización del Mapa
    function initMapa() {
        mapa = L.map('mapaLaPaz').setView([-16.4955, -68.1336], 12);

        L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; OpenStreetMap &copy; CARTO'
        }).addTo(mapa);

        capaMarcadores = L.featureGroup().addTo(mapa);
    }

    // Agregar marcadores
    function actualizarPinesMapa() {
        capaMarcadores.clearLayers();

        if (lugaresFiltrados.length === 0) return;

        lugaresFiltrados.forEach(lugar => {
            // Validamos que la latitud no sea NaN (Not a Number)
            if (!isNaN(lugar.lat) && !isNaN(lugar.lng)) {
                const iconoModerno = L.divIcon({
                    className: 'custom-pin-wrapper',
                    html: `<div class="custom-pin"></div>`,
                    iconSize: [20, 20],
                    iconAnchor: [10, 10],
                    popupAnchor: [0, -10]
                });

                const marker = L.marker([lugar.lat, lugar.lng], { icon: iconoModerno }).addTo(capaMarcadores);

                marker.bindPopup(`
                <div class="text-center p-1">
                    <h6>${lugar.nombre}</h6>
                    <span class="badge badge-info mb-2">${lugar.tipo}</span><br>
                    <small class="text-muted">⭐ ${lugar.rating} | ${lugar.visitas} visitas</small>
                </div>
                `);
            }
        });

        if (capaMarcadores.getLayers().length > 0) {
            mapa.fitBounds(capaMarcadores.getBounds(), { padding: [50, 50], maxZoom: 15 });
        }
        document.getElementById('badgeMapaTotal').innerText = `${lugaresFiltrados.length} Destinos en el Mapa`;
    }

    function irAlMapa(lat, lng, nombre) {
        window.scrollTo({ top: document.getElementById('mapaLaPaz').offsetTop - 100, behavior: 'smooth' });
        mapa.setView([lat, lng], 16, { animate: true, duration: 1.5 });
    }

    // Filtros
    function aplicarFiltros() {
        const zona = document.getElementById('filtroZona').value;
        const tipo = document.getElementById('filtroTipo').value;
        const ratingMin = document.getElementById('filtroRating').value;
        const busqueda = document.getElementById('buscarTexto').value.toLowerCase();

        lugaresFiltrados = lugaresDB.filter(lugar => {
            if (zona !== 'todos' && lugar.macro !== zona) return false;
            if (tipo !== 'todos' && lugar.tipo !== tipo) return false;
            if (ratingMin !== 'todos' && lugar.rating < parseFloat(ratingMin)) return false;
            if (busqueda && !lugar.nombre.toLowerCase().includes(busqueda)) return false;
            return true;
        });

        paginaActual = 1;
        actualizarVista();
    }

    function limpiarFiltros() {
        document.getElementById('filtroZona').value = 'todos';
        document.getElementById('filtroTipo').value = 'todos';
        document.getElementById('filtroRating').value = 'todos';
        document.getElementById('buscarTexto').value = '';

        lugaresFiltrados = [...lugaresDB];
        paginaActual = 1;
        actualizarVista();
    }

    // Paginación
    function cambiarPagina(pagina) {
        const totalPaginas = Math.ceil(lugaresFiltrados.length / lugaresPorPagina);
        if (pagina < 1 || pagina > totalPaginas) return;
        paginaActual = pagina;
        mostrarLugaresEnLista();
        document.getElementById('listaLugares').scrollIntoView({ behavior: 'smooth' });
    }

    function generarPaginacion() {
        const totalPaginas = Math.ceil(lugaresFiltrados.length / lugaresPorPagina);
        let html = '';

        if (totalPaginas <= 1) {
            document.getElementById('paginationControls').innerHTML = '';
            return;
        }

        html += `<li class="page-item ${paginaActual === 1 ? 'disabled' : ''}">
        <a class="page-link shadow-sm" href="#" onclick="cambiarPagina(${paginaActual - 1}); return false;" style="border-radius: 50px 0 0 50px;">Anterior</a>
    </li>`;

        for (let i = 1; i <= totalPaginas; i++) {
            html += `<li class="page-item ${paginaActual === i ? 'active' : ''}">
            <a class="page-link shadow-sm" href="#" onclick="cambiarPagina(${i}); return false;">${i}</a>
        </li>`;
        }

        html += `<li class="page-item ${paginaActual === totalPaginas ? 'disabled' : ''}">
        <a class="page-link shadow-sm" href="#" onclick="cambiarPagina(${paginaActual + 1}); return false;" style="border-radius: 0 50px 50px 0;">Siguiente</a>
    </li>`;

        document.getElementById('paginationControls').innerHTML = html;
    }

    // Renderizar Tarjetas (AHORA CON FOTOS REALES)
    function mostrarLugaresEnLista() {
        const inicio = (paginaActual - 1) * lugaresPorPagina;
        const fin = inicio + lugaresPorPagina;
        const lugaresPagina = lugaresFiltrados.slice(inicio, fin);

        let html = '';

        if (lugaresPagina.length === 0) {
            html = `
            <div class="col-12 text-center py-5">
                <i class="fas fa-search-location fa-4x text-muted mb-3" style="opacity: 0.3;"></i>
                <h4 class="text-muted">No encontramos destinos con esos filtros</h4>
            </div>
        `;
        } else {
            lugaresPagina.forEach(lugar => {
                // Generamos la ruta completa de la imagen
                const urlFondo = baseUrl.replace(/\/$/, "") + '/' + lugar.imagen;

                html += `
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card border-0 shadow-sm h-100 lugar-item" style="border-radius: 15px; overflow: hidden;">
                        
                        <div style="height: 200px; background-image: url('${urlFondo}'); background-size: cover; background-position: center; position: relative;">
                            <span class="badge position-absolute shadow-sm" style="top: 15px; right: 15px; background-color: rgba(255,255,255,0.9); color: #1A6A6D;">
                                ⭐ ${lugar.rating}
                            </span>
                        </div>
                        
                        <div class="card-body">
                            <span class="badge badge-success mb-2 px-2 py-1">${lugar.tipo}</span>
                            <h5 class="font-weight-bold text-dark mb-1">${lugar.nombre}</h5>
                            <p class="text-muted small"><i class="fas fa-map-marker-alt text-danger mr-1"></i> ${lugar.macro}</p>
                            <p class="card-text small text-muted" style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
                                ${lugar.descripcion}
                            </p>
                        </div>
                        <div class="card-footer bg-white border-0 pt-0 pb-4 text-center">
                            <button class="btn btn-outline-info rounded-pill btn-block font-weight-bold" onclick="irAlMapa(${lugar.lat}, ${lugar.lng}, '${lugar.nombre}')">
                                <i class="fas fa-map-pin mr-2"></i>Ver en el Mapa
                            </button>
                        </div>
                    </div>
                </div>
            `;
            });
        }
        document.getElementById('listaLugares').innerHTML = html;
        generarPaginacion();
    }

    function actualizarVista() {
        mostrarLugaresEnLista();
        actualizarPinesMapa();
    }

    document.addEventListener('DOMContentLoaded', function () {
        initMapa();
        actualizarVista();
    });
</script>