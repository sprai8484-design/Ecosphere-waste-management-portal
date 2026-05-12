<?php
// api/fetch_blogs.php — Returns approved blogs for the public page
// GET: ?search=keyword&page=1&limit=9

require_once '../config.php';

$search = trim($_GET['search'] ?? '');
$page   = max(1, (int)($_GET['page']  ?? 1));
$limit  = min(24, max(1, (int)($_GET['limit'] ?? 9)));
$offset = ($page - 1) * $limit;

try {
    $pdo    = getDB();
    $where  = "WHERE b.status = 'approved'";
    $params = [];

    if ($search !== '') {
        $where .= " AND (b.title LIKE :s OR b.author_name LIKE :s2)";
        $params[':s']  = "%$search%";
        $params[':s2'] = "%$search%";
    }

    // Total count
    $cntStmt = $pdo->prepare("SELECT COUNT(*) FROM blogs b $where");
    $cntStmt->execute($params);
    $total = (int)$cntStmt->fetchColumn();

    // Fetch
    $stmt = $pdo->prepare("
        SELECT id, title, image, author_name, created_at,
               LEFT(REGEXP_REPLACE(content, '<[^>]+>', ''), 220) AS excerpt
        FROM blogs b
        $where
        ORDER BY b.created_at DESC
        LIMIT :lim OFFSET :off
    ");
    foreach ($params as $k => $v) $stmt->bindValue($k, $v);
    $stmt->bindValue(':lim', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':off', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $blogs = $stmt->fetchAll();

    // Clean up excerpt
    foreach ($blogs as &$b) {
        $b['excerpt'] = mb_substr(preg_replace('/\s+/', ' ', $b['excerpt']), 0, 200) . '…';
    }

    jsonOut(['blogs' => $blogs, 'total' => $total, 'page' => $page, 'pages' => (int)ceil($total / $limit)]);

} catch (Exception $e) {
    jsonOut(['error' => $e->getMessage()], 500);
}
