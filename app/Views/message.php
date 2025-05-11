<?= $this->extend('layouts/auth') ?>
<?= $this->section('content') ?>

<main class="c-app d-flex align-items-center" style="min-height:100vh;">
  <div class="container d-flex justify-content-center align-items-center min-vh-100">
    <!-- Ahora ocupa más ancho en pantallas grandes -->
    <div class="col-12 col-sm-10 col-md-8 col-lg-6">
      <div class="card shadow-sm rounded-3 border-0" style="overflow: hidden;">
        <!-- Cabecera, texto alineado a la izquierda -->
        <div class="card-header bg-white py-3 px-4">
          <h5 class="mb-0 text-start">
            <?= esc($title ?? 'Título no definido.') ?>
          </h5>
        </div>

        <hr class="my-0">

        <!-- Mensaje, también alineado a la izquierda -->
        <div class="card-body py-4 px-4">
          <p class="mb-0 text-start">
            <?= esc($message ?? 'Mensaje no definido.') ?>
          </p>
        </div>

        <hr class="my-0">

        <!-- Botón full-width -->
        <div class="card-body py-3 px-4">
          <a 
            href="<?= base_url($view) ?>" 
            class="btn btn-primary w-100"
          >
            Ir a <?= esc($text ?? '') ?>
          </a>
        </div>
      </div>
    </div>
  </div>
</main>

<?= $this->endSection() ?>
