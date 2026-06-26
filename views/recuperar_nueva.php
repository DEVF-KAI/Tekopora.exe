<!DOCTYPE html>
<html lang="es">
<head>
    <title>Nueva Contraseña</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style> body { background: linear-gradient(rgba(35, 40, 45, 0.75), rgba(35, 40, 45, 0.85)), url('<?= asset('imgs/boli.png') ?>') center/cover fixed; min-height: 100vh; display: flex; align-items: center; justify-content: center; } .auth-card { background: rgba(255, 255, 255, 0.98); border-radius: 20px; padding: 40px; width: 100%; max-width: 420px; text-align: center; } .btn-primary-custom { background: #2A6F97; color: white; border: none; border-radius: 10px; padding: 12px; font-weight: 600; width: 100%; } </style>
</head>
<body>
    <div class="auth-card">
        <h3 class="fw-bold text-dark mb-2">Crea una nueva contraseña</h3>
        <p class="text-muted small mb-4">Asegúrate de que sea segura y fácil de recordar.</p>

        <form action="<?= url('/recuperar/actualizar') ?>" method="POST">
            <div class="input-group mb-4 shadow-sm rounded-3">
                <span class="input-group-text bg-light"><i class="fa fa-lock"></i></span>
                <input type="password" name="password" class="form-control bg-light" placeholder="Nueva Contraseña" required 
                       pattern="(?=.*\d)(?=.*[A-Z])(?=.*[\W_]).{8,}" title="Mínimo 8 caracteres, 1 mayúscula, 1 número y 1 símbolo">
            </div>
            <button type="submit" class="btn btn-primary-custom">Actualizar Contraseña</button>
        </form>
    </div>
</body>
</html>