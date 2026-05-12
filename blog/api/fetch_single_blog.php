<?php
// api/fetch_single_blog.php — Returns one approved blog by ID
// GET: ?id=5

require_once '../config.php';

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) jsonOut(['error' => 'Invalid ID'], 400);

try {
    $pdo  = getDB();
    $stmt = $pdo->prepare("
        SELECT id, title, content, image, author_name, created_at
        FROM blogs
        WHERE id = :id AND status = 'approved'
        LIMIT 1
    ");
    $stmt->execute([':id' => $id]);
    $blog = $stmt->fetch();

    if (!$blog) jsonOut(['error' => 'Blog not found or not yet approved'], 404);

    // Related blogs (same none, just latest 3 approved excluding this)
    $rel = $pdo->prepare("
        SELECT id, title, author_name, created_at
        FROM blogs WHERE status='approved' AND id != :id
        ORDER BY created_at DESC LIMIT 3
    ");
    $rel->execute([':id' => $id]);
    $blog['related'] = $rel->fetchAll();

    jsonOut($blog);

} catch (Exception $e) {
    jsonOut(['error' => $e->getMessage()], 500);
}
