

<!-- Header Start -->
<div class="container-fluid page-header" style="background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('img/herramientas-bolivia.jpg') center/cover no-repeat;">
    <div class="container">
        <div class="d-flex flex-column align-items-center justify-content-center" style="min-height: 400px">
            <h3 class="display-4 text-white text-uppercase">Herramientas Ciudadanas</h3>
            <div class="d-inline-flex text-white">
                <p class="m-0 text-uppercase"><a class="text-white" href="index.php">Inicio</a></p>
                <i class="fa fa-angle-double-right pt-1 px-3"></i>
                <p class="m-0 text-uppercase">Participación y Transparencia</p>
            </div>
        </div>
    </div>
</div>
<!-- Header End -->

<!-- Herramientas Principales Start -->
<div class="container-fluid py-5">
    <div class="container py-5">
        <div class="text-center mb-5">
            <h6 class="text-primary text-uppercase" style="letter-spacing: 5px;">Herramientas</h6>
            <h1 class="mb-3">Participación Ciudadana en La Paz</h1>
            <p>Utiliza estas herramientas para reportar, sugerir y dar seguimiento a proyectos públicos</p>
        </div>

        <div class="row">
            <!-- Reportar Proyecto -->
            <div class="col-lg-6 mb-4">
                <div class="card border-0 shadow-lg h-100 calculator-card">
                    <div class="card-header text-white" style="background-color: #217F82;">
                        <h5 class="card-title mb-0"><i class="fas fa-hard-hat mr-2"></i>Reportar Proyecto Público</h5>
                    </div>
                    <div class="card-body">
                        <p class="card-text">Reporta el avance, problemas o irregularidades en obras públicas:</p>
                        
                        <div class="form-group">
                            <label><strong>Macrodistrito:</strong></label>
                            <select class="form-control" id="macrodistritoReporte">
                                <option>Selecciona un macrodistrito</option>
                                <option>Mallasa</option>
                                <option>Zona Sur</option>
                                <option>San Antonio</option>
                                <option>Periférica</option>
                                <option>Max Paredes</option>
                                <option>Cotahuma</option>
                                <option>Centro</option>
                                <option>Hampaturi</option>
                                <option>Zongo</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label><strong>Nombre del Proyecto:</strong></label>
                            <input type="text" class="form-control" placeholder="Ej: Construcción Puente Villa Copacabana">
                        </div>
                        
                        <div class="form-group">
                            <label><strong>Tipo de Reporte:</strong></label>
                            <select class="form-control">
                                <option>Avance de obra</option>
                                <option>Problema o irregularidad</option>
                                <option>Retraso en ejecución</option>
                                <option>Calidad de materiales</option>
                                <option>Otro</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label><strong>Descripción:</strong></label>
                            <textarea class="form-control" rows="3" placeholder="Describe el avance o problema..."></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label><strong>¿Deseas agregar una foto?</strong></label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="fotoReporte">
                                <label class="custom-file-label" for="fotoReporte">Seleccionar imagen...</label>
                            </div>
                        </div>
                        
                        <button class="btn btn-success btn-block mt-3" onclick="enviarReporte()">
                            <i class="fas fa-paper-plane mr-2"></i>Enviar Reporte
                        </button>
                        
                        <div id="resultadoReporte" class="tool-result" style="display: none;">
                            <div class="alert alert-success mb-0">
                                <i class="fas fa-check-circle mr-2"></i>
                                ¡Reporte enviado con éxito! Gracias por tu participación.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Agregar Sitio Turístico -->
            <div class="col-lg-6 mb-4">
                <div class="card border-0 shadow-lg h-100 calculator-card">
                    <div class="card-header text-white" style="background-color: #28a745;">
                        <h5 class="card-title mb-0"><i class="fas fa-map-marker-alt mr-2"></i>Sugerir Sitio Turístico</h5>
                    </div>
                    <div class="card-body">
                        <p class="card-text">Comparte un lugar turístico de tu macrodistrito que no está en el mapa:</p>
                        
                        <div class="form-group">
                            <label><strong>Macrodistrito:</strong></label>
                            <select class="form-control" id="macrodistritoSitio">
                                <option>Selecciona un macrodistrito</option>
                                <option>Mallasa</option>
                                <option>Zona Sur</option>
                                <option>San Antonio</option>
                                <option>Periférica</option>
                                <option>Max Paredes</option>
                                <option>Cotahuma</option>
                                <option>Centro</option>
                                <option>Hampaturi</option>
                                <option>Zongo</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label><strong>Nombre del Sitio:</strong></label>
                            <input type="text" class="form-control" placeholder="Ej: Mirador Killi Killi">
                        </div>
                        
                        <div class="form-group">
                            <label><strong>Tipo de Atractivo:</strong></label>
                            <select class="form-control">
                                <option>Mirador</option>
                                <option>Parque</option>
                                <option>Sitio histórico</option>
                                <option>Área natural</option>
                                <option>Gastronomía</option>
                                <option>Otro</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label><strong>Descripción:</strong></label>
                            <textarea class="form-control" rows="3" placeholder="Describe el lugar..."></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label><strong>Ubicación aproximada:</strong></label>
                            <input type="text" class="form-control" placeholder="Ej: Calle principal, cerca de...">
                        </div>
                        
                        <button class="btn btn-success btn-block mt-3" onclick="enviarSitio()">
                            <i class="fas fa-paper-plane mr-2"></i>Sugerir Sitio
                        </button>
                        
                        <div id="resultadoSitio" class="tool-result" style="display: none;">
                            <div class="alert alert-success mb-0">
                                <i class="fas fa-check-circle mr-2"></i>
                                ¡Sitio sugerido con éxito! Será revisado por el equipo.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <!-- Calculadora de Presupuesto Participativo -->
            <div class="col-lg-6 mb-4">
                <div class="card border-0 shadow-lg h-100 calculator-card">
                    <div class="card-header text-white" style="background-color: #ffc107;">
                        <h5 class="card-title mb-0"><i class="fas fa-calculator mr-2"></i>Calculadora de Presupuesto</h5>
                    </div>
                    <div class="card-body">
                        <p class="card-text">Simula cómo se distribuye el presupuesto en tu macrodistrito:</p>
                        
                        <div class="form-group">
                            <label><strong>Macrodistrito:</strong></label>
                            <select class="form-control" id="presupuestoMacro">
                                <option>Selecciona un macrodistrito</option>
                                <option>Mallasa</option>
                                <option>Zona Sur</option>
                                <option>San Antonio</option>
                                <option>Periférica</option>
                                <option>Max Paredes</option>
                                <option>Cotahuma</option>
                                <option>Centro</option>
                                <option>Hampaturi</option>
                                <option>Zongo</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label><strong>Presupuesto total (Bs):</strong></label>
                            <input type="number" class="form-control" id="presupuestoTotal" value="10000000">
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><strong>% Infraestructura:</strong></label>
                                    <input type="number" class="form-control" id="porcInfra" value="40" min="0" max="100">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><strong>% Salud:</strong></label>
                                    <input type="number" class="form-control" id="porcSalud" value="25" min="0" max="100">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><strong>% Educación:</strong></label>
                                    <input type="number" class="form-control" id="porcEducacion" value="20" min="0" max="100">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><strong>% Otros:</strong></label>
                                    <input type="number" class="form-control" id="porcOtros" value="15" min="0" max="100">
                                </div>
                            </div>
                        </div>
                        
                        <button class="btn btn-primary btn-block mt-3" onclick="calcularPresupuesto()" style="background-color: #217F82; border-color: #217F82;">
                            <i class="fas fa-chart-pie mr-2"></i>Calcular Distribución
                        </button>
                        
                        <div id="resultadoPresupuesto" class="tool-result" style="display: none;">
                            <h6 class="text-center">Distribución del Presupuesto</h6>
                            <div class="progress mt-3" style="height: 30px;">
                                <div class="progress-bar bg-success" role="progressbar" id="barraInfra" style="width: 0%">Infra</div>
                                <div class="progress-bar bg-info" role="progressbar" id="barraSalud" style="width: 0%">Salud</div>
                                <div class="progress-bar bg-warning" role="progressbar" id="barraEducacion" style="width: 0%">Educ</div>
                                <div class="progress-bar bg-secondary" role="progressbar" id="barraOtros" style="width: 0%">Otros</div>
                            </div>
                            <div class="row text-center mt-3">
                                <div class="col-3">
                                    <small>Infra:</small>
                                    <p class="mb-0" id="montoInfra">Bs 0</p>
                                </div>
                                <div class="col-3">
                                    <small>Salud:</small>
                                    <p class="mb-0" id="montoSalud">Bs 0</p>
                                </div>
                                <div class="col-3">
                                    <small>Educ:</small>
                                    <p class="mb-0" id="montoEducacion">Bs 0</p>
                                </div>
                                <div class="col-3">
                                    <small>Otros:</small>
                                    <p class="mb-0" id="montoOtros">Bs 0</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Seguimiento de Proyectos -->
            <div class="col-lg-6 mb-4">
                <div class="card border-0 shadow-lg h-100 calculator-card">
                    <div class="card-header text-white" style="background-color: #17a2b8;">
                        <h5 class="card-title mb-0"><i class="fas fa-chart-line mr-2"></i>Seguimiento de Proyectos</h5>
                    </div>
                    <div class="card-body">
                        <p class="card-text">Ingresa el código de un proyecto para ver su avance:</p>
                        
                        <div class="form-group">
                            <label><strong>Código del Proyecto:</strong></label>
                            <input type="text" class="form-control" placeholder="Ej: LP-2024-0123">
                        </div>
                        
                        <button class="btn btn-info btn-block" onclick="buscarProyecto()">
                            <i class="fas fa-search mr-2"></i>Buscar Proyecto
                        </button>
                        
                        <div id="resultadoProyecto" class="tool-result" style="display: none;">
                            <div class="d-flex align-items-center">
                                <div class="mr-3">
                                    <i class="fas fa-hard-hat fa-3x text-success"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1">Construcción Puente Villa Copacabana</h6>
                                    <p class="mb-1"><small>Macrodistrito: San Antonio</small></p>
                                    <div class="progress" style="height: 10px;">
                                        <div class="progress-bar bg-success" style="width: 65%">65%</div>
                                    </div>
                                    <p class="mb-0 mt-2"><small>Último reporte: 15/03/2024</small></p>
                                </div>
                            </div>
                        </div>
                        
                        <hr>
                        
                        <h6>Proyectos en seguimiento popular:</h6>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Puente Villa Copacabana
                                <span class="badge bg-success text-white">65%</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Hospital Cotahuma
                                <span class="badge bg-warning text-white">32%</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Parque Zona Sur
                                <span class="badge bg-success text-white">90%</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Herramientas Principales End -->

