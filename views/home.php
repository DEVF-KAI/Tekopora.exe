<!-- Carousel Start -->
<div class="container-fluid p-0">
    <div id="header-carousel" class="carousel slide" data-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img class="w-100" src="<?= asset('imgs/bolivia.png') ?>"> alt="Salar de Uyuni">
                <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                    <div class="p-3" style="max-width: 900px;">
                        <h4 class="text-white text-uppercase mb-md-3">Transparencia y Participación</h4>
                        <h1 class="display-3 text-white mb-md-4">Seguimiento de Proyectos Públicos en Bolivia</h1>
                        <a href="<?= url('/proyectos') ?>" class="btn btn-primary py-md-3 px-md-5 mt-2">Ver Proyectos</a>
                    </div>
                </div>
            </div>
            <div class="carousel-item">
                <img class="w-100" src="public/imgs/boli.png" alt="Lago Titicaca">
                <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                    <div class="p-3" style="max-width: 900px;">
                        <h4 class="text-white mb-md-3">Mapa Turístico Colaborativo</h4>
                        <h1 class="display-3 text-white mb-md-4">Descubre y Comparte Sitios Turísticos</h1>
                        <a href="<?= url('/turismo') ?>" class="btn btn-primary py-md-3 px-md-5 mt-2">Explorar Mapa</a>
                    </div>
                </div>                          
            </div>
            <div class="carousel-item">
                <img class="w-100" src="public/imgs/llamas.png" alt="Valle de la Luna">
                <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                    <div class="p-3" style="max-width: 900px;">
                        <h4 class="text-white text-uppercase mb-md-3">Participación Ciudadana</h4>
                        <h1 class="display-3 text-white mb-md-4">Agrega Sitios Turísticos de tu Región</h1>
                        <a href="add-tourist-site.php" class="btn btn-primary py-md-3 px-md-5 mt-2">Contribuir</a>
                    </div>
                </div>
            </div>
        </div>
        <a class="carousel-control-prev" href="#header-carousel" data-slide="prev">
            <div class="btn btn-dark" style="width: 45px; height: 45px;">
                <span class="carousel-control-prev-icon mb-n2"></span>
            </div>
        </a>
        <a class="carousel-control-next" href="#header-carousel" data-slide="next">
            <div class="btn btn-dark" style="width: 45px; height: 45px;">
                <span class="carousel-control-next-icon mb-n2"></span>
            </div>
        </a>
    </div>
</div>
<!-- Carousel End -->

<!-- Dashboard Estadístico Start -->
<div class="container-fluid py-5">
    <div class="container pt-5">
        <div class="row">
            <div class="col-lg-12">
                <div class="text-center mb-5">
                    <h6 class="text-primary text-uppercase" style="letter-spacing: 5px;">Dashboard Transparencia</h6>
                    <h1 class="mb-3">Métricas Clave de Proyectos Públicos</h1>
                </div>
                
                <div class="row">
                    <!-- Gráfico 1: Distribución de Proyectos -->
                    <div class="col-lg-6 mb-4">
                        <div class="card h-100">
                            <div class="card-header">
                                <h5 class="card-title">Proyectos por Departamento</h5>
                            </div>
                            <div class="card-body">
                                <canvas id="projectsByDepartment" height="250"></canvas>

                            </div>
                        </div>
                    </div>
                    
                    <!-- Gráfico 2: Avance de Proyectos -->
                    <div class="col-lg-6 mb-4">
                        <div class="card h-100">
                            <div class="card-header">
                                <h5 class="card-title">Avance de Proyectos por Departamento</h5>
                            </div>
                            <div class="card-body">
                                <canvas id="projectProgress" height="250"></canvas>

                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row mt-4">
                    <!-- Estadísticas Descriptivas -->
                    <div class="col-lg-4 mb-4">
                        <div class="card text-white h-100" style="background-color: #217F82;">
                            <div class="card-body text-center">
                                <i class="fas fa-calculator fa-3x mb-3"></i>
                                <h3 class="card-title mb-0" style="color: white;">Proyectos Registrados</h3>
                                <p class="stat-value" style="color: white;">127</p>
                                <p class="mb-0">Total de proyectos públicos</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-4 mb-4">
                        <div class="card text-white h-100" style="background-color: #217F82;">
                            <div class="card-body text-center">
                                <i class="fas fa-chart-line fa-3x mb-3"></i>
                                <h3 class="card-title mb-0" style="color: white;">Proyectos Completados</h3>
                                <p class="stat-value" style="color: white;">48</p>
                                <p class="mb-0">38% del total</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-4 mb-4">
                        <div class="card text-white h-100" style="background-color: #217F82;">
                            <div class="card-body text-center">
                                <i class="fas fa-percentage fa-3x mb-3" ></i>
                                <h3 class="card-title mb-0" style="color: white;">Presupuesto Total</h3>
                                <p class="stat-value" style="color: white;">Bs. 2.9B</p>
                                <p class="mb-0">Inversión pública</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Dashboard Estadístico End -->

