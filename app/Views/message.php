<?= $this->extend('layouts/auth') ?>

<?= $this->section('content') ?>
<div class="container min-vh-100 d-flex justify-content-center align-items-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header text-center">
                <h3><?= isset($title) ? $title : 'Título no definido.' ?></h3>
            </div>
            <div class="card-body text-center">
                <p>
                    <?= isset($message) 
                        ? $message 
                        : 'Mensaje no definido.' 
                    ?>
                </p>
                <div class="d-grid gap-2">
                    <a href="<?= base_url($view) ?>" class="btn btn-primary">
                        <?php echo 'Ir a ' . $text ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
