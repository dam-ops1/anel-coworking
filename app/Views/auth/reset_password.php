<?= $this->extend('layouts/auth') ?>

<?= $this->section('content') ?>
<div class="container min-vh-100 d-flex justify-content-center align-items-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header text-center">
                <h3>Restablecer Contraseña</h3>
            </div>
            <div class="card-body">
                <?php if (session()->has('error')): ?>
                    <div class="alert alert-danger">
                        <?= session('error') ?>
                    </div>
                <?php endif; ?>

                <?php if (session()->has('success')): ?>
                    <div class="alert alert-success">
                        <?= session('success') ?>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('auth/reset-password') ?>" method="post">
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

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">Restablecer Contraseña</button>
                    </div>
                </form>

                <div class="mt-3 text-center">
                    <a href="<?= base_url('login') ?>" class="btn btn-link">Volver a inicio de sesión</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>