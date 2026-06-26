<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Cuenta | TekoPorã Bolivia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <link rel="stylesheet" href="<?= asset('css/register.css') ?>">
</head>
<body>

    <!-- FONDO PARALLAX -->
    <div id="parallax-bg"></div>

    <!-- TARJETA FIJA (SIN data-tilt) -->
    <div class="auth-card text-center">
        
        <a href="<?= url('/') ?>">
            <img src="<?= asset('imgs/logo_tekopora.png') ?>" alt="TekoPorã Bolivia" class="auth-logo">
        </a>
        
        <h1 class="auth-title">Únete a TekoPorã</h1>
        <p class="auth-subtitle">Regístrate como ciudadano y sé parte del cambio</p>

        <?php if (!empty($_SESSION['error'])): ?>
            <div class="alert alert-danger py-2 text-start small mb-4 shadow-sm" style="border-radius: 8px;">
                <i class="fa fa-exclamation-triangle me-2"></i> <?= htmlspecialchars($_SESSION['error']) ?>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <form action="<?= url('register') ?>" method="POST" class="text-start">
            
            <div class="row g-2 mb-3">
                <div class="col-md-6">
                    <div class="input-group shadow-sm">
                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                        <input type="text" name="nombre" class="form-control" placeholder="Nombre" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="input-group shadow-sm">
                        <span class="input-group-text"><i class="fas fa-user-tag"></i></span>
                        <input type="text" name="appPaterno" class="form-control" placeholder="Apellido Paterno" required>
                    </div>
                </div>
            </div>

            <div class="row g-2 mb-3">
                <div class="col-md-6">
                    <div class="input-group shadow-sm">
                        <span class="input-group-text text-muted"><i class="fas fa-user-tag"></i></span>
                        <input type="text" name="appMaterno" class="form-control" placeholder="Ap. Materno (Opcional)">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="input-group shadow-sm">
                        <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                        <input type="text" name="ci" class="form-control" placeholder="Carnet de Identidad" required>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <div class="input-group shadow-sm">
                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    <input type="email" name="email" class="form-control" placeholder="Correo electrónico" required>
                </div>
            </div>

            <div class="mb-3">
                <div class="input-group shadow-sm">
                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                    <input type="text" name="telefono" class="form-control" placeholder="Teléfono de contacto">
                </div>
            </div>

            <div class="mb-3">
                <div class="input-group shadow-sm">
                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                    <input type="password" name="password" id="passwordInput" class="form-control" placeholder="Crea una contraseña segura" 
                           pattern="(?=.*\d)(?=.*[A-Z])(?=.*[\W_]).{8,}" required>
                </div>
                
                <div class="pwd-strength-container">
                    <div class="pwd-bar-bg">
                        <div class="pwd-bar-progress" id="strengthBar"></div>
                    </div>
                    <ul class="pwd-req-list">
                        <li class="pwd-req-item invalid" id="reqLength"><i class="fas fa-circle"></i> 8+ Caracteres</li>
                        <li class="pwd-req-item invalid" id="reqUpper"><i class="fas fa-circle"></i> 1 Mayúscula</li>
                        <li class="pwd-req-item invalid" id="reqNumber"><i class="fas fa-circle"></i> 1 Número</li>
                        <li class="pwd-req-item invalid" id="reqSymbol"><i class="fas fa-circle"></i> 1 Símbolo</li>
                    </ul>
                </div>
            </div>

            <?php if (isset($roles) && count($roles) > 0): ?>
                <input type="hidden" name="rol" value="<?= $roles[0]['idRol'] ?>">
            <?php endif; ?>
            
            <div class="d-flex justify-content-center mb-3 mt-3">
                <div class="g-recaptcha" data-sitekey="6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI"></div>
            </div>

            <button type="submit" class="btn btn-primary-custom w-100 text-white shadow-sm">
                Crear Mi Cuenta
            </button>

            <div class="divider">o regístrate con</div>

            <a href="<?= url('/auth/google') ?>" class="btn btn-google w-100 shadow-sm text-decoration-none">
                <img src="https://www.google.com/favicon.ico" alt="Google" width="18"> 
                Continuar con Google
            </a>
            
            <div class="auth-links mt-4 text-center">
                <p class="mb-0 small">¿Ya tienes una cuenta? <a href="<?= url('/login') ?>">Inicia sesión aquí</a></p>
            </div>
        </form>
    </div>

    <!-- Script Propio -->
    <script src="<?= asset('js/register.js') ?>"></script>

</body>
</html>