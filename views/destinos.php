<!-- Header Start -->
<div class="container-fluid p-0">
    <div id="header-carousel" class="carousel slide" data-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img class="w-100" src="public/imgs/mercado.jpeg" alt="Salar de Uyuni">
                <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                    <div class="p-3" style="max-width: 900px;">
                        <h4 class="display-4 text-white text-uppercase mb-md-3">DESTINOS TURÍSTICOS</h4>
                        <p class="m-0 text-uppercase">Inicio  >>  La Paz - Macrodistritos</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Header End -->

<!-- Destinos Start -->
<div class="container-fluid py-5">
    <div class="container py-5">
        <div class="text-center mb-5">
            <h6 class="text-primary text-uppercase" style="letter-spacing: 5px;">Descubre La Paz</h6>
            <h1 class="mb-3">Destinos por Macrodistrito</h1>
            <p class="lead">Explora los lugares turísticos de los 9 macrodistritos de La Paz</p>
        </div>

        <!-- Filtros rápidos -->
        <div class="row mb-5">
            <div class="col-lg-12">
                <div class="btn-group flex-wrap justify-content-center" role="group">
                    <button class="btn btn-outline-primary m-1 active" style="border-color: #217F82; color: #217F82;" onclick="filtrarDestinos('todos')">Todos</button>
                    <button class="btn btn-outline-primary m-1" style="border-color: #217F82; color: #217F82;" onclick="filtrarDestinos('Mallasa')">Mallasa</button>
                    <button class="btn btn-outline-primary m-1" style="border-color: #217F82; color: #217F82;" onclick="filtrarDestinos('Zona Sur')">Zona Sur</button>
                    <button class="btn btn-outline-primary m-1" style="border-color: #217F82; color: #217F82;" onclick="filtrarDestinos('San Antonio')">San Antonio</button>
                    <button class="btn btn-outline-primary m-1" style="border-color: #217F82; color: #217F82;" onclick="filtrarDestinos('Periférica')">Periférica</button>
                    <button class="btn btn-outline-primary m-1" style="border-color: #217F82; color: #217F82;" onclick="filtrarDestinos('Max Paredes')">Max Paredes</button>
                    <button class="btn btn-outline-primary m-1" style="border-color: #217F82; color: #217F82;" onclick="filtrarDestinos('Cotahuma')">Cotahuma</button>
                    <button class="btn btn-outline-primary m-1" style="border-color: #217F82; color: #217F82;" onclick="filtrarDestinos('Centro')">Centro</button>
                    <button class="btn btn-outline-primary m-1" style="border-color: #217F82; color: #217F82;" onclick="filtrarDestinos('Hampaturi')">Hampaturi</button>
                    <button class="btn btn-outline-primary m-1" style="border-color: #217F82; color: #217F82;" onclick="filtrarDestinos('Zongo')">Zongo</button>
                </div>
            </div>
        </div>

        <div class="row" id="destinosContainer">
            <!-- Se carga con JS -->
        </div>
    </div>
</div>
<!-- Destinos End -->

<script>
// Base de datos simulada de destinos
const destinosDB = [
    {
        id: 1,
        nombre: "Valle de la Luna",
        macro: "Mallasa",
        tipo: "Formación natural",
        descripcion: "Formaciones arcillosas de millones de años con formas caprichosas. A solo 10 km del centro de La Paz.",
        imagen: "valle-luna.jpg",
        rating: 4.5,
        visitas: "50k+"
    },
    {
        id: 2,
        nombre: "Muela del Diablo",
        macro: "Mallasa",
        tipo: "Formación rocosa",
        descripcion: "Imponente formación rocosa con vistas panorámicas de La Paz. Ideal para senderismo.",
        imagen: "muela-diablo.jpg",
        rating: 4.3,
        visitas: "20k+"
    },
    {
        id: 3,
        nombre: "Parque de las Águilas",
        macro: "Zona Sur",
        tipo: "Parque",
        descripcion: "Parque urbano con áreas verdes, juegos infantiles y miradores.",
        imagen: "parque-aguilas.jpg",
        rating: 4.4,
        visitas: "35k+"
    },
    {
        id: 4,
        nombre: "Mirador Killi Killi",
        macro: "Centro",
        tipo: "Mirador",
        descripcion: "Mirador con vista de 360 grados de la ciudad de La Paz. Ideal para fotos.",
        imagen: "killi-killi.jpg",
        rating: 4.8,
        visitas: "100k+"
    },
    {
        id: 5,
        nombre: "Plaza Murillo",
        macro: "Centro",
        tipo: "Plaza histórica",
        descripcion: "Plaza principal de La Paz, rodeada de edificios gubernamentales y catedral.",
        imagen: "plaza-murillo.jpg",
        rating: 4.6,
        visitas: "200k+"
    },
    {
        id: 6,
        nombre: "Chacaltaya",
        macro: "Zongo",
        tipo: "Montaña",
        descripcion: "Antigua estación de esquí a 5.420 msnm. Vista impresionante de los Andes.",
        imagen: "chacaltaya.jpg",
        rating: 4.7,
        visitas: "30k+"
    },
    {
        id: 7,
        nombre: "Mirador Jach'a Kollo",
        macro: "Hampaturi",
        tipo: "Mirador",
        descripcion: "Mirador comunitario con vista a toda la cuenca de Hampaturi.",
        imagen: "jacha-kollo.jpg",
        rating: 4.2,
        visitas: "5k+"
    },
    {
        id: 8,
        nombre: "Parque Urbano Central",
        macro: "Centro",
        tipo: "Parque",
        descripcion: "Pulmón verde en el centro de La Paz, con lagunas y espacios deportivos.",
        imagen: "puc.jpg",
        rating: 4.5,
        visitas: "150k+"
    },
    {
        id: 9,
        nombre: "Cementerio General",
        macro: "Max Paredes",
        tipo: "Sitio histórico",
        descripcion: "Cementerio con arquitectura y mausoleos históricos. Visitas guiadas nocturnas.",
        imagen: "cementerio.jpg",
        rating: 4.0,
        visitas: "25k+"
    },
    {
        id: 10,
        nombre: "Valle de las Ánimas",
        macro: "Mallasa",
        tipo: "Formación natural",
        descripcion: "Formaciones rocosas espectaculares, ideal para trekking.",
        imagen: "valle-animas.jpg",
        rating: 4.6,
        visitas: "15k+"
    },
    {
        id: 11,
        nombre: "Teleférico La Paz",
        macro: "Centro",
        tipo: "Atracción",
        descripcion: "La red de teleférico más larga del mundo, con vistas panorámicas.",
        imagen: "teleferico.jpg",
        rating: 4.9,
        visitas: "1M+"
    },
    {
        id: 12,
        nombre: "Cascada de Zongo",
        macro: "Zongo",
        tipo: "Cascada",
        descripcion: "Cascada de 50 metros en medio de la selva nublada de Zongo.",
        imagen: "cascada-zongo.jpg",
        rating: 4.7,
        visitas: "8k+"
    }
];

