<?php
// Evitamos duplicidad de Layout si el controlador ya lo invoca
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<style>
    /* Diseño de Barras Laterales */
    .municipio-selector {
        max-height: 70vh;
        overflow-y: auto;
        padding-right: 10px;
    }
    .barra-municipio {
        background: #ffffff;
        border-left: 5px solid #dee2e6;
        border-radius: 10px;
        padding: 18px 25px;
        margin-bottom: 12px;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }
    .barra-municipio:hover {
        transform: translateX(8px);
        border-left-color: #F2B705;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    .barra-municipio.activa {
        background: #217F82;
        color: white;
        border-left-color: #1A6A6D;
    }
    .barra-municipio.activa h5 { color: white !important; }
    
    /* Panel de Detalle (Derecha) */
    .detalle-container {
        background: white;
        border-radius: 20px;
        box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        overflow: hidden;
        position: sticky;
        top: 110px;
        animation: fadeIn 0.5s ease;
    }
    .detalle-hero {
        height: 300px;
        background-size: cover;
        background-position: center;
        position: relative;
        background-color: #1A6A6D; /* Color de fondo por si tarda en cargar la imagen */
    }
    .detalle-hero-overlay {
        position: absolute;
        bottom: 0; left: 0; right: 0;
        background: linear-gradient(transparent, rgba(26, 106, 109, 0.95));
        padding: 30px;
        color: white;
    }
    .meta-tag {
        background: rgba(255,255,255,0.25);
        backdrop-filter: blur(4px);
        padding: 6px 16px;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 600;
        border: 1px solid rgba(255,255,255,0.3);
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Scrollbar estético */
    .municipio-selector::-webkit-scrollbar { width: 6px; }
    .municipio-selector::-webkit-scrollbar-thumb { background: #cbd5e0; border-radius: 10px; }
</style>

<div class="container-fluid py-5 bg-light" style="min-height: 90vh;">
    <div class="container">
        <div class="row mb-5">
            <div class="col-12 border-bottom pb-3">
                <a href="<?= url('/destinos') ?>" class="text-info text-decoration-none mb-2 d-inline-block">
                    <i class="fa fa-arrow-left mr-1"></i> Volver a Provincias
                </a>
                <h2 class="font-weight-bold mt-2" style="color: #1A6A6D;" id="tituloProvincia">Cargando Provincia...</h2>
                <p class="text-muted lead">Información técnica y turística para el desarrollo regional.</p>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-4 mb-4">
                <div class="municipio-selector" id="listaMunicipios">
                    </div>
            </div>

            <div class="col-lg-8">
                <div class="detalle-container d-none" id="panelDetalle">
                    <div class="detalle-hero" id="detImagen">
                        <div class="detalle-hero-overlay">
                            <h2 class="font-weight-bold mb-3" id="detNombre">Cargando...</h2>
                            <div class="d-flex flex-wrap gap-2">
                                <span class="meta-tag mr-2"><i class="fa fa-mountain mr-2"></i> <span id="detAltitud">--</span></span>
                                <span class="meta-tag"><i class="fa fa-thermometer-half mr-2 text-warning"></i> <span id="detClima">--</span></span>
                            </div>
                        </div>
                    </div>
                    <div class="p-4 p-md-5">
                        <div class="row">
                            <div class="col-md-12 mb-4">
                                <h5 class="font-weight-bold text-dark border-bottom pb-2 mb-3">Resumen Informativo</h5>
                                <p class="text-muted" id="detDescripcion" style="line-height: 1.8; font-size: 1.05rem;"></p>
                            </div>
                            <div class="col-md-12">
                                <div class="p-4 rounded-lg" style="background: #f8f9fa; border-left: 4px solid #F2B705;">
                                    <h6 class="font-weight-bold text-dark"><i class="fa fa-lightbulb text-warning mr-2"></i> Tips de Viaje</h6>
                                    <p class="mb-0 text-muted small" id="detTip"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// El controlador nos pasa los datos filtrados, nosotros solo los convertimos
const provinciaData = <?= json_encode($datosProvincia) ?>;

function renderizar() {
    if (!provinciaData || !provinciaData.lista) {
        document.getElementById('listaMunicipios').innerHTML = '<div class="alert alert-danger shadow-sm border-0"><i class="fa fa-exclamation-triangle mr-2"></i> Datos de la provincia no encontrados.</div>';
        return;
    }

    document.getElementById('tituloProvincia').innerText = `Provincia: ${provinciaData.nombreProv}`;
    const lista = document.getElementById('listaMunicipios');
    let html = '';

    provinciaData.lista.forEach((mun) => {
        html += `
            <div class="barra-municipio" id="btn-${mun.id}" onclick="verDetalle(${mun.id})">
                <h5 class="m-0 font-weight-bold">${mun.nombre}</h5>
                <i class="fa fa-chevron-right text-muted"></i>
            </div>
        `;
    });

    lista.innerHTML = html;
    
    // Cargar el primer municipio automáticamente si existe
    if(provinciaData.lista.length > 0) {
        verDetalle(provinciaData.lista[0].id);
    }
}

function verDetalle(id) {
    const mun = provinciaData.lista.find(m => m.id === id);
    if (!mun) return;

    // Actualizar clases activas
    document.querySelectorAll('.barra-municipio').forEach(b => b.classList.remove('activa'));
    document.getElementById('btn-' + id).classList.add('activa');

    // Llenar datos
    document.getElementById('detNombre').innerText = mun.nombre;
    document.getElementById('detAltitud').innerText = mun.alt;
    document.getElementById('detClima').innerText = mun.clima;
    document.getElementById('detDescripcion').innerText = mun.desc;
    document.getElementById('detTip').innerText = mun.tips;
    
    // Configurar imagen
    document.getElementById('detImagen').style.backgroundImage = `url('<?= asset('imgs/') ?>${mun.img}')`;
    
    // Mostrar panel
    document.getElementById('panelDetalle').classList.remove('d-none');
}

document.addEventListener('DOMContentLoaded', renderizar);
</script>