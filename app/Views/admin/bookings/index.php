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
    
    <div class="card shadow">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Gestión de Reservas</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Usuario</th>
                            <th scope="col">Sala</th>
                            <th scope="col">Fecha Inicio</th>
                            <th scope="col">Fecha Fin</th>
                            <th scope="col">Estado</th>
                            <th scope="col">Precio</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($bookings)): ?>
                            <?php foreach ($bookings as $index => $booking): ?>
                                <tr>
                                    <th scope="row"><?= $index + 1 ?></th>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="fw-bold"><?= esc($booking['user_name']) ?></span>
                                            <small class="text-muted"><?= esc($booking['user_email']) ?></small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="fw-bold"><?= esc($booking['room_name']) ?></span>
                                            <small class="text-muted">Piso: <?= esc($booking['floor']) ?>, Cap: <?= esc($booking['capacity']) ?></small>
                                        </div>
                                    </td>
                                    <td>
                                        <?= date('d/m/Y H:i', strtotime($booking['start_time'])) ?>
                                    </td>
                                    <td>
                                        <?= date('d/m/Y H:i', strtotime($booking['end_time'])) ?>
                                    </td>
                                    <td>
                                        <?php if ($booking['status'] == 'confirmed'): ?>
                                            <span class="badge bg-success">Confirmada</span>
                                        <?php elseif ($booking['status'] == 'pending'): ?>
                                            <span class="badge bg-warning">Pendiente</span>
                                        <?php elseif ($booking['status'] == 'cancelled'): ?>
                                            <span class="badge bg-danger">Cancelada</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary"><?= esc($booking['status']) ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        €<?= number_format($booking['total_price'], 2) ?>
                                    </td>
                                    <td>
                                        <?php if ($booking['status'] != 'cancelled'): ?>
                                            <form action="<?= base_url('admin/bookings/cancel/' . $booking['booking_id']) ?>" method="post" onsubmit="return confirm('¿Estás seguro de que quieres cancelar esta reserva? Se notificará al usuario por email.');">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    <i class="fas fa-times me-1"></i> Cancelar
                                                </button>
                                            </form>
                                        <?php else: ?>
                                            <button class="btn btn-secondary btn-sm" disabled>
                                                <i class="fas fa-ban me-1"></i> Ya cancelada
                                            </button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="text-center">No hay reservas disponibles.</td>
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
        padding: 5px 8px;
    }
    .btn-sm {
        font-size: 0.8rem;
    }
    .table-responsive {
        border-radius: 0.25rem;
    }
</style>

<?= $this->endSection() ?> 