<?php
require_once 'conexion.php';

$busqueda = trim($_GET['buscar'] ?? '');

if (!empty($busqueda)) {
    // Dividir la búsqueda por espacios en palabras clave
    $palabras = array_filter(explode(' ', $busqueda));
    $condiciones = [];
    $parametros = [];

    foreach ($palabras as $palabra) {
        // Cada palabra debe coincidir en el nombre o en la categoría
        $condiciones[] = "(nombre LIKE ? OR categoria LIKE ?)";
        $term = '%' . $palabra . '%';
        $parametros[] = $term;
        $parametros[] = $term;
    }

    $sql = "SELECT * FROM productos WHERE " . implode(' AND ', $condiciones) . " ORDER BY id DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($parametros);
} else {
    $stmt = $pdo->query("SELECT * FROM productos ORDER BY id DESC");
}

$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Inventario</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f6f9; margin: 0; padding: 40px; }
        .container { max-width: 1000px; margin: 0 auto; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        h1 { color: #333; margin: 0; }
        .btn-agregar { background-color: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; background-color: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        th { background-color: #212529; color: white; padding: 12px; text-align: left; }
        td { padding: 12px; border-bottom: 1px solid #dee2e6; }
        .badge { padding: 4px 8px; border-radius: 4px; font-size: 12px; color: white; font-weight: bold; }
        .badge-danger { background-color: #dc3545; }
        .badge-warning { background-color: #ffc107; color: #212529; }
        .badge-success { background-color: #28a745; }
        .btn-editar { color: #007bff; border: 1px solid #007bff; padding: 4px 8px; text-decoration: none; border-radius: 4px; }
        .btn-eliminar { color: #dc3545; border: 1px solid #dc3545; padding: 4px 8px; text-decoration: none; border-radius: 4px; margin-left: 5px; }
        .no-data { text-align: center; padding: 20px; color: #6c757d; }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h1>Gestión de Inventario</h1>
        <a href="agregar.php" class="btn-agregar">+ Agregar Producto</a>
    </div>

    <!-- Formulario de Búsqueda -->
    <form method="GET" action="index.php" style="margin-bottom: 20px; display: flex; gap: 10px;">
        <input type="text" name="buscar" placeholder="Buscar por nombre o categoría..." value="<?= htmlspecialchars($busqueda) ?>" style="padding: 8px; width: 300px; border: 1px solid #ccc; border-radius: 4px;">
        <button type="submit" style="padding: 8px 16px; background-color: #212529; color: white; border: none; border-radius: 4px; cursor: pointer;">Buscar</button>
        <?php if (!empty($busqueda)): ?>
            <a href="index.php" style="padding: 8px 12px; background-color: #6c757d; color: white; text-decoration: none; border-radius: 4px;">Limpiar</a>
        <?php endif; ?>
    </form>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Producto</th>
                <th>Categoría</th>
                <th>Stock</th>
                <th>Precio</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($productos) > 0): ?>
                <?php foreach ($productos as $producto): ?>
                    <tr>
                        <td><?= htmlspecialchars($producto['id']) ?></td>
                        <td><?= htmlspecialchars($producto['nombre']) ?></td>
                        <td><?= htmlspecialchars($producto['categoria'] ?? 'General') ?></td>
                        <td>
                            <?php 
                                $unidad_texto = ($producto['stock'] == 1) ? 'unidad' : 'unidades';
                            ?>
                            <?php if ($producto['stock'] <= 0): ?>
                                <span class="badge badge-danger">Sin Stock</span>
                            <?php elseif ($producto['stock'] < 5): ?>
                                <span class="badge badge-warning"><?= $producto['stock'] ?> <?= $unidad_texto ?></span>
                            <?php else: ?>
                                <span class="badge badge-success"><?= $producto['stock'] ?> <?= $unidad_texto ?></span>
                            <?php endif; ?>
                        </td>
                        <td>$<?= number_format($producto['precio'], 2) ?></td>
                        <td>
                            <a href="editar.php?id=<?= $producto['id'] ?>" class="btn-editar">Editar</a>
                            <a href="eliminar.php?id=<?= $producto['id'] ?>" class="btn-eliminar" onclick="return confirm('¿Eliminar este producto?');">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="no-data">No hay productos registrados.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>