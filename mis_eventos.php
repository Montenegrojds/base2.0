<?php
require 'includes/auth.php';
check_login();
require 'includes/db.php';

$user_id = $_SESSION['user_id'];

// Obtener los eventos del usuario
$stmt = $conn->prepare("SELECT reserva_evento.reserva_evento_id, evento.nombre_evento, evento.fecha_evento, reserva_evento.cantidad, reserva_evento.monto_total FROM reserva_evento INNER JOIN evento ON reserva_evento.evento_id = evento.evento_id WHERE reserva_evento.cliente_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$eventos = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis Eventos</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h1 class="my-5">Mis Eventos</h1>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nombre del Evento</th>
                    <th>Fecha</th>
                    <th>Cantidad</th>
                    <th>Monto Total</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($evento = $eventos->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($evento['nombre_evento']); ?></td>
                    <td><?php echo htmlspecialchars($evento['fecha_evento']); ?></td>
                    <td><?php echo htmlspecialchars($evento['cantidad']); ?></td>
                    <td><?php echo htmlspecialchars($evento['monto_total']); ?></td>
                    <td>
                        <form action="cancelar_evento.php" method="post">
                            <input type="hidden" name="reserva_evento_id" value="<?php echo htmlspecialchars($evento['reserva_evento_id']); ?>">
                            <button type="submit" class="btn btn-danger">Cancelar</button>
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
</body>
</html>
