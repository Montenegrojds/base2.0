<?php
require 'includes/auth.php';
check_login();
require 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cliente_id = $_SESSION['user_id'];
    $habitacion_id = $_POST['habitacion_id'];
    $fecha_entrada = $_POST['fecha_entrada'];
    $fecha_salida = $_POST['fecha_salida'];
    $monto_total = $_POST['monto_total'];

    // Comprobar que la habitación no esté ya reservada para las fechas dadas
    $stmt = $conn->prepare("SELECT * FROM reserva WHERE habitacion_id = ? AND ((fecha_entrada BETWEEN ? AND ?) OR (fecha_salida BETWEEN ? AND ?) OR (fecha_entrada <= ? AND fecha_salida >= ?))");
    $stmt->bind_param("issssss", $habitacion_id, $fecha_entrada, $fecha_salida, $fecha_entrada, $fecha_salida, $fecha_entrada, $fecha_salida);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        // Si la habitación no está reservada, proceder con la inserción de la reserva
        $stmt = $conn->prepare("INSERT INTO reserva (cliente_id, habitacion_id, fecha_entrada, fecha_salida, monto_total) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("iissd", $cliente_id, $habitacion_id, $fecha_entrada, $fecha_salida, $monto_total);

        if ($stmt->execute()) {
            echo "<div class='alert alert-success'>Reserva realizada con éxito</div>";
        } else {
            echo "<div class='alert alert-danger'>Error al realizar la reserva</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>La habitación seleccionada ya está reservada para las fechas indicadas</div>";
    }
}

// Obtener todas las reservas para mostrarlas en el calendario
$stmt = $conn->prepare("SELECT habitacion_id, fecha_entrada, fecha_salida FROM reserva");
$stmt->execute();
$reservas = $stmt->get_result();
$reservas_array = [];
while ($reserva = $reservas->fetch_assoc()) {
    $reservas_array[] = $reserva;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reservar Habitación</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.2/main.min.css' rel='stylesheet' />
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.2/main.min.js'></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: [
                    <?php foreach ($reservas_array as $reserva): ?>
                    {
                        title: 'Habitación <?php echo $reserva['habitacion_id']; ?>',
                        start: '<?php echo $reserva['fecha_entrada']; ?>',
                        end: '<?php echo (new DateTime($reserva['fecha_salida']))->modify('+1 day')->format('Y-m-d'); ?>',
                        color: 'red'
                    },
                    <?php endforeach; ?>
                ]
            });
            calendar.render();
        });
    </script>
</head>
<body>
    <div class="container">
        <h1 class="my-5">Reservar Habitación</h1>
        <form action="reserva.php" method="post">
            <div class="form-group">
                <label for="habitacion_id">ID de Habitación:</label>
                <input type="text" class="form-control" name="habitacion_id" required>
            </div>
            <div class="form-group">
                <label for="fecha_entrada">Fecha de Entrada:</label>
                <input type="date" class="form-control" name="fecha_entrada" required>
            </div>
            <div class="form-group">
                <label for="fecha_salida">Fecha de Salida:</label>
                <input type="date" class="form-control" name="fecha_salida" required>
            </div>
            <div class="form-group">
                <label for="monto_total">Monto Total:</label>
                <input type="number" step="0.01" class="form-control" name="monto_total" required>
            </div>
            <button type="submit" class="btn btn-primary">Reservar</button>
        </form>
        <div class="mt-4">
            <a href="reserva.php" class="btn btn-secondary">Reservar Otra Habitación</a>
            <a href="reserva_actividad.php" class="btn btn-secondary">Reservar Actividad</a>
            <a href="empleados.php" class="btn btn-secondary">Ver Información de Empleados</a>
            <a href="dashboard.php" class="btn btn-secondary">Volver al Dashboard</a>
        </div>
        <div id="calendar" class="mt-4"></div>
    </div>
</body>
</html>
