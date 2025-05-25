<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid mt-4">

    
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show col-md-8 mx-auto" role="alert">
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-coreui-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show col-md-8 mx-auto" role="alert">
            <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-coreui-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    
    
    <?php if (isset($errors) && !empty($errors) && is_array($errors)): ?>
        <div class="alert alert-danger col-md-8 mx-auto" role="alert">
            <h4 class="alert-heading">¡Error de Validación!</h4>
            <hr>
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header d-flex justify-content-between align-items-center bg-light">
                    <h5 class="card-title mb-0">
                        <?= isset($current_user) && $current_user ? 'Editar Usuario' : 'Crear Nuevo Usuario' ?>
                    </h5>
                </div>
                <div class="card-body">
                    <form action="<?= isset($current_user) && $current_user ? base_url('admin/users/update/' . $current_user['user_id']) : base_url('admin/users/create') ?>" method="post" enctype="multipart/form-data"
                    onsubmit="this.create_user.disabled=true; this.create_user.innerText='Cargando…'; return true;">
                        <?= csrf_field() ?>
                        <?php if (isset($current_user) && $current_user): ?>
                            <input type="hidden" name="_method" value="PUT">
                        <?php endif; ?>

                        <div class="row">
                            <!-- Datos Personales -->
                            <div class="col-md-6">
                                <h5 class="mb-3">Datos Personales</h5>
                                
                                <div class="mb-3">
                                    <label for="full_name" class="form-label">Nombre Completo</label>
                                    <input type="text" class="form-control <?= isset($errors['full_name']) ? 'is-invalid' : '' ?>" id="full_name" name="full_name" value="<?= old('full_name', $current_user['full_name'] ?? '') ?>">
                                    <?php if (isset($errors['full_name'])): ?><div class="invalid-feedback"><?= esc($errors['full_name']) ?></div><?php endif; ?>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="email" class="form-label">Correo Electrónico <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>" id="email" name="email" value="<?= old('email', $current_user['email'] ?? '') ?>" required>
                                    <?php if (isset($errors['email'])): ?><div class="invalid-feedback"><?= esc($errors['email']) ?></div><?php endif; ?>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="username" class="form-label">Nombre de Usuario <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control <?= isset($errors['username']) ? 'is-invalid' : '' ?>" id="username" name="username" value="<?= old('username', $current_user['username'] ?? '') ?>" required>
                                    <?php if (isset($errors['username'])): ?><div class="invalid-feedback"><?= esc($errors['username']) ?></div><?php endif; ?>
                                </div>
                            </div>
                            
                            <!-- Información de Cuenta -->
                            <div class="col-md-6">
                                <h5 class="mb-3">Información de Cuenta</h5>
                                
                                <?php if (isset($current_user) && $current_user): ?>
                                <div class="text-center mb-4">
                                    <img src="<?= base_url('uploads/avatars/' . esc($current_user['profile_image'] ?? 'default.png')) . '?v=' . time() ?>"
                                        class="rounded-circle border border-2 shadow-sm"
                                        style="width: 120px; height: 120px; object-fit: cover; transition: transform 0.3s;"
                                        onmouseover="this.style.transform='scale(1.05)';"
                                        onmouseout="this.style.transform='scale(1)';">
                                    
                                    <div class="mt-3">
                                        <label for="profile_image" class="form-label">Imagen de perfil</label>
                                        <input type="file" name="profile_image" id="profile_image" class="form-control" accept="image/*">
                                        <div class="form-text">Selecciona una imagen solo si deseas cambiarla</div>
                                    </div>
                                </div>
                                <?php endif; ?>
                                
                                <div class="mb-3">
                                    <label for="role_id" class="form-label">Rol <span class="text-danger">*</span></label>
                                    <select class="form-select <?= isset($errors['role_id']) ? 'is-invalid' : '' ?>" id="role_id" name="role_id" required>
                                        <?php if (!empty($roles)): ?>
                                            <?php foreach ($roles as $role): ?>
                                                <option value="<?= esc($role['role_id']) ?>" <?= $role['role_name'] == 'User' ? 'selected' : '' ?>>
                                                    <?= esc($role['role_name']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <option value="">No hay roles disponibles</option>
                                        <?php endif; ?>
                                    </select>
                                    <?php if (isset($errors['role_id'])): ?><div class="invalid-feedback"><?= esc($errors['role_id']) ?></div><?php endif; ?>
                                </div>

                                <div class="mb-3">
                                    <label for="password" class="form-label">Contraseña <?= !(isset($current_user) && $current_user) ? '<span class="text-danger">*</span>' : '(Opcional)' ?></label>
                                    <input type="password" class="form-control <?= isset($errors['password']) ? 'is-invalid' : '' ?>" id="password" name="password" <?= !(isset($current_user) && $current_user) ? 'required' : '' ?>>
                                    <?php if (isset($errors['password'])): ?><div class="invalid-feedback"><?= esc($errors['password']) ?></div><?php endif; ?>
                                    <?php if (isset($current_user) && $current_user): ?>
                                        <div class="form-text">Dejar en blanco para no cambiar la contraseña actual. Mínimo 6 caracteres.</div>
                                    <?php else: ?>
                                         <div class="form-text">Mínimo 6 caracteres.</div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid gap-2 mt-4">
                            <div class="row justify-content-center">
                                <div class="col-md-8 d-flex justify-content-center gap-3">
                                    <button name="create_user" type="submit" class="btn btn-primary btn-lg">
                                        <?= isset($current_user) && $current_user ? 'Actualizar Usuario' : 'Guardar Usuario' ?>
                                    </button>
                                    <a href="<?= base_url('admin/users') ?>" class="btn btn-outline-secondary btn-lg">
                                        Cancelar
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?> 