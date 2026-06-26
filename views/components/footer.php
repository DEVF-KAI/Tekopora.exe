<?php
// Asegurarnos de tener acceso a las variables de sesión
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$isLoggedIn = isset($_SESSION['usuario']);
$rolUser = $isLoggedIn ? $_SESSION['usuario']['rol'] : 'Visitante';
?>

<div class="container-fluid bg-dark text-white-50 py-5 px-sm-3 px-lg-5" style="margin-top: 90px; border-top: 5px solid #217F82;">
    <div class="row pt-5">
        
        <div class="col-lg-3 col-md-6 mb-5">
            <h5 class="text-white text-uppercase mb-4" style="letter-spacing: 3px; font-weight: bold; border-bottom: 2px solid #217F82; padding-bottom: 10px; display: inline-block;">Obras Públicas</h5>
            <div class="d-flex flex-column justify-content-start">
                <a class="text-white-50 mb-2" href="<?= url('/proyectos') ?>"><i class="fa fa-angle-right mr-2"></i>Todos los Proyectos</a>
                
                <?php if ($isLoggedIn && in_array($rolUser, ['Administrador', 'Personal Alcaldia'])): ?>
                    <a class="text-warning mb-2 font-weight-bold" href="<?= url('/proyectosadd') ?>"><i class="fa fa-plus-circle mr-2"></i>Añadir Proyecto</a>
                <?php endif; ?>
                
                <?php if ($isLoggedIn && in_array($rolUser, ['Administrador', 'Moderador Obra'])): ?>
                    <a class="text-warning mb-2 font-weight-bold" href="<?= url('/mis-obras') ?>"><i class="fa fa-hard-hat mr-2"></i>Mis Obras Asignadas</a>
                <?php endif; ?>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-5">
            <h5 class="text-white text-uppercase mb-4" style="letter-spacing: 3px; font-weight: bold; border-bottom: 2px solid #217F82; padding-bottom: 10px; display: inline-block;">Turismo Local</h5>
            <div class="d-flex flex-column justify-content-start">
                <a class="text-white-50 mb-2" href="<?= url('/turismo') ?>"><i class="fa fa-angle-right mr-2"></i>Mapa Turístico</a>
                <a class="text-white-50 mb-2" href="<?= url('/destinos') ?>"><i class="fa fa-angle-right mr-2"></i>Destinos Destacados</a>
                
                <?php if ($isLoggedIn && in_array($rolUser, ['Administrador', 'Moderador Turismo'])): ?>
                    <a class="text-warning mb-2 font-weight-bold" href="<?= url('/validar-sitios') ?>"><i class="fa fa-check-circle mr-2"></i>Validar Sitios</a>
                <?php endif; ?>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-5">
            <h5 class="text-white text-uppercase mb-4" style="letter-spacing: 3px; font-weight: bold; border-bottom: 2px solid #217F82; padding-bottom: 10px; display: inline-block;">Comunidad</h5>
            <div class="d-flex flex-column justify-content-start">
                <a class="text-white-50 mb-2" href="<?= url('/foro') ?>"><i class="fa fa-angle-right mr-2"></i>Foro Ciudadano</a>
                
                <?php if ($isLoggedIn): ?>
                    <a class="text-white-50 mb-2" href="<?= url('/perfil-ciudadano') ?>"><i class="fa fa-angle-right mr-2"></i>Mi Perfil</a>
                    <?php if ($rolUser === 'Administrador'): ?>
                        <a class="text-info mb-2 font-weight-bold" href="<?= url('/adminpanel') ?>"><i class="fa fa-cog mr-2"></i>Panel de Control</a>
                    <?php endif; ?>
                    <a class="text-danger mb-2 font-weight-bold" href="<?= url('/logout') ?>"><i class="fa fa-sign-out-alt mr-2"></i>Cerrar Sesión</a>
                <?php else: ?>
                    <a class="text-info mb-2 font-weight-bold" href="<?= url('/login') ?>"><i class="fa fa-sign-in-alt mr-2"></i>Iniciar Sesión</a>
                    <a class="text-white-50 mb-2" href="<?= url('/register') ?>"><i class="fa fa-angle-right mr-2"></i>Registrarse</a>
                <?php endif; ?>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-5">
            <h5 class="text-white text-uppercase mb-4" style="letter-spacing: 3px; font-weight: bold; border-bottom: 2px solid #217F82; padding-bottom: 10px; display: inline-block;">Contacto</h5>
            <p><i class="fa fa-building text-info mr-2"></i>Alcaldía de La Paz</p>
            <p><i class="fa fa-map-marker-alt text-danger mr-2"></i>La Paz, Bolivia</p>
            <p><i class="fa fa-envelope text-info mr-2"></i>soporte@tekopora.lapaz.bo</p>
        </div>
    </div>
</div>

<div class="container-fluid bg-dark text-white border-top py-4 px-sm-3 px-md-5" style="border-color: rgba(256, 256, 256, .1) !important;">
    <div class="row align-items-center">
        <div class="col-lg-6 text-center text-md-left mb-3 mb-md-0">
            <p class="m-0 text-white-50">TekoPorã Bolivia - Sistema Municipal de Transparencia</p>
        </div>
        <div class="col-lg-6 text-center text-md-right">
            <p class="m-0 text-white-50">
                Desarrollado por 
                <a href="https://www.facebook.com/villca.emil/" target="_blank" style="color: #F2B705; font-weight: bold; letter-spacing: 1px;">
                    <i class="fab fa-facebook-square mr-1"></i>DEVF
                </a>
            </p>
        </div>
    </div>
</div>
<a href="#" class="btn btn-lg btn-lg-square back-to-top shadow-lg" style="background-color: #217F82; color: white; border: none;">
    <i class="fa fa-angle-double-up"></i>
</a>

<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
<script src="<?= asset('lib/easing/easing.min.js') ?>"></script>
<script src="<?= asset('lib/owlcarousel/owl.carousel.min.js') ?>"></script>
<script src="<?= asset('lib/tempusdominus/js/moment.min.js') ?>"></script>
<script src="<?= asset('lib/tempusdominus/js/moment-timezone.min.js') ?>"></script>
<script src="<?= asset('lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js') ?>"></script> 

<script src="<?= asset('js/main.js') ?>"></script>