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
    <title>Panel de Inventario</title>
    <!-- Iconos Lucide/FontAwesome para darle realismo -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --bg-main: #f1f5f9;
            --sidebar-bg: #0f172a;
            --card-bg: #ffffff;
            --primary: #3b82f6;
            --text-main: #334155;
            --text-muted: #64748b;
        }

        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: var(--bg-main); color: var(--text-main); margin: 0; display: flex; min-height: 100vh; }
        
        /* Sidebar */
        .sidebar { width: 240px; background-color: var(--sidebar-bg); color: white; padding: 20px; display: flex; flex-direction: column; }
        .sidebar h2 { font-size: 1.2rem; margin-bottom: 30px; display: flex; align-items: center; gap: 10px; }
        .sidebar a { color: #94a3b8; text-decoration: none; padding: 12px; border-radius: 8px; margin-bottom: 5px; display: flex; align-items: center; gap: 10px; }
        .sidebar a.active, .sidebar a:hover { background-color: #1e293b; color: white; }

        /* Content Area */
        .main-content { flex: 1; padding: 30px; }
        .top-bar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; }
        
        /* KPI Cards */
        .kpi-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 25px; }
        .kpi-card { background: var(--card-bg); padding: 20px; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
        .kpi-card h4 { margin: 0; color: var(--text-muted); font-size: 0.85rem; text-transform: uppercase; }
        .kpi-card p { margin: 10px 0 0 0; font-size: 1.5rem; font-weight: bold; }

        /* Table & Form Container */
        .table-container { background: var(--card-bg); border-radius: 12px; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
        .table-header { display: flex; justify-content: space-between; margin-bottom: 20px; }
        
        table { width: 100%; border-collapse: collapse; }
        th { text-align: left; padding: 12px; color: var(--text-muted); border-bottom: 2px solid #e2e8f0; font-size: 0.85rem; }
        td { padding: 14px 12px; border-bottom: 1px solid #f1f5f9; font-size: 0.95rem; }
        
        /* Badges & Buttons */
        .badge { padding: 4px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 600; }
        .badge-danger { background: #fee2e2; color: #991b1b; }
        .badge-warning { background: #fef3c7; color: #92400e; }
        .badge-success { background: #dcfce7; color: #166534; }

        .btn { padding: 8px 16px; border-radius: 6px; text-decoration: none; font-size: 0.9rem; font-weight: 500; cursor: pointer; border: none; }
        .btn-primary { background: var(--primary); color: white; }
        .btn-action { color: var(--text-muted); padding: 6px 10px; border-radius: 4px; }
        .btn-action:hover { background: #f1f5f9; color: var(--text-main); }
    </style>
</head>
<body>

<div class="sidebar">
    <h2><i class="fa-solid fa-boxes-stacked"></i> StockControl</h2>
    <a href="#" class="active"><i class="fa-solid fa-box"></i> Inventario</a>
    <a href="#"><i class="fa-solid fa-chart-line"></i> Reportes</a>
    <a href="#"><i class="fa-solid fa-gear"></i> Configuración</a>
</div>

<div class="main-content">
    <div class="top-bar">
        <h1>Gestión de Productos</h1>
        <a href="agregar.php" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Nuevo Producto</a>
    </div>

    <!-- Tarjetas de Resumen (KPIs) -->
    <div class="kpi-grid">
        <div class="kpi-card">
            <h4>Total Productos</h4>
            <p><?= count($productos) ?></p>
        </div>
        <div class="kpi-card">
            <h4>Sin Stock</h4>
            <p style="color: #ef4444;">2</p>
        </div>
        <div class="kpi-card">
            <h4>Categorías</h4>
            <p>4</p>
        </div>
    </div>

    <!-- Tabla Principal -->
    <div class="table-container">
        <div class="table-header">
            <form method="GET" action="index.php" style="display: flex; gap: 10px;">
                <input type="text" name="buscar" placeholder="Buscar producto..." value="<?= htmlspecialchars($busqueda) ?>" style="padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px; width: 250px;">
                <button type="submit" class="btn" style="background: #e2e8f0;">Buscar</button>
            </form>
        </div>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>PRODUCTO</th>
                    <th>CATEGORÍA</th>
                    <th>ESTADO / STOCK</th>
                    <th>PRECIO</th>
                    <th>ACCIONES</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($productos as $producto): ?>
                <tr>
                    <td>#<?= $producto['id'] ?></td>
                    <td><strong><?= htmlspecialchars($producto['nombre']) ?></strong></td>
                    <td><?= htmlspecialchars($producto['categoria'] ?? 'General') ?></td>
                    <td>
                        <?php if ($producto['stock'] <= 0): ?>
                            <span class="badge badge-danger">Sin Stock</span>
                        <?php else: ?>
                            <span class="badge badge-success"><?= $producto['stock'] ?> <?= ($producto['stock'] == 1) ? 'unidad' : 'unidades' ?></span>
                        <?php endif; ?>
                    </td>
                    <td>$<?= number_format($producto['precio'], 2) ?></td>
                    <td>
                        <a href="editar.php?id=<?= $producto['id'] ?>" class="btn-action"><i class="fa-solid fa-pen"></i></a>
                        <a href="eliminar.php?id=<?= $producto['id'] ?>" class="btn-action" style="color: #ef4444;" onclick="return confirm('¿Eliminar?');"><i class="fa-solid fa-trash"></i></a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>