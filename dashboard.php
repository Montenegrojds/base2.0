<?php
require 'includes/auth.php';
check_login();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">Gestión Hotel</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item"><a class="nav-link" href="reserva.php">Reservar Habitación</a></li>
                <li class="nav-item"><a class="nav-link" href="reserva_actividad.php">Reservar Actividad</a></li>
                <li class="nav-item"><a class="nav-link" href="empleados.php">Ver Información de Empleados</a></li>
                <?php if (is_admin()): ?>
                    <li class="nav-item"><a class="nav-link" href="admin.php">Administración</a></li>
                <?php endif; ?>
                <li class="nav-item"><a class="nav-link" href="logout.php">Cerrar Sesión</a></li>
            </ul>
        </div>
    </nav>
    <div class="container">
        <h1 class="my-5">Bienvenido, <?php echo $_SESSION['user_name']; ?></h1>
    </div>
</body>
</html>
