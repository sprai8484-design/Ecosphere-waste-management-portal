<?php
// api/admin_fetch_blogs.php — All blogs for admin panel
// GET: ?status=pending|approved|rejected|all&search=keyword&page=1

session_start();
require_once '../config.php';

if (empty($_SESSION[ADMIN_SESSION_KEY])) jsonOut(['error' => 'Unauthorised'], 401);

$status = trim($_GET['status'] ?? 'all');
$search = trim($_GET['search'] ?? '');
$page   = max(1, (int)($_GET['page']  ?? 1));
$limit  = 20;
$offset = ($page - 1) * $limit;

try {
    $pdo    = getDB();
    $conds  = [];
    $params = [];

    if ($status !== 'all' && in_array($status, ['pending','approved','rejected'])) {
        $conds[] = 'status = :status';
        $params[':status'] = $status;
    }
    if ($search !== '') {
        $conds[] = '(title LIKE :s OR author_name LIKE :s2 OR email LIKE :s3)';
        $params[':s']  = "%$search%";
        $params[':s2'] = "%$search%";
        $params[':s3'] = "%$search%";
    }

    $where = $conds ? 'WHERE ' . implode(' AND ', $conds) : '';

    $cntStmt = $pdo->prepare("SELECT COUNT(*) FROM blogs $where");
    $cntStmt->execute($params);
    $total = (int)$cntStmt->fetchColumn();

    $stmt = $pdo->prepare("
        SELECT id, title, author_name, email, status, admin_note, reviewed_by, reviewed_at, created_at
        FROM blogs $where
        ORDER BY
            FIELD(status,'pending','rejected','approved'),
            created_at DESC
        LIMIT :lim OFFSET :off
    ");
    foreach ($params as $k => $v) $stmt->bindValue($k, $v);
    $stmt->bindValue(':lim', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':off', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $blogs = $stmt->fetchAll();

    // Counts by status
    $counts = $pdo->query("SELECT status, COUNT(*) cnt FROM blogs GROUP BY status")->fetchAll();
    $statusCounts = ['pending' => 0, 'approved' => 0, 'rejected' => 0, 'all' => 0];
    foreach ($counts as $c) {
        $statusCounts[$c['status']] = (int)$c['cnt'];
        $statusCounts['all'] += (int)$c['cnt'];
    }

    jsonOut(['blogs' => $blogs, 'total' => $total, 'page' => $page, 'pages' => (int)ceil($total/$limit), 'counts' => $statusCounts]);

} catch (Exception $e) {
    jsonOut(['error' => $e->getMessage()], 500);
}
