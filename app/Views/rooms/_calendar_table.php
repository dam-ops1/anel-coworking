<?php
/**
 * Variables requeridas:
 * @var DateTime $monthDate       Fecha con el día 1 del mes a mostrar
 * @var int      $offset          Desplazamiento actual (para enlaces)
 * @var string|null $startSelected Fecha inicio seleccionada (YYYY-MM-DD)
 * @var string|null $endSelected   Fecha fin seleccionada (YYYY-MM-DD)
 */

$monthNames = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];
$title = $monthNames[(int)$monthDate->format('n') - 1] . ' ' . $monthDate->format('Y');

$firstDayOfMonth = (clone $monthDate);
$firstDayOfMonth->modify('first day of this month');
$lastDayOfMonth = (clone $monthDate);
$lastDayOfMonth->modify('last day of this month');

$today = (new DateTime())->format('Y-m-d');

?>
<div class="calendar-month">
    <div class="month-header text-center mb-2">
        <h6><?= esc($title) ?></h6>
    </div>
    <table class="table table-sm calendar-table">
        <thead>
            <tr>
                <th>lun</th><th>mar</th><th>mié</th><th>jue</th><th>vie</th><th>sáb</th><th>dom</th>
            </tr>
        </thead>
        <tbody>
        <?php
            // Día de la semana del primer día (1=lunes, 7=domingo)
            $dow = (int)$firstDayOfMonth->format('N');
            echo '<tr>';
            // huecos antes del primer día
            for ($i = 1; $i < $dow; $i++) {
                echo '<td></td>';
            }
            $dayCounter = 1;
            $dowCounter = $dow;
            while ($dayCounter <= (int)$lastDayOfMonth->format('j')) {
                $currentDate = (clone $monthDate)->setDate($monthDate->format('Y'), $monthDate->format('n'), $dayCounter);
                $ymd = $currentDate->format('Y-m-d');

                $classes = ['calendar-day'];
                $isDisabled = false;

                if ($ymd < $today) {
                    $classes[] = 'calendar-day-disabled';
                    $isDisabled = true;
                }
                if ($ymd === $today) {
                    $classes[] = 'calendar-day-current';
                }
                if ($startSelected && $ymd === $startSelected) {
                    $classes[] = 'calendar-day-selected';
                }
                if ($endSelected && $ymd === $endSelected) {
                    $classes[] = 'calendar-day-selected';
                }
                if ($startSelected && $endSelected && $ymd > $startSelected && $ymd < $endSelected) {
                    $classes[] = 'calendar-day-range';
                }

                echo '<td>';
                if ($isDisabled) {
                    echo '<div class="'.implode(' ', $classes).'">'.$dayCounter.'</div>';
                } else {
                    $link = base_url('bookings').'?offset='.$offset.'&date='.$ymd;
                    echo '<a href="'.$link.'" hx-get="'.$link.'" hx-target="body" hx-swap="outerHTML" class="'.implode(' ', $classes).'">'.$dayCounter.'</a>';
                }
                echo '</td>';

                $dayCounter++;
                $dowCounter++;
                if ($dowCounter > 7 && $dayCounter <= (int)$lastDayOfMonth->format('j')) {
                    echo '</tr><tr>';
                    $dowCounter = 1;
                }
            }
            // Rellenar celdas vacías al final de la última semana
            if ($dowCounter !== 1) {
                for ($i = $dowCounter; $i <= 7; $i++) {
                    echo '<td></td>';
                }
            }
            echo '</tr>';
        ?>
        </tbody>
    </table>
</div>
