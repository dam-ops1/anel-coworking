<?= $this->extend('layouts/auth') ?>
<?= $this->section('content') ?>

<main class="c-app flex-row align-items-center" style="min-height:100vh;">
  <div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div 
      class="row w-75 bg-white flex-md-nowrap mx-auto" 
      style="border-radius:.375rem; overflow:hidden; min-height:500px;"
    >
      <!-- LOGO -->
      <div class="d-none d-lg-flex col-lg-5 justify-content-center align-items-center p-4">
        <img
          src="<?= base_url('images/icn_anel_logo.png') ?>"
          alt="Anel Logo"
          class="img-fluid"
          style="max-width:115%; height:auto;"
        >
      </div>

      <!-- DIVISOR -->
      <div class="d-none d-lg-flex col-auto justify-content-center">
        <div class="vr align-self-center" style="height:400px; border-color:#dee2e6;"></div>
      </div>

      <!-- FORMULARIO -->
      <div 
        class="
          col-12              
          col-md-8 offset-md-2 
          col-lg-6 offset-lg-0
          p-5 d-flex flex-column justify-content-center
        "
      >
        <h4 class="text-center mb-4">Iniciar Sesión</h4>

        <?php if ($err = session()->getFlashdata('error')): ?>
          <div class="alert alert-danger"><?= esc($err) ?></div>
        <?php endif; ?>
        <?php if ($msg = session()->getFlashdata('success')): ?>
          <div class="alert alert-success"><?= esc($msg) ?></div>
        <?php endif; ?>

        <form
          action="<?= base_url('login') ?>"
          method="post"
          onsubmit="this.loginButton.disabled=true; this.loginButton.innerText='Cargando…'; return true;">
          <?= csrf_field() ?>

          <div class="mb-3">
            <label for="email" class="form-label">Correo Electrónico</label>
            <input
              type="email"
              id="email"
              name="email"
              value="<?= old('email') ?>"
              class="form-control <?= (session('validation') && session('validation')->hasError('email')) ? 'is-invalid' : '' ?>"
              required
            >
            <?php if (session('validation') && session('validation')->hasError('email')): ?>
              <div class="invalid-feedback"><?= session('validation')->getError('email') ?></div>
            <?php endif; ?>
          </div>

          <div class="mb-3 text-end">
            <a href="<?= base_url('auth/forgot-password') ?>">¿Olvidaste tu contraseña?</a>
          </div>

          <div class="mb-4">
            <label for="password" class="form-label">Contraseña</label>
            <div class="input-group">
              <input
                type="password"
                id="password"
                name="password"
                class="form-control <?= (session('validation') && session('validation')->hasError('password')) ? 'is-invalid' : '' ?>"
                required
              >
              <?php if (session('validation') && session('validation')->hasError('password')): ?>
                <div class="invalid-feedback d-block"><?= session('validation')->getError('password') ?></div>
              <?php endif; ?>
            </div>
          </div>

          <div class="d-grid">
            <button type="submit" name="loginButton" class="btn btn-primary btn-block">
              Iniciar Sesión
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</main>


<?= $this->endSection() ?>
