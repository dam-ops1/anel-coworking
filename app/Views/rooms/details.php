<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container py-4">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url('bookings') ?>">Reservas</a></li>
            <li class="breadcrumb-item active" aria-current="page">Detalles de sala</li>
        </ol>
    </nav>

    <?= session()->getFlashdata('error') ? '<div class="alert alert-danger">'.session()->getFlashdata('error').'</div>' : '' ?>
    
    <div class="row">
        <div class="col-md-7 mb-4">
            <?php if (!empty($room['image'])): ?>
                <img src="<?= base_url('uploads/rooms/' . $room['image']) ?>" 
                     class="img-fluid rounded shadow" alt="<?= esc($room['name']) ?>"
                     style="width: 100%; height: 350px; object-fit: cover;">
            <?php else: ?>
                <div class="bg-light text-center py-5 rounded shadow">
                    <i class="bi bi-building fs-1"></i>
                    <p class="mt-3">No hay imagen disponible</p>
                </div>
            <?php endif; ?>
            
            <div class="mt-4">
                <h1 class="h2"><?= esc($room['name']) ?></h1>
                <div class="d-flex flex-wrap gap-3 mb-3">
                    <span class="badge bg-secondary">
                        <i class="bi bi-people me-1"></i> Capacidad: <?= $room['capacity'] ?> personas
                    </span>
                    <span class="badge bg-secondary">
                        <i class="bi bi-geo-alt me-1"></i> <?= esc($room['floor']) ?>
                    </span>
                    <span class="badge bg-secondary">
                        <i class="bi bi-cash me-1"></i> €<?= number_format($room['price_hour'], 2) ?> / hora
                    </span>
                </div>
                
                <h5 class="mt-4">Descripción</h5>
                <p><?= esc($room['description']) ?></p>
                
                <h5 class="mt-4">Equipamiento</h5>
                <?php if (!empty($room['equipment'])): ?>
                    <ul class="list-group list-group-flush">
                        <?php foreach (explode(', ', $room['equipment']) as $item): ?>
                            <li class="list-group-item bg-light"><i class="bi bi-check-circle-fill text-success me-2"></i><?= esc($item) ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p class="text-muted">No hay información sobre el equipamiento.</p>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="col-md-5">
            <div class="card shadow-sm">
                <div class="card-header bg-danger text-white">
                    <h5 class="card-title mb-0">Reservar esta sala</h5>
                </div>
                <div class="card-body">
                    <?php if (session()->get('isLoggedIn')): ?>
                        <?php if ($start_datetime && $end_datetime): ?>
                            <div class="mb-4">
                                <h6>Período seleccionado:</h6>
                                <p class="mb-0">
                                    <i class="bi bi-calendar3 me-1"></i> 
                                    <strong>Desde:</strong> <?= date('d/m/Y H:i', strtotime($start_datetime)) ?><br>
                                    <i class="bi bi-calendar3 me-1"></i> 
                                    <strong>Hasta:</strong> <?= date('d/m/Y H:i', strtotime($end_datetime)) ?>
                                </p>
                                
                                <?php
                                // Calcular duración y precio
                                $durationHours = (strtotime($end_datetime) - strtotime($start_datetime)) / 3600;
                                $totalPrice = $room['price_hour'] * $durationHours;
                                ?>
                                
                                <div class="alert alert-light mt-3">
                                    <p class="mb-1"><strong>Duración:</strong> <?= number_format($durationHours, 1) ?> horas</p>
                                    <p class="mb-0"><strong>Precio total:</strong> €<?= number_format($totalPrice, 2) ?></p>
                                </div>
                            </div>
                            
                            <?php if ($is_available): ?>
                                <form action="<?= base_url('bookings/create') ?>" method="post">
                                    <input type="hidden" name="room_id" value="<?= $room['room_id'] ?>">
                                    
                                    <div class="mb-3">
                                        <label for="notes" class="form-label">Notas adicionales (opcional)</label>
                                        <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Añade cualquier información adicional sobre tu reserva"></textarea>
                                    </div>
                                    
                                    <div class="d-grid gap-2">
                                        <button type="submit" class="btn btn-danger">Confirmar reserva</button>
                                        <a href="<?= base_url('rooms/available') ?>" class="btn btn-outline-secondary">Volver a las salas disponibles</a>
                                    </div>
                                </form>
                            <?php else: ?>
                                <div class="alert alert-danger">
                                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                    Esta sala no está disponible para el horario seleccionado.
                                </div>
                                <div class="d-grid">
                                    <a href="<?= base_url('bookings') ?>" class="btn btn-outline-danger">
                                        Seleccionar otro horario
                                    </a>
                                </div>
                            <?php endif; ?>
                        <?php else: ?>
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle-fill me-2"></i>
                                Para reservar esta sala, primero debes seleccionar una fecha y hora.
                            </div>
                            <div class="d-grid">
                                <a href="<?= base_url('bookings') ?>" class="btn btn-danger">
                                    Seleccionar fecha y hora
                                </a>
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            Debes iniciar sesión para realizar una reserva.
                        </div>
                        <div class="d-grid gap-2">
                            <a href="<?= base_url('login') ?>" class="btn btn-danger">Iniciar sesión</a>
                            <a href="<?= base_url('register') ?>" class="btn btn-outline-secondary">Registrarme</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?> 