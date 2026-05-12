<?php
// api/admin_product_action.php — Admin approve/reject/delete product

session_start();
require_once '../config.php';
if (!isAdmin()) jsonOut(['error' => 'Unauthorised'], 401);
if ($_SERVER['REQUEST_METHOD'] !== 'POST') jsonOut(['error' => 'POST only'], 405);

$body      = json_decode(file_get_contents('php://input'), true) ?? [];
$productId = (int)($body['product_id'] ?? 0);
$action    = trim($body['action']      ?? '');
$note      = clean(trim($body['admin_note'] ?? ''));

if ($productId <= 0) jsonOut(['error' => 'Invalid product_id'], 400);
if (!in_array($action, ['approve', 'reject', 'delete'], true)) jsonOut(['error' => 'Invalid action'], 400);

$reviewedBy = $_SESSION['admin_user'] ?? 'admin';

try {
    $pdo = getDB();
    $chk = $pdo->prepare("SELECT id FROM resell_products WHERE id=:id");
    $chk->execute([':id' => $productId]);
    if (!$chk->fetchColumn()) jsonOut(['error' => 'Product not found'], 404);

    if ($action === 'delete') {
        $pdo->prepare("DELETE FROM resell_products WHERE id=:id")->execute([':id' => $productId]);
        jsonOut(['success' => true, 'message' => 'Product deleted.']);
    }

    $status = $action === 'approve' ? 'Approved' : 'Rejected';
    $pdo->prepare("
        UPDATE resell_products
        SET status=:s, admin_note=:n, reviewed_by=:by, reviewed_at=NOW()
        WHERE id=:id
    ")->execute([':s' => $status, ':n' => ($note ?: ($action === 'approve' ? 'Approved.' : 'Rejected.')),
                 ':by' => $reviewedBy, ':id' => $productId]);

    jsonOut(['success' => true, 'status' => $status, 'message' => "Product $status."]);
} catch (Exception $e) {
    jsonOut(['error' => $e->getMessage()], 500);
}
