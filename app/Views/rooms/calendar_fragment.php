<?php
// Fragmento de calendario con botones de navegación y tablas de los dos meses.
// Variables disponibles: $months, $offset, $startSelected, $endSelected
?>
<div class="row">
    <div class="col-12 text-center mb-3">
        <a hx-get="<?= base_url('rooms/calendar-fragment?offset='.($offset-1)) ?>" hx-target="#calendar-wrapper" hx-swap="innerHTML" class="btn btn-sm btn-outline-secondary"><i class="bi bi-chevron-left"></i></a>
        <?php
            $mesesES = ['enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre'];
            $titulo1 = $mesesES[(int)$months[0]->format('n')-1] . ' ' . $months[0]->format('Y');
            $titulo2 = $mesesES[(int)$months[1]->format('n')-1] . ' ' . $months[1]->format('Y');
        ?>
        <span class="mx-2 fw-bold">
            <?= ucfirst($titulo1) ?> — <?= ucfirst($titulo2) ?>
        </span>
        <a hx-get="<?= base_url('rooms/calendar-fragment?offset='.($offset+1)) ?>" hx-target="#calendar-wrapper" hx-swap="innerHTML" class="btn btn-sm btn-outline-secondary"><i class="bi bi-chevron-right"></i></a>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <?php $monthDate = $months[0]; include(APPPATH.'Views/rooms/_calendar_table.php'); ?>
    </div>
    <div class="col-md-6">
        <?php $monthDate = $months[1]; include(APPPATH.'Views/rooms/_calendar_table.php'); ?>
    </div>
</div> 