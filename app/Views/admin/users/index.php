<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid mt-4">

    <!-- Session Messages -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-coreui-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-coreui-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    
    <!-- Validation Errors -->
    <?php if (isset($errors) && !empty($errors) && is_array($errors)): ?>
        <div class="alert alert-danger" role="alert">
            <h4 class="alert-heading">¡Error de Validación!</h4>
            <hr>
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="row">
        <!-- User List Section -->
        <div class="<?= $show_form ? 'col-lg-7' : 'col-lg-12' ?>">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Lista de Usuarios</h5>
                    <?php if (!$show_form): ?>
                        <a href="<?= base_url('admin/users/new') ?>" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Crear Nuevo Usuario
                        </a>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <div style="max-height: 400px; overflow-y: auto;">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Nombre Completo</th>
                                <th scope="col">Username</th>
                                <th scope="col">Email</th>
                                <th scope="col">Rol</th>
                                <th scope="col">Estado</th>
                                <th scope="col">Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($users)): ?>
                                <?php foreach ($users as $index => $user): ?>
                                    <tr>
                                        <th scope="row"><?= $index + 1 ?></th>
                                        <td><?= esc($user['full_name'] ?? 'N/A') ?></td>
                                        <td><?= esc($user['username']) ?></td>
                                        <td><?= esc($user['email']) ?></td>
                                        <td><?= esc($user['role_name'] ?? 'N/A') ?></td> <!-- From JOIN in controller -->
                                        <td>
                                            <?php if ($user['is_active']): ?>
                                                <span class="badge bg-success">Activo</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger">Inactivo</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="actionDropdown<?= $user['user_id'] ?>" data-coreui-toggle="dropdown" aria-expanded="false">
                                                    Acción
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="actionDropdown<?= $user['user_id'] ?>">
                                                    <li><a class="dropdown-item" href="<?= base_url('admin/users/edit/' . $user['user_id']) ?>">Editar</a></li>
                                                    <li>
                                                        <?php if (session()->get('user_id') != $user['user_id']): // Prevent delete button for self ?>
                                                        <form action="<?= base_url('admin/users/delete/' . $user['user_id']) ?>" method="post" onsubmit="return confirm('¿Estás seguro de que quieres eliminar este usuario? Esta acción no se puede deshacer.');">
                                                            <?= csrf_field() ?>
                                                            <input type="hidden" name="_method" value="DELETE">
                                                            <button type="submit" class="dropdown-item text-danger">Eliminar</button>
                                                        </form>
                                                        <?php else: ?>
                                                            <span class="dropdown-item text-muted">Eliminar (No disponible)</span>
                                                        <?php endif; ?>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center">No hay usuarios registrados.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            </div>
        </div>

        <!-- Create/Edit User Form Section -->
        <?php if ($show_form): ?>
        <div class="col-lg-5">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <?= isset($current_user) && $current_user ? 'Editar Usuario' : 'Crear Nuevo Usuario' ?>
                    </h5>
                    <a href="<?= base_url('admin/users') ?>" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-times"></i> Cerrar
                    </a>
                </div>
                <div class="card-body">
                    <form action="<?= isset($current_user) && $current_user ? base_url('admin/users/update/' . $current_user['user_id']) : base_url('admin/users/create') ?>" method="post">
                        <?= csrf_field() ?>
                        <?php if (isset($current_user) && $current_user): ?>
                            <input type="hidden" name="_method" value="PUT">
                        <?php endif; ?>

                        <div class="mb-3">
                            <label for="full_name" class="form-label">Nombre Completo</label>
                            <input type="text" class="form-control <?= isset($errors['full_name']) ? 'is-invalid' : '' ?>" id="full_name" name="full_name" value="<?= old('full_name', $current_user['full_name'] ?? '') ?>">
                            <?php if (isset($errors['full_name'])): ?><div class="invalid-feedback"><?= esc($errors['full_name']) ?></div><?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label for="username" class="form-label">Nombre de Usuario <span class="text-danger">*</span></label>
                            <input type="text" class="form-control <?= isset($errors['username']) ? 'is-invalid' : '' ?>" id="username" name="username" value="<?= old('username', $current_user['username'] ?? '') ?>" required>
                            <?php if (isset($errors['username'])): ?><div class="invalid-feedback"><?= esc($errors['username']) ?></div><?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Correo Electrónico <span class="text-danger">*</span></label>
                            <input type="email" class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>" id="email" name="email" value="<?= old('email', $current_user['email'] ?? '') ?>" required>
                            <?php if (isset($errors['email'])): ?><div class="invalid-feedback"><?= esc($errors['email']) ?></div><?php endif; ?>
                        </div>
                        
                        <div class="mb-3">
                            <label for="role_id" class="form-label">Rol <span class="text-danger">*</span></label>
                            <select class="form-select <?= isset($errors['role_id']) ? 'is-invalid' : '' ?>" id="role_id" name="role_id" required>
                                <?php if (!empty($roles)): ?>
                                    <?php foreach ($roles as $role): ?>
                                        <option value="<?= esc($role['role_id']) ?>" <?= old('role_id', $current_user['role_id'] ?? '') == $role['role_id'] ? 'selected' : '' ?>>
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
                            <label for="password" class="form-label">Contraseña <?= !(isset($current_user) && $current_user) ? '<span class="text-danger">*</span>' : '(Opcional - dejar en blanco para no cambiar)' ?></label>
                            <input type="password" class="form-control <?= isset($errors['password']) ? 'is-invalid' : '' ?>" id="password" name="password" <?= !(isset($current_user) && $current_user) ? 'required' : '' ?>>
                            <?php if (isset($errors['password'])): ?><div class="invalid-feedback"><?= esc($errors['password']) ?></div><?php endif; ?>
                            <?php if (isset($current_user) && $current_user): ?>
                                <div class="form-text">Dejar en blanco para no cambiar la contraseña actual. Mínimo 8 caracteres si se establece una nueva.</div>
                            <?php else: ?>
                                 <div class="form-text">Mínimo 8 caracteres.</div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label for="is_active" class="form-label">Estado <span class="text-danger">*</span></label>
                            <select class="form-select <?= isset($errors['is_active']) ? 'is-invalid' : '' ?>" id="is_active" name="is_active" required>
                                <option value="1" <?= old('is_active', $current_user['is_active'] ?? '1') == '1' ? 'selected' : '' ?>>Activo</option>
                                <option value="0" <?= old('is_active', $current_user['is_active'] ?? '') == '0' ? 'selected' : '' ?>>Inactivo</option>
                            </select>
                            <?php if (isset($errors['is_active'])): ?><div class="invalid-feedback"><?= esc($errors['is_active']) ?></div><?php endif; ?>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <?= isset($current_user) && $current_user ? 'Actualizar Usuario' : 'Guardar Usuario' ?>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Custom CSS for table styling if needed (can be same as rooms) -->
<style>
    .table th, .table td {
        vertical-align: middle;
    }
    .badge {
        font-size: 0.85em;
    }
    .card-header .btn-sm {
        font-size: 0.8rem; /* Smaller button in header */
    }
</style>

<?= $this->endSection() ?> 