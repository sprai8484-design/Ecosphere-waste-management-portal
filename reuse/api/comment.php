<?php
// api/comment.php — Add a comment to an idea
// POST: { "idea_id": 5, "name": "Alice", "comment": "Great idea!" }

require_once '../config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonOut(['error' => 'POST only'], 405);
}

$body    = json_decode(file_get_contents('php://input'), true) ?? [];
$ideaId  = (int)($body['idea_id'] ?? $_POST['idea_id'] ?? 0);
$name    = clean(trim($body['name']    ?? $_POST['name']    ?? ''));
$comment = clean(trim($body['comment'] ?? $_POST['comment'] ?? ''));

if ($ideaId <= 0)         jsonOut(['error' => 'Invalid idea_id'], 400);
if ($name === '')         jsonOut(['error' => 'Name is required'], 422);
if ($comment === '')      jsonOut(['error' => 'Comment is required'], 422);
if (mb_strlen($comment) > 1000) jsonOut(['error' => 'Comment too long (max 1000 chars)'], 422);

try {
    $pdo = getDB();

    // Verify idea exists
    $check = $pdo->prepare("SELECT id FROM reuse_ideas WHERE id = :id AND is_approved = 1");
    $check->execute([':id' => $ideaId]);
    if (!$check->fetchColumn()) jsonOut(['error' => 'Idea not found'], 404);

    // Insert comment
    $stmt = $pdo->prepare(
        "INSERT INTO reuse_comments (idea_id, name, comment) VALUES (:id, :name, :comment)"
    );
    $stmt->execute([':id' => $ideaId, ':name' => $name, ':comment' => $comment]);
    $newId = (int)$pdo->lastInsertId();

    // Return the new comment
    $fetch = $pdo->prepare("SELECT id, name, comment, created_at FROM reuse_comments WHERE id = :id");
    $fetch->execute([':id' => $newId]);
    $newComment = $fetch->fetch();

    jsonOut(['success' => true, 'comment' => $newComment]);

} catch (Exception $e) {
    jsonOut(['error' => $e->getMessage()], 500);
}
