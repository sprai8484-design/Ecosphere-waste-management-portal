<?php
// api/fetch_single.php — Returns one idea + its comments + saved status
// GET: ?id=5

require_once '../config.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) jsonOut(['error' => 'Invalid ID'], 400);

try {
    $pdo = getDB();

    // Idea
    $stmt = $pdo->prepare(
        "SELECT * FROM reuse_ideas WHERE id = :id AND is_approved = 1 LIMIT 1"
    );
    $stmt->execute([':id' => $id]);
    $idea = $stmt->fetch();
    if (!$idea) jsonOut(['error' => 'Idea not found'], 404);

    // Parse steps (stored as JSON)
    $idea['steps_array']    = json_decode($idea['steps'], true) ?? [];
    $idea['materials_list'] = array_map('trim', explode(',', $idea['materials']));

    // Comments (most recent first)
    $cStmt = $pdo->prepare(
        "SELECT id, name, comment, created_at
         FROM reuse_comments
         WHERE idea_id = :id
         ORDER BY created_at DESC"
    );
    $cStmt->execute([':id' => $id]);
    $idea['comments'] = $cStmt->fetchAll();

    // Is it saved by this user?
    $uid   = getUserId();
    $sStmt = $pdo->prepare(
        "SELECT id FROM reuse_saved WHERE idea_id = :id AND user_identifier = :uid LIMIT 1"
    );
    $sStmt->execute([':id' => $id, ':uid' => $uid]);
    $idea['is_saved'] = (bool)$sStmt->fetchColumn();

    jsonOut($idea);

} catch (Exception $e) {
    jsonOut(['error' => $e->getMessage()], 500);
}
