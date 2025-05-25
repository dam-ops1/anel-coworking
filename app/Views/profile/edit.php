<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<main class="container py-5">
  <div class="card shadow-lg p-4 mx-auto" style="max-width: 600px;">
    <h3 class="mb-4">Editar Perfil</h3>

    <?php if ($err = session()->getFlashdata('error')): ?>
      <div class="alert alert-danger"><?= esc($err) ?></div>
    <?php endif; ?>
    <?php if ($msg = session()->getFlashdata('success')): ?>
      <div class="alert alert-success"><?= esc($msg) ?></div>
    <?php endif; ?>

    <form action="<?= base_url('profile/update') ?>" method="post" enctype="multipart/form-data"
    onsubmit="this.update_profile.disabled=true; this.update_profile.innerText='Cargando…'; return true;">
      <?= csrf_field() ?>
      
      <?php
        $sessionImage = session()->get('profile_image');
        $profileImg = !empty($sessionImage) ? $sessionImage : ($user['profile_image'] ?? 'default.png');
      ?>
      <div class="text-center mb-4">
        <img src="<?= base_url('uploads/avatars/' . esc($profileImg)) . '?v=' . time() ?>"
            class="rounded-circle border border-2 shadow-sm"
            style="width: 120px; height: 120px; object-fit: cover; transition: transform 0.3s;"
            onmouseover="this.style.transform='scale(1.05)';"
            onmouseout="this.style.transform='scale(1)';">
        
        <div class="mt-3 mb-4">
          <label for="profile_image" class="form-label">Imagen de perfil</label>
          <input type="file" name="profile_image" id="profile_image" class="form-control" accept="image/*">
          <div class="form-text">Selecciona una imagen solo si deseas cambiarla</div>
        </div>
      </div>

      <div class="mb-3">
        <label for="full_name" class="form-label">Nombre completo</label>
        <input
          type="text"
          class="form-control <?= (session('validation') && session('validation')->hasError('full_name')) ? 'is-invalid' : '' ?>"
          id="full_name"
          name="full_name"
          value="<?= old('full_name', $user['full_name']) ?>"
          required
        >
        <?php if (session('validation') && session('validation')->hasError('full_name')): ?>
          <div class="invalid-feedback"><?= session('validation')->getError('full_name') ?></div>
        <?php endif; ?>
      </div>
      <div class="mb-3">
        <label for="email" class="form-label">Correo electrónico</label>
        <input
          type="email"
          class="form-control <?= (session('validation') && session('validation')->hasError('email')) ? 'is-invalid' : '' ?>"
          id="email"
          name="email"
          value="<?= old('email', $user['email']) ?>"
          required
        >
        <?php if (session('validation') && session('validation')->hasError('email')): ?>
          <div class="invalid-feedback"><?= session('validation')->getError('email') ?></div>
        <?php endif; ?>
      </div>
      <div class="mb-3">
        <label for="phone" class="form-label">Teléfono</label>
        <input
          type="text"
          class="form-control <?= (session('validation') && session('validation')->hasError('phone')) ? 'is-invalid' : '' ?>"
          id="phone"
          name="phone"
          value="<?= old('phone', $user['phone']) ?>"
        >
        <?php if (session('validation') && session('validation')->hasError('phone')): ?>
          <div class="invalid-feedback"><?= session('validation')->getError('phone') ?></div>
        <?php endif; ?>
      </div>
      <button name="update_profile" type="submit" class="btn btn-primary">Guardar Cambios</button>
      <a href="<?= base_url('profile') ?>" class="btn btn-secondary ms-2">Cancelar</a>
    </form>
  </div>
</main>

<?= $this->endSection() ?>