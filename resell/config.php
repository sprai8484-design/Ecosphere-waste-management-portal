<?php
// ================================================================
// config.php — Resell Module Configuration
// ================================================================

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'ecosphere_db');

define('UPLOADS_DIR', __DIR__ . '/uploads/');
define('UPLOADS_URL', 'uploads/');
define('MAX_UPLOAD_MB', 5);
define('CURRENCY', '₹');
define('CURRENCY_CODE', 'INR');
define('SITE_NAME', 'Ecosphere Resell');
define('ADMIN_SESSION', 'resell_admin_auth');

// ── PDO singleton ─────────────────────────────────────────────────
function getDB(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        try {
            $pdo = new PDO(
                'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
                DB_USER, DB_PASS,
                [PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                 PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                 PDO::ATTR_EMULATE_PREPARES   => false]
            );
        } catch (PDOException $e) {
            jsonOut(['error' => 'Database connection failed: ' . $e->getMessage()], 500);
        }
    }
    return $pdo;
}

// ── JSON output ───────────────────────────────────────────────────
function jsonOut(array $data, int $code = 200): void {
    http_response_code($code);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

// ── Sanitise string ───────────────────────────────────────────────
function clean(string $v): string {
    return htmlspecialchars(trim($v), ENT_QUOTES, 'UTF-8');
}

// ── Generate unique product UID ───────────────────────────────────
function generateProductUID(): string {
    $pdo = getDB();
    do {
        $uid = 'ECO-RSL-' . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
        $chk = $pdo->prepare("SELECT id FROM resell_products WHERE product_uid=:u LIMIT 1");
        $chk->execute([':u' => $uid]);
    } while ($chk->fetchColumn());
    return $uid;
}

// ── Generate unique transaction UID ──────────────────────────────
function generateTxnUID(): string {
    $pdo = getDB();
    do {
        $uid = 'TXN-ECO-' . strtoupper(substr(md5(uniqid()), 0, 9));
        $chk = $pdo->prepare("SELECT id FROM resell_transactions WHERE transaction_uid=:u LIMIT 1");
        $chk->execute([':u' => $uid]);
    } while ($chk->fetchColumn());
    return $uid;
}

// ── Image upload handler ──────────────────────────────────────────
function handleImageUpload(array $file): ?string {
    if ($file['error'] === UPLOAD_ERR_NO_FILE) return null;
    if ($file['error'] !== UPLOAD_ERR_OK) {
        throw new RuntimeException('Image upload failed (error ' . $file['error'] . ').');
    }
    $maxBytes = MAX_UPLOAD_MB * 1024 * 1024;
    if ($file['size'] > $maxBytes) {
        throw new RuntimeException('Image must be under ' . MAX_UPLOAD_MB . 'MB.');
    }
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime  = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    if (!in_array($mime, $allowed, true)) {
        throw new RuntimeException('Only JPG, PNG, WEBP, GIF images are allowed.');
    }
    if (!is_dir(UPLOADS_DIR)) mkdir(UPLOADS_DIR, 0755, true);
    $ext  = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $name = 'product_' . uniqid() . '.' . $ext;
    if (!move_uploaded_file($file['tmp_name'], UPLOADS_DIR . $name)) {
        throw new RuntimeException('Failed to save image. Check uploads/ folder permissions.');
    }
    return UPLOADS_URL . $name;
}

// ── Admin session ─────────────────────────────────────────────────
function startSess(): void {
    if (session_status() === PHP_SESSION_NONE) session_start();
}
function isAdmin(): bool {
    startSess();
    return !empty($_SESSION[ADMIN_SESSION]);
}
function requireAdmin(): void {
    if (!isAdmin()) { header('Location: login.php'); exit; }
}

// ── Categories & Conditions ───────────────────────────────────────
function categories(): array {
    return ['Electronics', 'Furniture', 'Clothes', 'Books', 'Fitness',
            'Kitchen', 'Games', 'Garden', 'Other'];
}
function conditions(): array {
    return ['New', 'Like New', 'Used', 'Damaged'];
}
function conditionColor(string $c): string {
    return match ($c) {
        'New'      => '#4a8c5c',
        'Like New' => '#7ab88a',
        'Used'     => '#d4a843',
        'Damaged'  => '#d4745a',
        default    => '#999',
    };
}
function categoryEmoji(string $cat): string {
    return match ($cat) {
        'Electronics'    => '💻',
        'Furniture'      => '🪑',
        'Clothes'        => '👕',
        'Books'          => '📚',
        'Fitness'=> '⚽',
        'Kitchen' => '🏠',
        'Games'   => '🎮',
        'Garden'         => '🌿',
        default          => '📦',
    };
}
