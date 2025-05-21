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
            <h5 class="card-title mb-0">Lista de Salas</h5>
            <a href="<?= base_url('admin/rooms/new') ?>" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Crear Nueva
            </a>
        </div>
        <div class="card-body">
            <div style="max-height: 600px; overflow-y: auto;">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Ubicación</th>
                            <th scope="col">Estado</th>
                            <th scope="col">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($rooms)): ?>
                            <?php foreach ($rooms as $index => $room): ?>
                                <tr>
                                    <th scope="row"><?= $index + 1 ?></th>
                                    <td><?= esc($room['name']) ?></td>
                                    <td><?= esc($room['description']) ?></td>
                                    <td>
                                        <?php if ($room['is_active']): ?>
                                            <span class="badge bg-success">Activa</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Inactiva</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="actionDropdown<?= $room['room_id'] ?>" data-coreui-toggle="dropdown" aria-expanded="false">
                                                Acción
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="actionDropdown<?= $room['room_id'] ?>">
                                                <li><a class="dropdown-item" href="<?= base_url('admin/rooms/edit/' . $room['room_id']) ?>">Editar</a></li>
                                                <li>
                                                    <form action="<?= base_url('admin/rooms/delete/' . $room['room_id']) ?>" method="post" onsubmit="return confirm('¿Estás seguro de que quieres eliminar esta sala?');">
                                                        <?= csrf_field() ?>
                                                        <input type="hidden" name="_method" value="DELETE">
                                                        <button type="submit" class="dropdown-item text-danger">Eliminar</button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center">No hay salas disponibles.</td>
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