<?php
require 'includes/auth.php';
check_login();
require 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $habitacion_id = $_POST['habitacion_id'];
    $fecha_entrada = $_POST['fecha_entrada'];
    $fecha_salida = $_POST['fecha_salida'];
    $user_id = $_SESSION['user_id'];

    // Verificar si la habitación ya está reservada en las fechas seleccionadas
    $stmt = $conn->prepare("SELECT COUNT(*) FROM reserva WHERE habitacion_id = ? AND ((fecha_entrada BETWEEN ? AND ?) OR (fecha_salida BETWEEN ? AND ?))");
    $stmt->bind_param("issss", $habitacion_id, $fecha_entrada, $fecha_salida, $fecha_entrada, $fecha_salida);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count > 0) {
        $error = "La habitación ya está ocupada en las fechas seleccionadas.";
    } else {
        // Obtener el precio por noche de la habitación
        $stmt = $conn->prepare("SELECT th.precio_por_noche FROM habitacion h JOIN tipo_habitacion th ON h.tipo_habitacion_id = th.tipo_habitacion_id WHERE h.habitacion_id = ?");
        $stmt->bind_param("i", $habitacion_id);
        $stmt->execute();
        $stmt->bind_result($precio_por_noche);
        $stmt->fetch();
        $stmt->close();

        // Calcular el monto total
        $datetime1 = new DateTime($fecha_entrada);
        $datetime2 = new DateTime($fecha_salida);
        $interval = $datetime1->diff($datetime2);
        $dias = $interval->format('%a');

        $monto_total = $dias * $precio_por_noche;

        // Insertar la reserva en la base de datos
        $stmt = $conn->prepare("INSERT INTO reserva (cliente_id, habitacion_id, fecha_entrada, fecha_salida, monto_total) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("iissi", $user_id, $habitacion_id, $fecha_entrada, $fecha_salida, $monto_total);

        if ($stmt->execute()) {
            // Actualizar el estado de la habitación a "Ocupada"
            $stmt = $conn->prepare("UPDATE habitacion SET estado = 'Ocupada' WHERE habitacion_id = ?");
            $stmt->bind_param("i", $habitacion_id);
            $stmt->execute();

            header("Location: mis_reservas.php");
            exit();
        } else {
            $error = "Error al realizar la reserva.";
        }
    }
}

// Obtener las habitaciones disponibles
$stmt = $conn->prepare("SELECT h.habitacion_id, h.numero_habitacion, th.nombre_tipo FROM habitacion h JOIN tipo_habitacion th ON h.tipo_habitacion_id = th.tipo_habitacion_id WHERE h.estado = 'Disponible'");
$stmt->execute();
$habitaciones = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reservar Habitación</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/css.css">
</head>
<body>
    <div class="container">
        <h1 class="my-5">Reservar Habitación</h1>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        <form action="reserva.php" method="post">
            <div class="form-group">
                <label for="habitacion_id">Habitación:</label>
                <select name="habitacion_id" class="form-control" required>
                    <?php while ($habitacion = $habitaciones->fetch_assoc()): ?>
                    <option value="<?php echo htmlspecialchars($habitacion['habitacion_id']); ?>">
                        <?php echo htmlspecialchars($habitacion['numero_habitacion'] . " - " . $habitacion['nombre_tipo']); ?>
                    </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="fecha_entrada">Fecha de Entrada:</label>
                <input type="date" class="form-control" name="fecha_entrada" required>
            </div>
            <div class="form-group">
                <label for="fecha_salida">Fecha de Salida:</label>
                <input type="date" class="form-control" name="fecha_salida" required>
            </div>
            <button type="submit" class="btn btn-primary">Reservar</button>
        </form>
        <div class="mt-4">
            <a href="dashboard.php" class="btn btn-secondary">Volver al Dashboard</a>
        </div>
    </div>
    <footer class="footer">
        <p>&copy; 2024 Sistema de Gestión Hotelera</p>
    </footer>
</body>
</html>
