<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Centro de Emprendimiento y Coworking</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <style>
        .sidebar {
            min-height: 100vh;
            background-color: #343a40;
            padding-top: 20px;
        }
        .sidebar .nav-link {
            color: #fff;
            padding: 10px 20px;
        }
        .sidebar .nav-link:hover {
            background-color: #495057;
        }
        .sidebar .nav-link.active {
            background-color: #007bff;
        }
        .main-content {
            padding: 20px;
        }
        .navbar {
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0,0,0,.1);
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 px-0 position-fixed sidebar">
                <div class="text-center mb-4">
                    <img src="/logo.png" alt="Logo" class="img-fluid px-3">
                </div>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link <?= current_url() == base_url('rooms/calendar') ? 'active' : '' ?>" 
                           href="<?= base_url('rooms/calendar') ?>">
                            <i class="fas fa-calendar-alt mr-2"></i> Calendario
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= current_url() == base_url('bookings') ? 'active' : '' ?>" 
                           href="<?= base_url('bookings') ?>">
                            <i class="fas fa-bookmark mr-2"></i> Mis Reservas
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= current_url() == base_url('rooms') ? 'active' : '' ?>" 
                           href="<?= base_url('rooms') ?>">
                            <i class="fas fa-door-open mr-2"></i> Salas
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Main Content -->
            <div class="col-md-10 offset-md-2 main-content">
                <!-- Navbar -->
                <nav class="navbar navbar-expand-lg navbar-light mb-4">
                    <div class="container-fluid">
                        <h1 class="navbar-brand mb-0">Centro de Emprendimiento y Coworking</h1>
                        <div class="navbar-nav ml-auto">
                            <div class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" 
                                   data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-user"></i> Usuario
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <li><a class="dropdown-item" href="#"><i class="fas fa-cog"></i> Configuración</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href=" <?= base_url('logout') ?> "><i class="fas fa-sign-out-alt" ></i> Cerrar Sesión</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </nav>

                <!-- Content Section -->
                <?= $this->renderSection('content') ?>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery (necesario para algunas funcionalidades) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>
</html> 