<?= $this->extend('layouts/auth') ?>

<?= $this->section('content') ?>

    <div class="container">
        <div class="register-container bg-white">
            <h2 class="text-center mb-4">Crear Cuenta</h2>

            <div class="card-body">
                <?php if (session()->has('error')): ?>
                    <div class="alert alert-danger">
                        <?= session('error') ?>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('register') ?>" method="POST" onsubmit="registerButton.disabled = true; return true;">
                    <?= csrf_field() ?>

                    <div class="form-group">
                        <label for="username">Nombre de Usuario</label>
                        <input type="text"
                            class="form-control <?= (session('validation') && session('validation')->hasError('username')) ? 'is-invalid' : '' ?>"
                            id="username" name="username" value="<?= old('username') ?>" required>
                        <?php if (session('validation') && session('validation')->hasError('username')): ?>
                            <div class="invalid-feedback">
                                <?= session('validation')->getError('username') ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
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

                    <div class="form-group">
                        <label for="password">Contraseña</label>
                        <input type="password"
                            class="form-control <?= (session('validation') && session('validation')->hasError('password')) ? 'is-invalid' : '' ?>"
                            id="password" name="password" required>
                        <?php if (session('validation') && session('validation')->hasError('password')): ?>
                            <div class="invalid-feedback">
                                <?= session('validation')->getError('password') ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="password_confirm">Confirmar Contraseña</label>
                        <input type="password"
                            class="form-control <?= (session('validation') && session('validation')->hasError('password_confirm')) ? 'is-invalid' : '' ?>"
                            id="password_confirm" name="password_confirm" required>
                        <?php if (session('validation') && session('validation')->hasError('password_confirm')): ?>
                            <div class="invalid-feedback">
                                <?= session('validation')->getError('password_confirm') ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="full_name">Nombre Completo</label>
                        <input type="text"
                            class="form-control <?= (session('validation') && session('validation')->hasError('full_name')) ? 'is-invalid' : '' ?>"
                            id="full_name" name="full_name" value="<?= old('full_name') ?>" required>
                        <?php if (session('validation') && session('validation')->hasError('full_name')): ?>
                            <div class="invalid-feedback">
                                <?= session('validation')->getError('full_name') ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="phone">Teléfono</label>
                        <input type="tel"
                            class="form-control <?= (session('validation') && session('validation')->hasError('phone')) ? 'is-invalid' : '' ?>"
                            id="phone" name="phone" value="<?= old('phone') ?>" required>
                        <?php if (session('validation') && session('validation')->hasError('phone')): ?>
                            <div class="invalid-feedback">
                                <?= session('validation')->getError('phone') ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="company_name">Compañía</label>
                        <input type="text"
                            class="form-control <?= (session('validation') && session('validation')->hasError('company_name')) ? 'is-invalid' : '' ?>"
                            id="company_name" name="company_name" value="<?= old('company_name') ?>" required>
                        <?php if (session('validation') && session('validation')->hasError('company_name')): ?>
                            <div class="invalid-feedback">
                                <?= session('validation')->getError('company_name') ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 mt-3" name="registerButton">Registrarse</button>

                    <div class="text-center mt-3">
                        ¿Ya tienes una cuenta? <a href="<?= base_url('login') ?>">Iniciar Sesión</a>
                    </div>
                </form>
            </div>
        </div>
<?= $this->endSection() ?>