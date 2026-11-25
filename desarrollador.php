<?php
require_once __DIR__ . '/config.php';
requiere_login();

$empresa = empresa_info();

$whatsappNumero = '584123679938';
$whatsappTexto  = '+58 412-367-9938';

$totalProductos = 0;
try {
    $totalProductos = (int) db()->query("SELECT COUNT(*) FROM products")->fetchColumn();
} catch (Throwable $e) {
    $totalProductos = 0;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Desarrollador - <?= e($empresa['name']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --op-dark: #1e242b;
            --op-accent: #2563eb;
            --op-soft-bg: #f3f5f8;
            --op-border: #e5e9f0;
        }
        body {
            background-color: var(--op-soft-bg);
            color: #1f2937;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .topbar {
            background: var(--op-dark);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.12);
        }
        .pro-card {
            background: #ffffff;
            border: 1px solid var(--op-border);
            border-radius: 14px;
            box-shadow: 0 8px 24px rgba(15, 23, 42, 0.05);
        }
        .profile-banner {
            background: linear-gradient(135deg, #eff6ff 0%, #f8fafc 100%);
            border: 1px solid #dbeafe;
            border-left: 5px solid var(--op-accent);
            border-radius: 12px;
        }
        .avatar-circle {
            width: 74px;
            height: 74px;
            border-radius: 50%;
            background: linear-gradient(135deg, #1d4ed8, #0ea5e9);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            font-weight: 700;
            flex-shrink: 0;
            box-shadow: 0 6px 16px rgba(37, 99, 235, 0.25);
        }
        .badge-principal {
            background: #dbeafe;
            color: #1d4ed8;
            font-weight: 700;
            letter-spacing: 0.05em;
            font-size: 0.72rem;
            padding: 0.45rem 0.75rem;
            border-radius: 999px;
        }
        .stack-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(135px, 1fr));
            gap: 12px;
        }
        .stack-tile {
            border: 1px solid var(--op-border);
            border-radius: 12px;
            padding: 14px 10px;
            text-align: center;
            background: #fcfcfd;
            transition: transform 0.18s ease, box-shadow 0.18s ease, border-color 0.18s ease;
        }
        .stack-tile:hover {
            transform: translateY(-3px);
            border-color: #93c5fd;
            box-shadow: 0 8px 18px rgba(37, 99, 235, 0.08);
        }
        .stack-tag {
            display: inline-block;
            font-size: 0.72rem;
            font-weight: 700;
            padding: 0.25rem 0.55rem;
            border-radius: 6px;
            margin-bottom: 8px;
        }
        .support-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 10px 0;
            border-bottom: 1px solid #f1f5f9;
        }
        .support-item:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }
        .support-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: #eff6ff;
            color: var(--op-accent);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            flex-shrink: 0;
        }
        .support-icon-wa {
            background: #dcfce7;
            color: #15803d;
        }
        .recognition-box {
            background: #fffbeb;
            border: 1px solid #fde68a;
            border-left: 4px solid #eab308;
            border-radius: 12px;
        }
        .env-bar {
            background: var(--op-dark);
            color: #f8fafc;
            border-radius: 12px;
            padding: 16px 20px;
        }
        .footer-note {
            margin-top: auto;
            font-size: 0.82rem;
            color: #6b7280;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-dark topbar px-3 mb-4">
    <a class="navbar-brand fw-semibold" href="index.php"><?= e($empresa['name']) ?></a>
    <div class="d-flex gap-2">
        <a href="index.php" class="btn btn-sm btn-outline-light">Inicio</a>
    </div>
</nav>

<div class="container-fluid px-4 pb-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center border-bottom pb-2 mb-4 gap-2">
        <h3 class="mb-0 fw-bold">Ficha Profesional</h3>
        <div class="d-flex flex-wrap gap-2">
            <a href="https://wa.me/<?= urlencode($whatsappNumero) ?>?text=<?= rawurlencode('Hola Omar, necesito soporte con ' . $empresa['name']) ?>" target="_blank" rel="noopener" class="btn btn-sm btn-success">
                WhatsApp
            </a>
            <a href="mailto:op2020pk@gmail.com?subject=Soporte%20-%20<?= rawurlencode($empresa['name']) ?>" class="btn btn-sm btn-primary">
                Contactar por correo
            </a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="pro-card p-4 h-100">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                    <h5 class="fw-bold mb-0">Perfil del Desarrollador</h5>
                    <span class="badge-principal">PRINCIPAL DEVELOPER</span>
                </div>

                <div class="profile-banner p-3 p-md-4 mb-4">
                    <div class="d-flex align-items-center gap-3 flex-wrap">
                        <div class="avatar-circle">OP</div>
                        <div>
                            <h4 class="fw-bold mb-1">Omar Pinto</h4>
                            <div class="text-primary fw-semibold small">
                                Especialista en Soluciones Web, Escritorio y Bases de Datos
                            </div>
                        </div>
                    </div>
                </div>

                <p class="text-secondary mb-3">
                    Desarrollador de software orientado a soluciones prácticas para gestión de inventarios, control operativo y sistemas web modulares en <strong>La Victoria, Estado Aragua, Venezuela</strong>.
                </p>
                <p class="text-secondary mb-4">
                    Enfocado en el ciclo completo de desarrollo: diseño de bases de datos relacionales con MySQL/MariaDB, lógica segura en PHP con PDO, interfaces claras y herramientas de escritorio con Python y SQLite3.
                </p>

                <hr class="my-4">

                <div class="small fw-bold text-uppercase text-muted mb-3">Stack Tecnológico Principal</div>
                <div class="stack-grid">
                    <div class="stack-tile">
                        <span class="stack-tag bg-primary-subtle text-primary">PHP 8 / PDO</span>
                        <div class="fw-semibold small">PHP 8 / MySQL</div>
                    </div>
                    <div class="stack-tile">
                        <span class="stack-tag bg-warning-subtle text-warning-emphasis">PYTHON</span>
                        <div class="fw-semibold small">Python / Tkinter</div>
                    </div>
                    <div class="stack-tile">
                        <span class="stack-tag bg-secondary-subtle text-secondary">SISTEMAS</span>
                        <div class="fw-semibold small">Lenguaje C</div>
                    </div>
                    <div class="stack-tile">
                        <span class="stack-tag bg-danger-subtle text-danger">WEB UI</span>
                        <div class="fw-semibold small">HTML5 / CSS3 / JS</div>
                    </div>
                    <div class="stack-tile">
                        <span class="stack-tag bg-info-subtle text-info-emphasis">SERVIDOR</span>
                        <div class="fw-semibold small">XAMPP / Apache</div>
                    </div>
                    <div class="stack-tile">
                        <span class="stack-tag bg-success-subtle text-success">DATOS</span>
                        <div class="fw-semibold small">MySQL / SQLite3</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 d-flex flex-column gap-4">
            <div class="pro-card p-4">
                <h5 class="fw-bold mb-3">Canales de Soporte</h5>

                <div class="support-item">
                    <div class="support-icon">OP</div>
                    <div>
                        <div class="small text-muted">Desarrollador</div>
                        <div class="fw-semibold">Omar Pinto</div>
                    </div>
                </div>

                <div class="support-item">
                    <div class="support-icon">@</div>
                    <div>
                        <div class="small text-muted">Correo Principal</div>
                        <div class="fw-semibold">
                            <a href="mailto:op2020pk@gmail.com" class="text-decoration-none">op2020pk@gmail.com</a>
                        </div>
                    </div>
                </div>

                <div class="support-item">
                    <div class="support-icon support-icon-wa">WA</div>
                    <div>
                        <div class="small text-muted">WhatsApp</div>
                        <div class="fw-semibold">
                            <a href="https://wa.me/<?= urlencode($whatsappNumero) ?>?text=<?= rawurlencode('Hola Omar, necesito soporte con ' . $empresa['name']) ?>" target="_blank" rel="noopener" class="text-success text-decoration-none">
                                <?= e($whatsappTexto) ?>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="support-item">
                    <div class="support-icon">VE</div>
                    <div>
                        <div class="small text-muted">Ubicación</div>
                        <div class="fw-semibold">La Victoria, Estado Aragua, Venezuela</div>
                    </div>
                </div>

                <div class="support-item">
                    <div class="support-icon">DB</div>
                    <div>
                        <div class="small text-muted">Sistema Activo</div>
                        <div class="fw-semibold"><?= e($empresa['name']) ?></div>
                    </div>
                </div>
            </div>

            <div class="recognition-box p-4">
                <div class="small fw-bold text-uppercase text-warning-emphasis mb-2">Reconocimiento</div>
                <p class="fst-italic text-secondary mb-0">
                    “Agradecimiento especial a docentes, compañeros y usuarios que impulsan el aprendizaje constante y la mejora continua de cada módulo.”
                </p>
            </div>
        </div>
    </div>

    <div class="env-bar mt-4 d-flex flex-wrap justify-content-between align-items-center gap-3">
        <div>
            <div class="fw-bold">Entorno Local: XAMPP · PHP <?= e(PHP_VERSION) ?></div>
            <div class="small text-white-50">Arquitectura modular PHP + PDO sobre <?= e(DB_NAME) ?></div>
        </div>
        <span class="badge bg-secondary fs-6 fw-semibold px-3 py-2">
            <?= (int) $totalProductos ?> Ítems en Catálogo
        </span>
    </div>
</div>

<footer class="footer-note px-4 py-3 d-flex flex-wrap justify-content-between gap-2">
    <div><?= e($empresa['name']) ?> — Desarrollado por Omar Pinto</div>
    <div>La Victoria, Estado Aragua, Venezuela</div>
</footer>
</body>
</html>