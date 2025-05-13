<?= $this->extend('layouts/auth') ?>
<?= $this->section('content') ?>

<main class="c-app d-flex align-items-center" style="min-height:100vh;">
  <div class="container">
    <div class="d-flex flex-md-nowrap bg-white mx-auto" style="
          border-radius:.375rem;
          overflow:hidden;
          min-height:600px;
          max-width:1100px;
        ">
      
      <!-- Logo solo visible en LG+ -->
      <div class="d-none d-lg-flex justify-content-center align-items-center p-4" style="flex:0 0 40%;">
        <img src="<?= base_url('images/icn_anel_logo.png') ?>" alt="Anel Logo"
             class="img-fluid" style="max-width:115%; height:auto;">
      </div>

      <!-- Línea divisoria: visible en LG+ -->
      <div class="d-none d-lg-flex col-auto justify-content-center">
        <div class="vr align-self-center" style="height:600px; border-color:#dee2e6;"></div>
      </div>

      <!-- Formulario -->
      <div class="p-5 d-flex flex-column justify-content-center" style="flex:1;">
        <h4 class="text-center mb-4">Crear Cuenta</h4>

        <!-- mensajes -->
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>
        <?php if ($validation = session('validation')): ?>
            <div class="alert alert-danger"><?= $validation->listErrors() ?></div>
        <?php endif; ?>

        <form action="<?= base_url('register') ?>" method="POST"
              onsubmit="this.querySelector('button').disabled=true; this.querySelector('button').innerText='Cargando…';">
          <?= csrf_field() ?>

          <div class="mb-3">
            <label for="full_name" class="form-label">Nombre Completo</label>
            <input type="text" id="full_name" name="full_name" value="<?= old('full_name') ?>"
                   class="form-control <?= $validation && $validation->hasError('full_name') ? 'is-invalid' : '' ?>"
                   required>
            <?php if ($validation && $validation->hasError('full_name')): ?>
              <div class="invalid-feedback"><?= $validation->getError('full_name') ?></div>
            <?php endif; ?>
          </div>

          <div class="row gx-2 gy-3">
            <div class="col-6">
              <label for="username" class="form-label">Usuario</label>
              <input type="text" id="username" name="username" value="<?= old('username') ?>"
                     class="form-control <?= $validation && $validation->hasError('username') ? 'is-invalid' : '' ?>"
                     required>
              <?php if ($validation && $validation->hasError('username')): ?>
                <div class="invalid-feedback"><?= $validation->getError('username') ?></div>
              <?php endif; ?>
            </div>
            <div class="col-6">
              <label for="phone" class="form-label">Teléfono</label>
              <input type="tel" id="phone" name="phone" value="<?= old('phone') ?>"
                     class="form-control <?= $validation && $validation->hasError('phone') ? 'is-invalid' : '' ?>"
                     required>
              <?php if ($validation && $validation->hasError('phone')): ?>
                <div class="invalid-feedback"><?= $validation->getError('phone') ?></div>
              <?php endif; ?>
            </div>
          </div>

          <div class="row gx-2 gy-3 mt-3">
            <div class="col-6">
              <label for="password" class="form-label">Contraseña</label>
              <div class="input-group">
                <input type="password" id="password" name="password"
                       class="form-control <?= $validation && $validation->hasError('password') ? 'is-invalid' : '' ?>"
                       required>
              </div>
              <?php if ($validation && $validation->hasError('password')): ?>
                <div class="invalid-feedback d-block"><?= $validation->getError('password') ?></div>
              <?php endif; ?>
            </div>
            <div class="col-6">
              <label for="password_confirm" class="form-label">Confirmar Contraseña</label>
              <div class="input-group">
                <input type="password" id="password_confirm" name="password_confirm"
                       class="form-control <?= $validation && $validation->hasError('password_confirm') ? 'is-invalid' : '' ?>"
                       required>
              </div>
              <?php if ($validation && $validation->hasError('password_confirm')): ?>
                <div class="invalid-feedback d-block"><?= $validation->getError('password_confirm') ?></div>
              <?php endif; ?>
            </div>
          </div>

          <div class="mb-3 mt-3">
            <label for="email" class="form-label">Correo Electrónico</label>
            <input type="email" id="email" name="email" value="<?= old('email') ?>"
                   class="form-control <?= $validation && $validation->hasError('email') ? 'is-invalid' : '' ?>"
                   required>
            <?php if ($validation && $validation->hasError('email')): ?>
              <div class="invalid-feedback"><?= $validation->getError('email') ?></div>
            <?php endif; ?>
          </div>

          <div class="d-grid mt-4">
            <button type="submit" name="registerButton" class="btn btn-primary">
              Registrarse
            </button>
          </div>
        </form>

        <hr class="my-4">
        <div class="text-center">
          ¿Ya tienes una cuenta? <a href="<?= base_url('login') ?>">Iniciar Sesión</a>
        </div>
      </div>
    </div>
  </div>
</main>

<?= $this->endSection() ?>
