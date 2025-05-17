<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Anel Coworking - Espacio para la innovación social</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?= base_url('images/favicon.png') ?>">
    
    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/@coreui/coreui@4.3.0/dist/css/coreui.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('css/general.css') ?>">
</head>
<body>
    <!-- Header -->
    <header class="header header-sticky mb-4">
        <div class="container-fluid">
            <button class="header-toggler px-md-0 me-md-3" type="button" onclick="coreui.Sidebar.getInstance(document.querySelector('#sidebar')).toggle()">
                <i class="icon icon-lg cil-menu"></i>
            </button>
            <a class="header-brand d-md-none" href="<?= base_url() ?>">
                <img src="<?= base_url('images/icn_anel_logo.png') ?>" height="46" alt="Anel Logo">
            </a>
            <a class="header-brand d-none d-md-flex" href="<?= base_url() ?>">
                <img src="<?= base_url('images/icn_anel_logo.png') ?>" height="46" alt="Anel Logo">
            </a>
            
            <ul class="header-nav d-none d-md-flex me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('nosotros') ?>">Nosotros</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('comunidad') ?>">Comunidad</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('servicios') ?>">Servicios</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('contacto') ?>">Contacto</a>
                </li>
            </ul>
            
            <ul class="header-nav ms-3">
                <?php if (session()->get('isLoggedIn')): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link py-0" data-coreui-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                            <div class="avatar avatar-md">
                                <i class="fas fa-user-circle fa-lg"></i>
                            </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end pt-0">
                            <div class="dropdown-header bg-light py-2">
                                <div class="fw-semibold">Cuenta</div>
                            </div>
                            <a class="dropdown-item" href="<?= base_url('profile') ?>">
                                <i class="fas fa-user me-2"></i> Mi Perfil
                            </a>
                            <a class="dropdown-item" href="<?= base_url('reservas') ?>">
                                <i class="fas fa-calendar-check me-2"></i> Mis Reservas
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="<?= base_url('logout') ?>">
                                <i class="fas fa-sign-out-alt me-2"></i> Cerrar Sesión
                            </a>
                        </div>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="btn btn-primary" href="<?= base_url('login') ?>">Iniciar Sesión</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </header>

    <!-- Main Content -->
    <div class="wrapper d-flex flex-column min-vh-100 bg-light">
        <div class="body flex-grow-1 px-3">
            <?= $this->renderSection('content') ?>
        </div>

        <!-- Footer -->
        <?= $this->include('components/footer') ?>
    </div>

    <!-- JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/@coreui/coreui@4.3.0/dist/js/coreui.bundle.min.js"></script>
</body>
</html>
