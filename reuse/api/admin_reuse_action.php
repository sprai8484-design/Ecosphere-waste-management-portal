<?php
require_once '../config.php';
header('Content-Type: application/json');

// Check if admin is logged in (using your existing session logic)
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['error' => 'Unauthorized']); exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$id = (int)($data['idea_id'] ?? 0);
$action = $data['action'] ?? '';
$note = $data['admin_note'] ?? '';

if (!$id || !in_array($action, ['approve', 'reject', 'delete'])) {
    echo json_encode(['error' => 'Invalid request']); exit;
}

try {
    $pdo = getDB();
    if ($action === 'delete') {
        $stmt = $pdo->prepare("DELETE FROM reuse_ideas WHERE id = ?");
        $stmt->execute([$id]);
    } else {
        $status = ($action === 'approve') ? 1 : 2; // 1=Approved, 2=Rejected
        $stmt = $pdo->prepare("UPDATE reuse_ideas SET is_approved = ?, admin_note = ? WHERE id = ?");
        $stmt->execute([$status, $note, $id]);
    }
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}