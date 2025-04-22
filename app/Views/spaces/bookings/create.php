<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Nueva Reserva</h3>
                </div>
                <div class="card-body">
                    <?php if (session()->has('error')): ?>
                        <div class="alert alert-danger">
                            <?= session('error') ?>
                        </div>
                    <?php endif; ?>

                    <form action="<?= base_url('bookings/store') ?>" method="POST">
                        <?= csrf_field() ?>
                        
                        <div class="form-group">
                            <label for="room_id">Sala</label>
                            <select name="room_id" id="room_id" class="form-control" required>
                                <option value="">Seleccione una sala</option>
                                <?php foreach ($rooms as $room): ?>
                                    <option value="<?= $room['id'] ?>"><?= esc($room['name']) ?> (Capacidad: <?= $room['capacity'] ?>)</option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="start_time">Fecha y hora de inicio</label>
                                    <input type="datetime-local" name="start_time" id="start_time" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="end_time">Fecha y hora de fin</label>
                                    <input type="datetime-local" name="end_time" id="end_time" class="form-control" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mt-4">
                            <h4>Recursos Adicionales</h4>
                            <div class="row">
                                <?php foreach ($resources as $resource): ?>
                                    <div class="col-md-4 mb-3">
                                        <div class="card">
                                            <div class="card-body">
                                                <h5 class="card-title"><?= esc($resource['name']) ?></h5>
                                                <p class="card-text">Disponibles: <?= $resource['quantity'] ?></p>
                                                <div class="input-group">
                                                    <input type="number" 
                                                           name="resources[<?= $resource['id'] ?>]" 
                                                           class="form-control" 
                                                           min="0" 
                                                           max="<?= $resource['quantity'] ?>" 
                                                           value="0">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">unidades</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary">Crear Reserva</button>
                            <a href="<?= base_url('rooms/calendar') ?>" class="btn btn-secondary">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Configurar fechas mínimas
    const now = new Date();
    const year = now.getFullYear();
    const month = String(now.getMonth() + 1).padStart(2, '0');
    const day = String(now.getDate()).padStart(2, '0');
    const hours = String(now.getHours()).padStart(2, '0');
    const minutes = String(now.getMinutes()).padStart(2, '0');
    
    const minDateTime = `${year}-${month}-${day}T${hours}:${minutes}`;
    
    document.getElementById('start_time').min = minDateTime;
    document.getElementById('end_time').min = minDateTime;
    
    // Validar que la fecha de fin sea posterior a la de inicio
    document.getElementById('start_time').addEventListener('change', function() {
        document.getElementById('end_time').min = this.value;
    });
    
    // Verificar disponibilidad al cambiar fechas
    function checkAvailability() {
        const roomId = document.getElementById('room_id').value;
        const startTime = document.getElementById('start_time').value;
        const endTime = document.getElementById('end_time').value;
        
        if (roomId && startTime && endTime) {
            fetch('/rooms/check-availability', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('input[name="csrf_test_name"]').value
                },
                body: JSON.stringify({ room_id: roomId, start_time: startTime, end_time: endTime })
            })
            .then(response => response.json())
            .then(data => {
                if (!data.available) {
                    alert('La sala no está disponible en el horario seleccionado');
                }
            });
        }
    }
    
    document.getElementById('room_id').addEventListener('change', checkAvailability);
    document.getElementById('start_time').addEventListener('change', checkAvailability);
    document.getElementById('end_time').addEventListener('change', checkAvailability);
});
</script>
<?= $this->endSection() ?> 