<!-- Análisis por Categorías Start -->
<div class="container-fluid pb-5">
    <div class="container pb-5">
        <div class="row">
            <div class="col-md-4">
                <div class="d-flex mb-4 mb-lg-0">
                    <div class="d-flex flex-shrink-0 align-items-center justify-content-center bg-primary mr-3" style="height: 100px; width: 100px;">
                        <i class="fa fa-2x fa-road text-white"></i>
                    </div>
                    <div class="d-flex flex-column">
                        <h5 class="">Infraestructura Vial</h5>
                        <p class="m-0">42 proyectos de carreteras y puentes con 68% de avance promedio</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="d-flex mb-4 mb-lg-0">
                    <div class="d-flex flex-shrink-0 align-items-center justify-content-center bg-primary mr-3" style="height: 100px; width: 100px;">
                        <i class="fa fa-2x fa-hospital text-white"></i>
                    </div>
                    <div class="d-flex flex-column">
                        <h5 class="">Salud</h5>
                        <p class="m-0">28 proyectos de hospitales y centros de salud, 45% en ejecución</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="d-flex mb-4 mb-lg-0">
                    <div class="d-flex flex-shrink-0 align-items-center justify-content-center bg-primary mr-3" style="height: 100px; width: 100px;">
                        <i class="fa fa-2x fa-water text-white"></i>
                    </div>
                    <div class="d-flex flex-column">
                        <h5 class="">Agua Potable</h5>
                        <p class="m-0">35 proyectos de sistemas de agua, 15 completados</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Gráfico de distribución de proyectos por departamento
    const projectsCtx = document.getElementById('projectsByDepartment').getContext('2d');
    const projectsChart = new Chart(projectsCtx, {
        type: 'bar',
        data: {
            labels: ['La Paz', 'Cochabamba', 'Santa Cruz', 'Potosí', 'Oruro', 'Beni', 'Pando', 'Tarija', 'Chuquisaca'],
            datasets: [{
                label: 'Número de Proyectos',
                data: [45, 38, 52, 22, 18, 15, 8, 24, 20],
                backgroundColor: 'rgba(54, 162, 235, 0.8)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Número de Proyectos'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Departamento'
                    }
                }
            }
        }
    });

    // Gráfico de avance de proyectos
    const progressCtx = document.getElementById('projectProgress').getContext('2d');
    const progressChart = new Chart(progressCtx, {
        type: 'line',
        data: {
            labels: ['La Paz', 'Cochabamba', 'Santa Cruz', 'Potosí', 'Oruro', 'Beni', 'Pando', 'Tarija', 'Chuquisaca'],
            datasets: [{
                label: 'Avance Promedio (%)',
                data: [68, 61, 72, 45, 52, 58, 41, 63, 55],
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 2,
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    title: {
                        display: true,
                        text: 'Avance (%)'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Departamento'
                    }
                }
            }
        }
    });
</script>