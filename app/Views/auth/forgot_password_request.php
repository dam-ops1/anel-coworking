<?= $this->extend('layouts/auth') ?>
<?= $this->section('content') ?>

<main class="c-app d-flex align-items-center" style="min-height:100vh;">
  <div class="container d-flex justify-content-center align-items-center min-vh-100">
    <!-- Ahora ancho máximo 600px y 95vw en móvil -->
    <div class="col-auto" style="max-width:600px; width:95vw;">
      <div class="card shadow-sm rounded-3 border-0 position-relative overflow-hidden">
        
        <!-- Botón volver atrás -->
        <a href="<?= base_url() ?>"
           class="position-absolute"
           style="top:1rem; left:1rem; z-index:10; text-decoration:none;"
        >
          <img src="<?= base_url('images/icn_back.png') ?>"
               alt="Volver"
               style="width:24px; height:24px;"
          >
        </a>

        <div class="card-body p-5 d-flex flex-column justify-content-center">

        <div class="d-flex justify-content-center align-items-center mb-1" style="flex:0 0 30%;">
            <img src="<?= base_url('images/icn_reset_pass.png') ?>" alt="Anel Logo" class="img-fluid"
              style="max-width:20%; height:auto;">
          </div>
          
          <!-- Icono -->
          <div class="text-center mb-4">
            <i class="cil-reload" style="font-size:2.5rem; color:#555;"></i>
          </div>

          <!-- Título -->
          <h4 class="text-center mb-1">¿Olvidaste tu contraseña?</h4>
          <p class="text-center text-muted mb-4">Ingresa tu correo electrónico</p>

          <!-- Error flash -->
          <?php if ($err = session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"><?= esc($err) ?></div>
          <?php endif; ?>

          <!-- Formulario -->
          <form action="<?= base_url('auth/password-email') ?>" method="post"
                onsubmit="this.forgotPassButton.disabled=true; this.forgotPassButton.innerText='Cargando…'; return true;">
            <?= csrf_field() ?>

            <div class="mb-4">
              <label for="email" class="form-label">Correo Electrónico</label>
              <div class="input-group input-group-lg">
                <input
                  type="email"
                  id="email"
                  name="email"
                  value="<?= old('email') ?>"
                  class="form-control <?= (session('validation') && session('validation')->hasError('email')) ? 'is-invalid' : '' ?>"
                  placeholder="name@example.com"
                  required
                >
                <div class="invalid-feedback">
                  <?= session('validation') ? session('validation')->getError('email') : '' ?>
                </div>
              </div>
            </div>

            <div class="d-grid mb-3">
              <button type="submit" class="btn btn-primary btn-lg" name="forgotPassButton"
                      style="background-color:#FF5722; border-color:#FF5722;">
                Enviar enlace
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</main>

<?= $this->endSection() ?>
