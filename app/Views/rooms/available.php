<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container py-4">
    <h1 class="mb-2">Salas disponibles</h1>
    
    <div class="mb-4">
        <div class="alert alert-light border">
            <p class="mb-0"><strong>Período seleccionado:</strong></p>
            <p class="mb-0">
                Desde: <?= date('d/m/Y H:i', strtotime($start_datetime)) ?><br>
                Hasta: <?= date('d/m/Y H:i', strtotime($end_datetime)) ?>
            </p>
            <a href="<?= base_url('bookings') ?>" class="btn btn-sm btn-outline-secondary mt-2">
                <i class="bi bi-arrow-left"></i> Cambiar fechas
            </a>
        </div>
    </div>
    
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        <?php foreach ($rooms as $room): ?>
            <div class="col">
                <div class="card h-100 shadow-sm">
                    <?php if (!empty($room['image'])): ?>
                        <img src="<?= base_url('uploads/rooms/' . $room['image']) ?>" 
                             class="card-img-top" alt="<?= esc($room['name']) ?>"
                             style="height: 200px; object-fit: cover;">
                    <?php else: ?>
                        <div class="bg-light text-center py-5">
                            <i class="bi bi-building fs-1"></i>
                        </div>
                    <?php endif; ?>
                    
                    <div class="card-body">
                        <h5 class="card-title"><?= esc($room['name']) ?></h5>
                        <div class="mb-2">
                            <span class="badge bg-success">Disponible</span>
                        </div>
                        <p class="card-text text-muted">
                            <i class="bi bi-people me-1"></i> Capacidad: <?= $room['capacity'] ?> personas<br>
                            <i class="bi bi-geo-alt me-1"></i> <?= esc($room['floor']) ?>
                        </p>
                        <p class="card-text small"><?= esc(substr($room['description'], 0, 100)) ?>...</p>
                        
                        <?php 
                        // Calcular precio total
                        $durationHours = (strtotime($end_datetime) - strtotime($start_datetime)) / 3600;
                        $totalPrice = $room['price_hour'] * $durationHours;
                        ?>
                        
                        <p class="card-text">
                            <span class="fw-bold">€<?= number_format($room['price_hour'], 2) ?> / hora</span><br>
                            <small class="text-muted">Total: €<?= number_format($totalPrice, 2) ?></small>
                        </p>
                    </div>
                    
                    <div class="card-footer bg-white border-top-0">
                        <a href="<?= base_url('rooms/details/' . $room['room_id']) ?>" 
                           class="btn btn-outline-danger w-100">Ver detalles</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    
    <?php if (empty($rooms)): ?>
        <div class="alert alert-info my-5 text-center">
            <h4>No hay salas disponibles para este horario</h4>
            <p>Por favor, prueba con un horario diferente.</p>
            <a href="<?= base_url('bookings') ?>" class="btn btn-danger mt-2">
                <i class="bi bi-arrow-left"></i> Volver a seleccionar fechas
            </a>
        </div>
    <?php endif; ?>
</div>
<?= $this->endSection() ?> 