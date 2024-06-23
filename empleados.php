<?php
require 'includes/auth.php';
check_login();
require 'includes/db.php';

$stmt = $conn->prepare("SELECT * FROM empleado");
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Información de Empleados</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h1 class="my-5">Información de Empleados</h1>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Email</th>
                    <th>Teléfono</th>
                    <th>Puesto</th>
                    <th>Salario</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['empleado_id']); ?></td>
                    <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                    <td><?php echo htmlspecialchars($row['apellido']); ?></td>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                    <td><?php echo htmlspecialchars($row['telefono']); ?></td>
                    <td><?php echo htmlspecialchars($row['puesto']); ?></td>
                    <td><?php echo htmlspecialchars($row['salario']); ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <div class="mt-4">
            <a href="reserva.php" class="btn btn-secondary">Reservar Habitación</a>
            <a href="reserva_actividad.php" class="btn btn-secondary">Reservar Actividad</a>
            <a href="dashboard.php" class="btn btn-secondary">Volver al Dashboard</a>
        </div>
    </div>
</body>
</html>
