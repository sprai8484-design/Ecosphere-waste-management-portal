<?php
// api/like.php — Toggle-style like (increments every call; no login needed)
// POST: { "idea_id": 5 }  OR  form POST with idea_id field

require_once '../config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonOut(['error' => 'POST only'], 405);
}

// Accept JSON body or form data
$body = json_decode(file_get_contents('php://input'), true);
$ideaId = (int)($body['idea_id'] ?? $_POST['idea_id'] ?? 0);

if ($ideaId <= 0) jsonOut(['error' => 'Invalid idea_id'], 400);

try {
    $pdo = getDB();

    // Verify idea exists
    $check = $pdo->prepare("SELECT id FROM reuse_ideas WHERE id = :id AND is_approved = 1");
    $check->execute([':id' => $ideaId]);
    if (!$check->fetchColumn()) jsonOut(['error' => 'Idea not found'], 404);

    // Increment
    $pdo->prepare("UPDATE reuse_ideas SET likes = likes + 1 WHERE id = :id")
        ->execute([':id' => $ideaId]);

    // Return new count
    $newCount = (int)$pdo->prepare("SELECT likes FROM reuse_ideas WHERE id = :id")
        ->execute([':id' => $ideaId]) ? $pdo->query("SELECT likes FROM reuse_ideas WHERE id = $ideaId")->fetchColumn() : 0;

    $stmt = $pdo->prepare("SELECT likes FROM reuse_ideas WHERE id = :id");
    $stmt->execute([':id' => $ideaId]);
    $newCount = (int)$stmt->fetchColumn();

    jsonOut(['success' => true, 'likes' => $newCount]);

} catch (Exception $e) {
    jsonOut(['error' => $e->getMessage()], 500);
}
