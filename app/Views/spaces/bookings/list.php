<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Mis Reservas</h3>
                    <a href="<?= base_url('bookings/create') ?>" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Nueva Reserva
                    </a>
                </div>
                <div class="card-body">
                    <?php if (session()->has('message')): ?>
                        <div class="alert alert-success">
                            <?= session('message') ?>
                        </div>
                    <?php endif; ?>

                    <?php if (session()->has('error')): ?>
                        <div class="alert alert-danger">
                            <?= session('error') ?>
                        </div>
                    <?php endif; ?>

                    <?php if (empty($bookings)): ?>
                        <div class="alert alert-info">
                            No hay reservas para mostrar.
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Sala</th>
                                        <th>Fecha</th>
                                        <th>Hora Inicio</th>
                                        <th>Hora Fin</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($bookings as $booking): ?>
                                        <tr>
                                            <td><?= esc($booking['room_name']) ?></td>
                                            <td><?= date('d/m/Y', strtotime($booking['start_time'])) ?></td>
                                            <td><?= date('H:i', strtotime($booking['start_time'])) ?></td>
                                            <td><?= date('H:i', strtotime($booking['end_time'])) ?></td>
                                            <td>
                                                <span class="badge badge-<?= $booking['status'] === 'confirmada' ? 'success' : 
                                                    ($booking['status'] === 'pendiente' ? 'warning' : 'danger') ?>">
                                                    <?= ucfirst($booking['status']) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php if ($booking['status'] !== 'cancelada'): ?>
                                                    <button class="btn btn-sm btn-danger" 
                                                            onclick="cancelBooking(<?= $booking['reservation_id'] ?>)">
                                                        Cancelar
                                                    </button>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function cancelBooking(bookingId) {
    if (confirm('¿Estás seguro de que deseas cancelar esta reserva?')) {
        fetch(`/bookings/cancel/${bookingId}`, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'Error al cancelar la reserva');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al procesar la solicitud');
        });
    }
}
</script>
<?= $this->endSection() ?> 