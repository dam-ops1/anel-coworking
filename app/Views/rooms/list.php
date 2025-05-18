<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container py-4">
    <h1 class="mb-4">Todas nuestras salas</h1>
    
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
                        <p class="card-text text-muted">
                            <i class="bi bi-people me-1"></i> Capacidad: <?= $room['capacity'] ?> personas<br>
                            <i class="bi bi-geo-alt me-1"></i> <?= esc($room['floor']) ?>
                        </p>
                        <p class="card-text small"><?= esc(substr($room['description'], 0, 100)) ?>...</p>
                        <p class="card-text fw-bold">€<?= number_format($room['price_hour'], 2) ?> / hora</p>
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
        <div class="alert alert-info text-center my-5">
            No hay salas disponibles en este momento.
        </div>
    <?php endif; ?>
</div>
<?= $this->endSection() ?> 