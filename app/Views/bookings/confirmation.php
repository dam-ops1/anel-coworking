<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container py-4">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url('bookings') ?>">Mis Reservas</a></li>
            <li class="breadcrumb-item active" aria-current="page">Confirmación</li>
        </ol>
    </nav>
    
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0">¡Reserva Confirmada!</h4>
                </div>
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <div class="mb-3">
                            <i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i>
                        </div>
                        <h2>Gracias por tu reserva</h2>
                        <p class="lead">Se ha enviado un correo de confirmación a tu dirección de email.</p>
                    </div>
                    
                    <h5 class="border-bottom pb-2 mb-3">Detalles de la reserva</h5>
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Número de reserva:</strong> #<?= $booking['booking_id'] ?></p>
                            <p class="mb-1">
                                <strong>Estado:</strong> 
                                <?php if ($booking['status'] == 'confirmed'): ?>
                                    <span class="badge bg-success">Confirmada</span>
                                <?php elseif ($booking['status'] == 'pending'): ?>
                                    <span class="badge bg-warning text-dark">Pendiente</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Cancelada</span>
                                <?php endif; ?>
                            </p>
                            <p class="mb-1"><strong>Fecha de reserva:</strong> <?= date('d/m/Y H:i', strtotime($booking['created_at'])) ?></p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Sala:</strong> <?= esc($room['name']) ?></p>
                            <p class="mb-1"><strong>Ubicación:</strong> <?= esc($room['floor']) ?></p>
                            <p class="mb-1"><strong>Capacidad:</strong> <?= $room['capacity'] ?> personas</p>
                        </div>
                    </div>
                    
                    <div class="alert alert-light">
                        <div class="row">
                            <div class="col-md-6">
                                <p class="mb-1"><strong>Fecha de inicio:</strong></p>
                                <p class="mb-3"><?= date('d/m/Y H:i', strtotime($booking['start_time'])) ?></p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-1"><strong>Fecha de fin:</strong></p>
                                <p class="mb-3"><?= date('d/m/Y H:i', strtotime($booking['end_time'])) ?></p>
                            </div>
                        </div>
                        
                        <?php
                        // Calcular duración
                        $durationHours = (strtotime($booking['end_time']) - strtotime($booking['start_time'])) / 3600;
                        ?>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <p class="mb-1"><strong>Duración:</strong></p>
                                <p class="mb-0"><?= number_format($durationHours, 1) ?> horas</p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-1"><strong>Precio total:</strong></p>
                                <p class="mb-0">€<?= number_format($booking['total_price'], 2) ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <?php if (!empty($booking['notes'])): ?>
                        <div class="mt-4">
                            <h5 class="border-bottom pb-2 mb-3">Notas adicionales</h5>
                            <p><?= esc($booking['notes']) ?></p>
                        </div>
                    <?php endif; ?>
                    
                    <div class="mt-4">
                        <h5 class="border-bottom pb-2 mb-3">Equipamiento de la sala</h5>
                        <?php if (!empty($room['equipment'])): ?>
                            <ul class="list-group list-group-flush">
                                <?php foreach (explode(', ', $room['equipment']) as $item): ?>
                                    <li class="list-group-item bg-light">
                                        <i class="bi bi-check-circle-fill text-success me-2"></i>
                                        <?= esc($item) ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <p class="text-muted">No hay información sobre el equipamiento.</p>
                        <?php endif; ?>
                    </div>
                    
                    <div class="d-flex justify-content-between mt-5">
                        <a href="<?= base_url('bookings') ?>" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-1"></i> Volver a mis reservas
                        </a>
                        
                        <?php if (($booking['status'] != 'cancelled') && strtotime($booking['start_time']) > time()): ?>
                            <a href="<?= base_url('bookings/cancel/' . $booking['booking_id']) ?>" 
                               class="btn btn-outline-danger"
                               onclick="return confirm('¿Estás seguro de que deseas cancelar esta reserva?');">
                                <i class="bi bi-x-circle me-1"></i> Cancelar reserva
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?> 