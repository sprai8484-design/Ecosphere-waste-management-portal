<?php
// api/admin_blog_action.php — Admin approve/reject a blog
// POST JSON: { "blog_id":5, "action":"approve"|"reject", "admin_note":"…" }
// Requires admin session.

session_start();
require_once '../config.php';

if (empty($_SESSION[ADMIN_SESSION_KEY])) {
    jsonOut(['error' => 'Unauthorised'], 401);
}
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonOut(['error' => 'POST only'], 405);
}

$body   = json_decode(file_get_contents('php://input'), true) ?? [];
$blogId = (int)($body['blog_id']    ?? 0);
$action = trim($body['action']      ?? '');
$note   = clean(trim($body['admin_note'] ?? ''));

if ($blogId <= 0)                        jsonOut(['error' => 'Invalid blog_id'], 400);
if (!in_array($action, ['approve','reject'], true)) jsonOut(['error' => 'Invalid action'], 400);

$status     = $action === 'approve' ? 'approved' : 'rejected';
$reviewedBy = $_SESSION['admin_user'] ?? 'admin';

try {
    $pdo  = getDB();

    // Verify blog exists
    $check = $pdo->prepare("SELECT id FROM blogs WHERE id = :id");
    $check->execute([':id' => $blogId]);
    if (!$check->fetchColumn()) jsonOut(['error' => 'Blog not found'], 404);

    $stmt = $pdo->prepare("
        UPDATE blogs
        SET status = :status, admin_note = :note,
            reviewed_by = :by, reviewed_at = NOW()
        WHERE id = :id
    ");
    $stmt->execute([
        ':status' => $status,
        ':note'   => $note ?: ($action === 'approve' ? 'Approved.' : 'Rejected.'),
        ':by'     => $reviewedBy,
        ':id'     => $blogId,
    ]);

    jsonOut(['success' => true, 'status' => $status, 'message' => 'Blog ' . $status . ' successfully.']);

} catch (Exception $e) {
    jsonOut(['error' => $e->getMessage()], 500);
}
