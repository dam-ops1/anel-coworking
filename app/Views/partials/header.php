<?php
$session = session();
$userName = $session->get('full_name');
$profileImage = $session->get('profile_image') ?? 'default.png';
?>

<nav class="navbar navbar-expand-lg navbar-light px-4 py-2 border-bottom bg-white">
    <div class="container-fluid d-flex justify-content-between align-items-center">

        <!-- Logo -->
        <a class="navbar-brand fw-bold border px-3 py-1" href="<?= base_url('/') ?>">INTRACONECTA</a>

        <!-- Enlaces de navegación -->
        <ul class="navbar-nav d-flex flex-row gap-4 align-items-center">
            <li class="nav-item">
                <a class="nav-link text-danger d-flex align-items-center" href="<?= base_url('bookings') ?>">
                    <i class="bi bi-calendar3 me-1"></i> Reservas
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-danger d-flex align-items-center" href="<?= base_url('comunidad') ?>">
                    <i class="bi bi-people me-1"></i> Comunidad
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-danger d-flex align-items-center" href="<?= base_url('servicios') ?>">
                    <i class="bi bi-geo-alt me-1"></i> Servicios
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link text-danger" href="<?= base_url('contacto') ?>">
                    Contacto
                </a>
            </li>

            <li class="nav-item dropdown">
                <button class="btn dropdown-toggle" type="button" data-coreui-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-gear me-1 text-danger"></i>
                </button>
                <ul class="dropdown-menu">
                    <li>
                        <a class="dropdown-item" href="<?= base_url('users') ?>">Usuarios

                        </a>
                    </li>
                </ul>
            </li>

            <!-- Avatar -->
            <li class="nav-item ms-3">
                <img src="<?= base_url('uploads/avatars/' . esc($profileImage)) ?>" alt="Usuario" class="rounded-circle" style="width:32px; height:32px; object-fit:cover;">
            </li>
        </ul>

    </div>
</nav>