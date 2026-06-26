<div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">

    <div class="register-card">

        <h2>
            <?php if (!empty($_SESSION['error'])): ?>
                <div class="alert alert-danger py-2 text-center" style="font-size: 0.85em; border-radius: 10px;">
                    <i class="fa fa-exclamation-circle mr-1"></i> <?= htmlspecialchars($_SESSION['error']) ?>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>
            <i class="fa fa-user-plus"></i> Crear Cuenta
        </h2>

        <form action="<?= url('register') ?>" method="POST">

            <div class="row">
                <div class="col-md-6 mb-3 position-relative">
                    <i class="fa fa-user input-icon"></i>
                    <input type="text" name="nombre" class="form-control" placeholder="Nombre" required>
                </div>

                <div class="col-md-6 mb-3 position-relative">
                    <i class="fa fa-user input-icon"></i>
                    <input type="text" name="appPaterno" class="form-control" placeholder="Apellido Paterno" required>
                </div>
            </div>

            <div class="mb-3 position-relative">
                <i class="fa fa-user input-icon"></i>
                <input type="text" name="appMaterno" class="form-control" placeholder="Apellido Materno">
            </div>

            <div class="mb-3 position-relative">
                <i class="fa fa-id-card input-icon"></i>
                <input type="text" name="ci" class="form-control" placeholder="Cédula de Identidad" required>
            </div>

            <div class="mb-3 position-relative">
                <i class="fa fa-envelope input-icon"></i>
                <input type="email" name="email" class="form-control" placeholder="Correo electrónico" required>
            </div>

            <div class="mb-3 position-relative">
                <i class="fa fa-phone input-icon"></i>
                <input type="text" name="telefono" class="form-control" placeholder="Teléfono">
            </div>

            <div class="mb-3">
                <div class="position-relative">
                    <i class="fa fa-lock input-icon"></i>
                    <input type="password" name="password" class="form-control" placeholder="Contraseña"
                        pattern="(?=.*\d)(?=.*[A-Z])(?=.*[\W_]).{8,}"
                        title="Debe contener al menos 8 caracteres, una mayúscula, un número y un carácter especial."
                        required>
                </div>
                <small class="text-muted d-block mt-1" style="font-size: 0.8em; margin-left: 5px;">
                    * Mínimo 8 caracteres, 1 mayúscula, 1 número y 1 carácter especial (ej: @!#$%).
                </small>
            </div>
            <div class="mb-3">
                <?php if (count($roles) === 1): ?>
                    <div class="position-relative">
                        <i class="fa fa-user-shield input-icon"></i>
                        <input type="text" class="form-control" value="<?= $roles[0]['nombre'] ?>" readonly>
                        <input type="hidden" name="rol" value="<?= $roles[0]['idRol'] ?>">
                    </div>
                    <small class="text-muted d-block mt-1" style="font-size: 0.8em; margin-left: 5px;">
                        * Te registrarás con el perfil de Ciudadano.
                    </small>
                <?php else: ?>
                    <div class="position-relative">
                        <i class="fa fa-user-shield input-icon"></i>
                        <select name="rol" class="form-control" required>
                            <option value="">Seleccionar rol</option>
                            <?php foreach ($roles as $r): ?>
                                <option value="<?= $r['idRol'] ?>"><?= $r['nombre'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                <?php endif; ?>
            </div>
            <button type="submit" class="btn btn-register w-100">
                Registrarse
            </button>

            <div class="text-center mt-3">
                <a href="<?= url('login') ?>" class="link-login">
                    ¿Ya tienes cuenta? Inicia sesión
                </a>
            </div>

        </form>
    </div>

</div>

<!-- ESTILOS -->
<style>
    body {
        background: #F5F7F6;
    }

    /* Card */
    .register-card {
        background: #fff;
        padding: 2.5rem;
        border-radius: 16px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        width: 100%;
        max-width: 500px;
        animation: fadeIn 0.8s ease-in-out;
    }

    /* Título */
    .register-card h2 {
        font-weight: 700;
        margin-bottom: 1.5rem;
        color: #2A6F97;
        text-align: center;
    }

    /* Inputs */
    .form-control {
        border-radius: 10px;
        padding-left: 2.5rem;
        border: 1px solid #ddd;
    }

    /* Iconos */
    .input-icon {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #2E7D6B;
    }

    /* Botón */
    .btn-register {
        border-radius: 10px;
        font-weight: bold;
        background: #2E7D6B;
        color: #fff;
        transition: 0.3s;
    }

    .btn-register:hover {
        background: #25675a;
        color: #fff;
    }

    /* Link */
    .link-login {
        color: #2A6F97;
        text-decoration: none;
    }

    .link-login:hover {
        text-decoration: underline;
    }

    select.form-control {
        padding-left: 2.5rem;
    }

    /* Animación */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-15px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>