<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$title = 'Catálogo de Obras - TekoPorã';
ob_start();
?>

<style>
    /* HERO: Imagen completa, sin filtros celestes, texto al centro */
    .hero-projects {
        background-image: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.6)), url("<?= asset('imgs/lago.jpeg') ?>");
        background-size: cover;
        background-position: center;
        min-height: 80vh;
        /* Para que la imagen luzca imponente y no se corte */
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
        color: white;
    }

    .hero-projects h1 {
        font-size: 4.5rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 2px;
        font-family: 'Times New Roman', Times, serif;
        margin-bottom: 0;
    }

    .hero-breadcrumb {
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        opacity: 0.8;
        margin-top: 10px;
    }

    /* SECCIÓN DE FILTROS: Totalmente separada debajo de la imagen */
    .filter-section {
        background-color: #ffffff;
        padding: 50px 0;
        border-bottom: 1px solid #eee;
    }

    .card-filter {
        border-radius: 15px;
        border: 1px solid #eee;
    }

    /* CARDS DE PROYECTO */
    .project-card {
        border: none;
        border-radius: 15px;
        overflow: hidden;
        transition: 0.3s;
    }

    .project-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1) !important;
    }

    .badge-status {
        position: absolute;
        top: 15px;
        right: 15px;
        z-index: 5;
        border-radius: 50px;
        padding: 5px 15px;
        font-weight: 700;
        font-size: 0.7rem;
        text-transform: uppercase;
    }
</style>

<div class="container-fluid p-0 hero-projects">
    <div class="container">
        <h1>Proyectos Públicos</h1>
        <div class="hero-breadcrumb">Inicio >> La Paz - Macrodistritos</div>
    </div>
</div>

<div class="container-fluid filter-section">
    <div class="container">
        <div class="card card-filter shadow-sm">
            <div class="card-body p-4 p-md-5">
                <h5 class="font-weight-bold mb-4" style="color: #1A6A6D;">
                    <i class="fas fa-search-location mr-2 text-warning"></i> Encuentra proyectos en tu zona
                </h5>

                <form action="<?= url('/proyectos') ?>" method="GET">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="text-muted small font-weight-bold">MACRODISTRITO</label>
                            <select class="form-control rounded-pill" name="macrodistrito">
                                <option value="todos">Todos los distritos</option>
                                <?php foreach ($macrodistritos as $m): ?>
                                    <option value="<?= $m['idMacrodistrito'] ?>" <?= (isset($_GET['macrodistrito']) && $_GET['macrodistrito'] == $m['idMacrodistrito']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($m['nombreMacrodistrito']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="text-muted small font-weight-bold">ESTADO</label>
                            <select class="form-control rounded-pill" name="estado">
                                <option value="todos">Cualquier estado</option>
                                <option value="En ejecución">En ejecución</option>
                                <option value="Completado">Completado</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="text-muted small font-weight-bold">BUSCAR</label>
                            <input type="text" name="buscar" class="form-control rounded-pill"
                                placeholder="Ej: Puente..." value="<?= htmlspecialchars($_GET['buscar'] ?? '') ?>">
                        </div>
                    </div>
                    <div class="text-right mt-3">
                        <button type="submit" class="btn px-5 rounded-pill text-white shadow"
                            style="background-color: #217F82;">
                            <i class="fas fa-search mr-2"></i> Aplicar Filtros
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="container py-5">
    <div class="row mb-5 align-items-center">
        <div class="col-md-6">
            <h2 class="font-weight-bold" style="color: #1A6A6D;">Catálogo de Obras</h2>
            <p class="text-muted">Mostrando <b><?= count($proyectos) ?></b> proyectos registrados.</p>
        </div>
        <div class="col-md-6 text-md-right">
            <?php if (isset($_SESSION['usuario']) && in_array($_SESSION['usuario']['rol'], ['Administrador', 'Personal Alcaldia'])): ?>
                <a href="<?= url('/proyectosadd') ?>" class="btn btn-warning rounded-pill font-weight-bold px-4">
                    <i class="fas fa-plus-circle mr-2"></i> Nuevo Proyecto
                </a>
            <?php endif; ?>
        </div>
    </div>

    <div class="row">
        <?php if (empty($proyectos)): ?>
            <div class="col-12 text-center py-5">
                <h4 class="text-muted">No hay proyectos para mostrar.</h4>
            </div>
        <?php else: ?>
            <?php foreach ($proyectos as $p): ?>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100 shadow-sm project-card position-relative">
                        <?php $badgeClass = ($p['estado'] == 'En ejecución') ? 'bg-warning text-dark' : 'bg-success text-white'; ?>
                        <div class="badge-status <?= $badgeClass ?>"><?= $p['estado'] ?></div>

                        <img src="<?= !empty($p['imagen_url']) ? asset($p['imagen_url']) : asset('imgs/obra_default.jpg') ?>"
                            class="card-img-top" style="height: 200px; object-fit: cover;" alt="Imagen del proyecto">

                        <div class="card-body">
                            <h5 class="font-weight-bold"><?= htmlspecialchars($p['nombreProyecto']) ?></h5>
                            <div class="progress mb-2" style="height: 8px;">
                                <div class="progress-bar bg-info" style="width: <?= $p['avancePorcentaje'] ?>%"></div>
                            </div>
                            <small class="text-muted">Avance: <?= $p['avancePorcentaje'] ?>%</small>
                            <div class="mt-3 small text-muted">
                                <i class="fa fa-map-marker-alt text-danger"></i>
                                <?= $p['nombreMacrodistrito'] ?? 'Zona Central' ?>
                            </div>
                        </div>
                        <div class="card-footer bg-white border-0 pb-4">
                            <a href="<?= url('/proyectos/detalle?codigo=' . $p['codigoProyecto']) ?>"
                                class="btn btn-outline-primary btn-block rounded-pill btn-sm font-weight-bold">
                                Ver Detalles
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>