<!-- Scripts -->
<script>
    function enviarReporte() {
        document.getElementById('resultadoReporte').style.display = 'block';
        setTimeout(() => {
            document.getElementById('resultadoReporte').style.display = 'none';
        }, 3000);
    }
    
    function enviarSitio() {
        document.getElementById('resultadoSitio').style.display = 'block';
        setTimeout(() => {
            document.getElementById('resultadoSitio').style.display = 'none';
        }, 3000);
    }
    
    function calcularPresupuesto() {
        const total = parseFloat(document.getElementById('presupuestoTotal').value) || 0;
        const pInfra = parseFloat(document.getElementById('porcInfra').value) || 0;
        const pSalud = parseFloat(document.getElementById('porcSalud').value) || 0;
        const pEduc = parseFloat(document.getElementById('porcEducacion').value) || 0;
        const pOtros = parseFloat(document.getElementById('porcOtros').value) || 0;
        
        const suma = pInfra + pSalud + pEduc + pOtros;
        if (suma !== 100) {
            alert('Los porcentajes deben sumar 100%');
            return;
        }
        
        const mInfra = total * pInfra / 100;
        const mSalud = total * pSalud / 100;
        const mEduc = total * pEduc / 100;
        const mOtros = total * pOtros / 100;
        
        document.getElementById('barraInfra').style.width = pInfra + '%';
        document.getElementById('barraSalud').style.width = pSalud + '%';
        document.getElementById('barraEducacion').style.width = pEduc + '%';
        document.getElementById('barraOtros').style.width = pOtros + '%';
        
        document.getElementById('montoInfra').textContent = 'Bs ' + mInfra.toLocaleString();
        document.getElementById('montoSalud').textContent = 'Bs ' + mSalud.toLocaleString();
        document.getElementById('montoEducacion').textContent = 'Bs ' + mEduc.toLocaleString();
        document.getElementById('montoOtros').textContent = 'Bs ' + mOtros.toLocaleString();
        
        document.getElementById('resultadoPresupuesto').style.display = 'block';
    }
    
    function buscarProyecto() {
        document.getElementById('resultadoProyecto').style.display = 'block';
    }
    
    // Actualizar label del file input
    document.getElementById('fotoReporte').addEventListener('change', function(e) {
        const fileName = e.target.files[0]?.name || 'Seleccionar imagen...';
        document.querySelector('label[for="fotoReporte"]').textContent = fileName;
    });
</script>