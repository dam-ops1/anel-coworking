<?= $this->extend('layouts/auth') ?>
<?= $this->section('content') ?>

<main class="c-app d-flex align-items-center" style="min-height:100vh;">
  <div class="container d-flex justify-content-center align-items-center min-vh-100">
    <!-- Contenedor fijo 600×600 -->
    <div class="col-auto" style="width:600px; height:600px;">
      <div class="card shadow-sm rounded-3 border-0 position-relative h-100" style="overflow:hidden;">
        <!-- Botón Volver ATRÁS (ahora antes de card-body) -->
        <a href="<?= base_url() ?>" class="position-absolute" style="
            top: 1rem; 
            left: 1rem; 
            z-index: 10;
          ">
          <img src="<?= base_url('images/icn_back.png') ?>" alt="Volver" style="width:24px; height:24px;">
        </a>

        <div class="card-body p-5 d-flex flex-column justify-content-center">
          <div class="d-flex justify-content-center align-items-center mb-1" style="flex:0 0 30%;">
            <img src="<?= base_url('images/icn_reset_pass.png') ?>" alt="Anel Logo" class="img-fluid"
              style="max-width:20%; height:auto;">
          </div>
          <!-- Título -->
          <h4 class="text-center mb-4">Restablecer Contraseña</h4>
          <p class="text-center text-muted mb-4">Ingresa tu nueva contraseña</p>

          <form action="<?= base_url('auth/reset-password') ?>" method="POST" onsubmit="this.resetPassButton.disabled=true; this.resetPassButton.innerText='Cargando…'; return true;">
            <?= csrf_field() ?>

            <input type="hidden" name="token" value="<?= esc($token) ?>">


            <div class="form-group mb-3">
                        <label for="password">Nueva Contraseña</label>
                        <input type="password"
                            class="form-control <?= (session('validation') && session('validation')->hasError('password')) ? 'is-invalid' : '' ?>"
                            id="password" name="password" required>
                        <?php if (session('validation') && session('validation')->hasError('password')): ?>
                            <div class="invalid-feedback">
                                <?= session('validation')->getError('password') ?>
                            </div>
                        <?php endif; ?>
                    </div>

            <div class="form-group mb-3">
                        <label for="confirm_password">Confirmar Contraseña</label>
                        <input type="password"
                            class="form-control <?= (session('validation') && session('validation')->hasError('confirm_password')) ? 'is-invalid' : '' ?>"
                            id="confirm_password" name="confirm_password" required>
                        <?php if (session('validation') && session('validation')->hasError('confirm_password')): ?>
                            <div class="invalid-feedback">
                                <?= session('validation')->getError('confirm_password') ?>
                            </div>
                        <?php endif; ?>
                    </div>

            <div class="d-grid">
              <button type="submit" class="btn btn-primary w-100" name="resetPassButton"
                style="background-color:#FF5722; border-color:#FF5722;">
                Restablecer Contraseña
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</main>

<?= $this->endSection() ?>