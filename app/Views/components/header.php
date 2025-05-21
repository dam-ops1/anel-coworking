<?php
$session = session();
$isLoggedIn = $session->get('isLoggedIn') ?? false;
$userName = $session->get('full_name') ?? 'Usuario';
$profileImage = $session->get('profile_image');
$userRoleId = $session->get('role') ?? 0;

if (empty($profileImage)) {
    $profileImage = 'default.png';
}
?>

<!-- Estilos personalizados para los dropdowns -->
<style>

</style>

<nav class="navbar navbar-expand-lg navbar-light px-4 py-2 border-bottom bg-white">
    <div class="container-fluid d-flex justify-content-between align-items-center">

        <!-- Logo -->
        <a class="navbar-brand fw-bold border px-3 py-1" href="<?= base_url('dashboard') ?>">INTRACONECTA</a>

        <!-- Enlaces de navegación -->
        <ul class="navbar-nav d-flex flex-row gap-4 align-items-center">
            <li class="nav-item">
                <a class="nav-link text-danger d-flex align-items-center" href="<?= base_url('bookings') ?>">
                    <i class="bi bi-calendar3 me-1"></i> Reservas
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-danger d-flex align-items-center" href="<?= base_url('en-construccion') ?>">
                    <i class="bi bi-people me-1"></i> Comunidad
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-danger d-flex align-items-center" href="<?= base_url('en-construccion') ?>">
                    <i class="bi bi-geo-alt me-1"></i> Servicios
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link text-danger" href="<?= base_url('en-construccion') ?>">
                    Contacto
                </a>
            </li>

            <?php if ($isLoggedIn): ?>
                <?php if ($userRoleId == 1): ?> 
                <!-- Menú de administración (solo visible para administradores) -->
                <li class="nav-item dropdown">
                    <button class="btn dropdown-toggle" type="button" data-coreui-toggle="dropdown" aria-expanded="false">
                        <img src="<?= base_url('/images/icn_settings.png') ?>"
                             style="width:18px; height:18px;" class="me-1">
                    </button>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item" href="<?= base_url('admin/users') ?>">
                                <i class="bi bi-people-fill me-2"></i>Usuarios
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="<?= base_url('admin/rooms') ?>">
                                <i class="bi bi-door-open-fill me-2"></i>Gestionar Salas
                            </a>
                        </li>
                    </ul>
                </li>
                <?php endif; ?>

                <!-- Avatar y nombre de usuario -->
                <li class="nav-item dropdown ms-3">
                    <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" id="userDropdown" data-coreui-toggle="dropdown" aria-expanded="false">
                        <img src="<?= base_url('/uploads/avatars/' . esc($profileImage)) ?>" alt="<?= esc($userName) ?>" 
                             class="rounded-circle" style="width:32px; height:32px; object-fit:cover;">
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end text-small" aria-labelledby="userDropdown">
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
            <?php endif; ?>
        </ul>

    </div>
</nav>

<link rel="stylesheet" href="<?= base_url('/css/header.css') ?>">