<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<main class="container py-5">

  <?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
  <?php elseif (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
  <?php endif; ?>

  <div class="card shadow-lg p-4 mx-auto" style="max-width: 900px;">
    <div class="d-flex flex-column flex-md-row align-items-center">
      
      <!-- Imagen de perfil -->
      <?php
        $sessionImage = session()->get('profile_image');
        $profileImg = !empty($sessionImage) ? $sessionImage : ($user['profile_image'] ?? 'default.png');
      ?>
      <div class="text-center me-md-4 mb-4 mb-md-0">
        <img src="<?= base_url('uploads/avatars/' . esc($profileImg)) . '?v=' . time() ?>"
            class="rounded-circle border border-2 shadow-sm"
            style="width: 120px; height: 120px; object-fit: cover; transition: transform 0.3s;"
            onmouseover="this.style.transform='scale(1.05)';"
            onmouseout="this.style.transform='scale(1)';">
      </div>

      <!-- Datos principales -->
      <div class="flex-grow-1">
        <h3 class="mb-1"><?= esc($user['full_name']) ?></h3>
        <p class="text-muted">Usuario registrado</p>
        <div class="d-flex flex-wrap gap-2 mt-2">
          <a href="<?= base_url('profile/edit') ?>" class="btn btn-dark btn-sm">Editar Perfil</a>
        </div>
      </div>
    </div>

    <hr class="my-4">

    <div class="row">
      <!-- Información personal -->
      <div class="col-md-6 mb-4">
        <h5 class="mb-3"><i class="bi bi-person me-2"></i>Información Personal</h5>
        <ul class="list-unstyled">
          <li class="mb-2"><i class="bi bi-envelope me-2"></i><?= esc($user['email']) ?></li>
          <li class="mb-2"><i class="bi bi-telephone me-2"></i><?= esc($user['phone']) ?></li>
          <li class="mb-2"><i class="bi bi-calendar-check me-2"></i>Último acceso: <?= esc($user['last_login']) ?></li>
        </ul>
      </div>

      <!-- Estado de cuenta -->
      <div class="col-md-6 mb-4">
        <h5 class="mb-3"><i class="bi bi-activity me-2"></i>Estado de Cuenta</h5>
        <ul class="list-unstyled">
          <li class="mb-2"><i class="bi bi-shield-check me-2"></i>Verificado: <?= $user['email_verified'] ? 'Sí' : 'No' ?></li>
          <li class="mb-2"><i class="bi bi-toggle-on me-2"></i>Activo: <?= $user['is_active'] ? 'Sí' : 'No' ?></li>
          <li class="mb-2"><i class="bi bi-calendar me-2"></i>Registrado desde: <?= esc($user['created_at']) ?></li>
        </ul>
      </div>
    </div>
  </div>
</main>

<?= $this->endSection() ?>
