<!DOCTYPE html>
<html lang="es">
<head>
    <title>Verificar Código</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style> body { background: linear-gradient(rgba(35, 40, 45, 0.75), rgba(35, 40, 45, 0.85)), url('<?= asset('imgs/boli.png') ?>') center/cover fixed; min-height: 100vh; display: flex; align-items: center; justify-content: center; } .auth-card { background: rgba(255, 255, 255, 0.98); border-radius: 20px; padding: 40px; width: 100%; max-width: 420px; text-align: center; } .btn-primary-custom { background: #217F82; color: white; border: none; border-radius: 10px; padding: 12px; font-weight: 600; width: 100%; } .code-input { letter-spacing: 10px; font-size: 1.5rem; text-align: center; font-weight: bold; } </style>
</head>
<body>
    <div class="auth-card">
        <h3 class="fw-bold text-dark mb-2">Ingresa tu código</h3>
        <p class="text-muted small mb-4">Revisa tu bandeja de entrada o spam.</p>

        <?php if (!empty($_SESSION['success'])): ?>
            <div class="alert alert-success py-2 small"><?= htmlspecialchars($_SESSION['success']) ?></div>
            <?php unset($_SESSION['success']); endif; ?>
        <?php if (!empty($_SESSION['error'])): ?>
            <div class="alert alert-danger py-2 small"><?= htmlspecialchars($_SESSION['error']) ?></div>
            <?php unset($_SESSION['error']); endif; ?>

        <form action="<?= url('/recuperar/verificar') ?>" method="POST">
            <input type="text" name="codigo" class="form-control code-input mb-4" placeholder="000000" maxlength="6" required>
            <button type="submit" class="btn btn-primary-custom mb-3">Verificar</button>
        </form>
    </div>
</body>
</html>