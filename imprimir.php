<?php
require_once __DIR__ . '/config.php';
requiere_login();

$empresa = empresa_info();
$tipo = $_GET['tipo'] ?? 'factura';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Imprimir - <?= e($empresa['name']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @media print { .no-print { display: none !important; } }
    </style>
</head>
<body class="p-4">
<div class="container">
    <div class="d-flex justify-content-between align-items-start border-bottom pb-3 mb-4">
        <div>
            <h4 class="mb-1"><?= e($empresa['name']) ?></h4>
            <div class="small text-muted"><?= e($empresa['address'] ?? '') ?></div>
            <div class="small text-muted">Tel: <?= e($empresa['phone'] ?? '') ?></div>
        </div>
        <div class="d-flex gap-2 no-print">
            <button onclick="window.print()" class="btn btn-dark btn-sm">Imprimir / Guardar PDF</button>
            <a href="index.php" onclick="cerrarFacturaEIrInicio(event)" class="btn btn-outline-primary btn-sm">Cerrar e ir al inicio</a>
        </div>
    </div>

    <?php if ($tipo === 'factura'): ?>
        <?php
        $id = (int) ($_GET['id'] ?? 0);
        $vStmt = db()->prepare("
            SELECT s.*, c.customer_name, c.phone, c.address
            FROM sells s
            LEFT JOIN customers c ON c.id = s.customer_id
            WHERE s.id = :id LIMIT 1
        ");
        $vStmt->execute(['id' => $id]);
        $venta = $vStmt->fetch();

        $detStmt = db()->prepare("
            SELECT sd.*, p.product_name
            FROM sell_details sd
            LEFT JOIN products p ON p.id = sd.product_id
            WHERE sd.sell_id = :id
        ");
        $detStmt->execute(['id' => $id]);
        $detalles = $detStmt->fetchAll();
        ?>
        <?php if ($venta): ?>
            <div class="mb-3">
                <strong>Factura #<?= (int) $venta['id'] ?></strong> | Fecha: <?= e($venta['sell_date']) ?><br>
                Cliente: <?= e($venta['customer_name'] ?? '') ?> (<?= e($venta['phone'] ?? '') ?>)
            </div>
            <table class="table table-bordered">
                <thead><tr><th>Producto</th><th>Cant.</th><th>Precio</th><th>Descuento</th><th>Total</th></tr></thead>
                <tbody>
                <?php foreach ($detalles as $det): ?>
                    <tr>
                        <td><?= e($det['product_name'] ?? '') ?></td>
                        <td><?= (int) $det['sold_quantity'] ?></td>
                        <td><?= monto((float) $det['sold_price']) ?></td>
                        <td><?= monto((float) $det['discount_amount']) ?></td>
                        <td><?= monto((float) $det['total_sold_price']) ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <div class="text-end">
                <div>Total factura: <strong><?= monto((float) $venta['total_amount']) ?></strong></div>
                <div>Pagado: <strong><?= monto((float) $venta['paid_amount']) ?></strong></div>
                <div>Saldo: <strong><?= monto(max(0.0, (float) $venta['total_amount'] - (float) $venta['paid_amount'])) ?></strong></div>
            </div>
        <?php else: ?>
            <div class="alert alert-warning">Factura no encontrada.</div>
        <?php endif; ?>
    <?php else: ?>
        <h5 class="mb-3">Reporte de <?= e(ucfirst($tipo)) ?></h5>
        <?php
        if ($tipo === 'stock') {
            $rows = db()->query("SELECT s.product_code, p.product_name, s.buying_price, s.selling_price, s.current_quantity FROM stocks s LEFT JOIN products p ON p.id = s.product_id ORDER BY s.id DESC")->fetchAll();
            echo '<table class="table table-bordered"><thead><tr><th>Código</th><th>Producto</th><th>Compra</th><th>Venta</th><th>Actual</th></tr></thead><tbody>';
            foreach ($rows as $r) {
                echo '<tr><td>' . e($r['product_code']) . '</td><td>' . e($r['product_name'] ?? '') . '</td><td>' . monto((float) $r['buying_price']) . '</td><td>' . monto((float) $r['selling_price']) . '</td><td>' . (int) $r['current_quantity'] . '</td></tr>';
            }
            echo '</tbody></table>';
        } else {
            $rows = db()->query("SELECT s.id, s.sell_date, c.customer_name, s.total_amount, s.paid_amount FROM sells s LEFT JOIN customers c ON c.id = s.customer_id ORDER BY s.id DESC")->fetchAll();
            echo '<table class="table table-bordered"><thead><tr><th>#</th><th>Fecha</th><th>Cliente</th><th>Total</th><th>Pagado</th></tr></thead><tbody>';
            foreach ($rows as $r) {
                echo '<tr><td>' . (int) $r['id'] . '</td><td>' . e($r['sell_date']) . '</td><td>' . e($r['customer_name'] ?? '') . '</td><td>' . monto((float) $r['total_amount']) . '</td><td>' . monto((float) $r['paid_amount']) . '</td></tr>';
            }
            echo '</tbody></table>';
        }
        ?>
    <?php endif; ?>
</div>
<script>
function cerrarFacturaEIrInicio(e) {
    e.preventDefault();
    if (window.opener && !window.opener.closed) {
        window.opener.location.href = 'index.php';
        window.opener.focus();
        window.close();
        return;
    }
    window.location.href = 'index.php';
}
</script>
</body>
</html>