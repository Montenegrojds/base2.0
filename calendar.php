<?php
require 'includes/auth.php';
check_login();
require 'includes/db.php';

// Obtener las reservas para mostrarlas en el calendario
$reservas = $conn->query("SELECT habitacion_id, fecha_entrada, fecha_salida FROM reserva WHERE estado != 'Cancelada'");

$eventos = [];
while ($reserva = $reservas->fetch_assoc()) {
    $eventos[] = [
        'title' => 'Habitación ' . $reserva['habitacion_id'],
        'start' => $reserva['fecha_entrada'],
        'end' => (new DateTime($reserva['fecha_salida']))->modify('+1 day')->format('Y-m-d')
    ];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Calendario de Disponibilidad</title>
    <link rel="stylesheet" href="css/style.css">
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.2/main.min.css' rel='stylesheet' />
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.2/main.min.js'></script>
</head>
<body>
    <div class="container">
        <h1 class="my-5">Calendario de Disponibilidad</h1>
        <div id="calendar"></div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: <?php echo json_encode($eventos); ?>
            });
            calendar.render();
        });
    </script>
</body>
</html>
