<?php
require_once __DIR__ . '/config.php';
requiere_login();

$tab = $_GET['tab'] ?? 'categorias';
if (!in_array($tab, ['categorias', 'proveedores', 'sucursales'], true)) {
    $tab = 'categorias';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $op = $_POST['op'] ?? '';
    $tabPost = $_POST['tab'] ?? $tab;

    if ($tabPost === 'categorias') {
        $id = (int) ($_POST['id'] ?? 0);
        if ($op === 'guardar') {
            $name = trim($_POST['name'] ?? '');
            $status = isset($_POST['status']) ? 1 : 0;
            if ($name !== '') {
                if ($id > 0) {
                    db()->prepare("UPDATE categories SET name = :n, status = :s, updated_at = :u WHERE id = :id")
                        ->execute(['n' => $name, 's' => $status, 'u' => ahora(), 'id' => $id]);
                    flash('success', 'Categoría actualizada.');
                } else {
                    db()->prepare("INSERT INTO categories (name, status, created_at, updated_at) VALUES (:n, :s, :c, :u)")
                        ->execute(['n' => $name, 's' => $status, 'c' => ahora(), 'u' => ahora()]);
                    flash('success', 'Categoría creada.');
                }
            }
        } elseif ($op === 'eliminar' && $id > 0) {
            db()->prepare("DELETE FROM categories WHERE id = :id")->execute(['id' => $id]);
            flash('info', 'Categoría eliminada.');
        }
    } elseif ($tabPost === 'proveedores') {
        $id = (int) ($_POST['id'] ?? 0);
        if ($op === 'guardar') {
            $name  = trim($_POST['name'] ?? '');
            $phone = trim($_POST['phone'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $addr  = trim($_POST['address'] ?? '');
            if ($name !== '' && $phone !== '') {
                if ($id > 0) {
                    db()->prepare("UPDATE vendors SET name = :n, phone = :p, email = :e, address = :a, updated_at = :u WHERE id = :id")
                        ->execute(['n' => $name, 'p' => $phone, 'e' => $email ?: null, 'a' => $addr ?: null, 'u' => ahora(), 'id' => $id]);
                    flash('success', 'Proveedor actualizado.');
                } else {
                    db()->prepare("INSERT INTO vendors (name, phone, email, address, created_at, updated_at) VALUES (:n, :p, :e, :a, :c, :u)")
                        ->execute(['n' => $name, 'p' => $phone, 'e' => $email ?: null, 'a' => $addr ?: null, 'c' => ahora(), 'u' => ahora()]);
                    flash('success', 'Proveedor registrado.');
                }
            }
        } elseif ($op === 'eliminar' && $id > 0) {
            db()->prepare("DELETE FROM vendors WHERE id = :id")->execute(['id' => $id]);
            flash('info', 'Proveedor eliminado.');
        }
    } elseif ($tabPost === 'sucursales') {
        $id = (int) ($_POST['id'] ?? 0);
        if ($op === 'guardar') {
            $bname = trim($_POST['branch_name'] ?? '');
            if ($bname !== '') {
                if ($id > 0) {
                    db()->prepare("UPDATE branches SET branch_name = :n, updated_at = :u WHERE id = :id")
                        ->execute(['n' => $bname, 'u' => ahora(), 'id' => $id]);
                    flash('success', 'Sucursal actualizada.');
                } else {
                    db()->prepare("INSERT INTO branches (branch_name, created_at, updated_at) VALUES (:n, :c, :u)")
                        ->execute(['n' => $bname, 'c' => ahora(), 'u' => ahora()]);
                    flash('success', 'Sucursal creada.');
                }
            }
        } elseif ($op === 'eliminar' && $id > 0) {
            db()->prepare("DELETE FROM branches WHERE id = :id")->execute(['id' => $id]);
            flash('info', 'Sucursal eliminada.');
        }
    }

    header('Location: catalogos.php?tab=' . urlencode($tabPost));
    exit;
}

$categorias = db()->query("SELECT * FROM categories ORDER BY id DESC")->fetchAll();
$proveedores = db()->query("SELECT * FROM vendors ORDER BY id DESC")->fetchAll();
$sucursales = db()->query("SELECT * FROM branches ORDER BY id DESC")->fetchAll();

$empresa = empresa_info();
$f = tomar_flash();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catálogos - <?= e($empresa['name']) ?></title>
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

    <ul class="nav nav-tabs mb-4">
        <li class="nav-item">
            <a class="nav-link <?= $tab === 'categorias' ? 'active' : '' ?>" href="catalogos.php?tab=categorias">Categorías (<?= count($categorias) ?>)</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= $tab === 'proveedores' ? 'active' : '' ?>" href="catalogos.php?tab=proveedores">Proveedores (<?= count($proveedores) ?>)</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= $tab === 'sucursales' ? 'active' : '' ?>" href="catalogos.php?tab=sucursales">Sucursales (<?= count($sucursales) ?>)</a>
        </li>
    </ul>

    <?php if ($tab === 'categorias'): ?>
        <div class="row g-4">
            <div class="col-lg-4">
                <div class="card shadow-sm"><div class="card-body">
                    <h6 class="fw-bold mb-3">Nueva categoría</h6>
                    <form method="post">
                        <input type="hidden" name="tab" value="categorias">
                        <input type="hidden" name="op" value="guardar">
                        <div class="mb-3">
                            <label class="form-label">Nombre</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="form-check mb-3">
                            <input type="checkbox" name="status" class="form-check-input" id="c_st" checked>
                            <label class="form-check-label" for="c_st">Activa</label>
                        </div>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </form>
                </div></div>
            </div>
            <div class="col-lg-8">
                <div class="card shadow-sm"><div class="table-responsive">
                    <table class="table table-striped mb-0">
                        <thead><tr><th>ID</th><th>Categoría</th><th>Estado</th><th class="text-end">Acción</th></tr></thead>
                        <tbody>
                        <?php foreach ($categorias as $c): ?>
                            <tr>
                                <td><?= (int) $c['id'] ?></td>
                                <td><?= e($c['name']) ?></td>
                                <td><?= (int) $c['status'] === 1 ? 'Activa' : 'Inactiva' ?></td>
                                <td class="text-end">
                                    <form method="post" class="d-inline" onsubmit="return confirm('¿Eliminar categoría?');">
                                        <input type="hidden" name="tab" value="categorias">
                                        <input type="hidden" name="op" value="eliminar">
                                        <input type="hidden" name="id" value="<?= (int) $c['id'] ?>">
                                        <button class="btn btn-sm btn-danger">Borrar</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div></div>
            </div>
        </div>
    <?php elseif ($tab === 'proveedores'): ?>
        <div class="row g-4">
            <div class="col-lg-4">
                <div class="card shadow-sm"><div class="card-body">
                    <h6 class="fw-bold mb-3">Nuevo proveedor</h6>
                    <form method="post">
                        <input type="hidden" name="tab" value="proveedores">
                        <input type="hidden" name="op" value="guardar">
                        <div class="mb-2"><label class="form-label">Nombre</label><input type="text" name="name" class="form-control" required></div>
                        <div class="mb-2"><label class="form-label">Teléfono</label><input type="text" name="phone" class="form-control" required></div>
                        <div class="mb-2"><label class="form-label">Correo</label><input type="email" name="email" class="form-control"></div>
                        <div class="mb-3"><label class="form-label">Dirección</label><input type="text" name="address" class="form-control"></div>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </form>
                </div></div>
            </div>
            <div class="col-lg-8">
                <div class="card shadow-sm"><div class="table-responsive">
                    <table class="table table-striped mb-0">
                        <thead><tr><th>ID</th><th>Proveedor</th><th>Teléfono</th><th>Correo</th><th class="text-end">Acción</th></tr></thead>
                        <tbody>
                        <?php foreach ($proveedores as $v): ?>
                            <tr>
                                <td><?= (int) $v['id'] ?></td>
                                <td><div class="fw-semibold"><?= e($v['name']) ?></div><div class="small text-muted"><?= e($v['address'] ?? '') ?></div></td>
                                <td><?= e($v['phone']) ?></td>
                                <td><?= e($v['email'] ?? '') ?></td>
                                <td class="text-end">
                                    <form method="post" class="d-inline" onsubmit="return confirm('¿Eliminar proveedor?');">
                                        <input type="hidden" name="tab" value="proveedores">
                                        <input type="hidden" name="op" value="eliminar">
                                        <input type="hidden" name="id" value="<?= (int) $v['id'] ?>">
                                        <button class="btn btn-sm btn-danger">Borrar</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div></div>
            </div>
        </div>
    <?php else: ?>
        <div class="row g-4">
            <div class="col-lg-4">
                <div class="card shadow-sm"><div class="card-body">
                    <h6 class="fw-bold mb-3">Nueva sucursal</h6>
                    <form method="post">
                        <input type="hidden" name="tab" value="sucursales">
                        <input type="hidden" name="op" value="guardar">
                        <div class="mb-3"><label class="form-label">Nombre de sucursal</label><input type="text" name="branch_name" class="form-control" required></div>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </form>
                </div></div>
            </div>
            <div class="col-lg-8">
                <div class="card shadow-sm"><div class="table-responsive">
                    <table class="table table-striped mb-0">
                        <thead><tr><th>ID</th><th>Sucursal</th><th class="text-end">Acción</th></tr></thead>
                        <tbody>
                        <?php foreach ($sucursales as $b): ?>
                            <tr>
                                <td><?= (int) $b['id'] ?></td>
                                <td><?= e($b['branch_name']) ?></td>
                                <td class="text-end">
                                    <form method="post" class="d-inline" onsubmit="return confirm('¿Eliminar sucursal?');">
                                        <input type="hidden" name="tab" value="sucursales">
                                        <input type="hidden" name="op" value="eliminar">
                                        <input type="hidden" name="id" value="<?= (int) $b['id'] ?>">
                                        <button class="btn btn-sm btn-danger">Borrar</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div></div>
            </div>
        </div>
    <?php endif; ?>
</div>
</body>
</html>