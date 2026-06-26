<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <title>Login - TekoPorã Bolivia</title>
    
    <style>
        body {
            /* Aseguramos que el fondo cubra todo bien */
            background: url(<?= asset('imgs/boli.png') ?>) no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            font-family: 'Segoe UI', sans-serif;
        }

        /* Contenedor que agrupa el logo y la tarjeta */
        .login-wrapper {
            width: 100%;
            max-width: 400px;
            padding: 15px;
            animation: fadeIn 1s ease-in-out;
        }

        /* Estilo interactivo para el logo */
        .logo-container img {
            width: 200px;
            object-fit: contain;
            filter: drop-shadow(0 4px 6px rgba(0,0,0,0.3));
            transition: transform 0.3s ease;
            border: 1px solid rgba(255,255,255,0.3);
        }

        .logo-container img:hover {
            transform: scale(1.05);
        }

        .login-card {
            background: #fff;
            padding: 2.5rem;
            border-radius: 1rem;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            width: 100%;
        }

        .login-card h2 {
            font-weight: 700;
            margin-bottom: 1.5rem;
            color: #0d6efd;
            text-align: center;
        }

        .form-control {
            border-radius: 0.75rem;
            padding-left: 2.5rem;
        }

        .input-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
        }

        .btn-custom {
            border-radius: 0.75rem;
            font-weight: bold;
            background: #0d6efd;
            color: #fff;
            transition: 0.3s;
        }

        .btn-custom:hover {
            background: #0b5ed7;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>

<body>

    <div class="login-wrapper">
        
        <div class="text-center mb-4 logo-container">
            <a href="<?= url('/') ?>" title="Volver al Inicio">
                <img src="<?= asset('imgs/logo_tekopora.png') ?>" alt="Logo TekoPorã Bolivia" class="bg-white rounded-pill px-3 py-2 shadow-sm">
            </a>
        </div>

        <div class="login-card">
            <h2><i class="fa fa-user-circle"></i> Iniciar Sesión</h2>

            <?php if (!empty($_SESSION['error'])): ?>
                <div class="alert alert-danger mt-2 mb-3 text-center" style="font-size: 0.9em; border-radius: 10px;">
                    <?= htmlspecialchars($_SESSION['error']) ?>
                </div>
                <script>
                    console.error("<?= addslashes($_SESSION['error']) ?>");
                </script>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <form action="<?= url('/login') ?>" method="POST">
                <div class="mb-3 position-relative">
                    <i class="fa fa-envelope input-icon"></i>
                    <input type="email" name="email" class="form-control" placeholder="Correo electrónico" required>
                </div>
                <div class="mb-3 position-relative">
                    <i class="fa fa-lock input-icon"></i>
                    <input type="password" name="password" class="form-control" placeholder="Contraseña" required>
                </div>
                <button type="submit" class="btn btn-custom w-100 py-2">Ingresar</button>
                <div class="text-center mt-3">
                    <a href="#" class="text-decoration-none small">¿Olvidaste tu contraseña?</a>
                    <br>
                    <a href="<?= url('/register') ?>" class="text-decoration-none font-weight-bold mt-2 d-inline-block">Crear una cuenta</a>
                </div>
            </form>
        </div>
        
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>