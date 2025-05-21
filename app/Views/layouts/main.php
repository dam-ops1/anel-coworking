<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Anel Coworking - Espacio para la innovación social</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?= base_url('images/favicon.png') ?>">
    
    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/@coreui/coreui@5.4.0/dist/css/coreui.min.css" rel="stylesheet" integrity="sha384-TjEsBrREQ8e4UQZBv0t+xyJqXlIR9Z0I2S84WzGcxjOpwG3287e0uXc5MqDVOLPh" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('css/general.css') ?>">
    <link rel="stylesheet" href="<?= base_url('css/construction.css') ?>">
</head>
<body>
    <!-- Header -->
    <?= $this->include('components/header') ?>

    <!-- Main Content -->
    <div class="wrapper d-flex flex-column min-vh-100 bg-light">
        <div class="body flex-grow-1 px-3">
            <?= $this->renderSection('content') ?>
        </div>

        <!-- Footer -->
        <?= $this->include('components/footer') ?>
    </div>

    <!-- JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@coreui/coreui@5.4.0/dist/js/coreui.min.js" integrity="sha384-VlLkV9lXrXUjf40eVjPyWh+2xj8u+Yo+xW8JqHvMYm93rurFKip37OtfOzqiyMgd" crossorigin="anonymous"></script>

</body>
</html>
