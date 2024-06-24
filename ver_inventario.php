<?php
require 'includes/auth.php';
check_login();
require 'includes/db.php';

// Obtener la lista de artículos del inventario
$stmt = $conn->prepare("SELECT nombre_articulo, descripcion, cantidad FROM inventario");
$stmt->execute();
$inventario = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inventario Disponible</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/css.css">
</head>
<body>
    <div class="container">
        <h1 class="my-5">Inventario Disponible</h1>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nombre del Artículo</th>
                    <th>Descripción</th>
                    <th>Cantidad</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($articulo = $inventario->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($articulo['nombre_articulo']); ?></td>
                    <td><?php echo htmlspecialchars($articulo['descripcion']); ?></td>
                    <td><?php echo htmlspecialchars($articulo['cantidad']); ?></td>
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
