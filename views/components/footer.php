<!-- Footer Start -->
<div class="container-fluid bg-dark text-white-50 py-5 px-sm-3 px-lg-5" style="margin-top: 90px;">
    <div class="row pt-5">
        <div class="col-lg-3 col-md-6 mb-5">
            <h5 class="text-white text-uppercase mb-4" style="letter-spacing: 5px;">Proyectos Públicos</h5>
            <div class="d-flex flex-column justify-content-start">
                <a class="text-white-50 mb-2" href="<?= url('proyectos') ?>"><i class="fa fa-angle-right mr-2"></i>Todos los Proyectos</a>
                <a class="text-white-50 mb-2" href="<?= url('proyectos?estado=ejecucion') ?>"><i class="fa fa-angle-right mr-2"></i>En Ejecución</a>
                <a class="text-white-50 mb-2" href="<?= url('proyectos?estado=completado') ?>"><i class="fa fa-angle-right mr-2"></i>Completados</a>
                <a class="text-white-50 mb-2" href="<?= url('proyectos?estado=planificados') ?>"><i class="fa fa-angle-right mr-2"></i>Planificados</a>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-5">
            <h5 class="text-white text-uppercase mb-4" style="letter-spacing: 5px;">Turismo</h5>
            <div class="d-flex flex-column justify-content-start">
                <a class="text-white-50 mb-2" href="<?= url('turismo') ?>"><i class="fa fa-angle-right mr-2"></i>Mapa Turístico</a>
                <a class="text-white-50 mb-2" href="<?= url('destinos') ?>"><i class="fa fa-angle-right mr-2"></i>Destinos Destacados</a>
                <a class="text-white-50 mb-2" href="<?= url('add-tourist-site') ?>"><i class="fa fa-angle-right mr-2"></i>Agregar Sitio</a>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-5">
            <h5 class="text-white text-uppercase mb-4" style="letter-spacing: 5px;">Métricas</h5>
            <div class="d-flex flex-column justify-content-start">
                <a class="text-white-50 mb-2" href="#"><i class="fa fa-angle-right mr-2"></i>Avance de Proyectos</a>
                <a class="text-white-50 mb-2" href="#"><i class="fa fa-angle-right mr-2"></i>Distribución de Presupuesto</a>
                <a class="text-white-50 mb-2" href="#"><i class="fa fa-angle-right mr-2"></i>Sitios por Departamento</a>
                <a class="text-white-50 mb-2" href="#"><i class="fa fa-angle-right mr-2"></i>Estadísticas Turísticas</a>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-5">
            <h5 class="text-white text-uppercase mb-4" style="letter-spacing: 5px;">Contacto</h5>
            <p><i class="fa fa-map-marker-alt mr-2"></i>Ministerio de Planificación</p>
            <p><i class="fa fa-map-marker-alt mr-2"></i>La Paz, Bolivia</p>
            <p><i class="fa fa-envelope mr-2"></i>info@tekopora.gob.bo</p>
        </div>
    </div>
</div>

<div class="container-fluid bg-dark text-white border-top py-4 px-sm-3 px-md-5"
     style="border-color: rgba(256, 256, 256, .1) !important;">
    <div class="row">
        <div class="col-lg-6 text-center text-md-left mb-3 mb-md-0">
            <p class="m-0 text-white-50">TekoPorã Bolivia - Transparencia y Participación Ciudadana</p>
        </div>
        <div class="col-lg-6 text-center text-md-right">
            <p class="m-0 text-white-50">
                Desarrollado por 
                <a href="https://www.facebook.com/villca.emil/" target="_blank">DEVF</a>
            </p>
        </div>
    </div>
</div>
<!-- Footer End -->

<!-- Back to Top -->
<a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top">
    <i class="fa fa-angle-double-up"></i>
</a>

<!-- JavaScript Libraries -->
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
<script src="<?= asset('lib/easing/easing.min.js') ?>"></script>
<script src="<?= asset('lib/owlcarousel/owl.carousel.min.js') ?>"></script>
<script src="<?= asset('lib/tempusdominus/js/moment.min.js') ?>"></script>
<script src="<?= asset('lib/tempusdominus/js/moment-timezone.min.js') ?>"></script>
<script src="<?= asset('lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js') ?>"></script> 

<!-- Template Javascript -->
<script src="<?= asset('js/main.js') ?>"></script> 