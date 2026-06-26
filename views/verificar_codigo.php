<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificar Código | TekoPorã Bolivia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            background: linear-gradient(rgba(35, 40, 45, 0.8), rgba(35, 40, 45, 0.9)), url('<?= asset('imgs/boli.png') ?>') center/cover fixed;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .verify-card {
            background: rgba(255, 255, 255, 0.98);
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.3);
            width: 100%;
            max-width: 450px;
            padding: 40px;
            text-align: center;
            animation: fadeIn 0.6s ease-out;
        }

        .icon-box {
            width: 80px;
            height: 80px;
            background: rgba(33, 127, 130, 0.1);
            color: #217F82;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            margin: 0 auto 20px;
        }

        .code-input {
            letter-spacing: 12px;
            font-size: 2rem;
            font-weight: 700;
            text-align: center;
            border-radius: 12px;
            border: 2px solid #e0e0e0;
            padding: 15px;
            color: #2A6F97;
            transition: all 0.3s ease;
        }

        .code-input:focus {
            border-color: #217F82;
            box-shadow: 0 0 10px rgba(33, 127, 130, 0.2);
            outline: none;
        }

        .btn-verify {
            background: #217F82;
            color: white;
            border-radius: 12px;
            padding: 12px;
            font-weight: 600;
            border: none;
            transition: 0.3s;
        }

        .btn-verify:hover {
            background: #185c5e;
            transform: translateY(-2px);
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

    <div class="verify-card">
        <div class="icon-box">
            <i class="fas fa-shield-alt"></i>
        </div>
        
        <h2 class="fw-bold mb-2">Verifica tu identidad</h2>
        <p class="text-muted small mb-4">
            Hemos enviado un código de 6 dígitos a su correo institucional.
        </p>

        <?php if (!empty($_SESSION['error'])): ?>
            <div class="alert alert-danger py-2 small mb-3">
                <?= htmlspecialchars($_SESSION['error']) ?>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <?php if (!empty($_SESSION['success'])): ?>
            <div class="alert alert-success py-2 small mb-3">
                <?= htmlspecialchars($_SESSION['success']) ?>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <form action="<?= url('/verificar/validar') ?>" method="POST">
            <div class="mb-4">
                <input type="text" name="codigo" class="form-control code-input" 
                       placeholder="000000" maxlength="6" pattern="\d{6}" required autocomplete="off">
            </div>

            <button type="submit" class="btn btn-verify w-100 mb-3">
                Verificar Código
            </button>
        </form>

        <div class="mt-3">
            <p class="text-muted small">¿No recibiste nada?</p>
            <a href="<?= url('/verificar/enviar') ?>" class="text-decoration-none fw-bold" style="color: #2A6F97;">
                <i class="fas fa-sync-alt me-1"></i> Reenviar nuevo código
            </a>
        </div>
    </div>

</body>
</html>