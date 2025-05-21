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
    <?php if (isset($errors) && !empty($errors)): ?>
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
        <!-- Room List Section -->
        <div class="<?= $show_form ? 'col-lg-7' : 'col-lg-12' ?>">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Lista de Salas</h5>
                    <?php if (!$show_form): ?>
                        <a href="<?= base_url('admin/rooms/new') ?>" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Crear Nueva
                        </a>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <div style="max-height: 400px; overflow-y: auto;">
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
                                                            <input type="hidden" name="_method" value="DELETE"> <!-- For DELETE request -->
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

        <!-- Create/Edit Room Form Section -->
        <?php if ($show_form): ?>
        <div class="col-lg-5">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <?= isset($current_room) ? 'Editar Sala' : 'Crear Nueva Sala' ?>
                    </h5>
                    <a href="<?= base_url('admin/rooms') ?>" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-times"></i> Cerrar
                    </a>
                </div>
                <div class="card-body">
                    <form action="<?= isset($current_room) ? base_url('admin/rooms/update/' . $current_room['room_id']) : base_url('admin/rooms/create') ?>" method="post" enctype="multipart/form-data">
                        <?= csrf_field() ?>
                        <?php if (isset($current_room)): ?>
                            <input type="hidden" name="_method" value="PUT"> <!-- For UPDATE request -->
                        <?php endif; ?>

                        <div class="mb-3">
                            <label for="name" class="form-label">Nombre de la Sala <span class="text-danger">*</span></label>
                            <input type="text" class="form-control <?= isset($errors['name']) ? 'is-invalid' : '' ?>" id="name" name="name" value="<?= old('name', $current_room['name'] ?? '') ?>" required>
                            <?php if (isset($errors['name'])): ?>
                                <div class="invalid-feedback"><?= esc($errors['name']) ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Ubicación / Descripción</label>
                            <textarea class="form-control <?= isset($errors['description']) ? 'is-invalid' : '' ?>" id="description" name="description" rows="3"><?= old('description', $current_room['description'] ?? '') ?></textarea>
                            <?php if (isset($errors['description'])): ?>
                                <div class="invalid-feedback"><?= esc($errors['description']) ?></div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="capacity" class="form-label">Capacidad</label>
                                <input type="number" class="form-control <?= isset($errors['capacity']) ? 'is-invalid' : '' ?>" id="capacity" name="capacity" value="<?= old('capacity', $current_room['capacity'] ?? '') ?>" min="1">
                                <?php if (isset($errors['capacity'])): ?>
                                    <div class="invalid-feedback"><?= esc($errors['capacity']) ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="floor" class="form-label">Piso</label>
                                <input type="text" class="form-control <?= isset($errors['floor']) ? 'is-invalid' : '' ?>" id="floor" name="floor" value="<?= old('floor', $current_room['floor'] ?? '') ?>">
                                <?php if (isset($errors['floor'])): ?>
                                    <div class="invalid-feedback"><?= esc($errors['floor']) ?></div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="equipment" class="form-label">Equipamiento (ej: Proyector, Pizarra, WiFi)</label>
                            <textarea class="form-control" id="equipment" name="equipment" rows="2"><?= old('equipment', $current_room['equipment'] ?? '') ?></textarea>
                        </div>
                        
                        <div class="row">
                             <div class="col-md-6 mb-3">
                                <label for="price_hour" class="form-label">Precio por Hora (€)</label>
                                <input type="number" step="0.01" class="form-control <?= isset($errors['price_hour']) ? 'is-invalid' : '' ?>" id="price_hour" name="price_hour" value="<?= old('price_hour', $current_room['price_hour'] ?? '0.00') ?>">
                                <?php if (isset($errors['price_hour'])): ?>
                                    <div class="invalid-feedback"><?= esc($errors['price_hour']) ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="is_active" class="form-label">Estado <span class="text-danger">*</span></label>
                                <select class="form-select <?= isset($errors['is_active']) ? 'is-invalid' : '' ?>" id="is_active" name="is_active" required>
                                    <option value="1" <?= old('is_active', $current_room['is_active'] ?? '1') == '1' ? 'selected' : '' ?>>Activa</option>
                                    <option value="0" <?= old('is_active', $current_room['is_active'] ?? '') == '0' ? 'selected' : '' ?>>Inactiva</option>
                                </select>
                                <?php if (isset($errors['is_active'])): ?>
                                    <div class="invalid-feedback"><?= esc($errors['is_active']) ?></div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label">Imagen de la Sala</label>
                            <input type="file" class="form-control" id="image" name="image" accept="image/png, image/jpeg, image/gif">
                            <div class="form-text">Si no se selecciona una imagen, se usará una imagen por defecto.</div>
                            <?php if (isset($current_room) && $current_room['image'] && $current_room['image'] !== 'default_room.png'): ?>
                                <div class="mt-2">
                                    <small>Imagen actual:</small><br>
                                    <img src="<?= base_url('uploads/rooms/' . esc($current_room['image'])) ?>" alt="<?= esc($current_room['name']) ?>" style="max-height: 100px; border-radius: 0.25rem;">
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <?= isset($current_room) ? 'Actualizar Sala' : 'Guardar Sala' ?>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Custom CSS for table styling if needed -->
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