<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión | TekoPorã Bolivia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            /* OVERLAY GRIS OSCURO */
            background: linear-gradient(rgba(35, 40, 45, 0.75), rgba(35, 40, 45, 0.85)), url('<?= asset('imgs/boli.png') ?>') center/cover fixed;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .auth-card {
            background: rgba(255, 255, 255, 0.98);
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.25);
            width: 100%;
            max-width: 420px;
            padding: 40px;
            animation: slideUp 0.5s ease-out;
        }

        .auth-logo {
            width: 180px;
            margin-bottom: 25px;
            transition: transform 0.3s ease;
        }

        .auth-logo:hover {
            transform: translateY(-5px);
        }

        .auth-title {
            color: #2c3e50;
            font-weight: 700;
            font-size: 1.5rem;
            margin-bottom: 5px;
        }

        .auth-subtitle {
            color: #7f8c8d;
            font-size: 0.9rem;
            margin-bottom: 30px;
        }

        .input-group-text {
            background: #f8f9fa;
            border-right: none;
            color: #2A6F97;
            border-radius: 10px 0 0 10px;
        }

        .form-control {
            background: #f8f9fa;
            border-left: none;
            border-radius: 0 10px 10px 0;
            padding: 12px 15px;
        }

        .form-control:focus {
            background: #fff;
            box-shadow: none;
            border-color: #ced4da;
        }

        .btn-primary-custom {
            background: #2A6F97;
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary-custom:hover {
            background: #1d506e;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(42, 111, 151, 0.3);
        }

        .btn-google {
            background: #fff;
            border: 1px solid #dfe1e5;
            color: #3c4043;
            border-radius: 10px;
            padding: 12px;
            font-weight: 500;
            transition: background 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn-google:hover {
            background: #f8f9fa;
        }

        .divider {
            display: flex;
            align-items: center;
            text-align: center;
            color: #a0a5aa;
            margin: 20px 0;
            font-size: 0.85rem;
        }
        .divider::before, .divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid #e0e0e0;
        }
        .divider:not(:empty)::before { margin-right: .25em; }
        .divider:not(:empty)::after { margin-left: .25em; }

        .auth-links a {
            color: #2A6F97;
            text-decoration: none;
            font-weight: 600;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

<?php if (session_status() === PHP_SESSION_NONE) { session_start(); } ?>

    <div class="auth-card text-center">
        
        <a href="<?= url('/') ?>">
            <img src="<?= asset('imgs/logo_tekopora.png') ?>" alt="TekoPorã Bolivia" class="auth-logo">
        </a>
        
        <h1 class="auth-title">Bienvenido de vuelta</h1>
        <p class="auth-subtitle">Ingresa tus credenciales para continuar</p>

        <?php if (!empty($_SESSION['error'])): ?>
            <div class="alert alert-danger py-2 text-start small mb-4 shadow-sm" style="border-radius: 8px;">
                <i class="fa fa-exclamation-triangle me-2"></i> <?= htmlspecialchars($_SESSION['error']) ?>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <?php if (!empty($_SESSION['success'])): ?>
            <div class="alert alert-success py-2 text-start small mb-4 shadow-sm" style="border-radius: 8px;">
                <i class="fa fa-check-circle me-2"></i> <?= htmlspecialchars($_SESSION['success']) ?>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <form action="<?= url('/login') ?>" method="POST" class="text-start">
            
            <div class="mb-3">
                <div class="input-group shadow-sm rounded-3">
                    <span class="input-group-text"><i class="far fa-envelope"></i></span>
                    <input type="email" name="email" class="form-control" placeholder="Correo electrónico" required>
                </div>
            </div>
            
            <div class="mb-4">
                <div class="input-group shadow-sm rounded-3">
                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                    <input type="password" name="password" class="form-control" placeholder="Contraseña" required>
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary-custom w-100 text-white shadow-sm">
                Iniciar Sesión
            </button>
            
            <div class="divider">o continúa con</div>

            <a href="<?= url('/auth/google') ?>" class="btn btn-google w-100 shadow-sm text-decoration-none">
                <img src="https://www.google.com/favicon.ico" alt="Google" width="18"> 
                Continuar con Google
            </a>

            <div class="auth-links mt-4">
                <p class="mb-1 small">¿Aún no eres parte? <a href="<?= url('/register') ?>">Crea una cuenta ciudadana</a></p>
                <a href="<?= url('/recuperar') ?>" class="small text-muted fw-normal">Olvidé mi contraseña</a>
            </div>
        </form>
    </div>

</body>
</html>