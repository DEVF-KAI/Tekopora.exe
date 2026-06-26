<!DOCTYPE html>
<html lang="es">
<!-- Usa el mismo <head> de tu login.php con los estilos css -->
<head>
    <title>Recuperar Contraseña</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style> body { background: linear-gradient(rgba(35, 40, 45, 0.75), rgba(35, 40, 45, 0.85)), url('<?= asset('imgs/boli.png') ?>') center/cover fixed; min-height: 100vh; display: flex; align-items: center; justify-content: center; } .auth-card { background: rgba(255, 255, 255, 0.98); border-radius: 20px; padding: 40px; width: 100%; max-width: 420px; text-align: center; } .btn-primary-custom { background: #2A6F97; color: white; border: none; border-radius: 10px; padding: 12px; font-weight: 600; width: 100%; } </style>
</head>
<body>
    <div class="auth-card">
        <h3 class="fw-bold text-dark mb-2">Recuperar Acceso</h3>
        <p class="text-muted small mb-4">Ingresa tu correo para recibir un código de recuperación.</p>
        
        <?php if (!empty($_SESSION['error'])): ?>
            <div class="alert alert-danger py-2 small"><?= htmlspecialchars($_SESSION['error']) ?></div>
            <?php unset($_SESSION['error']); endif; ?>

        <form action="<?= url('/recuperar/enviar') ?>" method="POST">
            <div class="input-group mb-3 shadow-sm rounded-3">
                <span class="input-group-text bg-light"><i class="fa fa-envelope"></i></span>
                <input type="email" name="email" class="form-control bg-light" placeholder="Tu correo electrónico" required>
            </div>
            <button type="submit" class="btn btn-primary-custom mb-3">Enviar Código</button>
        </form>
        <a href="<?= url('/login') ?>" class="text-decoration-none small" style="color: #2A6F97;">Volver al Login</a>
    </div>
</body>
</html>