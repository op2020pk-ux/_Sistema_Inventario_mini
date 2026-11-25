<?php
require_once __DIR__ . '/config.php';
requiere_login();

$empresa = empresa_info();
$menus = menu_permitido((int) ($_SESSION['role_id'] ?? 0));

$padres = [];
$hijos = [];
foreach ($menus as $m) {
    if ((int) $m['parent_id'] === 0) {
        $padres[] = $m;
    } else {
        $hijos[(int) $m['parent_id']][] = $m;
    }
}

$totProductos = (int) db()->query("SELECT COUNT(*) FROM products")->fetchColumn();
$totClientes  = (int) db()->query("SELECT COUNT(*) FROM customers")->fetchColumn();
$totVentas    = (float) db()->query("SELECT COALESCE(SUM(total_amount), 0) FROM sells")->fetchColumn();
$totStock     = (int) db()->query("SELECT COALESCE(SUM(current_quantity), 0) FROM stocks")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($empresa['name']) ?> - Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-dark bg-dark px-3">
    <a class="navbar-brand" href="index.php"><?= e($empresa['name']) ?></a>
    <div class="d-flex align-items-center gap-3 text-white">
        <span class="small"><?= e($_SESSION['user_name'] ?? 'Usuario') ?></span>
        <a href="auth.php?accion=logout" class="btn btn-sm btn-outline-light">Salir</a>
    </div>
</nav>

<div class="container-fluid">
    <div class="row">
        <aside class="col-md-3 col-lg-2 bg-white border-end min-vh-100 p-3">
            <div class="list-group list-group-flush">
                <a href="index.php" class="list-group-item list-group-item-action fw-bold">Inicio</a>
                <?php foreach ($padres as $padre): ?>
                    <?php $submenus = $hijos[(int) $padre['id']] ?? []; ?>
                    <?php if (empty($submenus)): ?>
                        <a href="<?= e(ruta_a_archivo((string) $padre['menu_url'])) ?>"
                           class="list-group-item list-group-item-action">
                            <?= e($padre['name']) ?>
                        </a>
                    <?php else: ?>
                        <div class="mt-2 small text-muted fw-semibold px-2"><?= e($padre['name']) ?></div>
                        <?php foreach ($submenus as $sub): ?>
                            <a href="<?= e(ruta_a_archivo((string) $sub['menu_url'])) ?>"
                               class="list-group-item list-group-item-action ps-4">
                                <?= e($sub['name']) ?>
                            </a>
                        <?php endforeach; ?>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </aside>

        <main class="col-md-9 col-lg-10 p-4">
            <h4 class="mb-4">Resumen de inventario y ventas</h4>
            <div class="row g-3">
                <div class="col-sm-6 col-xl-3">
                    <div class="card shadow-sm"><div class="card-body">
                        <div class="text-muted small">Productos registrados</div>
                        <div class="fs-4 fw-bold"><?= $totProductos ?></div>
                    </div></div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="card shadow-sm"><div class="card-body">
                        <div class="text-muted small">Unidades en existencia</div>
                        <div class="fs-4 fw-bold"><?= $totStock ?></div>
                    </div></div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="card shadow-sm"><div class="card-body">
                        <div class="text-muted small">Clientes</div>
                        <div class="fs-4 fw-bold"><?= $totClientes ?></div>
                    </div></div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="card shadow-sm"><div class="card-body">
                        <div class="text-muted small">Total vendido</div>
                        <div class="fs-4 fw-bold"><?= monto($totVentas) ?></div>
                    </div></div>
                </div>
            </div>
        </main>
    </div>
</div>
</body>
</html>