<?php
require 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    $telefono = $_POST['telefono'];
    $direccion = $_POST['direccion'];

    // Validar que se ha ingresado una fecha de nacimiento válida
    if (!empty($fecha_nacimiento) && $fecha_nacimiento !== '0000-00-00') {
        // Calcular la edad del usuario
        $fecha_actual = new DateTime();
        $fecha_nac = new DateTime($fecha_nacimiento);
        $edad = $fecha_actual->diff($fecha_nac)->y;

        if ($edad >= 18) {
            $stmt = $conn->prepare("INSERT INTO cliente (nombre, apellido, email, contrasena, fecha_nacimiento, telefono, direccion) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssss", $nombre, $apellido, $email, $password, $fecha_nacimiento, $telefono, $direccion);

            if ($stmt->execute()) {
                header("Location: login.php");
                exit();
            } else {
                $error = "Error al registrar el usuario";
            }
        } else {
            $error = "Debes ser mayor de edad para registrarte.";
        }
    } else {
        $error = "Por favor, ingrese una fecha de nacimiento válida.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrarse</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h1 class="my-5">Registrarse</h1>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        <form action="register.php" method="post">
            <div class="form-group">
                <label for="nombre">Nombre:</label>
                <input type="text" class="form-control" name="nombre" required>
            </div>
            <div class="form-group">
                <label for="apellido">Apellido:</label>
                <input type="text" class="form-control" name="apellido" required>
            </div>
            <div class="form-group">
                <label for="email">Correo Electrónico:</label>
                <input type="email" class="form-control" name="email" required>
            </div>
            <div class="form-group">
                <label for="fecha_nacimiento">Fecha de Nacimiento:</label>
                <input type="date" class="form-control" name="fecha_nacimiento" required>
            </div>
            <div class="form-group">
                <label for="telefono">Teléfono:</label>
                <input type="text" class="form-control" name="telefono" required>
            </div>
            <div class="form-group">
                <label for="direccion">Dirección:</label>
                <input type="text" class="form-control" name="direccion" required>
            </div>
            <div class="form-group">
                <label for="password">Contraseña:</label>
                <input type="password" class="form-control" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Registrarse</button>
        </form>
        <div class="mt-4">
            <a href="login.php" class="btn btn-secondary">¿Ya tienes una cuenta? Inicia sesión</a>
        </div>
    </div>
</body>
</html>
