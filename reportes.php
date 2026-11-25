<?php
require_once __DIR__ . '/config.php';
requiere_login();

$tipo = $_GET['tipo'] ?? 'ventas';
if (!in_array($tipo, ['ventas', 'stock', 'ganancias', 'deudas'], true)) {
    $tipo = 'ventas';
}

$desde = $_GET['desde'] ?? '';
$hasta = $_GET['hasta'] ?? '';

$ventas = db()->query("
    SELECT s.*, c.customer_name
    FROM sells s
    LEFT JOIN customers c ON c.id = s.customer_id
    ORDER BY s.id DESC
")->fetchAll();

$stockList = db()->query("
    SELECT s.*, p.product_name, c.name AS category_name, v.name AS vendor_name
    FROM stocks s
    LEFT JOIN products p ON p.id = s.product_id
    LEFT JOIN categories c ON c.id = s.category_id
    LEFT JOIN vendors v ON v.id = s.vendor_id
    ORDER BY s.id DESC
")->fetchAll();

$ganancias = db()->query("
    SELECT sd.id, sd.selling_date, p.product_name, sd.sold_quantity,
           sd.total_buy_price, sd.total_sold_price,
           (sd.total_sold_price - sd.total_buy_price) AS ganancia
    FROM sell_details sd
    LEFT JOIN products p ON p.id = sd.product_id
    ORDER BY sd.id DESC
")->fetchAll();

$deudas = db()->query("
    SELECT s.*, c.customer_name, c.phone,
           (s.total_amount - s.paid_amount) AS saldo_pendiente
    FROM sells s
    LEFT JOIN customers c ON c.id = s.customer_id
    WHERE (s.total_amount - s.paid_amount) > 0.009
    ORDER BY s.id DESC
")->fetchAll();

$empresa = empresa_info();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reportes - <?= e($empresa['name']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-dark bg-dark px-3 mb-4">
    <a class="navbar-brand" href="index.php"><?= e($empresa['name']) ?></a>
    <div class="d-flex gap-2">
        <a href="imprimir.php?tipo=<?= e($tipo) ?>" target="_blank" class="btn btn-sm btn-warning">Imprimir reporte</a>
        <a href="index.php" class="btn btn-sm btn-outline-light">Inicio</a>
    </div>
</nav>

<div class="container-fluid px-4 pb-5">
    <ul class="nav nav-tabs mb-4">
        <li class="nav-item"><a class="nav-link <?= $tipo === 'ventas' ? 'active' : '' ?>" href="reportes.php?tipo=ventas">Ventas</a></li>
        <li class="nav-item"><a class="nav-link <?= $tipo === 'stock' ? 'active' : '' ?>" href="reportes.php?tipo=stock">Existencias</a></li>
        <li class="nav-item"><a class="nav-link <?= $tipo === 'ganancias' ? 'active' : '' ?>" href="reportes.php?tipo=ganancias">Ganancias</a></li>
        <li class="nav-item"><a class="nav-link <?= $tipo === 'deudas' ? 'active' : '' ?>" href="reportes.php?tipo=deudas">Saldos / Deudas</a></li>
    </ul>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <?php if ($tipo === 'ventas'): ?>
                <table class="table table-striped mb-0">
                    <thead><tr><th>ID</th><th>Fecha</th><th>Cliente</th><th>Descuento</th><th>Total</th><th>Pagado</th></tr></thead>
                    <tbody>
                    <?php foreach ($ventas as $v): ?>
                        <tr>
                            <td><?= (int) $v['id'] ?></td>
                            <td><?= e($v['sell_date']) ?></td>
                            <td><?= e($v['customer_name'] ?? '') ?></td>
                            <td><?= monto((float) $v['discount_amount']) ?></td>
                            <td><?= monto((float) $v['total_amount']) ?></td>
                            <td><?= monto((float) $v['paid_amount']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            <?php elseif ($tipo === 'stock'): ?>
                <table class="table table-striped mb-0">
                    <thead><tr><th>Código</th><th>Producto</th><th>Categoría</th><th>Compra</th><th>Venta</th><th>Actual / Inicial</th></tr></thead>
                    <tbody>
                    <?php foreach ($stockList as $s): ?>
                        <tr>
                            <td><?= e($s['product_code']) ?></td>
                            <td><?= e($s['product_name'] ?? '') ?></td>
                            <td><?= e($s['category_name'] ?? '') ?></td>
                            <td><?= monto((float) $s['buying_price']) ?></td>
                            <td><?= monto((float) $s['selling_price']) ?></td>
                            <td><?= (int) $s['current_quantity'] ?> / <?= (int) $s['stock_quantity'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            <?php elseif ($tipo === 'ganancias'): ?>
                <table class="table table-striped mb-0">
                    <thead><tr><th>Fecha</th><th>Producto</th><th>Cant.</th><th>Costo total</th><th>Venta total</th><th>Ganancia</th></tr></thead>
                    <tbody>
                    <?php foreach ($ganancias as $g): ?>
                        <tr>
                            <td><?= e($g['selling_date']) ?></td>
                            <td><?= e($g['product_name'] ?? '') ?></td>
                            <td><?= (int) $g['sold_quantity'] ?></td>
                            <td><?= monto((float) $g['total_buy_price']) ?></td>
                            <td><?= monto((float) $g['total_sold_price']) ?></td>
                            <td class="fw-bold text-success"><?= monto((float) $g['ganancia']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <table class="table table-striped mb-0">
                    <thead><tr><th>Venta #</th><th>Fecha</th><th>Cliente</th><th>Teléfono</th><th>Total</th><th>Pagado</th><th>Saldo pendiente</th></tr></thead>
                    <tbody>
                    <?php foreach ($deudas as $d): ?>
                        <tr>
                            <td><?= (int) $d['id'] ?></td>
                            <td><?= e($d['sell_date']) ?></td>
                            <td><?= e($d['customer_name'] ?? '') ?></td>
                            <td><?= e($d['phone'] ?? '') ?></td>
                            <td><?= monto((float) $d['total_amount']) ?></td>
                            <td><?= monto((float) $d['paid_amount']) ?></td>
                            <td class="fw-bold text-danger"><?= monto((float) $d['saldo_pendiente']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>