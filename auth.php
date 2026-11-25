<?php
require_once __DIR__ . '/config.php';

$accion = $_GET['accion'] ?? 'login';

function obtener_fondo_login(): ?string {
    $dir = __DIR__ . '/uploads';
    if (!is_dir($dir)) {
        return null;
    }
    foreach (['jpg', 'jpeg', 'png', 'webp', 'gif'] as $ext) {
        $archivo = $dir . '/login_bg.' . $ext;
        if (is_file($archivo)) {
            return 'uploads/login_bg.' . $ext . '?v=' . filemtime($archivo);
        }
    }
    return null;
}

if ($accion === 'logout') {
    $_SESSION = [];
    session_destroy();
    header('Location: auth.php');
    exit;
}

if ($accion === 'password') {
    requiere_login();
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $actual = $_POST['password_actual'] ?? '';
        $nueva  = $_POST['password_nueva'] ?? '';
        $rep    = $_POST['password_confirmar'] ?? '';

        $stmt = db()->prepare("SELECT id, password FROM users WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => (int) $_SESSION['user_id']]);
        $usuario = $stmt->fetch();

        if (!$usuario || !password_verify($actual, $usuario['password'])) {
            flash('danger', 'La contraseña actual no es correcta.');
        } elseif (strlen($nueva) < 6) {
            flash('warning', 'La nueva contraseña debe tener al menos 6 caracteres.');
        } elseif ($nueva !== $rep) {
            flash('warning', 'La confirmación de contraseña no coincide.');
        } else {
            $hash = password_hash($nueva, PASSWORD_BCRYPT);
            $upd = db()->prepare("UPDATE users SET password = :pass, updated_at = :upd WHERE id = :id");
            $upd->execute(['pass' => $hash, 'upd' => ahora(), 'id' => (int) $usuario['id']]);
            flash('success', 'Contraseña actualizada correctamente.');
        }
        header('Location: auth.php?accion=password');
        exit;
    }

    $empresa = empresa_info();
    $f = tomar_flash();
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Cambiar contraseña - <?= e($empresa['name']) ?></title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body class="bg-light">
    <div class="container py-5" style="max-width: 480px;">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h5 class="mb-3">Cambiar contraseña</h5>
                <?php if ($f): ?>
                    <div class="alert alert-<?= e($f['tipo']) ?>"><?= e($f['mensaje']) ?></div>
                <?php endif; ?>
                <form method="post">
                    <div class="mb-3">
                        <label class="form-label">Contraseña actual</label>
                        <input type="password" name="password_actual" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nueva contraseña</label>
                        <input type="password" name="password_nueva" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Confirmar nueva contraseña</label>
                        <input type="password" name="password_confirmar" class="form-control" required>
                    </div>
                    <div class="d-flex justify-content-between">
                        <a href="index.php" class="btn btn-outline-secondary">Volver</a>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </body>
    </html>
    <?php
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $pass  = $_POST['password'] ?? '';

    $stmt = db()->prepare("
        SELECT u.*, r.role_name
        FROM users u
        LEFT JOIN roles r ON r.id = u.role_id
        WHERE u.email = :email
        LIMIT 1
    ");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch();

    if ($user && password_verify($pass, $user['password'])) {
        $_SESSION['user_id']   = (int) $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['role_id']   = (int) $user['role_id'];
        $_SESSION['role_name'] = $user['role_name'] ?? '';
        $_SESSION['branch_id'] = (int) $user['branch_id'];
        header('Location: index.php');
        exit;
    }
    flash('danger', 'Correo o contraseña incorrectos.');
    header('Location: auth.php');
    exit;
}

$empresa = empresa_info();
$fondoLogin = obtener_fondo_login();
$f = tomar_flash();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión - <?= e($empresa['name']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        html, body {
            width: 100%;
            min-height: 100vh;
            margin: 0;
            overflow-x: hidden;
        }
        <?php if ($fondoLogin): ?>
        .login-bg-full {
            position: fixed;
            inset: 0;
            width: 100vw;
            height: 100vh;
            background-image: url('<?= e($fondoLogin) ?>');
            background-size: cover;
            background-position: center center;
            background-repeat: no-repeat;
            z-index: 0;
            pointer-events: none;
        }
        <?php endif; ?>
        .login-wrap {
            position: relative;
            z-index: 1;
            width: 100%;
            display: flex;
            justify-content: flex-end;
            padding-right: 28px;
        }
        .login-card {
            width: 100%;
            max-width: 390px;
            <?php if ($fondoLogin): ?>
            background-color: rgba(255, 255, 255, 0.94);
            -webkit-backdrop-filter: blur(6px);
            backdrop-filter: blur(6px);
            <?php endif; ?>
        }
        @media (max-width: 576px) {
            .login-wrap {
                justify-content: center;
                padding-right: 12px;
                padding-left: 12px;
            }
        }
    </style>
</head>
<body class="<?= $fondoLogin ? '' : 'bg-light' ?> d-flex align-items-center min-vh-100">
<?php if ($fondoLogin): ?>
<div class="login-bg-full" aria-hidden="true"></div>
<?php endif; ?>
<div class="login-wrap">
    <div class="card shadow-lg border-0 login-card">
        <div class="card-body p-4">
            <h4 class="mb-1 text-center"><?= e($empresa['name']) ?></h4>
            <p class="text-muted small text-center mb-4">Acceso al sistema de inventario</p>
            <?php if ($f): ?>
                <div class="alert alert-<?= e($f['tipo']) ?>"><?= e($f['mensaje']) ?></div>
            <?php endif; ?>
            <form method="post">
                <div class="mb-3">
                    <label class="form-label">Correo electrónico</label>
                    <input type="email" name="email" class="form-control" required autofocus>
                </div>
                <div class="mb-3">
                    <label class="form-label">Contraseña</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Entrar</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>