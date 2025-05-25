<?php
$session = session();
$isLoggedIn = $session->get('isLoggedIn') ?? false;
$userName = $session->get('full_name') ?? 'Usuario';
$profileImage = $session->get('profile_image');
$userRoleId = $session->get('role') ?? 0;
$refreshParam = $session->has('profile_refresh') ? time() : '';

// Asegurar que tengamos una imagen válida
if (empty($profileImage)) {
    $profileImage = 'default.png';
}

// Ruta completa a la imagen con parámetro anti-caché
$profileImageUrl = base_url('/uploads/avatars/' . esc($profileImage)) . '?nocache=' . time() . '&r=' . $refreshParam;
?>

<!-- Estilos personalizados para los dropdowns -->
<style>

</style>

<nav class="navbar navbar-expand-lg navbar-light py-3 border-bottom bg-white shadow-sm">
    <div class="container">
        <!-- Logo -->
        <a class="navbar-brand fw-bold border px-3 py-2 rounded-2" href="<?= base_url('dashboard') ?>">
            INTRACONECTA
        </a>
        
        <!-- Botón de hamburguesa para móviles -->
        <button class="navbar-toggler" type="button" data-coreui-toggle="collapse" data-coreui-target="#navbarNav" 
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Enlaces de navegación -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item">
                    <a class="nav-link text-danger d-flex align-items-center" href="<?= base_url('bookings') ?>">
                        <i class="bi bi-calendar3 me-2"></i> Reservas
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-danger d-flex align-items-center" href="<?= base_url('en-construccion') ?>">
                        <i class="bi bi-people me-2"></i> Comunidad
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-danger d-flex align-items-center" href="<?= base_url('en-construccion') ?>">
                        <i class="bi bi-geo-alt me-2"></i> Servicios
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link text-danger" href="<?= base_url('en-construccion') ?>">
                        Contacto
                    </a>
                </li>

                <?php if ($isLoggedIn): ?>
                    <li class="nav-item">
                        <a class="nav-link text-danger d-flex align-items-center" href="<?= base_url('my-bookings') ?>">
                            <i class="bi bi-calendar-check me-2"></i> Mis Reservas
                        </a>
                    </li>

                    <?php if ($userRoleId == 1): ?> 
                    <!-- Menú de administración (solo visible para administradores) -->
                    <li class="nav-item dropdown">
                        <button class="btn dropdown-toggle" type="button" data-coreui-toggle="dropdown" aria-expanded="false">
                            <img src="<?= base_url('/images/icn_settings.png') ?>"
                                 style="width:20px; height:20px;" class="me-1">
                        </button>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href="<?= base_url('admin/users') ?>">
                                    <i class="bi bi-people-fill me-2"></i>Gestionar Usuarios
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="<?= base_url('admin/rooms') ?>">
                                    <i class="bi bi-door-open-fill me-2"></i>Gestionar Salas
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="<?= base_url('admin/bookings') ?>">
                                    <i class="bi bi-calendar-x-fill me-2"></i>Gestionar Reservas
                                </a>
                            </li>
                        </ul>
                    </li>
                    <?php endif; ?>

                    <!-- Avatar y nombre de usuario -->
                    <li class="nav-item dropdown ms-3">
                        <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" id="userDropdown" data-coreui-toggle="dropdown" aria-expanded="false">
                            <img src="<?= $profileImageUrl ?>" alt="<?= esc($userName) ?>" 
                                 class="rounded-circle border shadow-sm" style="width:38px; height:38px; object-fit:cover;">
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end text-small shadow" aria-labelledby="userDropdown">
                            <li>
                                <a class="dropdown-item" href="<?= base_url('profile') ?>">
                                    <i class="bi bi-person-fill me-2"></i>Mi Perfil
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item text-danger" href="<?= base_url('logout') ?>">
                                    <i class="bi bi-box-arrow-right me-2"></i>Cerrar Sesión
                                </a>
                            </li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item ms-3">
                        <a href="<?= base_url('login') ?>" class="btn btn-outline-danger">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Iniciar Sesión
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<link rel="stylesheet" href="<?= base_url('/css/header.css') ?>">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">