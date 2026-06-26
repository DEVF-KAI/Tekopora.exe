<?php
// Aseguramos que la sesión esté iniciada para poder verificar al usuario
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$title = "TekoPorã Bolivia | Participación Ciudadana Activa";
?>

<!-- Librerías de CSS Externas -->
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<!-- CSS Limpio -->
<link rel="stylesheet" href="<?= asset('css/home.css') ?>">

<!-- HERO SECTION: EL BAILE DE LUCES -->
<section class="hero-modern" id="hero-section">
    <!-- Contenedor de la Constelación / Partículas -->
    <div id="particles-js"></div>
    
    <div class="container">
        <div class="row align-items-center">
            
            <!-- Texto del Hero -->
            <div class="col-lg-6 hero-content" data-aos="fade-right">
                <h1 class="hero-title">Construyamos juntos un <br><span>TekoPorã</span></h1>
                <p class="hero-subtitle">
                    Supervisa obras públicas, descubre tesoros turísticos y haz oír tu voz. Conectamos ciudadanos con la gestión pública mediante tecnología transparente.
                </p>
                <div class="mt-4">
                    <!-- LOGICA DE SESIÓN: Ocultar si ya ingresó -->
                    <?php if (!isset($_SESSION['usuario'])): ?>
                        <a href="<?= url('/register') ?>" class="btn-neon"><i class="fas fa-bolt"></i> Únete a la Comunidad</a>
                    <?php endif; ?>
                    
                    <a href="<?= url('/proyectos') ?>" class="btn btn-outline-light"><i class="fas fa-eye"></i> Auditoría Pública</a>
                </div>
            </div>

            <!-- UI Flotante de Cristal -->
            <div class="col-lg-6" data-aos="fade-left" data-aos-delay="300">
                <div class="floating-composition">
                    <div class="glowing-orb"></div>
                    
                    <div class="glass-card glass-1">
                        <i class="fas fa-chart-line text-info mb-2 fs-3"></i>
                        <h5 class="m-0 fw-bold">Transparencia</h5>
                        <p class="m-0 text-light small mt-1">Datos abiertos y en tiempo real.</p>
                    </div>

                    <div class="glass-card glass-2">
                        <i class="fas fa-map-marker-alt text-warning mb-2 fs-3"></i>
                        <h5 class="m-0 fw-bold">+120 Sitios</h5>
                        <p class="m-0 text-light small mt-1">Validados por la comunidad.</p>
                    </div>

                    <div class="glass-card glass-3">
                        <div class="d-flex align-items-center mb-2">
                            <div class="bg-primary rounded-circle p-2 me-3">
                                <i class="fas fa-hard-hat text-white"></i>
                            </div>
                            <div>
                                <h6 class="m-0 fw-bold text-white">Fiscalización Ciudadana</h6>
                                <small class="text-info">Alerta de retrasos activada</small>
                            </div>
                        </div>
                        <div class="progress mt-2" style="height: 5px; background: rgba(255,255,255,0.1);">
                            <div class="progress-bar bg-info" style="width: 75%"></div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- SECCIÓN 2: NUESTROS PILARES (Fondo Blanco) -->
<section class="values-section">
    <div class="container">
        <div class="row">
            <div class="col-md-3 col-6 value-box" data-aos="fade-up" data-aos-delay="0">
                <div class="value-icon"><i class="fas fa-search-dollar"></i></div>
                <div class="value-label">Transparencia</div>
                <div class="value-text">Cuentas claras en cada obra</div>
            </div>
            <div class="col-md-3 col-6 value-box" data-aos="fade-up" data-aos-delay="100">
                <div class="value-icon"><i class="fas fa-users"></i></div>
                <div class="value-label">Participación</div>
                <div class="value-text">Tu voz tiene peso real</div>
            </div>
            <div class="col-md-3 col-6 value-box" data-aos="fade-up" data-aos-delay="200">
                <div class="value-icon"><i class="fas fa-map-marked-alt"></i></div>
                <div class="value-label">Turismo</div>
                <div class="value-text">Impulso a la cultura local</div>
            </div>
            <div class="col-md-3 col-6 value-box" data-aos="fade-up" data-aos-delay="300">
                <div class="value-icon"><i class="fas fa-shield-alt"></i></div>
                <div class="value-label">Confianza</div>
                <div class="value-text">Sin datos falsos, todo auditable</div>
            </div>
        </div>
    </div>
</section>

<!-- SECCIÓN 3: HERRAMIENTAS CIUDADANAS (Fondo Oscuro) -->
<section class="modules-section">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <h6 class="text-uppercase" style="color: #48c6ef; font-weight: 700; letter-spacing: 2px;">Módulos de la Plataforma</h6>
            <h2 class="font-weight-bold" style="color: white; font-size: 2.5rem;">El Poder en tus Manos</h2>
        </div>

        <div class="row">
            <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="100">
                <div class="module-card js-tilt">
                    <div class="module-icon"><i class="fas fa-hard-hat"></i></div>
                    <h3 class="module-title">Catálogo de Obras</h3>
                    <p class="module-text">Audita el progreso de los proyectos de infraestructura en tu ciudad. Transparencia en tiempo real.</p>
                    <a href="<?= url('/proyectos') ?>" class="module-link">Fiscalizar ahora <i class="fas fa-arrow-right ms-1"></i></a>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="200">
                <div class="module-card js-tilt">
                    <div class="module-icon"><i class="fas fa-map-marked-alt"></i></div>
                    <h3 class="module-title">Mapa Turístico</h3>
                    <p class="module-text">Descubre joyas ocultas validadas por la comunidad y propone nuevos destinos culturales.</p>
                    <a href="<?= url('/turismo') ?>" class="module-link">Explorar mapa <i class="fas fa-arrow-right ms-1"></i></a>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="300">
                <div class="module-card js-tilt">
                    <div class="module-icon"><i class="fas fa-comments"></i></div>
                    <h3 class="module-title">Foro Ciudadano</h3>
                    <p class="module-text">Debate, reporta problemas en tu zona y gana puntos de "Karma" aportando soluciones reales.</p>
                    <a href="<?= url('/foro') ?>" class="module-link">Unirse al debate <i class="fas fa-arrow-right ms-1"></i></a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- SECCIÓN 4: CÓMO FUNCIONA -->
<section class="steps-section">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="font-weight-bold" style="color: #0f2027; font-size: 2.5rem;">¿Cómo ser parte del cambio?</h2>
        </div>
        <div class="row">
            <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="0">
                <div class="step-card h-100">
                    <div class="step-number">1</div>
                    <h4 class="fw-bold mb-3">Regístrate</h4>
                    <p class="text-muted">Crea tu cuenta ciudadana en segundos usando tu correo. Tu identidad le da peso a tu voz.</p>
                </div>
            </div>
            <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="150">
                <div class="step-card h-100">
                    <div class="step-number">2</div>
                    <h4 class="fw-bold mb-3">Participa</h4>
                    <p class="text-muted">Vota en el foro, evalúa a las constructoras y añade puntos turísticos de tu barrio al mapa.</p>
                </div>
            </div>
            <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="300">
                <div class="step-card h-100">
                    <div class="step-number">3</div>
                    <h4 class="fw-bold mb-3">Sube de Nivel</h4>
                    <p class="text-muted">Gana puntos de Karma por aportes positivos y conviértete en un líder vecinal verificado.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- LIBRERÍAS JS EXTERNAS -->
<script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-tilt/1.8.0/vanilla-tilt.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

<!-- Tu JS Limpio -->
<script src="<?= asset('js/home.js') ?>"></script>