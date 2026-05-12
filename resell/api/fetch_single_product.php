<?php
// api/fetch_single_product.php — Single product detail

require_once '../config.php';

$id  = (int)($_GET['id']  ?? 0);
$uid = trim($_GET['uid']  ?? '');

if (!$id && !$uid) jsonOut(['error' => 'ID required'], 400);

try {
    $pdo  = getDB();
    $cond = $id ? "id = :id AND status = 'Approved'" : "product_uid = :uid AND status = 'Approved'";
    $stmt = $pdo->prepare("SELECT * FROM resell_products WHERE $cond LIMIT 1");
    $stmt->execute($id ? [':id' => $id] : [':uid' => $uid]);
    $product = $stmt->fetch();
    if (!$product) jsonOut(['error' => 'Product not found or not approved'], 404);

    $product['price_fmt'] = CURRENCY . number_format((float)$product['price'], 2);

    jsonOut($product);
} catch (Exception $e) {
    jsonOut(['error' => $e->getMessage()], 500);
}
