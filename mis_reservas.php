<?php
require 'includes/auth.php';
check_login();
require 'includes/db.php';

$user_id = $_SESSION['user_id'];

// Obtener las reservas del usuario
$stmt = $conn->prepare("SELECT reserva.reserva_id, reserva.fecha_entrada, reserva.fecha_salida, reserva.monto_total, pago.pago_id, pago.metodo_pago, pago.fecha_pago FROM reserva LEFT JOIN pago ON reserva.reserva_id = pago.reserva_id WHERE reserva.cliente_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$reservas = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis Reservas</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h1 class="my-5">Mis Reservas</h1>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID de Reserva</th>
                    <th>Fecha de Entrada</th>
                    <th>Fecha de Salida</th>
                    <th>Monto Total</th>
                    <th>Estado de Pago</th>
                    <th>Método de Pago</th>
                    <th>Fecha de Pago</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($reserva = $reservas->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($reserva['reserva_id']); ?></td>
                    <td><?php echo htmlspecialchars($reserva['fecha_entrada']); ?></td>
                    <td><?php echo htmlspecialchars($reserva['fecha_salida']); ?></td>
                    <td><?php echo htmlspecialchars($reserva['monto_total']); ?></td>
                    <td><?php echo $reserva['pago_id'] ? 'Pagado' : 'Pendiente'; ?></td>
                    <td><?php echo htmlspecialchars($reserva['metodo_pago'] ?? 'N/A'); ?></td>
                    <td><?php echo htmlspecialchars($reserva['fecha_pago'] ?? 'N/A'); ?></td>
                    <td>
                        <?php if (!$reserva['pago_id']): ?>
                        <form action="pagar_reserva.php" method="post">
                            <input type="hidden" name="reserva_id" value="<?php echo htmlspecialchars($reserva['reserva_id']); ?>">
                            <select name="metodo_pago" class="form-control" required>
                                <option value="">Seleccionar Método</option>
                                <option value="Efectivo">Efectivo</option>
                                <option value="Tarjeta">Tarjeta</option>
                            </select>
                            <button type="submit" class="btn btn-primary mt-2">Pagar</button>
                        </form>
                        <?php else: ?>
                        N/A
                        <?php endif; ?>
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
