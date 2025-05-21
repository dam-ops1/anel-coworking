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
                <?= csrf_field() ?>
                <h2 class="mb-4 text-center">Selecciona fecha y hora</h2>
                
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> Las reservas solo están disponibles de lunes a viernes. No se permiten reservas en fin de semana.
                </div>

                <?php if (session('error')): ?>
                    <div class="alert alert-danger"><?= esc(session('error')) ?></div>
                <?php endif; ?>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="mb-2">
                            <label class="form-label fw-bold fs-5">Inicio</label>
                        </div>
                        <div class="mb-3 fw-bold text-primary">
                            <?= $fmtDate($startSelected) ?: '—' ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-2">
                            <label class="form-label fw-bold fs-5">Fin</label>
                        </div>
                        <div class="mb-3 fw-bold text-primary">
                            <?= $fmtDate($endSelected) ?: '—' ?>
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="start_date">Fecha inicio</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                            <input type="date" id="start_date" name="start_date" 
                                  class="form-control form-control-lg" 
                                  value="<?= esc($startSelected) ?>" 
                                  min="<?= esc($minDate) ?>" required>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="end_date">Fecha fin</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                            <input type="date" id="end_date" name="end_date" 
                                  class="form-control form-control-lg" 
                                  value="<?= esc($endSelected) ?>" 
                                  min="<?= esc($minDate) ?>" required>
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Hora inicio</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-clock"></i></span>
                            <input type="time" id="start_time" name="start_time" 
                                  value="<?= esc($startTimeSelected) ?>" 
                                  class="form-control form-control-lg"
                                  <?= ($startSelected === $currentDate) ? "min='{$minTime}'" : '' ?>>
                        </div>
                        <?php if ($startSelected === $currentDate): ?>
                            <small class="form-text text-muted">
                                La hora mínima para hoy es <?= esc($minTime) ?>
                            </small>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Hora fin</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-clock"></i></span>
                            <input type="time" id="end_time" name="end_time" 
                                  value="<?= esc($endTimeSelected) ?>" 
                                  class="form-control form-control-lg">
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-5">
                    <button type="submit" class="btn btn-danger">Buscar salas disponibles</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- CSS específico para formulario de reservas -->
<link rel="stylesheet" href="<?= base_url('css/calendar.css') ?>">

<?= $this->endSection() ?> 