<?php
require_once 'conexion.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    header('Location: index.php');
    exit;
}

// Obtener datos actuales del producto mediante PDO
$stmt = $pdo->prepare("SELECT * FROM productos WHERE id = ?");
$stmt->execute([$id]);
$producto = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$producto) {
    header('Location: index.php');
    exit;
}

// Procesar la actualización al enviar el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre    = $_POST['nombre'];
    $categoria = $_POST['categoria'];
    $precio    = $_POST['precio'];
    $stock     = $_POST['stock'];

    $updateStmt = $pdo->prepare("UPDATE productos SET nombre = ?, categoria = ?, precio = ?, stock = ? WHERE id = ?");
    
    if ($updateStmt->execute([$nombre, $categoria, $precio, $stock, $id])) {
        header('Location: index.php');
        exit;
    } else {
        $error = "Error al actualizar el producto.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Producto</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f6f9; padding: 40px; }
        .form-card { max-width: 500px; margin: auto; background: white; padding: 25px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        input { width: 100%; padding: 8px; margin: 8px 0 15px 0; box-sizing: border-box; }
        button { background: #007bff; color: white; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; }
        a { text-decoration: none; color: #6c757d; margin-left: 10px; }
    </style>
</head>
<body>

<div class="form-card">
    <h2>Editar Producto #<?= htmlspecialchars($producto['id']) ?></h2>

    <?php if (isset($error)): ?>
        <p style="color: red;"><?= $error ?></p>
    <?php endif; ?>

    <form action="editar.php?id=<?= $id ?>" method="POST">
        <label>Nombre:</label>
        <input type="text" name="nombre" value="<?= htmlspecialchars($producto['nombre']) ?>" required>

        <label>Categoría:</label>
        <input type="text" name="categoria" value="<?= htmlspecialchars($producto['categoria'] ?? '') ?>" required>

        <label>Precio:</label>
        <input type="number" step="0.01" name="precio" value="<?= htmlspecialchars($producto['precio']) ?>" required>

        <label>Stock:</label>
        <input type="number" name="stock" value="<?= htmlspecialchars($producto['stock']) ?>" required>

        <button type="submit">Guardar Cambios</button>
        <a href="index.php">Cancelar</a>
    </form>
</div>

</body>
</html>