<?= $this->extend('layouts/auth') ?>

<?= $this->section('content') ?>
<div class="container min-vh-100 d-flex justify-content-center align-items-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header text-center">
                <h3>Has olvidado tu contraseña</h3>
            </div>

            <div class="card-body">
                <?php if (session()->has('error')): ?>
                    <div class="alert alert-danger">
                        <?= session('error') ?>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('auth/password-email') ?>" method="post">
                    <?= csrf_field() ?>

                    <div class="form-group mb-3">
                        <label for="email">Correo Electrónico</label>
                        <input type="email"
                            class="form-control <?= (session('validation') && session('validation')->hasError('email')) ? 'is-invalid' : '' ?>"
                            id="email" name="email" value="<?= old('email') ?>" required>
                        <?php if (session('validation') && session('validation')->hasError('email')): ?>
                            <div class="invalid-feedback">
                                <?= session('validation')->getError('email') ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">Enviar enlace</button>
                    </div>
                </form>

                <div class="mt-3 text-center">
                    <a href="<?= base_url() ?>" class="btn btn-link">Volver a inicio de sesión</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>