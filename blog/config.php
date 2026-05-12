<?php
// ================================================================
// config.php — Shared configuration & helpers
// ================================================================

define('DB_HOST', 'localhost');
define('DB_USER', 'root');      // XAMPP default
define('DB_PASS', '');          // XAMPP default (blank)
define('DB_NAME', 'ecosphere_db');

define('UPLOADS_DIR', __DIR__ . '/uploads/');
define('UPLOADS_URL', 'uploads/');
define('MAX_UPLOAD_MB', 5);
define('SITE_NAME', 'Ecosphere Blog');
define('ADMIN_SESSION_KEY', 'blog_admin_auth');

// ── PDO singleton ────────────────────────────────────────────────
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
            jsonOut(['error' => 'Database connection failed: ' . $e->getMessage()], 500);
        }
    }
    return $pdo;
}

// ── JSON response ────────────────────────────────────────────────
function jsonOut(array $data, int $code = 200): void {
    http_response_code($code);
    header('Content-Type: application/json; charset=utf-8');
    header('Access-Control-Allow-Origin: *');
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

// ── Sanitise ─────────────────────────────────────────────────────
function clean(string $v): string {
    return htmlspecialchars(trim($v), ENT_QUOTES, 'UTF-8');
}

// ── Excerpt from HTML content ─────────────────────────────────────
function excerpt(string $html, int $chars = 180): string {
    $plain = strip_tags($html);
    $plain = preg_replace('/\s+/', ' ', $plain);
    return mb_strlen($plain) > $chars ? mb_substr($plain, 0, $chars) . '…' : $plain;
}

// ── Waste-management keyword check ───────────────────────────────
function isWasteRelated(string $title, string $content): bool {
    $keywords = [
        'waste','recycle','recycling','reuse','upcycle','upcycling','compost','composting',
        'sustainability','sustainable','environment','environmental','landfill','plastic',
        'e-waste','ewaste','zero waste','pollution','green','eco','biodegradable',
        'carbon','climate','circular economy','textile waste','food waste','organic waste',
        'reduce','refuse','rot','repair','repurpose','conservation','energy',
        'emissions','greenhouse','ecology','nature','planet','earth',
    ];
    $text = strtolower($title . ' ' . strip_tags($content));
    foreach ($keywords as $kw) {
        if (str_contains($text, $kw)) return true;
    }
    return false;
}

// ── User identifier (email-based, stored in session) ─────────────
function startSession(): void {
    if (session_status() === PHP_SESSION_NONE) session_start();
}

// ── Image upload handler ─────────────────────────────────────────
function handleImageUpload(array $file): ?string {
    if ($file['error'] === UPLOAD_ERR_NO_FILE) return null;
    if ($file['error'] !== UPLOAD_ERR_OK) {
        throw new RuntimeException('Image upload failed (error code ' . $file['error'] . ').');
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
    $name = 'blog_' . uniqid() . '.' . $ext;
    $dest = UPLOADS_DIR . $name;

    if (!move_uploaded_file($file['tmp_name'], $dest)) {
        throw new RuntimeException('Could not save uploaded image. Check permissions on /uploads/');
    }

    return UPLOADS_URL . $name;
}
