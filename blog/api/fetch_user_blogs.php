<?php
// api/fetch_user_blogs.php — Returns blogs submitted by a specific email
// GET: ?email=user@example.com

require_once '../config.php';

$email = trim($_GET['email'] ?? '');
if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    jsonOut(['error' => 'Valid email required'], 400);
}

try {
    $pdo  = getDB();
    $stmt = $pdo->prepare("
        SELECT id, title, status, admin_note, reviewed_by, reviewed_at, created_at,
               LEFT(REGEXP_REPLACE(content, '<[^>]+>', ''), 160) AS excerpt
        FROM blogs
        WHERE email = :email
        ORDER BY created_at DESC
    ");
    $stmt->execute([':email' => $email]);
    $blogs = $stmt->fetchAll();

    foreach ($blogs as &$b) {
        $b['excerpt'] = mb_substr(preg_replace('/\s+/', ' ', $b['excerpt']), 0, 160) . '…';
    }

    jsonOut(['blogs' => $blogs, 'count' => count($blogs)]);

} catch (Exception $e) {
    jsonOut(['error' => $e->getMessage()], 500);
}
