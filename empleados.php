<?php
require 'includes/auth.php';
check_login();
require 'includes/db.php';

// Obtener la lista de empleados
$stmt = $conn->prepare("SELECT nombre, apellido, email, telefono, puesto FROM empleado");
$stmt->execute();
$empleados = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Empleados</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/css.css">
    
</head>
<body>
    <div class="container">
        <h1 class="my-5">Lista de Empleados</h1>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Email</th>
                    <th>Teléfono</th>
                    <th>Puesto</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($empleado = $empleados->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($empleado['nombre']); ?></td>
                    <td><?php echo htmlspecialchars($empleado['apellido']); ?></td>
                    <td><?php echo htmlspecialchars($empleado['email']); ?></td>
                    <td><?php echo htmlspecialchars($empleado['telefono']); ?></td>
                    <td><?php echo htmlspecialchars($empleado['puesto']); ?></td>
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
