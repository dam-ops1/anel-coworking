<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Mis Reservas</h1>
        <a href="<?= base_url('bookings') ?>" class="btn btn-danger">
            <i class="bi bi-plus-circle me-1"></i> Nueva Reserva
        </a>
    </div>
    
    <?= session()->getFlashdata('success') ? '<div class="alert alert-success">'.session()->getFlashdata('success').'</div>' : '' ?>
    <?= session()->getFlashdata('error') ? '<div class="alert alert-danger">'.session()->getFlashdata('error').'</div>' : '' ?>
    
    <?php if (!empty($bookings)): ?>
        <div class="row">
            <div class="col-lg-3 mb-4">
                <div class="list-group sticky-top" style="top: 2rem;">
                    <a href="#upcoming" class="list-group-item list-group-item-action active" data-coreui-toggle="list">
                        <i class="bi bi-calendar-check me-2"></i> Próximas
                    </a>
                    <a href="#past" class="list-group-item list-group-item-action" data-coreui-toggle="list">
                        <i class="bi bi-calendar-x me-2"></i> Pasadas
                    </a>
                    <a href="#cancelled" class="list-group-item list-group-item-action" data-coreui-toggle="list">
                        <i class="bi bi-calendar-minus me-2"></i> Canceladas
                    </a>
                </div>
            </div>
            
            <div class="col-lg-9">
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="upcoming">
                        <h3 class="h5 mb-3">Reservas próximas</h3>
                        
                        <?php 
                        $now = time();
                        $hasUpcoming = false;
                        
                        foreach ($bookings as $booking):
                            if (($booking['status'] != 'cancelled') && strtotime($booking['end_time']) >= $now):
                                $hasUpcoming = true;
                        ?>
                            <div class="card mb-3">
                                <div class="row g-0">
                                    <div class="col-md-3">
                                        <?php if (!empty($booking['room_image'])): ?>
                                            <img src="<?= base_url('uploads/rooms/' . $booking['room_image']) ?>" 
                                                class="img-fluid rounded-start" alt="<?= esc($booking['room_name']) ?>"
                                                style="height: 100%; object-fit: cover;">
                                        <?php else: ?>
                                            <div class="bg-light h-100 d-flex align-items-center justify-content-center">
                                                <i class="bi bi-building fs-1 text-muted"></i>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <h5 class="card-title"><?= esc($booking['room_name']) ?></h5>
                                                <span class="badge bg-success">
                                                    <?= $booking['status'] == 'pending' ? 'Pendiente' : 'Confirmada' ?>
                                                </span>
                                            </div>
                                            <p class="card-text">
                                                <i class="bi bi-calendar3 me-1"></i> 
                                                <strong>Desde:</strong> <?= date('d/m/Y H:i', strtotime($booking['start_time'])) ?><br>
                                                <i class="bi bi-calendar3 me-1"></i> 
                                                <strong>Hasta:</strong> <?= date('d/m/Y H:i', strtotime($booking['end_time'])) ?>
                                            </p>
                                            <p class="card-text">
                                                <i class="bi bi-geo-alt me-1"></i> <?= esc($booking['floor']) ?><br>
                                                <i class="bi bi-people me-1"></i> Capacidad: <?= $booking['capacity'] ?> personas
                                            </p>
                                            <p class="card-text">
                                                <strong>Precio total:</strong> €<?= number_format($booking['total_price'], 2) ?>
                                            </p>
                                            <?php if (!empty($booking['notes'])): ?>
                                                <p class="card-text small text-muted">
                                                    <strong>Notas:</strong> <?= esc($booking['notes']) ?>
                                                </p>
                                            <?php endif; ?>
                                            <div class="d-flex gap-2 mt-3">
                                                <a href="<?= base_url('bookings/confirmation/' . $booking['booking_id']) ?>" 
                                                   class="btn btn-sm btn-outline-secondary">
                                                    Ver detalles
                                                </a>
                                                <a href="<?= base_url('bookings/cancel/' . $booking['booking_id']) ?>" 
                                                   class="btn btn-sm btn-outline-danger"
                                                   onclick="return confirm('¿Estás seguro de que deseas cancelar esta reserva?');">
                                                    Cancelar reserva
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php 
                            endif;
                        endforeach; 
                        
                        if (!$hasUpcoming):
                        ?>
                            <div class="alert alert-light text-center py-5">
                                <i class="bi bi-calendar fs-1 text-muted"></i>
                                <p class="mt-3">No tienes reservas próximas</p>
                                <a href="<?= base_url('bookings') ?>" class="btn btn-danger mt-2">
                                    Hacer una reserva
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="tab-pane fade" id="past">
                        <h3 class="h5 mb-3">Reservas pasadas</h3>
                        
                        <?php 
                        $hasPast = false;
                        
                        foreach ($bookings as $booking):
                            if (($booking['status'] != 'cancelled') && strtotime($booking['end_time']) < $now):
                                $hasPast = true;
                        ?>
                            <div class="card mb-3 opacity-75">
                                <div class="row g-0">
                                    <div class="col-md-3">
                                        <?php if (!empty($booking['room_image'])): ?>
                                            <img src="<?= base_url('uploads/rooms/' . $booking['room_image']) ?>" 
                                                class="img-fluid rounded-start" alt="<?= esc($booking['room_name']) ?>"
                                                style="height: 100%; object-fit: cover;">
                                        <?php else: ?>
                                            <div class="bg-light h-100 d-flex align-items-center justify-content-center">
                                                <i class="bi bi-building fs-1 text-muted"></i>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <h5 class="card-title"><?= esc($booking['room_name']) ?></h5>
                                                <span class="badge bg-secondary">Completada</span>
                                            </div>
                                            <p class="card-text">
                                                <i class="bi bi-calendar3 me-1"></i> 
                                                <strong>Desde:</strong> <?= date('d/m/Y H:i', strtotime($booking['start_time'])) ?><br>
                                                <i class="bi bi-calendar3 me-1"></i> 
                                                <strong>Hasta:</strong> <?= date('d/m/Y H:i', strtotime($booking['end_time'])) ?>
                                            </p>
                                            <p class="card-text">
                                                <i class="bi bi-geo-alt me-1"></i> <?= esc($booking['floor']) ?><br>
                                                <i class="bi bi-people me-1"></i> Capacidad: <?= $booking['capacity'] ?> personas
                                            </p>
                                            <p class="card-text">
                                                <strong>Precio total:</strong> €<?= number_format($booking['total_price'], 2) ?>
                                            </p>
                                            <div class="d-flex gap-2 mt-3">
                                                <a href="<?= base_url('bookings/confirmation/' . $booking['booking_id']) ?>" 
                                                   class="btn btn-sm btn-outline-secondary">
                                                    Ver detalles
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php 
                            endif;
                        endforeach; 
                        
                        if (!$hasPast):
                        ?>
                            <div class="alert alert-light text-center py-5">
                                <i class="bi bi-calendar-x fs-1 text-muted"></i>
                                <p class="mt-3">No tienes reservas pasadas</p>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="tab-pane fade" id="cancelled">
                        <h3 class="h5 mb-3">Reservas canceladas</h3>
                        
                        <?php 
                        $hasCancelled = false;
                        
                        foreach ($bookings as $booking):
                            if ($booking['status'] == 'cancelled'):
                                $hasCancelled = true;
                        ?>
                            <div class="card mb-3 bg-light">
                                <div class="row g-0">
                                    <div class="col-md-3">
                                        <?php if (!empty($booking['room_image'])): ?>
                                            <img src="<?= base_url('uploads/rooms/' . $booking['room_image']) ?>" 
                                                class="img-fluid rounded-start opacity-50" alt="<?= esc($booking['room_name']) ?>"
                                                style="height: 100%; object-fit: cover;">
                                        <?php else: ?>
                                            <div class="bg-light h-100 d-flex align-items-center justify-content-center">
                                                <i class="bi bi-building fs-1 text-muted"></i>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <h5 class="card-title"><?= esc($booking['room_name']) ?></h5>
                                                <span class="badge bg-danger">Cancelada</span>
                                            </div>
                                            <p class="card-text">
                                                <i class="bi bi-calendar3 me-1"></i> 
                                                <strong>Desde:</strong> <?= date('d/m/Y H:i', strtotime($booking['start_time'])) ?><br>
                                                <i class="bi bi-calendar3 me-1"></i> 
                                                <strong>Hasta:</strong> <?= date('d/m/Y H:i', strtotime($booking['end_time'])) ?>
                                            </p>
                                            <p class="card-text">
                                                <i class="bi bi-geo-alt me-1"></i> <?= esc($booking['floor']) ?><br>
                                                <i class="bi bi-people me-1"></i> Capacidad: <?= $booking['capacity'] ?> personas
                                            </p>
                                            <p class="card-text">
                                                <strong>Precio total:</strong> €<?= number_format($booking['total_price'], 2) ?>
                                            </p>
                                            <div class="d-flex gap-2 mt-3">
                                                <a href="<?= base_url('bookings/confirmation/' . $booking['booking_id']) ?>" 
                                                   class="btn btn-sm btn-outline-secondary">
                                                    Ver detalles
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php 
                            endif;
                        endforeach; 
                        
                        if (!$hasCancelled):
                        ?>
                            <div class="alert alert-light text-center py-5">
                                <i class="bi bi-calendar-minus fs-1 text-muted"></i>
                                <p class="mt-3">No tienes reservas canceladas</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-light text-center py-5 my-4">
            <i class="bi bi-calendar fs-1 text-muted"></i>
            <h4 class="mt-3">No tienes reservas</h4>
            <p>Haz tu primera reserva ahora.</p>
            <a href="<?= base_url('bookings') ?>" class="btn btn-danger mt-2">
                <i class="bi bi-plus-circle me-1"></i> Nueva Reserva
            </a>
        </div>
    <?php endif; ?>
</div>
<?= $this->endSection() ?> 