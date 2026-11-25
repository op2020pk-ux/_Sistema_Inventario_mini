<?php
require_once __DIR__ . '/config.php';
requiere_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $op = $_POST['op'] ?? '';

    if ($op === 'guardar') {
        $id          = (int) ($_POST['id'] ?? 0);
        $categoryId  = (int) ($_POST['category_id'] ?? 0);
        $productName = trim($_POST['product_name'] ?? '');
        $details     = trim($_POST['details'] ?? '');
        $status      = isset($_POST['status']) ? 1 : 0;

        if ($productName === '' || $categoryId <= 0) {
            flash('warning', 'Selecciona una categoría e indica el nombre del producto.');
        } elseif ($id > 0) {
            $stmt = db()->prepare("
                UPDATE products
                SET category_id = :cat, product_name = :nom, details = :det, status = :st, updated_at = :upd
                WHERE id = :id
            ");
            $stmt->execute([
                'cat' => $categoryId,
                'nom' => $productName,
                'det' => $details !== '' ? $details : null,
                'st'  => $status,
                'upd' => ahora(),
                'id'  => $id,
            ]);
            flash('success', 'Producto actualizado.');
        } else {
            $stmt = db()->prepare("
                INSERT INTO products (category_id, product_name, details, status, created_at, updated_at)
                VALUES (:cat, :nom, :det, :st, :cre, :upd)
            ");
            $stmt->execute([
                'cat' => $categoryId,
                'nom' => $productName,
                'det' => $details !== '' ? $details : null,
                'st'  => $status,
                'cre' => ahora(),
                'upd' => ahora(),
            ]);
            flash('success', 'Producto registrado.');
        }
    } elseif ($op === 'eliminar') {
        $id = (int) ($_POST['id'] ?? 0);
        if ($id > 0) {
            $stmt = db()->prepare("DELETE FROM products WHERE id = :id");
            $stmt->execute(['id' => $id]);
            flash('info', 'Producto eliminado.');
        }
    }

    header('Location: productos.php');
    exit;
}

$editarId = (int) ($_GET['editar'] ?? 0);
$productoEditar = null;
if ($editarId > 0) {
    $stmt = db()->prepare("SELECT * FROM products WHERE id = :id LIMIT 1");
    $stmt->execute(['id' => $editarId]);
    $productoEditar = $stmt->fetch() ?: null;
}

$categorias = db()->query("SELECT id, name FROM categories ORDER BY name ASC")->fetchAll();
$buscar = trim($_GET['q'] ?? '');
$catFiltro = (int) ($_GET['categoria'] ?? 0);

$sql = "
    SELECT p.*, c.name AS category_name
    FROM products p
    LEFT JOIN categories c ON c.id = p.category_id
    WHERE 1=1
";
$params = [];
if ($buscar !== '') {
    $sql .= " AND (p.product_name LIKE :q OR p.details LIKE :q)";
    $params['q'] = '%' . $buscar . '%';
}
if ($catFiltro > 0) {
    $sql .= " AND p.category_id = :cat";
    $params['cat'] = $catFiltro;
}
$sql .= " ORDER BY p.id DESC";

$stmt = db()->prepare($sql);
$stmt->execute($params);
$productos = $stmt->fetchAll();

$empresa = empresa_info();
$f = tomar_flash();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos - <?= e($empresa['name']) ?></title>
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
                <div class="card-header fw-bold">
                    <?= $productoEditar ? 'Editar producto #' . (int) $productoEditar['id'] : 'Nuevo producto' ?>
                </div>
                <div class="card-body">
                    <form method="post">
                        <input type="hidden" name="op" value="guardar">
                        <input type="hidden" name="id" value="<?= (int) ($productoEditar['id'] ?? 0) ?>">

                        <div class="mb-3">
                            <label class="form-label">Categoría</label>
                            <select name="category_id" class="form-select" required>
                                <option value="">-- Seleccionar --</option>
                                <?php foreach ($categorias as $c): ?>
                                    <option value="<?= (int) $c['id'] ?>"
                                        <?= ((int) ($productoEditar['category_id'] ?? 0) === (int) $c['id']) ? 'selected' : '' ?>>
                                        <?= e($c['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nombre del producto</label>
                            <input type="text" name="product_name" class="form-control"
                                   value="<?= e($productoEditar['product_name'] ?? '') ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Detalles</label>
                            <textarea name="details" rows="3" class="form-control"><?= e($productoEditar['details'] ?? '') ?></textarea>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="status" id="st"
                                   <?= (!isset($productoEditar['status']) || (int) $productoEditar['status'] === 1) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="st">Activo</label>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Guardar</button>
                            <?php if ($productoEditar): ?>
                                <a href="productos.php" class="btn btn-outline-secondary">Cancelar</a>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header">
                    <form method="get" class="row g-2 align-items-center">
                        <div class="col-md-5">
                            <input type="text" name="q" class="form-control form-control-sm"
                                   placeholder="Buscar producto..." value="<?= e($buscar) ?>">
                        </div>
                        <div class="col-md-4">
                            <select name="categoria" class="form-select form-select-sm">
                                <option value="0">Todas las categorías</option>
                                <?php foreach ($categorias as $c): ?>
                                    <option value="<?= (int) $c['id'] ?>" <?= $catFiltro === (int) $c['id'] ? 'selected' : '' ?>>
                                        <?= e($c['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-3 d-flex gap-1">
                            <button type="submit" class="btn btn-sm btn-dark w-100">Filtrar</button>
                            <a href="productos.php" class="btn btn-sm btn-outline-secondary">Limpiar</a>
                        </div>
                    </form>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle mb-0">
                        <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Producto</th>
                            <th>Categoría</th>
                            <th>Estado</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($productos as $p): ?>
                            <tr>
                                <td><?= (int) $p['id'] ?></td>
                                <td>
                                    <div class="fw-semibold"><?= e($p['product_name']) ?></div>
                                    <?php if (!empty($p['details'])): ?>
                                        <div class="small text-muted"><?= e(mb_strimwidth($p['details'], 0, 90, '...')) ?></div>
                                    <?php endif; ?>
                                </td>
                                <td><?= e($p['category_name'] ?? 'Sin categoría') ?></td>
                                <td>
                                    <?php if ((int) $p['status'] === 1): ?>
                                        <span class="badge bg-success">Activo</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Inactivo</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end text-nowrap">
                                    <a href="productos.php?editar=<?= (int) $p['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
                                    <form method="post" class="d-inline" onsubmit="return confirm('¿Eliminar este producto?');">
                                        <input type="hidden" name="op" value="eliminar">
                                        <input type="hidden" name="id" value="<?= (int) $p['id'] ?>">
                                        <button type="submit" class="btn btn-sm btn-danger">Borrar</button>
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