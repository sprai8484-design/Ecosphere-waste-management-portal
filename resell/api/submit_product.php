<?php
// api/submit_product.php — Insert new resell listing
// POST: multipart/form-data

require_once '../config.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') jsonOut(['error' => 'POST only'], 405);

// ── Collect & validate ────────────────────────────────────────────
$required = ['seller_name', 'seller_email', 'seller_phone',
             'product_name', 'category', 'description', 'condition', 'quantity', 'price'];

$data = [];
foreach ($required as $f) {
    $val = trim($_POST[$f] ?? '');
    if ($val === '') jsonOut(['error' => "Field '$f' is required."], 422);
    $data[$f] = clean($val);
}

// Email
if (!filter_var($data['seller_email'], FILTER_VALIDATE_EMAIL)) {
    jsonOut(['error' => 'Invalid email address.'], 422);
}
// Category
if (!in_array($data['category'], categories(), true)) {
    jsonOut(['error' => 'Invalid category.'], 422);
}
// Condition
if (!in_array($data['condition'], conditions(), true)) {
    jsonOut(['error' => 'Invalid condition.'], 422);
}
// Quantity
$qty = (int)$data['quantity'];
if ($qty < 1 || $qty > 9999) jsonOut(['error' => 'Quantity must be between 1 and 9999.'], 422);

// Price
$price = (float)str_replace(',', '', $data['price']);
if ($price <= 0 || $price > 9999999) jsonOut(['error' => 'Price must be a positive number.'], 422);

// Description length
if (mb_strlen($data['description']) < 20) {
    jsonOut(['error' => 'Description must be at least 20 characters.'], 422);
}

// ── Image upload ──────────────────────────────────────────────────
$imagePath = null;
try {
    if (!empty($_FILES['image'])) {
        $imagePath = handleImageUpload($_FILES['image']);
    }
} catch (RuntimeException $e) {
    jsonOut(['error' => $e->getMessage()], 422);
}

// ── Upsert user ───────────────────────────────────────────────────
try {
    $pdo = getDB();

    // Get or create user by email
    $userStmt = $pdo->prepare("SELECT id FROM resell_users WHERE email=:e LIMIT 1");
    $userStmt->execute([':e' => $data['seller_email']]);
    $userId = $userStmt->fetchColumn();
    if (!$userId) {
        $ins = $pdo->prepare("INSERT INTO resell_users (name,email,phone) VALUES (:n,:e,:p)");
        $ins->execute([':n' => $data['seller_name'], ':e' => $data['seller_email'], ':p' => $data['seller_phone']]);
        $userId = (int)$pdo->lastInsertId();
    }

    $productUID = generateProductUID();

    $stmt = $pdo->prepare("
        INSERT INTO resell_products
          (product_uid, user_id, seller_name, seller_email, seller_phone,
           product_name, category, description, `condition`, quantity, price, image, status)
        VALUES
          (:uid, :uid2, :sname, :semail, :sphone,
           :pname, :cat, :desc, :cond, :qty, :price, :img, 'Pending')
    ");
    $stmt->execute([
        ':uid'    => $productUID,
        ':uid2'   => $userId,
        ':sname'  => $data['seller_name'],
        ':semail' => $data['seller_email'],
        ':sphone' => $data['seller_phone'],
        ':pname'  => $data['product_name'],
        ':cat'    => $data['category'],
        ':desc'   => $data['description'],
        ':cond'   => $data['condition'],
        ':qty'    => $qty,
        ':price'  => $price,
        ':img'    => $imagePath,
    ]);

    $newId = (int)$pdo->lastInsertId();
    jsonOut(['success' => true, 'product_id' => $newId, 'product_uid' => $productUID,
             'message' => 'Listing submitted! It will appear after admin approval.']);

} catch (Exception $e) {
    jsonOut(['error' => 'Database error: ' . $e->getMessage()], 500);
}
