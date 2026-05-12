<?php
// ============================================================
// config.php — Database connection & global settings
// ============================================================

define('DB_HOST', 'localhost');
define('DB_USER', 'root');        // XAMPP default
define('DB_PASS', '');            // XAMPP default (empty)
define('DB_NAME', 'ecosphere_db');

// Site settings
define('SITE_NAME', 'Ecosphere');
define('UPLOADS_DIR', __DIR__ . '/uploads/');
define('UPLOADS_URL', 'uploads/');
define('ADMIN_SESSION_KEY', 'eco_admin_logged_in');

// Connect to database
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
            http_response_code(500);
            die(json_encode(['error' => 'Database connection failed: ' . $e->getMessage()]));
        }
    }
    return $pdo;
}

// Generate unique tracking ID
function generateTrackingId(): string {
    $year = date('Y');
    $pdo  = getDB();
    $stmt = $pdo->query("SELECT COUNT(*) FROM recycle_requests");
    $count = (int)$stmt->fetchColumn() + 1;
    return 'ECO-' . $year . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
}

// Sanitize input
function clean(string $val): string {
    return htmlspecialchars(trim($val), ENT_QUOTES, 'UTF-8');
}

// JSON response helper
function jsonResponse(array $data, int $code = 200): void {
    http_response_code($code);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

// Status labels
function statusLabel(int $s): string {
    return match($s) {
        1 => 'Request Submitted',
        2 => 'Item Picked Up',
        3 => 'Processing Started',
        4 => 'Recycling Completed',
        5 => 'Product Ready',
        6 => 'Delivered Back',
        default => 'Unknown'
    };
}

function statusColor(int $s): string {
    return match($s) {
        1 => '#7ab88a',
        2 => '#4a8c5c',
        3 => '#d4a843',
        4 => '#2d5a3d',
        5 => '#4a8c5c',
        6 => '#1a3a2a',
        default => '#999'
    };
}

// Waste type list
function wasteTypes(): array {
    return ['Plastic','E-Waste','Organic','Clothes/Fabric','Construction Waste'];
}
