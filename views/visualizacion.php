<!-- Header Start -->
<div class="container-fluid page-header" style="background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('img/datos-bolivia.jpg') center/cover no-repeat;">
    <div class="container">
        <div class="d-flex flex-column align-items-center justify-content-center" style="min-height: 400px">
            <h3 class="display-4 text-white text-uppercase">Visualización de Datos</h3>
            <div class="d-inline-flex text-white">
                <p class="m-0 text-uppercase"><a class="text-white" href="index.php">Inicio</a></p>
                <i class="fa fa-angle-double-right pt-1 px-3"></i>
                <p class="m-0 text-uppercase">Transparencia en Gráficos</p>
            </div>
        </div>
    </div>
</div>
<!-- Header End -->

<!-- Dashboard Interactivo Start -->
<div class="container-fluid py-5">
    <div class="container py-5">
        <div class="text-center mb-5">
            <h6 class="text-primary text-uppercase" style="letter-spacing: 5px;">Visualización</h6>
            <h1 class="mb-3">Datos de Proyectos Públicos y Turismo</h1>
            <p>Explora y analiza datos de transparencia mediante visualizaciones interactivas</p>
        </div>

        <!-- Controles del Dashboard -->
        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label><strong>Filtrar por Departamento:</strong></label>
                            <select class="form-control" onchange="actualizarDashboard()">
                                <option>Todos los departamentos</option>
                                <option>La Paz</option>
                                <option>Cochabamba</option>
                                <option>Santa Cruz</option>
                                <option>Potosí</option>
                                <option>Oruro</option>
                                <option>Beni</option>
                                <option>Pando</option>
                                <option>Tarija</option>
                                <option>Chuquisaca</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label><strong>Tipo de Dato:</strong></label>
                            <select class="form-control" onchange="cambiarVisualizacion()">
                                <option value="proyectos">Proyectos Públicos</option>
                                <option value="turismo">Sitios Turísticos</option>
                                <option value="presupuesto">Presupuesto</option>
                                <option value="avance">Avance de Obras</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label><strong>Métrica Principal:</strong></label>
                            <select class="form-control" onchange="actualizarDashboard()">
                                <option value="cantidad">Cantidad de Proyectos</option>
                                <option value="presupuesto">Presupuesto (Bs)</option>
                                <option value="avance">% de Avance</option>
                                <option value="tiempo">Tiempo de Ejecución</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Primera Fila de Gráficos -->
        <div class="row mb-4">
            <div class="col-lg-8 mb-4">
                <div class="card border-0 shadow-lg h-100">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title mb-0">Proyectos por Departamento</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="proyectosDepartamentoChart" height="300"></canvas>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 mb-4">
                <div class="card border-0 shadow-lg h-100">
                    <div class="card-header bg-success text-white">
                        <h5 class="card-title mb-0">Resumen General</h5>
                    </div>
                    <div class="card-body">
                        <div class="text-center">
                            <div class="row">
                                <div class="col-6 mb-3">
                                    <div class="p-3 bg-light rounded">
                                        <h4 class="text-primary mb-1">127</h4>
                                        <small>Proyectos</small>
                                    </div>
                                </div>
                                <div class="col-6 mb-3">
                                    <div class="p-3 bg-light rounded">
                                        <h4 class="text-success mb-1">Bs 2.9B</h4>
                                        <small>Presupuesto</small>
                                    </div>
                                </div>
                                <div class="col-6 mb-3">
                                    <div class="p-3 bg-light rounded">
                                        <h4 class="text-warning mb-1">61%</h4>
                                        <small>Avance Promedio</small>
                                    </div>
                                </div>
                                <div class="col-6 mb-3">
                                    <div class="p-3 bg-light rounded">
                                        <h4 class="text-info mb-1">284</h4>
                                        <small>Sitios Turísticos</small>
                                    </div>
                                </div>
                            </div>
                            <div class="progress mt-2" style="height: 20px;">
                                <div class="progress-bar bg-success" style="width: 38%">Completados</div>
                                <div class="progress-bar bg-warning" style="width: 45%">En Ejecución</div>
                                <div class="progress-bar bg-secondary" style="width: 17%">Planificados</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Segunda Fila de Gráficos -->
        <div class="row mb-4">
            <div class="col-lg-6 mb-4">
                <div class="card border-0 shadow-lg h-100">
                    <div class="card-header bg-warning text-white">
                        <h5 class="card-title mb-0">Presupuesto por Departamento</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="presupuestoChart" height="250"></canvas>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6 mb-4">
                <div class="card border-0 shadow-lg h-100">
                    <div class="card-header bg-info text-white">
                        <h5 class="card-title mb-0">Avance de Proyectos por Departamento</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="avanceChart" height="250"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tercera Fila de Gráficos -->
        <div class="row">
            <div class="col-lg-6 mb-4">
                <div class="card border-0 shadow-lg h-100">
                    <div class="card-header bg-danger text-white">
                        <h5 class="card-title mb-0">Estado de Proyectos</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="estadoChart" height="250"></canvas>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6 mb-4">
                <div class="card border-0 shadow-lg h-100">
                    <div class="card-header bg-dark text-white">
                        <h5 class="card-title mb-0" style="color: white;">Correlación: Presupuesto vs Avance</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="correlacionChart" height="250"></canvas>
                        <div class="mt-3 text-center">
                            <small class="text-muted">Coeficiente de correlación: r = 0.42</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Dashboard Interactivo End -->

