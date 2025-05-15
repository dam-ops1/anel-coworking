<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>


<main class="container py-5">
  <div class="card shadow-sm p-4">
    <div class="row g-4">
      <div class="col-md-3 text-center">
        <img src="<?= base_url('uploads/avatars/' . esc($user['profile_image'] ?? 'default.png')) ?>" class="rounded-circle" style="width:100px; height:100px;">
      </div>
      <div class="col-md-9">
        <h4 class="mb-0"><?= esc($user['full_name']) ?></h4>
        <p class="text-muted mb-2">Usuario Registrado</p>
        <button class="btn btn-dark btn-sm me-2">Editar Perfil</button>
        <button class="btn btn-outline-secondary btn-sm">Cambiar Contraseña</button>
      </div>
    </div>

    <hr class="my-4">

    <div class="row g-4">
      <div class="col-md-6">
        <h5 class="mb-3">Información Personal</h5>
        <div class="mb-2"><i class="bi bi-envelope me-2"></i> <?= esc($user['email']) ?></div>
        <div class="mb-2"><i class="bi bi-telephone me-2"></i> <?= esc($user['phone']) ?></div>
        <div class="mb-2"><i class="bi bi-calendar-check me-2"></i> Último acceso: <?= esc($user['last_login']) ?></div>
      </div>

      <div class="col-md-6">
        <h5 class="mb-3">Estado</h5>
        <div class="mb-2"><i class="bi bi-shield-check me-2"></i> Verificado: <?= $user['email_verified'] ? 'Sí' : 'No' ?></div>
        <div class="mb-2"><i class="bi bi-toggle-on me-2"></i> Activo: <?= $user['is_active'] ? 'Sí' : 'No' ?></div>
        <div class="mb-2"><i class="bi bi-calendar me-2"></i> Registro: <?= esc($user['created_at']) ?></div>
      </div>
    </div>
  </div>
</main>

<?= $this->endSection() ?>
