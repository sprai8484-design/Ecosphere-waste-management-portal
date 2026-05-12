<?php
// api/save.php — Toggle save/bookmark for an idea (session-based)
// POST: { "idea_id": 5 }

require_once '../config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonOut(['error' => 'POST only'], 405);
}

$body   = json_decode(file_get_contents('php://input'), true);
$ideaId = (int)($body['idea_id'] ?? $_POST['idea_id'] ?? 0);

if ($ideaId <= 0) jsonOut(['error' => 'Invalid idea_id'], 400);

try {
    $pdo = getDB();
    $uid = getUserId();

    // Check if already saved
    $check = $pdo->prepare(
        "SELECT id FROM reuse_saved WHERE idea_id = :id AND user_identifier = :uid"
    );
    $check->execute([':id' => $ideaId, ':uid' => $uid]);
    $existing = $check->fetchColumn();

    if ($existing) {
        // Unsave
        $pdo->prepare("DELETE FROM reuse_saved WHERE id = :id")->execute([':id' => $existing]);
        jsonOut(['success' => true, 'saved' => false, 'message' => 'Removed from saved']);
    } else {
        // Save
        $pdo->prepare(
            "INSERT INTO reuse_saved (idea_id, user_identifier) VALUES (:id, :uid)"
        )->execute([':id' => $ideaId, ':uid' => $uid]);
        jsonOut(['success' => true, 'saved' => true, 'message' => 'Saved!']);
    }

} catch (Exception $e) {
    jsonOut(['error' => $e->getMessage()], 500);
}
