<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'db_inventory');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

function db(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        $dsn = sprintf('mysql:host=%s;dbname=%s;charset=%s', DB_HOST, DB_NAME, DB_CHARSET);
        $pdo = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    }
    return $pdo;
}

function e(?string $valor): string {
    return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
}

function ahora(): string {
    return date('Y-m-d H:i:s');
}

function monto(float $valor): string {
    return number_format($valor, 2, '.', ',');
}

function flash(string $tipo, string $mensaje): void {
    $_SESSION['flash'] = ['tipo' => $tipo, 'mensaje' => $mensaje];
}

function tomar_flash(): ?array {
    if (empty($_SESSION['flash'])) {
        return null;
    }
    $f = $_SESSION['flash'];
    unset($_SESSION['flash']);
    return $f;
}

function requiere_login(): void {
    if (empty($_SESSION['user_id'])) {
        header('Location: auth.php');
        exit;
    }
}

function empresa_info(): array {
    $stmt = db()->query("SELECT * FROM companies ORDER BY id ASC LIMIT 1");
    return $stmt->fetch() ?: [
        'id' => 1,
        'name' => 'StockInventarios',
        'phone' => '',
        'address' => '',
    ];
}

function menu_permitido(int $roleId): array {
    $stmt = db()->prepare("
        SELECT m.*
        FROM menus m
        INNER JOIN permissions p ON p.menu_id = m.id
        WHERE p.role_id = :role_id
        ORDER BY m.parent_id ASC, m.id ASC
    ");
    $stmt->execute(['role_id' => $roleId]);
    return $stmt->fetchAll();
}

function ruta_a_archivo(string $menuUrl): string {
    $mapa = [
        'customer.index'  => 'personas_config.php?tab=clientes',
        'category.index'  => 'catalogos.php?tab=categorias',
        'product.index'   => 'productos.php',
        'supplier.index'  => 'catalogos.php?tab=proveedores',
        'stock.index'     => 'existencias.php',
        'invoice.index'   => 'facturacion.php',
        'role.index'      => 'personas_config.php?tab=roles',
        'user.index'      => 'personas_config.php?tab=usuarios',
        'report.index'    => 'reportes.php',
        'company.index'   => 'personas_config.php?tab=empresa',
        'password.index'  => 'auth.php?accion=password',
        'developer.index' => 'desarrollador.php',
        'desarrollador.php' => 'desarrollador.php',
    ];
    return $mapa[$menuUrl] ?? 'index.php';
}