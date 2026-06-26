<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <title><?= $title ?? 'Tekopora' ?></title>
    
    <?php include __DIR__ . '/../components/header.php'; ?>
    
    <link rel="stylesheet" href="<?= asset('css/sidebar.css') ?>">
</head>

<body>

    <?php include __DIR__ . '/../components/navbar.php'; ?>

    <div class="container-fluid p-0">
        <?= $content ?>
    </div>

    <?php include __DIR__ . '/../components/footer.php'; ?>

    <?php include __DIR__ . '/../components/chatbot.php'; ?>

</body>

</html>