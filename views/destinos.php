<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$title = 'Provincias de La Paz - TekoPorã';
ob_start();
?>

<style>
    /* Estilos modernos para las tarjetas de provincias */
    .provincia-card {
        transition: all 0.3s ease;
        border-radius: 15px;
        overflow: hidden;
        border: none;
        background: #fff;
    }
    .provincia-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 30px rgba(33, 127, 130, 0.15) !important;
    }
    .card-img-wrapper {
        position: relative;
        height: 220px;
        overflow: hidden;
        background: linear-gradient(45deg, #1A6A6D, #217F82); /* Fondo base si no hay imagen */
    }
    .card-img-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    .provincia-card:hover .card-img-wrapper img {
        transform: scale(1.1);
    }
    .badge-region {
        position: absolute;
        top: 15px;
        right: 15px;
        background: rgba(255, 255, 255, 0.95);
        color: #1A6A6D;
        font-weight: bold;
        padding: 5px 15px;
        border-radius: 50px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        z-index: 2;
    }
    .btn-explorar {
        background-color: #217F82;
        color: white;
        border-radius: 50px;
        font-weight: bold;
        transition: 0.3s;
    }
    .btn-explorar:hover {
        background-color: #1A6A6D;
        color: #F2B705;
    }
</style>

<div class="container-fluid p-0">
    <div id="header-carousel" class="carousel slide" data-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img class="w-100" src="<?= asset('imgs/valle.jpeg') ?>" alt="Paisaje La Paz" style="height: 400px; object-fit: cover; filter: brightness(0.65);">
                <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                    <div class="p-3" style="max-width: 900px;">
                        <h4 class="display-4 text-white text-uppercase font-weight-bold mb-md-3" style="letter-spacing: 2px;">DEPARTAMENTO DE LA PAZ</h4>
                        <p class="m-0 text-uppercase font-weight-bold text-warning">Inicio >> Catálogo de Provincias</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid py-4 bg-light border-bottom">
    <div class="container">
        <div class="row align-items-center justify-content-between">
            <div class="col-lg-4 mb-3 mb-lg-0">
                <h5 class="font-weight-bold text-dark m-0"><i class="fa fa-map text-info mr-2"></i> Explora nuestras Provincias</h5>
            </div>
            <div class="col-lg-8">
                <div class="row">
                    <div class="col-md-6 mb-2 mb-md-0">
                        <select class="form-control border-0 shadow-sm" id="filtroRegion" onchange="aplicarFiltros()" style="border-radius: 10px;">
                            <option value="todos">Todas las Zonas Geográficas</option>
                            <option value="Altiplano">Altiplano</option>
                            <option value="Valles">Valles</option>
                            <option value="Yungas">Yungas</option>
                            <option value="Amazonía">Amazonía</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group shadow-sm" style="border-radius: 10px; overflow: hidden;">
                            <input type="text" class="form-control border-0" id="buscarTexto" placeholder="Buscar provincia o capital..." oninput="aplicarFiltros()">
                            <div class="input-group-append">
                                <span class="input-group-text bg-white border-0 text-info"><i class="fa fa-search"></i></span>
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
        <div class="text-center mb-5">
            <h6 class="text-primary text-uppercase font-weight-bold" style="letter-spacing: 5px;">TekoPorã Bolivia</h6>
            <h2 class="mb-3 font-weight-bold" style="color: #1A6A6D;">Las 20 Provincias Paceñas</h2>
            <p class="text-muted">Selecciona una provincia para descubrir sus municipios y proyectos.</p>
        </div>

        <div class="row" id="contenedorProvincias">
            </div>
    </div>
</div>

<script>
// 1. BASE DE DATOS (Tus 20 provincias intactas)
const provinciasDB = [
    { id: 1, nombre: "Abel Iturralde", capital: "Ixiamas", region: "Amazonía", imagen: "iturralde.jpg", descripcion: "La provincia más extensa y amazónica del norte paceño." },
    { id: 2, nombre: "Aroma", capital: "Sica Sica", region: "Altiplano", imagen: "aroma.jpg", descripcion: "Cuna de historia y vastas planicies altiplánicas." },
    { id: 3, nombre: "Bautista Saavedra", capital: "Charazani", region: "Valles", imagen: "saavedra.jpg", descripcion: "Tierra de los famosos médicos tradicionales Kallawayas." },
    { id: 4, nombre: "Caranavi", capital: "Caranavi", region: "Yungas", imagen: "caranavi.jpg", descripcion: "La capital cafetalera de Bolivia por excelencia." },
    { id: 5, nombre: "Eliodoro Camacho", capital: "Puerto Acosta", region: "Altiplano", imagen: "eliodoro.jpg", descripcion: "Provincia fronteriza a orillas del majestuoso Lago Titicaca." },
    { id: 6, nombre: "Franz Tamayo", capital: "Apolo", region: "Amazonía", imagen: "franztamayo.jpg", descripcion: "Hogar del Parque Nacional Madidi, reserva de biodiversidad." },
    { id: 7, nombre: "Gualberto Villarroel", capital: "San Pedro de Curahuara", region: "Altiplano", imagen: "gualberto.png", descripcion: "Importante productora de camélidos y quinua." },
    { id: 8, nombre: "Ingavi", capital: "Viacha", region: "Altiplano", imagen: "ingavi.jpg", descripcion: "Centro industrial y sede de las ruinas arqueológicas de Tiwanaku." },
    { id: 9, nombre: "Inquisivi", capital: "Inquisivi", region: "Valles", imagen: "inquisivi.jpg", descripcion: "Zona de valles profundos y rica historia republicana." },
    { id: 10, nombre: "José Manuel Pando", capital: "Santiago de Machaca", region: "Altiplano", imagen: "pando.jpg", descripcion: "Provincia ganadera en la frontera occidental del país." },
    { id: 11, nombre: "Larecaja", capital: "Sorata", region: "Valles", imagen: "larecaja.jpg", descripcion: "El paraíso terrenal a los pies del imponente nevado Illampu." },
    { id: 12, nombre: "Loayza", capital: "Luribay", region: "Valles", imagen: "loayza.jpg", descripcion: "Famosa por su exquisita producción de duraznos y vinos de altura." },
    { id: 13, nombre: "Los Andes", capital: "Pucarani", region: "Altiplano", imagen: "losandes.jpg", descripcion: "Tierra de la imponente Cordillera Real y nevados eternos." },
    { id: 14, nombre: "Manco Kapac", capital: "Copacabana", region: "Altiplano", imagen: "mancokapac.jpg", descripcion: "Santuario y principal puerto turístico del Lago Titicaca." },
    { id: 15, nombre: "Muñecas", capital: "Chuma", region: "Valles", imagen: "muñecas.jpg", descripcion: "Provincia de abruptos valles y rica herencia cultural." },
    { id: 16, nombre: "Nor Yungas", capital: "Coroico", region: "Yungas", imagen: "noryungas.jpg", descripcion: "Destino turístico principal y cuna de la cultura afroboliviana." },
    { id: 17, nombre: "Omasuyos", capital: "Achacachi", region: "Altiplano", imagen: "omasuyos.jpg", descripcion: "Los famosos 'Ponchos Rojos', guardianes de la cultura Aymara." },
    { id: 18, nombre: "Pacajes", capital: "Coro Coro", region: "Altiplano", imagen: "pacajes.jpg", descripcion: "Centro minero histórico y tierra de los 'hombres águila'." },
    { id: 19, nombre: "Pedro Domingo Murillo", capital: "Palca", region: "Altiplano", imagen: "murillo.jpg", descripcion: "Sede de gobierno, incluye a las ciudades de La Paz y El Alto." },
    { id: 20, nombre: "Sud Yungas", capital: "Chulumani", region: "Yungas", imagen: "sudyungas.jpg", descripcion: "Paraíso de clima subtropical, abundante vegetación y cascadas." }
];

// 2. MEMORIA DE ESTADO (Para evitar re-renderizados inútiles)
let temporizadorBuscador;
let ultimoTexto = "";
let ultimaRegion = "todos";

// 3. LÓGICA DE DIBUJADO
function renderizarProvincias(datos) {
    const contenedor = document.getElementById('contenedorProvincias');
    let html = '';

    if(datos.length === 0) {
        contenedor.innerHTML = `
            <div class="col-12 text-center py-5">
                <i class="fa fa-search fa-3x text-muted mb-3"></i>
                <h4 class="text-muted">No se encontraron provincias con esos criterios.</h4>
            </div>
        `;
        return;
    }

    datos.forEach(prov => {
        let iconoRegion = 'fa-mountain';
        if(prov.region === 'Amazonía') iconoRegion = 'fa-leaf';
        if(prov.region === 'Valles') iconoRegion = 'fa-seedling';
        if(prov.region === 'Yungas') iconoRegion = 'fa-tree';

        html += `
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card shadow-sm provincia-card h-100">
                    <div class="card-img-wrapper">
                        <span class="badge-region"><i class="fa ${iconoRegion} mr-1"></i> ${prov.region}</span>
                        <img src="<?= asset('imgs/') ?>${prov.imagen}" alt="${prov.nombre}" onerror="this.onerror=null; this.src='https://via.placeholder.com/400x250/1A6A6D/FFFFFF?text=Sin+Imagen'">
                    </div>
                    <div class="card-body d-flex flex-column">
                        <h4 class="font-weight-bold text-dark mb-1">${prov.nombre}</h4>
                        <p class="text-info font-weight-bold small mb-2"><i class="fa fa-map-pin mr-1"></i> Capital: ${prov.capital}</p>
                        <p class="text-muted small flex-grow-1">${prov.descripcion}</p>
                        
                        <button class="btn btn-explorar w-100 py-2 mt-3" onclick="verMunicipios(${prov.id}, '${prov.nombre}')">
                            Ver Municipios <i class="fa fa-arrow-right ml-2"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;
    });

    contenedor.innerHTML = html;
}

// 4. FILTRO INTELIGENTE (Debounce + Verificación de estado)
function aplicarFiltros() {
    clearTimeout(temporizadorBuscador);

    temporizadorBuscador = setTimeout(() => {
        const textoActual = document.getElementById('buscarTexto').value.toLowerCase().trim();
        const regionActual = document.getElementById('filtroRegion').value;

        // ¡EL CANDADO! Si el usuario hizo clic pero el texto y el filtro no cambiaron, abortamos.
        if (textoActual === ultimoTexto && regionActual === ultimaRegion) {
            return; 
        }

        // Si realmente cambió, actualizamos nuestra memoria
        ultimoTexto = textoActual;
        ultimaRegion = regionActual;

        // Y filtramos
        const filtrados = provinciasDB.filter(prov => {
            const coincideTexto = prov.nombre.toLowerCase().includes(textoActual) || prov.capital.toLowerCase().includes(textoActual);
            const coincideRegion = (regionActual === 'todos' || prov.region === regionActual);
            return coincideTexto && coincideRegion;
        });

        renderizarProvincias(filtrados);
    }, 250); // 250ms de gracia
}

function verMunicipios(idProvincia, nombreProvincia) {
    window.location.href = "<?= url('/municipios?prov=') ?>" + idProvincia;
}

// Inicializar la vista
document.addEventListener('DOMContentLoaded', () => {
    renderizarProvincias(provinciasDB);
});
</script>