<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Calendario de Reservas</h3>
                    <div class="card-tools">
                        <div class="input-group">
                            <input type="date" class="form-control" id="date-picker" value="<?= $date ?>">
                            <div class="input-group-append">
                                <button class="btn btn-primary" onclick="window.location.href='/rooms/create'">
                                    Nueva Reserva
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Hora</th>
                                    <?php foreach ($rooms as $room): ?>
                                        <th><?= esc($room['name']) ?></th>
                                    <?php endforeach; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $hours = [];
                                for ($i = 8; $i <= 20; $i++) {
                                    $hours[] = sprintf('%02d:00', $i);
                                }
                                ?>
                                <?php foreach ($hours as $hour): ?>
                                    <tr>
                                        <td><?= $hour ?></td>
                                        <?php foreach ($rooms as $room): ?>
                                            <td class="calendar-cell" 
                                                data-room="<?= $room['id'] ?>" 
                                                data-hour="<?= $hour ?>">
                                                <?php
                                                foreach ($bookings as $booking) {
                                                    if ($booking['room_id'] == $room['id']) {
                                                        $start = date('H:i', strtotime($booking['start_time']));
                                                        $end = date('H:i', strtotime($booking['end_time']));
                                                        if ($hour >= $start && $hour < $end) {
                                                            echo '<div class="booking-block" style="background-color: #007bff; color: white; padding: 5px; border-radius: 3px;">';
                                                            echo esc($booking['user_name']) . '<br>';
                                                            echo $start . ' - ' . $end;
                                                            echo '</div>';
                                                        }
                                                    }
                                                }
                                                ?>
                                            </td>
                                        <?php endforeach; ?>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('date-picker').addEventListener('change', function() {
    window.location.href = '/rooms/calendar?date=' + this.value;
});

// Función para mostrar el modal de reserva rápida
function showQuickBooking(roomId, hour) {
    // Implementar lógica de reserva rápida
}

// Agregar eventos a las celdas del calendario
document.querySelectorAll('.calendar-cell').forEach(cell => {
    cell.addEventListener('click', function() {
        const roomId = this.dataset.room;
        const hour = this.dataset.hour;
        showQuickBooking(roomId, hour);
    });
});
</script>

<style>
.calendar-cell {
    height: 60px;
    width: 150px;
    vertical-align: top;
    cursor: pointer;
}

.calendar-cell:hover {
    background-color: #f8f9fa;
}

.booking-block {
    margin: -5px;
    font-size: 0.8em;
}
</style>
<?= $this->endSection() ?> 