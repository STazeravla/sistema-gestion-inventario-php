<?php
require_once 'conexion.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre    = trim($_POST['nombre']);
    $categoria = trim($_POST['categoria']);
    $stock     = intval($_POST['stock']);
    $precio    = floatval($_POST['precio']);

    if (!empty($nombre) && !empty($categoria) && $stock >= 0 && $precio >= 0) {
        $sql = "INSERT INTO productos (nombre, categoria, stock, precio) VALUES (:nombre, :categoria, :stock, :precio)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nombre'    => $nombre,
            ':categoria' => $categoria,
            ':stock'     => $stock,
            ':precio'    => $precio
        ]);

        header('Location: index.php');
        exit;
    } else {
        $error = 'Por favor, completa todos los campos con valores válidos.';
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Producto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container my-5" style="max-width: 600px;">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Nuevo Producto</h4>
        </div>
        <div class="card-body">

            <?php if ($error): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>

            <form method="POST" action="agregar.php">
                <div class="mb-3">
                    <label class="form-label">Nombre del Producto</label>
                    <input type="text" name="nombre" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Categoría</label>
                    <input type="text" name="categoria" class="form-control" placeholder="Ej: Periféricos, Hardware" required>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Stock Inicial</label>
                        <input type="number" name="stock" class="form-control" min="0" value="0" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Precio ($)</label>
                        <input type="number" step="0.01" name="precio" class="form-control" min="0" value="0.00" required>
                    </div>
                </div>
                <div class="d-flex justify-content-between">
                    <a href="index.php" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-primary">Guardar Producto</button>
                </div>
            </form>

        </div>
    </div>
</div>

</body>
</html>