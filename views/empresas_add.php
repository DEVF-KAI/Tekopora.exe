<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Seguridad estricta: Solo Admin o Personal Alcaldía
if (!isset($_SESSION['usuario']) || !in_array($_SESSION['usuario']['rol'], ['Administrador', 'Personal Alcaldia'])) {
    header("Location: " . url('/?error=Acceso Denegado'));
    exit();
}

$title = 'Registrar Constructora - TekoPorã';
ob_start();
?>

<style>
    .card-custom { border-radius: 20px; border: none; }
    .btn-save { background-color: #1A6A6D; color: white; border-radius: 50px; transition: 0.3s; font-weight: bold; }
    .btn-save:hover { background-color: #217F82; transform: scale(1.02); color: white; }
    .form-control { border-radius: 10px; border: 1px solid #ced4da; padding: 12px; }
    .form-control:focus { border-color: #217F82; box-shadow: 0 0 0 0.2rem rgba(33, 127, 130, 0.25); }
    .input-icon { position: absolute; left: 15px; top: 40px; color: #1A6A6D; }
    .has-icon { padding-left: 45px !important; }
</style>

<div class="container py-5">
    <div class="mb-4">
        <a href="<?= url('/empresas') ?>" class="btn btn-outline-secondary rounded-pill shadow-sm px-4">
            <i class="fa fa-arrow-left mr-2"></i> Volver al Listado
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg card-custom overflow-hidden">
                <div class="card-header text-white p-4" style="background: linear-gradient(135deg, #217F82 0%, #1A6A6D 100%); border-bottom: none;">
                    <div class="d-flex align-items-center">
                        <div class="bg-white p-3 rounded-circle mr-3 shadow-sm">
                            <i class="fa fa-building fa-2x" style="color: #1A6A6D;"></i>
                        </div>
                        <div>
                            <h3 class="mb-0 font-weight-bold" style="font-family: 'Times New Roman', Times, serif;">Nueva Empresa Constructora</h3>
                            <p class="mb-0 text-white-50">Registro legal de proveedores gubernamentales</p>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4 p-md-5">
                    <form action="<?= url('/empresas/store') ?>" method="POST">
                        <div class="row">
                            <div class="col-md-12 form-group mb-4 position-relative">
                                <label class="small font-weight-bold text-muted">RAZÓN SOCIAL / NOMBRE DE LA EMPRESA</label>
                                <i class="fa fa-industry input-icon"></i>
                                <input type="text" name="nombreEmpresa" class="form-control has-icon" placeholder="Ej: Constructora Los Andes S.R.L." required>
                            </div>

                            <div class="col-md-6 form-group mb-4 position-relative">
                                <label class="small font-weight-bold text-muted">TELÉFONO DE CONTACTO</label>
                                <i class="fa fa-phone input-icon"></i>
                                <input type="text" name="telefono" class="form-control has-icon" placeholder="Ej: 22445566" required>
                            </div>

                            <div class="col-md-6 form-group mb-4 position-relative">
                                <label class="small font-weight-bold text-muted">NIT / IDENTIFICADOR FISCAL</label>
                                <i class="fa fa-id-card input-icon"></i>
                                <input type="text" name="nit" class="form-control has-icon" placeholder="Ej: 1020304050" required>
                            </div>

                            <div class="col-md-12 form-group mb-4 position-relative">
                                <label class="small font-weight-bold text-muted">DIRECCIÓN FISCAL</label>
                                <i class="fa fa-map-marked-alt input-icon"></i>
                                <textarea name="direccion" class="form-control has-icon" rows="3" placeholder="Calle, Número, Edificio y Zona..." required></textarea>
                            </div>
                        </div>

                        <div class="alert alert-info border-0 shadow-sm" style="border-radius: 12px;">
                            <small>
                                <i class="fa fa-info-circle mr-2"></i> 
                                Al registrar la empresa, se le asignará un <strong>Código de Constructora único</strong> para su seguimiento en licitaciones y obras públicas.
                            </small>
                        </div>

                        <div class="mt-4 text-right">
                            <button type="reset" class="btn btn-light rounded-pill px-4 mr-2">Limpiar</button>
                            <button type="submit" class="btn btn-save px-5 py-3 shadow-lg">
                                <i class="fa fa-check-circle mr-2"></i> REGISTRAR EMPRESA
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
