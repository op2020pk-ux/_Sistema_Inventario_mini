<?php
require_once __DIR__ . '/config.php';
requiere_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $op = $_POST['op'] ?? '';

    if ($op === 'crear_venta') {
        $customerId    = (int) ($_POST['customer_id'] ?? 0);
        $branchId      = (int) ($_POST['branch_id'] ?? ($_SESSION['branch_id'] ?: 1));
        $sellDate      = trim($_POST['sell_date'] ?? date('Y-m-d'));
        $stockId       = (int) ($_POST['stock_id'] ?? 0);
        $soldQty       = (int) ($_POST['sold_quantity'] ?? 0);
        $discountAmt   = max(0.0, (float) ($_POST['discount_amount'] ?? 0));
        $paidAmount    = max(0.0, (float) ($_POST['paid_amount'] ?? 0));
        $paymentMethod = (int) ($_POST['payment_method'] ?? 1);
        $paidIn        = trim($_POST['paid_in'] ?? 'efectivo');
        $bankInfo      = trim($_POST['bank_information'] ?? '');

        $stStmt = db()->prepare("SELECT * FROM stocks WHERE id = :id LIMIT 1");
        $stStmt->execute(['id' => $stockId]);
        $lote = $stStmt->fetch();

        if ($customerId <= 0 || !$lote || $soldQty <= 0) {
            flash('warning', 'Selecciona un cliente, un lote con existencia y una cantidad válida.');
        } elseif ($soldQty > (int) $lote['current_quantity']) {
            flash('danger', 'No hay suficiente existencia en el lote seleccionado (disponible: ' . (int) $lote['current_quantity'] . ').');
        } else {
            $pdo = db();
            $pdo->beginTransaction();
            try {
                $buyPrice      = (float) $lote['buying_price'];
                $soldPrice     = (float) $lote['selling_price'];
                $totalBuy      = $buyPrice * $soldQty;
                $subtotalSold  = $soldPrice * $soldQty;
                $totalAmount   = max(0.0, $subtotalSold - $discountAmt);
                $paymentStatus = ($paidAmount >= $totalAmount && $totalAmount > 0) ? 1 : 0;

                $insSell = $pdo->prepare("
                    INSERT INTO sells (
                        user_id, customer_id, branch_id, total_amount, paid_amount,
                        sell_date, discount_amount, payment_method, payment_status,
                        created_at, updated_at
                    ) VALUES (
                        :usr, :cust, :br, :tot, :paid,
                        :sdate, :disc, :pm, :ps,
                        :cre, :upd
                    )
                ");
                $insSell->execute([
                    'usr'   => (int) $_SESSION['user_id'],
                    'cust'  => $customerId,
                    'br'    => $branchId,
                    'tot'   => $totalAmount,
                    'paid'  => $paidAmount,
                    'sdate' => $sellDate,
                    'disc'  => $discountAmt,
                    'pm'    => $paymentMethod,
                    'ps'    => $paymentStatus,
                    'cre'   => ahora(),
                    'upd'   => ahora(),
                ]);
                $sellId = (int) $pdo->lastInsertId();

                $insDet = $pdo->prepare("
                    INSERT INTO sell_details (
                        stock_id, sell_id, product_id, category_id, vendor_id, user_id,
                        chalan_no, selling_date, customer_id, sold_quantity,
                        buy_price, sold_price, total_buy_price, total_sold_price,
                        discount, discount_type, discount_amount, created_at, updated_at
                    ) VALUES (
                        :stock_id, :sell_id, :prod_id, :cat_id, :vend_id, :usr_id,
                        :chalan, :sdate, :cust_id, :qty,
                        :bprice, :sprice, :tbprice, :tsprice,
                        :disc, 1, :damt, :cre, :upd
                    )
                ");
                $insDet->execute([
                    'stock_id' => (int) $lote['id'],
                    'sell_id'  => $sellId,
                    'prod_id'  => (int) $lote['product_id'],
                    'cat_id'   => (int) $lote['category_id'],
                    'vend_id'  => (int) $lote['vendor_id'],
                    'usr_id'   => (int) $_SESSION['user_id'],
                    'chalan'   => (string) $lote['chalan_no'],
                    'sdate'    => $sellDate,
                    'cust_id'  => (string) $customerId,
                    'qty'      => $soldQty,
                    'bprice'   => $buyPrice,
                    'sprice'   => $soldPrice,
                    'tbprice'  => $totalBuy,
                    'tsprice'  => $totalAmount,
                    'disc'     => $discountAmt,
                    'damt'     => $discountAmt,
                    'cre'      => ahora(),
                    'upd'      => ahora(),
                ]);

                $updStock = $pdo->prepare("
                    UPDATE stocks
                    SET current_quantity = current_quantity - :qty, updated_at = :upd
                    WHERE id = :id
                ");
                $updStock->execute([
                    'qty' => $soldQty,
                    'upd' => ahora(),
                    'id'  => (int) $lote['id'],
                ]);

                if ($paidAmount > 0) {
                    $insPay = $pdo->prepare("
                        INSERT INTO payments (
                            sell_id, customer_id, user_id, date, paid_in,
                            bank_information, amount, created_at, updated_at
                        ) VALUES (
                            :sell_id, :cust_id, :usr_id, :pdate, :paid_in,
                            :binfo, :amt, :cre, :upd
                        )
                    ");
                    $insPay->execute([
                        'sell_id' => $sellId,
                        'cust_id' => $customerId,
                        'usr_id'  => (int) $_SESSION['user_id'],
                        'pdate'   => $sellDate,
                        'paid_in' => $paidIn !== '' ? $paidIn : 'efectivo',
                        'binfo'   => $bankInfo !== '' ? $bankInfo : null,
                        'amt'     => $paidAmount,
                        'cre'     => ahora(),
                        'upd'     => ahora(),
                    ]);
                }

                $pdo->commit();
                flash('success', 'Venta #' . $sellId . ' registrada correctamente.');
            } catch (Throwable $e) {
                $pdo->rollBack();
                flash('danger', 'Error al registrar la venta: ' . $e->getMessage());
            }
        }
    } elseif ($op === 'abonar') {
        $sellId   = (int) ($_POST['sell_id'] ?? 0);
        $abono    = (float) ($_POST['amount'] ?? 0);
        $paidIn   = trim($_POST['paid_in'] ?? 'efectivo');
        $bankInfo = trim($_POST['bank_information'] ?? '');
        $pDate    = trim($_POST['date'] ?? date('Y-m-d'));

        $vStmt = db()->prepare("SELECT * FROM sells WHERE id = :id LIMIT 1");
        $vStmt->execute(['id' => $sellId]);
        $venta = $vStmt->fetch();

        if (!$venta || $abono <= 0) {
            flash('warning', 'Ingresa un monto de abono válido.');
        } else {
            $pdo = db();
            $pdo->beginTransaction();
            try {
                $insPay = $pdo->prepare("
                    INSERT INTO payments (
                        sell_id, customer_id, user_id, date, paid_in,
                        bank_information, amount, created_at, updated_at
                    ) VALUES (
                        :sell_id, :cust_id, :usr_id, :pdate, :paid_in,
                        :binfo, :amt, :cre, :upd
                    )
                ");
                $insPay->execute([
                    'sell_id' => $sellId,
                    'cust_id' => (int) $venta['customer_id'],
                    'usr_id'  => (int) $_SESSION['user_id'],
                    'pdate'   => $pDate,
                    'paid_in' => $paidIn !== '' ? $paidIn : 'efectivo',
                    'binfo'   => $bankInfo !== '' ? $bankInfo : null,
                    'amt'     => $abono,
                    'cre'     => ahora(),
                    'upd'     => ahora(),
                ]);

                $nuevoPagado = (float) $venta['paid_amount'] + $abono;
                $estadoPago  = ($nuevoPagado >= (float) $venta['total_amount']) ? 1 : 0;

                $updSell = $pdo->prepare("
                    UPDATE sells
                    SET paid_amount = :paid, payment_status = :ps, updated_at = :upd
                    WHERE id = :id
                ");
                $updSell->execute([
                    'paid' => $nuevoPagado,
                    'ps'   => $estadoPago,
                    'upd'  => ahora(),
                    'id'   => $sellId,
                ]);

                $pdo->commit();
                flash('success', 'Abono registrado a la venta #' . $sellId . '.');
            } catch (Throwable $e) {
                $pdo->rollBack();
                flash('danger', 'Error al registrar abono: ' . $e->getMessage());
            }
        }
    }

    header('Location: facturacion.php');
    exit;
}

$clientes = db()->query("SELECT id, customer_name FROM customers ORDER BY customer_name ASC")->fetchAll();
$sucursales = db()->query("SELECT id, branch_name FROM branches ORDER BY branch_name ASC")->fetchAll();
$lotesDisponibles = db()->query("
    SELECT s.id, s.product_code, s.selling_price, s.current_quantity, p.product_name
    FROM stocks s
    INNER JOIN products p ON p.id = s.product_id
    WHERE s.current_quantity > 0
    ORDER BY p.product_name ASC, s.id ASC
")->fetchAll();

$ventas = db()->query("
    SELECT s.*, c.customer_name, u.name AS user_name
    FROM sells s
    LEFT JOIN customers c ON c.id = s.customer_id
    LEFT JOIN users u ON u.id = s.user_id
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
    <title>Facturación y Ventas - <?= e($empresa['name']) ?></title>
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
                <div class="card-header fw-bold">Nueva venta / factura</div>
                <div class="card-body">
                    <form method="post">
                        <input type="hidden" name="op" value="crear_venta">

                        <div class="mb-3">
                            <label class="form-label">Cliente</label>
                            <select name="customer_id" class="form-select" required>
                                <option value="">-- Seleccionar --</option>
                                <?php foreach ($clientes as $c): ?>
                                    <option value="<?= (int) $c['id'] ?>"><?= e($c['customer_name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Lote / Producto disponible</label>
                            <select name="stock_id" class="form-select" required>
                                <option value="">-- Seleccionar --</option>
                                <?php foreach ($lotesDisponibles as $l): ?>
                                    <option value="<?= (int) $l['id'] ?>">
                                        <?= e($l['product_name']) ?> (Disp: <?= (int) $l['current_quantity'] ?> | $<?= monto((float) $l['selling_price']) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <label class="form-label">Cantidad</label>
                                <input type="number" name="sold_quantity" class="form-control" min="1" required>
                            </div>
                            <div class="col-6">
                                <label class="form-label">Fecha</label>
                                <input type="date" name="sell_date" class="form-control" value="<?= date('Y-m-d') ?>" required>
                            </div>
                        </div>

                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <label class="form-label">Descuento</label>
                                <input type="number" step="0.01" name="discount_amount" class="form-control" value="0">
                            </div>
                            <div class="col-6">
                                <label class="form-label">Monto pagado</label>
                                <input type="number" step="0.01" name="paid_amount" class="form-control" value="0" required>
                            </div>
                        </div>

                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <label class="form-label">Medio de pago</label>
                                <input type="text" name="paid_in" class="form-control" value="efectivo">
                            </div>
                            <div class="col-6">
                                <label class="form-label">Sucursal</label>
                                <select name="branch_id" class="form-select">
                                    <option value="1">Principal (#1)</option>
                                    <?php foreach ($sucursales as $b): ?>
                                        <option value="<?= (int) $b['id'] ?>"><?= e($b['branch_name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Referencia / Info bancaria</label>
                            <input type="text" name="bank_information" class="form-control">
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Registrar venta</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header fw-bold">Historial de ventas</div>
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle mb-0">
                        <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Fecha</th>
                            <th>Cliente</th>
                            <th>Total</th>
                            <th>Pagado</th>
                            <th>Saldo</th>
                            <th>Estado</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($ventas as $v): ?>
                            <?php $saldo = max(0.0, (float) $v['total_amount'] - (float) $v['paid_amount']); ?>
                            <tr>
                                <td><?= (int) $v['id'] ?></td>
                                <td class="small"><?= e($v['sell_date']) ?></td>
                                <td><?= e($v['customer_name'] ?? '') ?></td>
                                <td><?= monto((float) $v['total_amount']) ?></td>
                                <td><?= monto((float) $v['paid_amount']) ?></td>
                                <td><?= monto($saldo) ?></td>
                                <td>
                                    <?php if ((int) $v['payment_status'] === 1 || $saldo <= 0): ?>
                                        <span class="badge bg-success">Pagada</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning text-dark">Pendiente</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end text-nowrap">
                                    <a href="imprimir.php?tipo=factura&id=<?= (int) $v['id'] ?>" target="_blank" class="btn btn-sm btn-outline-secondary">Imprimir</a>
                                    <a href="index.php" class="btn btn-sm btn-outline-primary">Inicio</a>
                                    <?php if ($saldo > 0): ?>
                                        <form method="post" class="d-inline-flex gap-1 align-items-center">
                                            <input type="hidden" name="op" value="abonar">
                                            <input type="hidden" name="sell_id" value="<?= (int) $v['id'] ?>">
                                            <input type="number" step="0.01" name="amount" class="form-control form-control-sm"
                                                   style="width: 85px;" max="<?= $saldo ?>" value="<?= $saldo ?>" required>
                                            <button type="submit" class="btn btn-sm btn-success">Abonar</button>
                                        </form>
                                    <?php endif; ?>
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