<?php
// send_request.php — Handles recycling request form submission (POST)
// Returns JSON: { success: true, tracking_id: "ECO-2025-XXXX" }
//            or { success: false, error: "message" }

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(['error' => 'Method not allowed'], 405);
}

// ── Collect & validate fields ─────────────────────────────────
$required = ['user_name','email','phone','address','city','pincode','waste_type','description','method'];
$data     = [];

foreach ($required as $field) {
    $val = isset($_POST[$field]) ? trim($_POST[$field]) : '';
    if ($val === '') {
        jsonResponse(['error' => "Field '$field' is required."], 422);
    }
    $data[$field] = clean($val);
}

// Optional fields
$data['custom_request'] = isset($_POST['custom_request']) ? clean(trim($_POST['custom_request'])) : null;

// Validate email
if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
    jsonResponse(['error' => 'Invalid email address.'], 422);
}

// Validate method
if (!in_array($data['method'], ['pickup','drop'])) {
    jsonResponse(['error' => 'Invalid collection method.'], 422);
}

// Validate waste type
if (!in_array($data['waste_type'], wasteTypes())) {
    jsonResponse(['error' => 'Invalid waste type.'], 422);
}

// ── Handle image upload ───────────────────────────────────────
$imagePath = null;
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $file     = $_FILES['image'];
    $maxSize  = 5 * 1024 * 1024; // 5 MB
    $allowed  = ['image/jpeg','image/png','image/gif','image/webp'];

    if ($file['size'] > $maxSize) {
        jsonResponse(['error' => 'Image must be under 5MB.'], 422);
    }

    // Use finfo for real MIME check
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime  = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    if (!in_array($mime, $allowed)) {
        jsonResponse(['error' => 'Only JPG, PNG, GIF, WEBP images are allowed.'], 422);
    }

    // Create uploads dir if needed
    if (!is_dir(UPLOADS_DIR)) {
        mkdir(UPLOADS_DIR, 0755, true);
    }

    $ext       = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename  = 'img_' . uniqid() . '.' . strtolower($ext);
    $destPath  = UPLOADS_DIR . $filename;

    if (!move_uploaded_file($file['tmp_name'], $destPath)) {
        jsonResponse(['error' => 'Failed to save uploaded image.'], 500);
    }

    $imagePath = UPLOADS_URL . $filename;
}

// ── Insert into DB ────────────────────────────────────────────
try {
    $pdo        = getDB();
    $trackingId = generateTrackingId();

    $stmt = $pdo->prepare("
        INSERT INTO recycle_requests
            (tracking_id, user_name, email, phone, address, city, pincode,
             waste_type, description, custom_request, image, method, status)
        VALUES
            (:tid, :uname, :email, :phone, :addr, :city, :pin,
             :wtype, :desc, :custom, :img, :method, 1)
    ");

    $stmt->execute([
        ':tid'    => $trackingId,
        ':uname'  => $data['user_name'],
        ':email'  => $data['email'],
        ':phone'  => $data['phone'],
        ':addr'   => $data['address'],
        ':city'   => $data['city'],
        ':pin'    => $data['pincode'],
        ':wtype'  => $data['waste_type'],
        ':desc'   => $data['description'],
        ':custom' => $data['custom_request'],
        ':img'    => $imagePath,
        ':method' => $data['method'],
    ]);

    jsonResponse(['success' => true, 'tracking_id' => $trackingId]);

} catch (Exception $e) {
    jsonResponse(['error' => 'Database error: ' . $e->getMessage()], 500);
}