function mostrarDestinos(filtro = 'todos') {
    const container = document.getElementById('destinosContainer');
    let html = '';
    
    const filtrados = filtro === 'todos' 
        ? destinosDB 
        : destinosDB.filter(d => d.macro === filtro);
    
    filtrados.forEach(destino => {
        // Generar estrellas
        let estrellas = '';
        for (let i = 1; i <= 5; i++) {
            if (i <= Math.floor(destino.rating)) {
                estrellas += '<i class="fas fa-star text-warning"></i>';
            } else if (i - destino.rating < 1 && i - destino.rating > 0) {
                estrellas += '<i class="fas fa-star-half-alt text-warning"></i>';
            } else {
                estrellas += '<i class="far fa-star text-warning"></i>';
            }
        }
        
        html += `
            <div class="col-lg-4 col-md-6 mb-4 destino-card">
                <div class="card border-0 shadow-sm h-100">
                    <img src="public/imgs/${destino.imagen}" class="card-img-top" alt="${destino.nombre}" style="height: 200px; object-fit: cover;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="card-title mb-0">${destino.nombre}</h5>
                            <span class="badge" style="background-color: #217F82; color: white;">${destino.macro}</span>
                        </div>
                        <p class="text-muted small mb-2">
                            <i class="fas fa-tag mr-1" style="color: #217F82;"></i>${destino.tipo}
                        </p>
                        <p class="card-text small">${destino.descripcion}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                ${estrellas}
                                <small class="ml-1">(${destino.rating})</small>
                            </div>
                            <small class="text-muted">
                                <i class="fas fa-eye mr-1"></i>${destino.visitas}
                            </small>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-0 pb-3">
                        <button class="btn btn-sm btn-block" style="background-color: #217F82; color: white;" onclick="verDestino(${destino.id})">
                            <i class="fas fa-info-circle mr-2"></i>Ver detalles
                        </button>
                    </div>
                </div>
            </div>
        `;
    });
    
    if (filtrados.length === 0) {
        html = `
            <div class="col-12">
                <div class="alert alert-info text-center py-5">
                    <i class="fas fa-map-marked-alt fa-3x mb-3"></i>
                    <h4>No hay destinos en este macrodistrito</h4>
                    <p>¿Conoces algún lugar? ¡Agrégalo al mapa!</p>
                    <a href="add-tourist-site.php" class="btn btn-primary" style="background-color: #217F82; border-color: #217F82;">
                        Agregar sitio
                    </a>
                </div>
            </div>
        `;
    }
    
    container.innerHTML = html;
    
    // Actualizar botones activos
    document.querySelectorAll('.btn-group .btn').forEach(btn => {
        btn.classList.remove('active');
    });
    if (filtro === 'todos') {
        document.querySelector('.btn-group .btn:first-child').classList.add('active');
    } else {
        event.target.classList.add('active');
    }
}

function filtrarDestinos(macro) {
    mostrarDestinos(macro);
}

function verDestino(id) {
    const destino = destinosDB.find(d => d.id === id);
    alert(`📍 ${destino.nombre}\n\n` +
          `Macrodistrito: ${destino.macro}\n` +
          `Tipo: ${destino.tipo}\n` +
          `Rating: ${destino.rating}\n` +
          `Visitas: ${destino.visitas}\n\n` +
          `${destino.descripcion}\n\n` +
          `(Simulación - Aquí irían más detalles cuando el backend esté listo)`);
}

// Cargar destinos al inicio
document.addEventListener('DOMContentLoaded', function() {
    mostrarDestinos('todos');
});
</script>
