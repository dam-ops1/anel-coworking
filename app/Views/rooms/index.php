<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <h1 class="display-4 mb-4">Bienvenido a nuestro sistema de reservas</h1>
            <p class="lead mb-5">Reserva salas para tus reuniones, eventos o sesiones de trabajo de forma rápida y sencilla.</p>
            
            <div class="card shadow-sm">
                <div class="card-body p-5">
                    <h2 class="mb-4">¿Listo para hacer una reserva?</h2>
                    <p class="mb-4">Selecciona fecha y hora para ver qué salas están disponibles.</p>
                    <a href="#calendar-modal" class="btn btn-danger btn-lg px-4 py-2" data-coreui-toggle="modal" data-coreui-target="#calendar-modal">
                        <i class="bi bi-calendar3 me-2"></i>Abrir Calendario
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Calendar Modal -->
<div class="modal fade" id="calendar-modal" tabindex="-1" aria-labelledby="calendarModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="calendarModalLabel">Selecciona fecha y hora</h5>
                <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="reservation-form" action="<?= base_url('rooms/check-availability') ?>" method="post">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <label class="form-label">Iniciar</label>
                                <div id="start-date-display" class="fw-bold text-primary">
                                    <!-- Fecha inicio se mostrará aquí -->
                                </div>
                                <div id="start-time-display" class="fw-bold text-primary">
                                    <!-- Hora inicio se mostrará aquí -->
                                </div>
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <label class="form-label">Finalizar</label>
                                <div id="end-date-display" class="fw-bold text-primary">
                                    <!-- Fecha fin se mostrará aquí -->
                                </div>
                                <div id="end-time-display" class="fw-bold text-primary">
                                    <!-- Hora fin se mostrará aquí -->
                                </div>
                            </div>
                        </div>
                        
                        <!-- Campos ocultos para almacenar datos -->
                        <input type="hidden" id="start_date" name="start_date">
                        <input type="hidden" id="start_time" name="start_time">
                        <input type="hidden" id="end_date" name="end_date">
                        <input type="hidden" id="end_time" name="end_time">
                    </div>
                    
                    <div class="calendar-container">
                        <!-- Calendario de selección -->
                        <div class="row">
                            <div class="col-12 text-center mb-3">
                                <button type="button" id="prev-month" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-chevron-left"></i>
                                </button>
                                <span id="current-month-year" class="mx-2 fw-bold">Mayo 2025</span>
                                <button type="button" id="next-month" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-chevron-right"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <!-- Primer mes -->
                                <div id="calendar-month-1" class="calendar-month">
                                    <div class="month-header text-center mb-2">
                                        <h6 id="month-1-title">mayo 2025</h6>
                                    </div>
                                    <table class="table table-sm calendar-table">
                                        <thead>
                                            <tr>
                                                <th>lun</th>
                                                <th>mar</th>
                                                <th>mié</th>
                                                <th>jue</th>
                                                <th>vie</th>
                                                <th>sáb</th>
                                                <th>dom</th>
                                            </tr>
                                        </thead>
                                        <tbody id="month-1-days">
                                            <!-- Días del mes -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <!-- Segundo mes -->
                                <div id="calendar-month-2" class="calendar-month">
                                    <div class="month-header text-center mb-2">
                                        <h6 id="month-2-title">junio 2025</h6>
                                    </div>
                                    <table class="table table-sm calendar-table">
                                        <thead>
                                            <tr>
                                                <th>lun</th>
                                                <th>mar</th>
                                                <th>mié</th>
                                                <th>jue</th>
                                                <th>vie</th>
                                                <th>sáb</th>
                                                <th>dom</th>
                                            </tr>
                                        </thead>
                                        <tbody id="month-2-days">
                                            <!-- Días del mes -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="input-group mb-3">
                                <span class="input-group-text">Hora inicio</span>
                                <input type="number" min="0" max="23" class="form-control" id="start-hour" placeholder="HH" value="9">
                                <span class="input-group-text">:</span>
                                <input type="number" min="0" max="59" step="15" class="form-control" id="start-minute" placeholder="MM" value="0">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group mb-3">
                                <span class="input-group-text">Hora fin</span>
                                <input type="number" min="0" max="23" class="form-control" id="end-hour" placeholder="HH" value="10">
                                <span class="input-group-text">:</span>
                                <input type="number" min="0" max="59" step="15" class="form-control" id="end-minute" placeholder="MM" value="0">
                            </div>
                        </div>
                    </div>
                    
                    <div class="alert alert-danger d-none" id="validation-error"></div>

                    <div class="d-flex justify-content-between mt-4">
                        <button type="button" class="btn btn-outline-secondary" data-coreui-dismiss="modal">Cerrar</button>
                        <button type="button" id="apply-button" class="btn btn-danger">Aplicar</button>
                    </div>
                </form>
            </div>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Variables para almacenar fechas seleccionadas
    let startDate = null;
    let endDate = null;
    let currentMonthOffset = 0;
    
    // Referencias a elementos del DOM
    const startDateDisplay = document.getElementById('start-date-display');
    const startTimeDisplay = document.getElementById('start-time-display');
    const endDateDisplay = document.getElementById('end-date-display');
    const endTimeDisplay = document.getElementById('end-time-display');
    
    const startDateInput = document.getElementById('start_date');
    const startTimeInput = document.getElementById('start_time');
    const endDateInput = document.getElementById('end_date');
    const endTimeInput = document.getElementById('end_time');
    
    const startHourInput = document.getElementById('start-hour');
    const startMinuteInput = document.getElementById('start-minute');
    const endHourInput = document.getElementById('end-hour');
    const endMinuteInput = document.getElementById('end-minute');
    
    const month1Title = document.getElementById('month-1-title');
    const month2Title = document.getElementById('month-2-title');
    const month1Days = document.getElementById('month-1-days');
    const month2Days = document.getElementById('month-2-days');
    
    const prevMonthBtn = document.getElementById('prev-month');
    const nextMonthBtn = document.getElementById('next-month');
    const applyBtn = document.getElementById('apply-button');
    const validationError = document.getElementById('validation-error');
    
    // Función para generar los días del mes
    function generateCalendar(offset = 0) {
        // Obtener fecha actual
        const today = new Date();
        
        // Calcular meses a mostrar
        const month1Date = new Date();
        month1Date.setMonth(month1Date.getMonth() + offset);
        month1Date.setDate(1);
        
        const month2Date = new Date(month1Date);
        month2Date.setMonth(month2Date.getMonth() + 1);
        
        // Actualizar títulos de los meses
        const monthNames = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 
                           'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];
        
        month1Title.textContent = `${monthNames[month1Date.getMonth()]} ${month1Date.getFullYear()}`;
        month2Title.textContent = `${monthNames[month2Date.getMonth()]} ${month2Date.getFullYear()}`;
        
        // Generar días para el primer mes
        generateMonthDays(month1Date, month1Days);
        
        // Generar días para el segundo mes
        generateMonthDays(month2Date, month2Days);
        
        // Actualizar selección
        if (startDate) {
            highlightSelectedDates();
        }
    }
    
    // Generar los días para un mes específico
    function generateMonthDays(date, container) {
        container.innerHTML = '';
        
        const year = date.getFullYear();
        const month = date.getMonth();
        
        // Obtener el primer día del mes
        const firstDay = new Date(year, month, 1);
        // Obtener el último día del mes
        const lastDay = new Date(year, month + 1, 0);
        
        // Ajustar para empezar en lunes (1) en lugar de domingo (0)
        let firstDayOfWeek = firstDay.getDay();
        firstDayOfWeek = firstDayOfWeek === 0 ? 7 : firstDayOfWeek;
        
        // Crear filas para el calendario
        let currentRow = document.createElement('tr');
        
        // Añadir celdas vacías para los días antes del inicio del mes
        for (let i = 1; i < firstDayOfWeek; i++) {
            const emptyCell = document.createElement('td');
            currentRow.appendChild(emptyCell);
        }
        
        // Hoy para comparar
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        
        // Añadir días del mes
        for (let day = 1; day <= lastDay.getDate(); day++) {
            // Si es domingo (7) o llegamos al final de la semana, crear nueva fila
            if (firstDayOfWeek > 7) {
                container.appendChild(currentRow);
                currentRow = document.createElement('tr');
                firstDayOfWeek = 1;
            }
            
            const cell = document.createElement('td');
            const daySpan = document.createElement('div');
            daySpan.classList.add('calendar-day');
            daySpan.textContent = day;
            
            // Fecha para comparación
            const currentDate = new Date(year, month, day);
            currentDate.setHours(0, 0, 0, 0);
            
            // Añadir data attribute con la fecha
            daySpan.dataset.date = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
            
            // Verificar si es el día actual
            if (currentDate.getTime() === today.getTime()) {
                daySpan.classList.add('calendar-day-current');
            }
            
            // Deshabilitar días pasados
            if (currentDate < today) {
                daySpan.classList.add('calendar-day-disabled');
            } else {
                // Agregar evento click para días válidos
                daySpan.addEventListener('click', function() {
                    selectDate(daySpan.dataset.date);
                });
            }
            
            cell.appendChild(daySpan);
            currentRow.appendChild(cell);
            firstDayOfWeek++;
        }
        
        // Añadir la última fila si tiene contenido
        if (currentRow.hasChildNodes()) {
            container.appendChild(currentRow);
        }
    }
    
    // Función para seleccionar una fecha
    function selectDate(dateStr) {
        const selectedDate = new Date(dateStr);
        
        // Si no hay fecha de inicio seleccionada o si ambas están seleccionadas, comenzar de nuevo
        if (!startDate || (startDate && endDate)) {
            startDate = selectedDate;
            endDate = null;
            
            // Actualizar visualización
            updateDateDisplays();
            highlightSelectedDates();
        } 
        // Si ya hay una fecha de inicio pero no de fin
        else if (startDate && !endDate) {
            // Verificar que la fecha de fin es después de la de inicio
            if (selectedDate < startDate) {
                // Si la nueva selección es anterior, usarla como inicio y la anterior como fin
                endDate = startDate;
                startDate = selectedDate;
            } else {
                endDate = selectedDate;
            }
            
            // Actualizar visualización
            updateDateDisplays();
            highlightSelectedDates();
        }
    }
    
    // Resaltar las fechas seleccionadas en el calendario
    function highlightSelectedDates() {
        // Quitar todas las clases de selección previas
        document.querySelectorAll('.calendar-day').forEach(day => {
            day.classList.remove('calendar-day-selected', 'calendar-day-range');
        });
        
        if (!startDate) return;
        
        // Convertir a fechas para comparación
        const start = new Date(startDate);
        start.setHours(0, 0, 0, 0);
        
        // Seleccionar fecha inicio
        const startSelector = `.calendar-day[data-date="${start.getFullYear()}-${String(start.getMonth() + 1).padStart(2, '0')}-${String(start.getDate()).padStart(2, '0')}"]`;
        const startElement = document.querySelector(startSelector);
        if (startElement) {
            startElement.classList.add('calendar-day-selected');
        }
        
        // Si hay fecha de fin, seleccionarla y el rango entre ambas
        if (endDate) {
            const end = new Date(endDate);
            end.setHours(0, 0, 0, 0);
            
            const endSelector = `.calendar-day[data-date="${end.getFullYear()}-${String(end.getMonth() + 1).padStart(2, '0')}-${String(end.getDate()).padStart(2, '0')}"]`;
            const endElement = document.querySelector(endSelector);
            if (endElement) {
                endElement.classList.add('calendar-day-selected');
            }
            
            // Resaltar días intermedios
            document.querySelectorAll('.calendar-day').forEach(day => {
                const dayDate = new Date(day.dataset.date);
                if (dayDate > start && dayDate < end) {
                    day.classList.add('calendar-day-range');
                }
            });
        }
    }
    
    // Actualizar las etiquetas de fechas y horas
    function updateDateDisplays() {
        if (startDate) {
            const startDateFormatted = formatDate(startDate);
            startDateDisplay.textContent = startDateFormatted;
            
            const startTimeFormatted = padZero(startHourInput.value) + ':' + padZero(startMinuteInput.value);
            startTimeDisplay.textContent = startTimeFormatted;
            
            // Actualizar inputs ocultos
            startDateInput.value = formatDateYMD(startDate);
            startTimeInput.value = startTimeFormatted;
        } else {
            startDateDisplay.textContent = '';
            startTimeDisplay.textContent = '';
            startDateInput.value = '';
            startTimeInput.value = '';
        }
        
        if (endDate) {
            const endDateFormatted = formatDate(endDate);
            endDateDisplay.textContent = endDateFormatted;
            
            const endTimeFormatted = padZero(endHourInput.value) + ':' + padZero(endMinuteInput.value);
            endTimeDisplay.textContent = endTimeFormatted;
            
            // Actualizar inputs ocultos
            endDateInput.value = formatDateYMD(endDate);
            endTimeInput.value = endTimeFormatted;
        } else {
            endDateDisplay.textContent = '';
            endTimeDisplay.textContent = '';
            endDateInput.value = '';
            endTimeInput.value = '';
        }
    }
    
    // Formatear fecha para mostrar
    function formatDate(date) {
        if (!date) return '';
        return `${padZero(date.getDate())}/${padZero(date.getMonth() + 1)}/${date.getFullYear()}`;
    }
    
    // Formatear fecha en formato YYYY-MM-DD para el input
    function formatDateYMD(date) {
        if (!date) return '';
        return `${date.getFullYear()}-${padZero(date.getMonth() + 1)}-${padZero(date.getDate())}`;
    }
    
    // Añadir ceros a la izquierda
    function padZero(num) {
        return String(num).padStart(2, '0');
    }
    
    // Validar la reserva
    function validateReservation() {
        validationError.classList.add('d-none');
        
        if (!startDate || !endDate) {
            showError('Por favor, selecciona las fechas de inicio y fin.');
            return false;
        }
        
        // Obtener horas y minutos
        const startHour = parseInt(startHourInput.value);
        const startMinute = parseInt(startMinuteInput.value);
        const endHour = parseInt(endHourInput.value);
        const endMinute = parseInt(endMinuteInput.value);
        
        if (isNaN(startHour) || isNaN(startMinute) || isNaN(endHour) || isNaN(endMinute)) {
            showError('Por favor, introduce horas válidas.');
            return false;
        }
        
        // Crear objetos Date completos con fecha y hora
        const startDateTime = new Date(startDate);
        startDateTime.setHours(startHour, startMinute, 0, 0);
        
        const endDateTime = new Date(endDate);
        endDateTime.setHours(endHour, endMinute, 0, 0);
        
        // Verificar que la hora de fin es posterior a la de inicio
        if (endDateTime <= startDateTime) {
            showError('La fecha y hora de fin debe ser posterior a la de inicio.');
            return false;
        }
        
        // Verificar que no es una fecha pasada
        const now = new Date();
        if (startDateTime < now) {
            showError('No puedes hacer reservas para fechas pasadas.');
            return false;
        }
        
        return true;
    }
    
    // Mostrar mensaje de error
    function showError(message) {
        validationError.textContent = message;
        validationError.classList.remove('d-none');
    }
    
    // Inicializar calendario
    generateCalendar(currentMonthOffset);
    
    // Eventos
    prevMonthBtn.addEventListener('click', function() {
        currentMonthOffset--;
        generateCalendar(currentMonthOffset);
    });
    
    nextMonthBtn.addEventListener('click', function() {
        currentMonthOffset++;
        generateCalendar(currentMonthOffset);
    });
    
    // Actualizar la visualización de hora cuando cambian los inputs
    startHourInput.addEventListener('change', updateDateDisplays);
    startMinuteInput.addEventListener('change', updateDateDisplays);
    endHourInput.addEventListener('change', updateDateDisplays);
    endMinuteInput.addEventListener('change', updateDateDisplays);
    
    // Validar límites de hora
    [startHourInput, endHourInput].forEach(input => {
        input.addEventListener('change', function() {
            if (this.value < 0) this.value = 0;
            if (this.value > 23) this.value = 23;
            updateDateDisplays();
        });
    });
    
    [startMinuteInput, endMinuteInput].forEach(input => {
        input.addEventListener('change', function() {
            if (this.value < 0) this.value = 0;
            if (this.value > 59) this.value = 59;
            // Redondear a múltiplos de 15
            this.value = Math.round(this.value / 15) * 15;
            updateDateDisplays();
        });
    });
    
    // Botón Aplicar
    applyBtn.addEventListener('click', function() {
        if (validateReservation()) {
            document.getElementById('reservation-form').submit();
        }
    });
    
    // Modal events
    const calendarModal = document.getElementById('calendar-modal');
    calendarModal.addEventListener('shown.coreui.modal', function() {
        generateCalendar(currentMonthOffset);
    });
});
</script>

<?= $this->endSection() ?> 