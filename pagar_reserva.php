<?php
require 'includes/auth.php';
check_login();
require 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $reserva_id = $_POST['reserva_id'];
    $metodo_pago = $_POST['metodo_pago'];
    $monto = 0;

    // Obtener el monto de la reserva
    $stmt = $conn->prepare("SELECT monto_total FROM reserva WHERE reserva_id = ?");
    $stmt->bind_param("i", $reserva_id);
    $stmt->execute();
    $stmt->bind_result($monto);
    $stmt->fetch();
    $stmt->close();

    // Insertar el pago en la base de datos
    $stmt = $conn->prepare("INSERT INTO pago (reserva_id, fecha_pago, monto, metodo_pago) VALUES (?, NOW(), ?, ?)");
    $stmt->bind_param("ids", $reserva_id, $monto, $metodo_pago);

    if ($stmt->execute()) {
        header("Location: mis_reservas.php");
        exit();
    } else {
        $error = "Error al procesar el pago";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Pagar Reserva</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h1 class="my-5">Pagar Reserva</h1>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        <div class="mt-4">
            <a href="mis_reservas.php" class="btn btn-secondary">Volver a Mis Reservas</a>
        </div>
    </div>
</body>
</html>
