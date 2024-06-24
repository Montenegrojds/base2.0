<?php
require 'includes/auth.php';
check_login();
require 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre_articulo = $_POST['nombre_articulo'];
    $descripcion = $_POST['descripcion'];
    $cantidad = $_POST['cantidad'];
    $proveedor_id = $_POST['proveedor_id'];

    $stmt = $conn->prepare("INSERT INTO inventario (nombre_articulo, descripcion, cantidad, proveedor_id) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssii", $nombre_articulo, $descripcion, $cantidad, $proveedor_id);

    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Artículo añadido con éxito</div>";
    } else {
        echo "<div class='alert alert-danger'>Error al añadir el artículo</div>";
    }
}

$stmt_proveedores = $conn->prepare("SELECT * FROM proveedor");
$stmt_proveedores->execute();
$result_proveedores = $stmt_proveedores->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Inventario</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h1 class="my-5">Gestión de Inventario</h1>
        <form action="inventario.php" method="post">
            <div class="form-group">
                <label for="nombre_articulo">Nombre del Artículo:</label>
                <input type="text" class="form-control" name="nombre_articulo" required>
            </div>
            <div class="form-group">
                <label for="descripcion">Descripción:</label>
                <textarea class="form-control" name="descripcion" rows="3" required></textarea>
            </div>
            <div class="form-group">
                <label for="cantidad">Cantidad:</label>
                <input type="number" class="form-control" name="cantidad" required>
            </div>
            <div class="form-group">
                <label for="proveedor_id">Proveedor:</label>
                <select class="form-control" name="proveedor_id" required>
                    <?php while ($row = $result_proveedores->fetch_assoc()): ?>
                    <option value="<?php echo $row['proveedor_id']; ?>"><?php echo htmlspecialchars($row['nombre_empresa']); ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Añadir Artículo</button>
        </form>
    </div>

    <footer class="footer">
        <p>&copy; 2024 Sistema de Gestión Hotelera</p>
    </footer>
</body>
</html>
