
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

    <?php
        // Recuperar datos de sesión si existen
        $old_input = session()->getFlashdata('old_input') ?? [];
        $validation = session('validation');
        $current_room = session()->getFlashdata('current_room') ?? ($current_room ?? []);
        $errors = [];
        if ($validation) {
            $errors = $validation->getErrors();
        } elseif (isset($errors) && !empty($errors)) {
            // Si vienen errores directos del controlador
            $errors = $errors;
        }
    ?>

    <?php if (!empty($errors)): ?>
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
                        <?= isset($current_room['room_id']) ? 'Editar Sala' : 'Crear Nueva Sala' ?>
                    </h5>
                </div>
                <div class="card-body">
                    <form action="<?= isset($current_room['room_id']) ? base_url('admin/rooms/update/' . $current_room['room_id']) : base_url('admin/rooms/create') ?>" method="post" enctype="multipart/form-data"
                    onsubmit="this.create_room.disabled=true; this.create_room.innerText='Cargando…'; return true;">
                        <?= csrf_field() ?>
                        <?php if (isset($current_room['room_id'])): ?>
                            <input type="hidden" name="_method" value="PUT">
                        <?php endif; ?>

                        <div class="mb-3">
                            <label for="name" class="form-label">Nombre de la Sala <span class="text-danger">*</span></label>
                            <input type="text" class="form-control <?= isset($errors['name']) ? 'is-invalid' : '' ?>" id="name" name="name" value="<?= old('name', $old_input['name'] ?? ($current_room['name'] ?? '')) ?>" required>
                            <?php if (isset($errors['name'])): ?>
                                <div class="invalid-feedback"><?= esc($errors['name']) ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Ubicación / Descripción</label>
                            <textarea class="form-control <?= isset($errors['description']) ? 'is-invalid' : '' ?>" id="description" name="description" rows="3"><?= old('description', $old_input['description'] ?? ($current_room['description'] ?? '')) ?></textarea>
                            <?php if (isset($errors['description'])): ?>
                                <div class="invalid-feedback"><?= esc($errors['description']) ?></div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="capacity" class="form-label">Capacidad</label>
                                <input type="number" class="form-control <?= isset($errors['capacity']) ? 'is-invalid' : '' ?>" id="capacity" name="capacity" value="<?= old('capacity', $old_input['capacity'] ?? ($current_room['capacity'] ?? '')) ?>" min="1">
                                <?php if (isset($errors['capacity'])): ?>
                                    <div class="invalid-feedback"><?= esc($errors['capacity']) ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="floor" class="form-label">Piso</label>
                                <input type="text" class="form-control <?= isset($errors['floor']) ? 'is-invalid' : '' ?>" id="floor" name="floor" value="<?= old('floor', $old_input['floor'] ?? ($current_room['floor'] ?? '')) ?>">
                                <?php if (isset($errors['floor'])): ?>
                                    <div class="invalid-feedback"><?= esc($errors['floor']) ?></div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="equipment" class="form-label">Equipamiento (ej: Proyector, Pizarra, WiFi)</label>
                            <textarea class="form-control" id="equipment" name="equipment" rows="2"><?= old('equipment', $old_input['equipment'] ?? ($current_room['equipment'] ?? '')) ?></textarea>
                        </div>
                        
                        <div class="row">
                             <div class="col-md-6 mb-3">
                                <label for="price_hour" class="form-label">Precio por Hora (€)</label>
                                <input type="number" step="0.01" class="form-control <?= isset($errors['price_hour']) ? 'is-invalid' : '' ?>" id="price_hour" name="price_hour" value="<?= old('price_hour', $old_input['price_hour'] ?? ($current_room['price_hour'] ?? '0.00')) ?>">
                                <?php if (isset($errors['price_hour'])): ?>
                                    <div class="invalid-feedback"><?= esc($errors['price_hour']) ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="is_active" class="form-label">Estado <span class="text-danger">*</span></label>
                                <select class="form-select <?= isset($errors['is_active']) ? 'is-invalid' : '' ?>" id="is_active" name="is_active" required>
                                    <option value="1" <?= old('is_active', $old_input['is_active'] ?? ($current_room['is_active'] ?? '1')) == '1' ? 'selected' : '' ?>>Activa</option>
                                    <option value="0" <?= old('is_active', $old_input['is_active'] ?? ($current_room['is_active'] ?? '')) == '0' ? 'selected' : '' ?>>Inactiva</option>
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
                            <?php if (isset($current_room['image']) && $current_room['image'] && $current_room['image'] !== 'default_room.png'): ?>
                                <div class="mt-2">
                                    <small>Imagen actual:</small><br>
                                    <img src="<?= base_url('uploads/rooms/' . esc($current_room['image'])) ?>" alt="<?= esc($current_room['name'] ?? '') ?>" style="max-height: 100px; border-radius: 0.25rem;">
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="d-grid gap-2">
                            <div class="row justify-content-center mt-4">
                                <div class="col-md-8 d-flex justify-content-center gap-3">
                                    <button name="create_room" type="submit" class="btn btn-primary btn-lg">
                                        <?= isset($current_room['room_id']) ? 'Actualizar Sala' : 'Guardar Sala' ?>
                                    </button>
                                    <a href="<?= base_url('admin/rooms') ?>" class="btn btn-outline-secondary btn-lg">
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