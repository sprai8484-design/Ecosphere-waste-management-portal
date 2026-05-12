<?php
// ================================================================
// config.php — Database connection & shared helpers
// ================================================================

define('DB_HOST', 'localhost');
define('DB_USER', 'root');   // XAMPP default
define('DB_PASS', '');       // XAMPP default (empty)
define('DB_NAME', 'ecosphere_db');

define('UPLOADS_DIR', __DIR__ . '/uploads/');
define('UPLOADS_URL', 'uploads/');
define('MAX_UPLOAD_MB', 5);

// ── Database connection (singleton) ─────────────────────────────
function getDB(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        try {
            $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
            $pdo = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
        } catch (PDOException $e) {
            jsonOut(['error' => 'DB connection failed: ' . $e->getMessage()], 500);
        }
    }
    return $pdo;
}

// ── JSON response ────────────────────────────────────────────────
function jsonOut(array $data, int $code = 200): void {
    http_response_code($code);
    header('Content-Type: application/json; charset=utf-8');
    header('Access-Control-Allow-Origin: *');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

// ── Sanitise string ──────────────────────────────────────────────
function clean(string $v): string {
    return htmlspecialchars(trim($v), ENT_QUOTES, 'UTF-8');
}

// ── Categories list ──────────────────────────────────────────────
function categories(): array {
    return ['Plastic', 'Clothes', 'Paper', 'Glass', 'Wood', 'Metal', 'Garden', 'Electronics'];
}

// ── Difficulty colour map ────────────────────────────────────────
function diffColor(string $d): string {
    return match ($d) {
        'Easy'   => '#4a8c5c',
        'Medium' => '#d4a843',
        'Hard'   => '#d4745a',
        default  => '#999',
    };
}

// ── Session-based user identifier (no login needed) ─────────────
function getUserId(): string {
    if (session_status() === PHP_SESSION_NONE) session_start();
    if (empty($_SESSION['eco_uid'])) {
        $_SESSION['eco_uid'] = 'sess_' . bin2hex(random_bytes(12));
    }
    return $_SESSION['eco_uid'];
}
