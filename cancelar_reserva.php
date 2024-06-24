<?php
require 'includes/auth.php';
check_login();
require 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $reserva_id = $_POST['reserva_id'];

    // Eliminar la reserva
    $stmt = $conn->prepare("DELETE FROM reserva WHERE reserva_id = ?");
    $stmt->bind_param("i", $reserva_id);

    if ($stmt->execute()) {
        $message = "Reserva cancelada con éxito.";
    } else {
        $error = "Error al cancelar la reserva.";
    }
}
header("Location: mis_reservas.php");
exit();
?>
