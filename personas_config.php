<?php
require_once __DIR__ . '/config.php';
requiere_login();

$tab = $_GET['tab'] ?? 'clientes';
if (!in_array($tab, ['clientes', 'usuarios', 'roles', 'empresa'], true)) {
    $tab = 'clientes';
}

function ruta_imagen_login(): ?string {
    $dir = __DIR__ . '/uploads';
    if (!is_dir($dir)) {
        return null;
    }
    foreach (['jpg', 'jpeg', 'png', 'webp', 'gif'] as $ext) {
        $archivo = $dir . '/login_bg.' . $ext;
        if (is_file($archivo)) {
            return 'uploads/login_bg.' . $ext;
        }
    }
    return null;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $op = $_POST['op'] ?? '';
    $tabPost = $_POST['tab'] ?? $tab;

    if ($tabPost === 'clientes') {
        $id = (int) ($_POST['id'] ?? 0);
        if ($op === 'guardar') {
            $cname = trim($_POST['customer_name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $phone = trim($_POST['phone'] ?? '');
            $addr  = trim($_POST['address'] ?? '');
            $st    = isset($_POST['status']) ? 1 : 0;
            if ($cname !== '') {
                if ($id > 0) {
                    db()->prepare("UPDATE customers SET customer_name = :n, email = :e, phone = :p, address = :a, status = :s, updated_at = :u WHERE id = :id")
                        ->execute(['n' => $cname, 'e' => $email ?: null, 'p' => $phone ?: null, 'a' => $addr ?: null, 's' => $st, 'u' => ahora(), 'id' => $id]);
                    flash('success', 'Cliente actualizado.');
                } else {
                    db()->prepare("INSERT INTO customers (customer_name, email, phone, address, status, created_at, updated_at) VALUES (:n, :e, :p, :a, :s, :c, :u)")
                        ->execute(['n' => $cname, 'e' => $email ?: null, 'p' => $phone ?: null, 'a' => $addr ?: null, 's' => $st, 'c' => ahora(), 'u' => ahora()]);
                    flash('success', 'Cliente registrado.');
                }
            }
        } elseif ($op === 'eliminar' && $id > 0) {
            db()->prepare("DELETE FROM customers WHERE id = :id")->execute(['id' => $id]);
            flash('info', 'Cliente eliminado.');
        }
    } elseif ($tabPost === 'usuarios') {
        if ($op === 'crear_usuario') {
            $name   = trim($_POST['name'] ?? '');
            $email  = trim($_POST['email'] ?? '');
            $pass   = $_POST['password'] ?? '';
            $roleId = (int) ($_POST['role_id'] ?? 4);
            $brId   = (int) ($_POST['branch_id'] ?? 1);
            if ($name !== '' && $email !== '' && strlen($pass) >= 6) {
                $hash = password_hash($pass, PASSWORD_BCRYPT);
                db()->prepare("INSERT INTO users (name, email, password, branch_id, role_id, created_at, updated_at) VALUES (:n, :e, :p, :b, :r, :c, :u)")
                    ->execute(['n' => $name, 'e' => $email, 'p' => $hash, 'b' => $brId, 'r' => $roleId, 'c' => ahora(), 'u' => ahora()]);
                flash('success', 'Usuario creado.');
            } else {
                flash('warning', 'Completa nombre, correo y contraseña (mínimo 6 caracteres).');
            }
        } elseif ($op === 'cambiar_rol') {
            $uid    = (int) ($_POST['user_id'] ?? 0);
            $roleId = (int) ($_POST['role_id'] ?? 0);
            if ($uid > 0 && $roleId > 0) {
                db()->prepare("UPDATE users SET role_id = :r, updated_at = :u WHERE id = :id")
                    ->execute(['r' => $roleId, 'u' => ahora(), 'id' => $uid]);
                flash('success', 'Rol de usuario actualizado.');
            }
        }
    } elseif ($tabPost === 'roles') {
        if ($op === 'crear_rol') {
            $rname = trim($_POST['role_name'] ?? '');
            if ($rname !== '') {
                db()->prepare("INSERT INTO roles (role_name, created_at, updated_at) VALUES (:n, :c, :u)")
                    ->execute(['n' => $rname, 'c' => ahora(), 'u' => ahora()]);
                flash('success', 'Rol creado.');
            }
        } elseif ($op === 'guardar_permisos') {
            $roleId  = (int) ($_POST['role_id'] ?? 0);
            $menuIds = $_POST['menu_ids'] ?? [];
            if ($roleId > 0) {
                $pdo = db();
                $pdo->beginTransaction();
                $pdo->prepare("DELETE FROM permissions WHERE role_id = :r")->execute(['r' => $roleId]);
                $ins = $pdo->prepare("INSERT INTO permissions (role_id, menu_id, created_at, updated_at) VALUES (:r, :m, :c, :u)");
                foreach ($menuIds as $mid) {
                    $ins->execute(['r' => $roleId, 'm' => (int) $mid, 'c' => ahora(), 'u' => ahora()]);
                }
                $pdo->commit();
                flash('success', 'Permisos del rol actualizados.');
            }
        }
    } elseif ($tabPost === 'empresa') {
        if ($op === 'quitar_imagen_login') {
            $actualImg = ruta_imagen_login();
            if ($actualImg && is_file(__DIR__ . '/' . $actualImg)) {
                @unlink(__DIR__ . '/' . $actualImg);
            }
            flash('info', 'Imagen de fondo del inicio eliminada.');
            header('Location: personas_config.php?tab=empresa');
            exit;
        }

        $name = trim($_POST['name'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $addr = trim($_POST['address'] ?? '');
        if ($name !== '') {
            $emp = empresa_info();
            if (!empty($emp['id'])) {
                db()->prepare("UPDATE companies SET name = :n, phone = :p, address = :a, updated_at = :u WHERE id = :id")
                    ->execute(['n' => $name, 'p' => $phone, 'a' => $addr, 'u' => ahora(), 'id' => (int) $emp['id']]);
            } else {
                db()->prepare("INSERT INTO companies (name, phone, address, created_at, updated_at) VALUES (:n, :p, :a, :c, :u)")
                    ->execute(['n' => $name, 'p' => $phone, 'a' => $addr, 'c' => ahora(), 'u' => ahora()]);
            }

            $msgExtra = '';
            if (!empty($_FILES['imagen_login']['name']) && (int) ($_FILES['imagen_login']['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_OK) {
                $tmpPath = $_FILES['imagen_login']['tmp_name'];
                $mime = function_exists('mime_content_type') ? (string) mime_content_type($tmpPath) : '';
                $extMap = [
                    'image/jpeg' => 'jpg',
                    'image/pjpeg' => 'jpg',
                    'image/png' => 'png',
                    'image/webp' => 'webp',
                    'image/gif' => 'gif',
                ];
                $ext = $extMap[$mime] ?? strtolower(pathinfo((string) $_FILES['imagen_login']['name'], PATHINFO_EXTENSION));
                if (!in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif'], true)) {
                    flash('warning', 'Datos guardados, pero la imagen debe ser JPG, PNG, WEBP o GIF.');
                    header('Location: personas_config.php?tab=empresa');
                    exit;
                }
                if ($ext === 'jpeg') {
                    $ext = 'jpg';
                }

                $uploadDir = __DIR__ . '/uploads';
                if (!is_dir($uploadDir)) {
                    @mkdir($uploadDir, 0775, true);
                }

                $anterior = ruta_imagen_login();
                if ($anterior && is_file(__DIR__ . '/' . $anterior)) {
                    @unlink(__DIR__ . '/' . $anterior);
                }

                $destino = $uploadDir . '/login_bg.' . $ext;
                if (move_uploaded_file($tmpPath, $destino)) {
                    $msgExtra = ' e imagen de inicio actualizada';
                } else {
                    flash('warning', 'Datos guardados, pero no se pudo mover la imagen a uploads/ (revisa permisos de carpeta).');
                    header('Location: personas_config.php?tab=empresa');
                    exit;
                }
            }

            flash('success', 'Información de la empresa guardada' . $msgExtra . '.');
        }
    }

    header('Location: personas_config.php?tab=' . urlencode($tabPost));
    exit;
}

$clientes = db()->query("SELECT * FROM customers ORDER BY id DESC")->fetchAll();
$roles = db()->query("SELECT * FROM roles ORDER BY id ASC")->fetchAll();
$menus = db()->query("SELECT * FROM menus ORDER BY id ASC")->fetchAll();
$usuarios = db()->query("
    SELECT u.*, r.role_name
    FROM users u
    LEFT JOIN roles r ON r.id = u.role_id
    ORDER BY u.id DESC
")->fetchAll();

$rolSel = (int) ($_GET['rol'] ?? ($roles[0]['id'] ?? 2));
$permStmt = db()->prepare("SELECT menu_id FROM permissions WHERE role_id = :r");
$permStmt->execute(['r' => $rolSel]);
$permActuales = array_map('intval', array_column($permStmt->fetchAll(), 'menu_id'));

$empresa = empresa_info();
$imagenLogin = ruta_imagen_login();
$f = tomar_flash();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clientes, Usuarios y Configuración - <?= e($empresa['name']) ?></title>
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
        <li class="nav-item"><a class="nav-link <?= $tab === 'clientes' ? 'active' : '' ?>" href="personas_config.php?tab=clientes">Clientes</a></li>
        <li class="nav-item"><a class="nav-link <?= $tab === 'usuarios' ? 'active' : '' ?>" href="personas_config.php?tab=usuarios">Usuarios</a></li>
        <li class="nav-item"><a class="nav-link <?= $tab === 'roles' ? 'active' : '' ?>" href="personas_config.php?tab=roles">Roles y Permisos</a></li>
        <li class="nav-item"><a class="nav-link <?= $tab === 'empresa' ? 'active' : '' ?>" href="personas_config.php?tab=empresa">Empresa</a></li>
    </ul>

    <?php if ($tab === 'clientes'): ?>
        <div class="row g-4">
            <div class="col-lg-4">
                <div class="card shadow-sm"><div class="card-body">
                    <h6 class="fw-bold mb-3">Nuevo cliente</h6>
                    <form method="post">
                        <input type="hidden" name="tab" value="clientes">
                        <input type="hidden" name="op" value="guardar">
                        <div class="mb-2"><label class="form-label">Nombre</label><input type="text" name="customer_name" class="form-control" required></div>
                        <div class="mb-2"><label class="form-label">Correo</label><input type="email" name="email" class="form-control"></div>
                        <div class="mb-2"><label class="form-label">Teléfono</label><input type="text" name="phone" class="form-control"></div>
                        <div class="mb-3"><label class="form-label">Dirección</label><input type="text" name="address" class="form-control"></div>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </form>
                </div></div>
            </div>
            <div class="col-lg-8">
                <div class="card shadow-sm"><div class="table-responsive">
                    <table class="table table-striped mb-0">
                        <thead><tr><th>ID</th><th>Cliente</th><th>Teléfono</th><th>Correo</th><th class="text-end">Acción</th></tr></thead>
                        <tbody>
                        <?php foreach ($clientes as $c): ?>
                            <tr>
                                <td><?= (int) $c['id'] ?></td>
                                <td><div class="fw-semibold"><?= e($c['customer_name']) ?></div><div class="small text-muted"><?= e($c['address'] ?? '') ?></div></td>
                                <td><?= e($c['phone'] ?? '') ?></td>
                                <td><?= e($c['email'] ?? '') ?></td>
                                <td class="text-end">
                                    <form method="post" class="d-inline" onsubmit="return confirm('¿Eliminar cliente?');">
                                        <input type="hidden" name="tab" value="clientes">
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
    <?php elseif ($tab === 'usuarios'): ?>
        <div class="row g-4">
            <div class="col-lg-4">
                <div class="card shadow-sm"><div class="card-body">
                    <h6 class="fw-bold mb-3">Nuevo usuario</h6>
                    <form method="post">
                        <input type="hidden" name="tab" value="usuarios">
                        <input type="hidden" name="op" value="crear_usuario">
                        <div class="mb-2"><label class="form-label">Nombre</label><input type="text" name="name" class="form-control" required></div>
                        <div class="mb-2"><label class="form-label">Correo</label><input type="email" name="email" class="form-control" required></div>
                        <div class="mb-2"><label class="form-label">Contraseña</label><input type="password" name="password" class="form-control" required></div>
                        <div class="mb-3">
                            <label class="form-label">Rol</label>
                            <select name="role_id" class="form-select">
                                <?php foreach ($roles as $r): ?>
                                    <option value="<?= (int) $r['id'] ?>"><?= e($r['role_name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Crear usuario</button>
                    </form>
                </div></div>
            </div>
            <div class="col-lg-8">
                <div class="card shadow-sm"><div class="table-responsive">
                    <table class="table table-striped align-middle mb-0">
                        <thead><tr><th>ID</th><th>Nombre</th><th>Correo</th><th>Rol</th></tr></thead>
                        <tbody>
                        <?php foreach ($usuarios as $u): ?>
                            <tr>
                                <td><?= (int) $u['id'] ?></td>
                                <td><?= e($u['name']) ?></td>
                                <td><?= e($u['email']) ?></td>
                                <td>
                                    <form method="post" class="d-flex gap-1">
                                        <input type="hidden" name="tab" value="usuarios">
                                        <input type="hidden" name="op" value="cambiar_rol">
                                        <input type="hidden" name="user_id" value="<?= (int) $u['id'] ?>">
                                        <select name="role_id" class="form-select form-select-sm">
                                            <?php foreach ($roles as $r): ?>
                                                <option value="<?= (int) $r['id'] ?>" <?= ((int) $u['role_id'] === (int) $r['id']) ? 'selected' : '' ?>>
                                                    <?= e($r['role_name']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <button class="btn btn-sm btn-outline-dark">Guardar</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div></div>
            </div>
        </div>
    <?php elseif ($tab === 'roles'): ?>
        <div class="row g-4">
            <div class="col-lg-4">
                <div class="card shadow-sm mb-3"><div class="card-body">
                    <h6 class="fw-bold mb-3">Nuevo rol</h6>
                    <form method="post">
                        <input type="hidden" name="tab" value="roles">
                        <input type="hidden" name="op" value="crear_rol">
                        <div class="mb-3"><label class="form-label">Nombre del rol</label><input type="text" name="role_name" class="form-control" required></div>
                        <button type="submit" class="btn btn-primary">Crear rol</button>
                    </form>
                </div></div>
            </div>
            <div class="col-lg-8">
                <div class="card shadow-sm"><div class="card-body">
                    <form method="get" class="mb-3 d-flex align-items-center gap-2">
                        <input type="hidden" name="tab" value="roles">
                        <label class="form-label mb-0 fw-semibold">Permisos para el rol:</label>
                        <select name="rol" class="form-select form-select-sm w-auto" onchange="this.form.submit()">
                            <?php foreach ($roles as $r): ?>
                                <option value="<?= (int) $r['id'] ?>" <?= $rolSel === (int) $r['id'] ? 'selected' : '' ?>>
                                    <?= e($r['role_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </form>
                    <form method="post">
                        <input type="hidden" name="tab" value="roles">
                        <input type="hidden" name="op" value="guardar_permisos">
                        <input type="hidden" name="role_id" value="<?= $rolSel ?>">
                        <div class="row g-2 mb-3">
                            <?php foreach ($menus as $m): ?>
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="menu_ids[]"
                                               id="m_<?= (int) $m['id'] ?>" value="<?= (int) $m['id'] ?>"
                                               <?= in_array((int) $m['id'], $permActuales, true) ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="m_<?= (int) $m['id'] ?>"><?= e($m['name']) ?></label>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <button type="submit" class="btn btn-success">Guardar permisos</button>
                    </form>
                </div></div>
            </div>
        </div>
    <?php else: ?>
        <div class="card shadow-sm" style="max-width: 600px;"><div class="card-body">
            <h6 class="fw-bold mb-3">Datos de la empresa</h6>
            <form method="post" enctype="multipart/form-data">
                <input type="hidden" name="tab" value="empresa">
                <div class="mb-3"><label class="form-label">Nombre</label><input type="text" name="name" class="form-control" value="<?= e($empresa['name'] ?? '') ?>" required></div>
                <div class="mb-3"><label class="form-label">Teléfono</label><input type="text" name="phone" class="form-control" value="<?= e($empresa['phone'] ?? '') ?>"></div>
                <div class="mb-3"><label class="form-label">Dirección</label><textarea name="address" rows="3" class="form-control"><?= e($empresa['address'] ?? '') ?></textarea></div>

                <hr class="my-3">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Imagen de fondo para el inicio (auth.php)</label>
                    <input type="file" name="imagen_login" class="form-control" accept="image/jpeg,image/png,image/webp,image/gif">
                    <div class="form-text">Se mostrará ampliada a pantalla completa y adaptada automáticamente a la resolución.</div>
                </div>

                <?php if ($imagenLogin): ?>
                    <div class="mb-3">
                        <div class="small text-muted mb-1">Imagen actual cargada:</div>
                        <img src="<?= e($imagenLogin) ?>?v=<?= time() ?>" alt="Fondo de inicio" class="img-thumbnail" style="max-height: 150px; object-fit: cover;">
                    </div>
                <?php endif; ?>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Guardar cambios</button>
                    <?php if ($imagenLogin): ?>
                        <button type="submit" name="op" value="quitar_imagen_login" class="btn btn-outline-danger" onclick="return confirm('¿Quitar la imagen de fondo del inicio?');">Quitar imagen</button>
                    <?php endif; ?>
                </div>
            </form>
        </div></div>
    <?php endif; ?>
</div>
</body>
</html>