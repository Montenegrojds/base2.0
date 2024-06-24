<?php
require 'includes/auth.php';
check_login();
require 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre_evento = $_POST['nombre_evento'];
    $fecha_evento = $_POST['fecha_evento'];
    $descripcion = $_POST['descripcion'];
    $nombre_organizador = $_POST['nombre_organizador'];
    $contacto_organizador = $_POST['contacto_organizador'];
    $precio_evento = $_POST['precio_evento'];

    // Insertar el evento propuesto en la base de datos
    $stmt = $conn->prepare("INSERT INTO evento (nombre_evento, fecha_evento, descripcion, nombre_organizador, contacto_organizador, precio_evento) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssd", $nombre_evento, $fecha_evento, $descripcion, $nombre_organizador, $contacto_organizador, $precio_evento);

    if ($stmt->execute()) {
        $message = "Evento propuesto con éxito.";
    } else {
        $error = "Error al proponer el evento.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Proponer Evento</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/css.css">
</head>
<body>
    <div class="container">
        <h1 class="my-5">Proponer Evento</h1>
        <?php if (isset($message)): ?>
            <div class="alert alert-success"><?php echo $message; ?></div>
        <?php elseif (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        <form action="proponer_evento.php" method="post">
            <div class="form-group">
                <label for="nombre_evento">Nombre del Evento:</label>
                <input type="text" class="form-control" name="nombre_evento" required>
            </div>
            <div class="form-group">
                <label for="fecha_evento">Fecha del Evento:</label>
                <input type="date" class="form-control" name="fecha_evento" required>
            </div>
            <div class="form-group">
                <label for="descripcion">Descripción:</label>
                <textarea class="form-control" name="descripcion" required></textarea>
            </div>
            <div class="form-group">
                <label for="nombre_organizador">Nombre del Organizador:</label>
                <input type="text" class="form-control" name="nombre_organizador" required>
            </div>
            <div class="form-group">
                <label for="contacto_organizador">Contacto del Organizador:</label>
                <input type="text" class="form-control" name="contacto_organizador" required>
            </div>
            <div class="form-group">
                <label for="precio_evento">Precio del Evento:</label>
                <input type="number" step="0.01" class="form-control" name="precio_evento" required>
            </div>
            <button type="submit" class="btn btn-primary">Proponer</button>
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
