<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <h1 class="display-4 mb-4 text-center">Bienvenido a nuestro sistema de reservas</h1>
            <p class="lead mb-5 text-center">Reserva salas para tus reuniones, eventos o sesiones de trabajo de forma rápida y sencilla.</p>

            <?php
                // Helper para formatear fecha dd/mm/YYYY
                $fmtDate = function (?string $ymd) {
                    if (!$ymd) return '';
                    [$y,$m,$d] = explode('-', $ymd);
                    return $d . '/' . $m . '/' . $y;
                };
            ?>

            <form action="<?= base_url('rooms/check-availability') ?>" method="post" class="card shadow-sm p-4">
                <h2 class="mb-4 text-center">Selecciona fecha y hora</h2>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Inicio</label>
                        <div class="fw-bold text-primary">
                            <?= $fmtDate($startSelected) ?: '—' ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Fin</label>
                        <div class="fw-bold text-primary">
                            <?= $fmtDate($endSelected) ?: '—' ?>
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="start_date">Fecha inicio</label>
                        <input type="date" id="start_date" name="start_date" class="form-control" value="<?= esc($startSelected) ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="end_date">Fecha fin</label>
                        <input type="date" id="end_date" name="end_date" class="form-control" value="<?= esc($endSelected) ?>" required>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-md-6">
                        <label class="form-label">Hora inicio</label>
                        <input type="time" name="start_time" value="09:00" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Hora fin</label>
                        <input type="time" name="end_time" value="10:00" class="form-control">
                    </div>
                </div>

                <?php if (session('error')): ?>
                    <div class="alert alert-danger mt-3"><?= esc(session('error')) ?></div>
                <?php endif; ?>

                <div class="d-flex justify-content-end mt-4">
                    <button type="submit" class="btn btn-danger">Buscar salas disponibles</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .calendar-container {
        margin-top: 20px;
    }
    .calendar-table {
        width: 100%;
    }
    .calendar-table th, 
    .calendar-table td {
        text-align: center;
        width: 14.28%;
        padding: 8px 4px;
    }
    .calendar-day {
        cursor: pointer;
        border-radius: 50%;
        width: 36px;
        height: 36px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }
    .calendar-day:hover {
        background-color: rgba(220, 53, 69, 0.1);
    }
    .calendar-day-selected {
        background-color: rgba(220, 53, 69, 0.7) !important;
        color: white !important;
    }
    .calendar-day-range {
        background-color: rgba(220, 53, 69, 0.3);
        color: #333;
    }
    .calendar-day-disabled {
        color: #ccc;
        cursor: not-allowed;
    }
    .calendar-day-current {
        border: 1px solid #dc3545;
    }
</style>

<?= $this->endSection() ?> 