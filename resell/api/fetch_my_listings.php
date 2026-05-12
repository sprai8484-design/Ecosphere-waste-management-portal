<?php
// api/fetch_my_listings.php — Get all listings by seller email

require_once '../config.php';

$email = trim($_GET['email'] ?? '');
if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    jsonOut(['error' => 'Valid email required'], 400);
}

try {
    $pdo  = getDB();
    $stmt = $pdo->prepare("
        SELECT p.id, p.product_uid, p.product_name, p.category, p.condition,
               p.quantity, p.price, p.image, p.status, p.admin_note, p.created_at,
               (SELECT COUNT(*) FROM resell_transactions t WHERE t.product_id=p.id) AS sale_count
        FROM resell_products p
        WHERE p.seller_email = :email
        ORDER BY p.created_at DESC
    ");
    $stmt->execute([':email' => $email]);
    $listings = $stmt->fetchAll();
    foreach ($listings as &$l) {
        $l['price_fmt'] = CURRENCY . number_format((float)$l['price'], 2);
    }
    jsonOut(['listings' => $listings, 'count' => count($listings)]);
} catch (Exception $e) {
    jsonOut(['error' => $e->getMessage()], 500);
}
