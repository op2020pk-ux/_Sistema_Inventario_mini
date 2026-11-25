<?php
require_once __DIR__ . '/config.php';
requiere_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $op = $_POST['op'] ?? '';

    if ($op === 'crear_entrada') {
        $productId  = (int) ($_POST['product_id'] ?? 0);
        $vendorId   = (int) ($_POST['vendor_id'] ?? 0);
        $chalanNo   = trim($_POST['chalan_no'] ?? date('Y-m-d'));
        $buyPrice   = (float) ($_POST['buying_price'] ?? 0);
        $sellPrice  = (float) ($_POST['selling_price'] ?? 0);
        $discount   = (float) ($_POST['discount'] ?? 0);
        $quantity   = (int) ($_POST['stock_quantity'] ?? 0);
        $note       = trim($_POST['note'] ?? '');

        $prodStmt = db()->prepare("SELECT category_id FROM products WHERE id = :id LIMIT 1");
        $prodStmt->execute(['id' => $productId]);
        $prod = $prodStmt->fetch();

        if (!$prod || $vendorId <= 0 || $quantity <= 0) {
            flash('warning', 'Verifica producto, proveedor y cantidad mayor a 0.');
        } else {
            $code = (string) time();
            $stmt = db()->prepare("
                INSERT INTO stocks (
                    category_id, product_code, product_id, vendor_id, user_id,
                    chalan_no, buying_price, selling_price, discount,
                    stock_quantity, current_quantity, note, status, created_at, updated_at
                ) VALUES (
                    :cat, :code, :prod, :vend, :usr,
                    :chalan, :buy, :sell, :disc,
                    :qty, :curr, :note, 1, :cre, :upd
                )
            ");
            $stmt->execute([
                'cat'    => (int) $prod['category_id'],
                'code'   => $code,
                'prod'   => $productId,
                'vend'   => $vendorId,
                'usr'    => (int) $_SESSION['user_id'],
                'chalan' => $chalanNo,
                'buy'    => $buyPrice,
                'sell'   => $sellPrice,
                'disc'   => $discount,
                'qty'    => $quantity,
                'curr'   => $quantity,
                'note'   => $note !== '' ? $note : null,
                'cre'    => ahora(),
                'upd'    => ahora(),
            ]);
            flash('success', 'Entrada de stock registrada (código ' . $code . ').');
        }
    } elseif ($op === 'ajustar_cantidad') {
        $stockId = (int) ($_POST['stock_id'] ?? 0);
        $nuevaCant = max(0, (int) ($_POST['current_quantity'] ?? 0));
        if ($stockId > 0) {
            $stmt = db()->prepare("UPDATE stocks SET current_quantity = :curr, updated_at = :upd WHERE id = :id");
            $stmt->execute(['curr' => $nuevaCant, 'upd' => ahora(), 'id' => $stockId]);
            flash('success', 'Existencia actual actualizada.');
        }
    }

    header('Location: existencias.php');
    exit;
}

$productos = db()->query("SELECT id, product_name FROM products ORDER BY product_name ASC")->fetchAll();
$proveedores = db()->query("SELECT id, name FROM vendors ORDER BY name ASC")->fetchAll();

$entradas = db()->query("
    SELECT s.*, p.product_name, v.name AS vendor_name, c.name AS category_name
    FROM stocks s
    LEFT JOIN products p ON p.id = s.product_id
    LEFT JOIN vendors v ON v.id = s.vendor_id
    LEFT JOIN categories c ON c.id = s.category_id
    ORDER BY s.id DESC
")->fetchAll();

$empresa = empresa_info();
$f = tomar_flash();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Existencias - <?= e($empresa['name']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-dark bg-dark px-3 mb-4">
    <a class="navbar-brand" href="index.php"><?= e($empresa['name']) ?></a>
    <a href="index.php" class="btn btn-sm btn-outline-light">Inicio</a>
</nav>

<div class="container-fluid px-4 pb-5">
    <?php if ($f): ?>
        <div class="alert alert-<?= e($f['tipo']) ?>"><?= e($f['mensaje']) ?></div>
    <?php endif; ?>

    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-header fw-bold">Registrar entrada de stock</div>
                <div class="card-body">
                    <form method="post">
                        <input type="hidden" name="op" value="crear_entrada">

                        <div class="mb-3">
                            <label class="form-label">Producto</label>
                            <select name="product_id" class="form-select" required>
                                <option value="">-- Seleccionar --</option>
                                <?php foreach ($productos as $p): ?>
                                    <option value="<?= (int) $p['id'] ?>"><?= e($p['product_name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Proveedor</label>
                            <select name="vendor_id" class="form-select" required>
                                <option value="">-- Seleccionar --</option>
                                <?php foreach ($proveedores as $v): ?>
                                    <option value="<?= (int) $v['id'] ?>"><?= e($v['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <label class="form-label">Cantidad inicial</label>
                                <input type="number" name="stock_quantity" class="form-control" min="1" required>
                            </div>
                            <div class="col-6">
                                <label class="form-label">Guía / Chalan</label>
                                <input type="text" name="chalan_no" class="form-control" value="<?= date('Y-m-d') ?>">
                            </div>
                        </div>

                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <label class="form-label">Precio compra</label>
                                <input type="number" step="0.01" name="buying_price" class="form-control" required>
                            </div>
                            <div class="col-6">
                                <label class="form-label">Precio venta</label>
                                <input type="number" step="0.01" name="selling_price" class="form-control" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Descuento</label>
                            <input type="number" step="0.01" name="discount" class="form-control" value="0">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nota</label>
                            <input type="text" name="note" class="form-control">
                        </div>

                        <button type="submit" class="btn btn-success w-100">Guardar entrada</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header fw-bold">Lotes y existencias actuales</div>
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle mb-0">
                        <thead class="table-light">
                        <tr>
                            <th>Código</th>
                            <th>Producto</th>
                            <th>Proveedor</th>
                            <th>Compra / Venta</th>
                            <th>Inicial / Actual</th>
                            <th>Ajustar</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($entradas as $s): ?>
                            <tr>
                                <td class="small"><?= e($s['product_code']) ?></td>
                                <td>
                                    <div class="fw-semibold"><?= e($s['product_name'] ?? '') ?></div>
                                    <div class="small text-muted"><?= e($s['category_name'] ?? '') ?></div>
                                </td>
                                <td class="small"><?= e($s['vendor_name'] ?? '') ?></td>
                                <td class="small"><?= monto((float) $s['buying_price']) ?> / <?= monto((float) $s['selling_price']) ?></td>
                                <td><span class="badge bg-primary"><?= (int) $s['current_quantity'] ?> / <?= (int) $s['stock_quantity'] ?></span></td>
                                <td>
                                    <form method="post" class="d-flex gap-1">
                                        <input type="hidden" name="op" value="ajustar_cantidad">
                                        <input type="hidden" name="stock_id" value="<?= (int) $s['id'] ?>">
                                        <input type="number" name="current_quantity" class="form-control form-control-sm"
                                               style="width: 75px;" min="0" value="<?= (int) $s['current_quantity'] ?>">
                                        <button type="submit" class="btn btn-sm btn-outline-dark">OK</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>