<!-- Scripts para gráficos -->
<script>
    // Gráfico de proyectos por departamento
    const proyectosCtx = document.getElementById('proyectosDepartamentoChart').getContext('2d');
    new Chart(proyectosCtx, {
        type: 'bar',
        data: {
            labels: ['La Paz', 'Cochabamba', 'Santa Cruz', 'Potosí', 'Oruro', 'Beni', 'Tarija', 'Chuquisaca', 'Pando'],
            datasets: [{
                label: 'Número de Proyectos',
                data: [45, 38, 52, 22, 18, 15, 24, 20, 8],
                backgroundColor: 'rgba(54, 162, 235, 0.7)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    title: { display: true, text: 'Cantidad de Proyectos' }
                }
            }
        }
    });

    // Gráfico de presupuesto
    const presupuestoCtx = document.getElementById('presupuestoChart').getContext('2d');
    new Chart(presupuestoCtx, {
        type: 'bar',
        data: {
            labels: ['La Paz', 'Cochabamba', 'Santa Cruz', 'Potosí', 'Oruro', 'Beni', 'Tarija', 'Chuquisaca', 'Pando'],
            datasets: [{
                label: 'Presupuesto (Millones Bs)',
                data: [850, 620, 920, 280, 190, 150, 210, 180, 80],
                backgroundColor: 'rgba(255, 99, 132, 0.7)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    title: { display: true, text: 'Millones de Bolivianos' }
                }
            }
        }
    });

    // Gráfico de avance
    const avanceCtx = document.getElementById('avanceChart').getContext('2d');
    new Chart(avanceCtx, {
        type: 'line',
        data: {
            labels: ['La Paz', 'Cochabamba', 'Santa Cruz', 'Potosí', 'Oruro', 'Beni', 'Tarija', 'Chuquisaca', 'Pando'],
            datasets: [{
                label: 'Avance Promedio (%)',
                data: [68, 61, 72, 45, 52, 58, 63, 55, 41],
                borderColor: 'rgba(75, 192, 192, 1)',
                backgroundColor: 'rgba(75, 192, 192, 0.1)',
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
                    title: { display: true, text: 'Avance (%)' }
                }
            }
        }
    });

    // Gráfico de estado de proyectos
    const estadoCtx = document.getElementById('estadoChart').getContext('2d');
    new Chart(estadoCtx, {
        type: 'pie',
        data: {
            labels: ['Completados', 'En Ejecución', 'Planificados'],
            datasets: [{
                data: [48, 58, 21],
                backgroundColor: [
                    'rgba(40, 167, 69, 0.7)',
                    'rgba(255, 193, 7, 0.7)',
                    'rgba(108, 117, 125, 0.7)'
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });

    // Gráfico de correlación
    const correlacionCtx = document.getElementById('correlacionChart').getContext('2d');
    new Chart(correlacionCtx, {
        type: 'scatter',
        data: {
            datasets: [{
                label: 'Proyectos por Departamento',
                data: [
                    {x: 850, y: 68}, {x: 620, y: 61}, {x: 920, y: 72},
                    {x: 280, y: 45}, {x: 190, y: 52}, {x: 150, y: 58},
                    {x: 210, y: 63}, {x: 180, y: 55}, {x: 80, y: 41}
                ],
                backgroundColor: 'rgba(153, 102, 255, 0.7)',
                pointRadius: 8
            }]
        },
        options: {
            responsive: true,
            scales: {
                x: { title: { display: true, text: 'Presupuesto (Millones Bs)' } },
                y: { title: { display: true, text: 'Avance (%)' } }
            }
        }
    });

    // Funciones de utilidad
    function actualizarDashboard() {
        console.log('Dashboard actualizado');
    }

    function cambiarVisualizacion() {
        console.log('Visualización cambiada');
    }
</script>