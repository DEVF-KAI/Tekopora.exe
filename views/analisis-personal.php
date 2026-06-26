<style>
.nav-bar, 
.navbar, 
.container-fluid.position-relative.nav-bar {
    margin-top: 0 !important;
    padding-top: 0 !important;
    border-top: none !important;
}
</style>

<div class="container-fluid p-0">
    <img class="w-100" src="<?= asset('imgs/look.jpg') ?>" alt="Perfil Ciudadano">
</div>

<div class="container py-5">

    <!-- PERFIL -->
    <div class="row mb-4">

        <!-- INFO GENERAL -->
        <div class="col-lg-4">
            <div class="card text-center shadow-sm">
                <div class="card-body">

                    <i class="fas fa-user-circle fa-5x mb-3" style="color:#217F82;"></i>

                    <h4><?= $usuario['nombre'] ?? 'Usuario' ?></h4>
                    <p class="text-muted">
                        Miembro desde <?= $usuario['fechaRegistro'] ?? '-' ?>
                    </p>

                    <div class="d-flex justify-content-around mt-3">
                        <div>
                            <h5><?= count($sitios ?? []) ?></h5>
                            <small>Sitios</small>
                        </div>
                        <div>
                            <h5><?= count($reportes ?? []) ?></h5>
                            <small>Reportes</small>
                        </div>
                        <div>
                            <h5><?= count($actividad ?? []) ?></h5>
                            <small>Actividad</small>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- DATOS -->
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header" style="background:#217F82;">
                    <h5 class="text-white">Mis Datos</h5>
                </div>

                <div class="card-body">

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Nombre</label>
                            <input class="form-control" value="<?= $usuario['nombre'] ?? '' ?>" disabled>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Email</label>
                            <input class="form-control" value="<?= $usuario['email'] ?? '' ?>" disabled>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Teléfono</label>
                            <input class="form-control" value="<?= $usuario['telefono'] ?? '' ?>" disabled>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>

    <!-- SITIOS -->
    <div class="card mb-4 shadow-sm">
        <div class="card-header" style="background:#217F82;">
            <h5 class="text-white">Sitios agregados</h5>
        </div>

        <div class="card-body">
            <table class="table table-hover">

                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if (!empty($sitios)): ?>
                        <?php foreach ($sitios as $s): ?>
                            <tr>
                                <td><?= $s['nombre'] ?></td>
                                <td><?= $s['fecha'] ?></td>
                                <td><?= $s['estado'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3">No hay datos</td>
                        </tr>
                    <?php endif; ?>
                </tbody>

            </table>
        </div>
    </div>

    <!-- REPORTES -->
    <div class="card mb-4 shadow-sm">
        <div class="card-header" style="background:#217F82;">
            <h5 class="text-white">Mis reportes</h5>
        </div>

        <div class="card-body">
            <table class="table table-hover">

                <thead>
                    <tr>
                        <th>Proyecto</th>
                        <th>Avance</th>
                        <th>Estado</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if (!empty($reportes)): ?>
                        <?php foreach ($reportes as $r): ?>
                            <tr>
                                <td><?= $r['proyecto'] ?></td>
                                <td><?= $r['avance'] ?></td>
                                <td><?= $r['estado'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3">No hay reportes</td>
                        </tr>
                    <?php endif; ?>
                </tbody>

            </table>
        </div>
    </div>

    <!-- ACTIVIDAD -->
    <div class="card shadow-sm">
        <div class="card-header" style="background:#217F82;">
            <h5 class="text-white">Actividad reciente</h5>
        </div>

        <div class="card-body">

            <ul class="list-group">

                <?php if (!empty($actividad)): ?>
                    <?php foreach ($actividad as $a): ?>
                        <li class="list-group-item d-flex justify-content-between">
                            <span><?= $a['descripcion'] ?></span>
                            <small><?= $a['fecha'] ?></small>
                        </li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li class="list-group-item">Sin actividad</li>
                <?php endif; ?>

            </ul>

        </div>
    </div>

</div>