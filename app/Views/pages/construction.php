<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="construction-container">
    <div class="construction-icon">
        <i class="fas fa-hard-hat"></i>
    </div>
    
    <h1 class="construction-title">Página en construcción</h1>
    
    <p class="construction-text">
        Estamos trabajando para mejorar tu experiencia. Esta sección estará disponible próximamente.
        Agradecemos tu paciencia mientras completamos este desarrollo.
    </p>
    
    <a href="<?= base_url('/dashboard') ?>" class="btn btn-danger btn-lg return-link mt-4">
        Volver al inicio
    </a>
</div>

<?= $this->endSection() ?> 