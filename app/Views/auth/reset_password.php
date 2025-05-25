<?= $this->extend('layouts/auth') ?>
<?= $this->section('content') ?>

<main class="c-app d-flex align-items-center" style="min-height:100vh;">
  <div class="container-fluid px-3 px-md-4 d-flex justify-content-center align-items-center min-vh-100">
    <!-- Contenedor responsive -->
    <div class="col-12 col-sm-10 col-md-8 col-lg-6 col-xl-5 col-xxl-4" style="max-width: 600px;">
      <div class="card shadow-sm rounded-3 border-0 position-relative" style="overflow:hidden;">
        <!-- Botón Volver ATRÁS -->
        <a href="<?= base_url() ?>" class="position-absolute" style="
            top: 0.75rem; 
            left: 0.75rem; 
            z-index: 10;
          ">
          <img src="<?= base_url('images/icn_back.png') ?>" alt="Volver" style="width:20px; height:20px;" class="d-none d-sm-block">
          <img src="<?= base_url('images/icn_back.png') ?>" alt="Volver" style="width:18px; height:18px;" class="d-block d-sm-none">
        </a>

        <div class="card-body p-3 p-sm-4 p-md-5 d-flex flex-column justify-content-center">
          <!-- Logo/Icono -->
          <div class="d-flex justify-content-center align-items-center mb-3 mb-md-4">
            <img src="<?= base_url('images/icn_reset_pass.png') ?>" alt="Restablecer Contraseña" 
                 class="img-fluid" 
                 style="max-width: 80px; height: auto;">
          </div>
          
          <!-- Título -->
          <h4 class="text-center mb-3 mb-md-4 fs-5 fs-md-4">Restablecer Contraseña</h4>
          <p class="text-center text-muted mb-3 mb-md-4 small">Ingresa tu nueva contraseña</p>

          <!-- Mensajes de error/éxito -->
          <?php if (session('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
              <small><?= session('error') ?></small>
              <button type="button" class="btn-close btn-close-sm" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          <?php endif; ?>

          <?php if (session('success')): ?>
            <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
              <small><?= session('success') ?></small>
              <button type="button" class="btn-close btn-close-sm" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          <?php endif; ?>

          <form action="<?= base_url('auth/reset-password') ?>" method="POST"
            onsubmit="this.resetPassButton.disabled=true; this.resetPassButton.innerText='Cargando…'; return true;">
            <?= csrf_field() ?>

            <input type="hidden" name="token" value="<?= esc($token) ?>">

            <!-- Campo Nueva Contraseña -->
            <div class="form-group mb-3">
              <label for="password" class="form-label small fw-medium">Nueva Contraseña</label>
              <input type="password"
                class="form-control form-control-sm form-control-md-lg <?= (session('validation') && session('validation')->hasError('password')) ? 'is-invalid' : '' ?>"
                id="password" 
                name="password" 
                placeholder="Ingresa tu nueva contraseña"
                required>
              <?php if (session('validation') && session('validation')->hasError('password')): ?>
                <div class="invalid-feedback small">
                  <?= session('validation')->getError('password') ?>
                </div>
              <?php endif; ?>
            </div>

            <!-- Campo Confirmar Contraseña -->
            <div class="form-group mb-3 mb-md-4">
              <label for="confirm_password" class="form-label small fw-medium">Confirmar Contraseña</label>
              <input type="password"
                class="form-control form-control-sm form-control-md-lg <?= (session('validation') && session('validation')->hasError('confirm_password')) ? 'is-invalid' : '' ?>"
                id="confirm_password" 
                name="confirm_password" 
                placeholder="Confirma tu nueva contraseña"
                required>
              <?php if (session('validation') && session('validation')->hasError('confirm_password')): ?>
                <div class="invalid-feedback small">
                  <?= session('validation')->getError('confirm_password') ?>
                </div>
              <?php endif; ?>
            </div>

            <!-- Botón de envío -->
            <div class="d-grid">
              <button type="submit" 
                      class="btn btn-primary w-100 py-2 py-md-3" 
                      name="resetPassButton"
                      style="background-color:#FF5722; border-color:#FF5722; font-size: 0.9rem;">
                <span class="d-none d-sm-inline">Restablecer Contraseña</span>
                <span class="d-inline d-sm-none">Restablecer</span>
              </button>
            </div>
          </form>

          <!-- Enlaces adicionales -->
          <div class="text-center mt-3 mt-md-4">
            <a href="<?= base_url('auth/login') ?>" class="text-decoration-none small text-muted">
              ← Volver al inicio de sesión
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>

<!-- Estilos adicionales para mejorar la responsividad -->
<style>
  @media (max-width: 576px) {
    .card-body {
      padding: 1.5rem 1rem !important;
    }
    
    .form-control {
      font-size: 16px; /* Previene zoom en iOS */
    }
    
    .btn {
      font-size: 0.875rem;
    }
  }
  
  @media (max-width: 768px) {
    .container-fluid {
      padding-left: 1rem;
      padding-right: 1rem;
    }
  }
  
  /* Mejoras para tablets */
  @media (min-width: 768px) and (max-width: 1024px) {
    .card {
      margin: 2rem 0;
    }
  }
  
  /* Asegurar que el formulario sea accesible en pantallas pequeñas */
  @media (max-height: 600px) and (orientation: landscape) {
    .min-vh-100 {
      min-height: auto !important;
    }
    
    .card-body {
      padding: 1rem !important;
    }
    
    .mb-3, .mb-md-4 {
      margin-bottom: 0.75rem !important;
    }
  }
</style>

<?= $this->endSection() ?>