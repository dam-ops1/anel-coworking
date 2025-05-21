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
    
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Lista de Usuarios</h5>
            <a href="<?= base_url('admin/users/new') ?>" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Crear Nuevo Usuario
            </a>
        </div>
        <div class="card-body">
            <div style="max-height: 600px; overflow-y: auto;">
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
                                    <td><?= esc($user['role_name'] ?? 'N/A') ?></td>
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

<!-- Custom CSS for table styling -->
<style>
    .table th, .table td {
        vertical-align: middle;
    }
    .badge {
        font-size: 0.85em;
    }
    .card-header .btn-sm {
        font-size: 0.8rem;
    }
</style>

<?= $this->endSection() ?> 