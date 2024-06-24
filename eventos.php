<?php
require 'includes/auth.php';
check_login();
require 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $evento_id = $_POST['evento_id'];
    $cantidad = $_POST['cantidad'];
    $user_id = $_SESSION['user_id'];

    // Obtener el precio del evento
    $stmt = $conn->prepare("SELECT precio_evento FROM evento WHERE evento_id = ?");
    $stmt->bind_param("i", $evento_id);
    $stmt->execute();
    $stmt->bind_result($precio_evento);
    $stmt->fetch();
    $stmt->close();

    $monto_total = $cantidad * $precio_evento;

    // Insertar la reserva de evento en la base de datos
    $stmt = $conn->prepare("INSERT INTO reserva_evento (evento_id, cliente_id, fecha_reserva, cantidad, monto_total) VALUES (?, ?, NOW(), ?, ?)");
    $stmt->bind_param("iiid", $evento_id, $user_id, $cantidad, $monto_total);

    if ($stmt->execute()) {
        header("Location: mis_eventos.php");
        exit();
    } else {
        $error = "Error al registrar el evento.";
    }
}

// Obtener los eventos disponibles
$stmt = $conn->prepare("SELECT evento_id, nombre_evento, fecha_evento, descripcion, precio_evento FROM evento WHERE fecha_evento >= CURDATE()");
$stmt->execute();
$eventos = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Eventos Disponibles</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/css.css">
</head>
<body>
    <div class="container">
        <h1 class="my-5">Eventos Disponibles</h1>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nombre del Evento</th>
                    <th>Fecha</th>
                    <th>Descripción</th>
                    <th>Precio</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($evento = $eventos->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($evento['nombre_evento']); ?></td>
                    <td><?php echo htmlspecialchars($evento['fecha_evento']); ?></td>
                    <td><?php echo htmlspecialchars($evento['descripcion']); ?></td>
                    <td><?php echo htmlspecialchars($evento['precio_evento']); ?></td>
                    <td>
                        <form action="eventos.php" method="post">
                            <input type="hidden" name="evento_id" value="<?php echo htmlspecialchars($evento['evento_id']); ?>">
                            <input type="number" name="cantidad" class="form-control" min="1" value="1" required>
                            <button type="submit" class="btn btn-primary mt-2">Registrarse</button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <div class="mt-4">
            <a href="dashboard.php" class="btn btn-secondary">Volver al Dashboard</a>
        </div>
    </div>
    <footer class="footer">
        <p>&copy; 2024 Sistema de Gestión Hotelera</p>
    </footer>
</body>
</html>
