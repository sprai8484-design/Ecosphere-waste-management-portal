<?php
// api/fetch_ideas.php — Returns JSON array of reuse ideas
// GET params:
//   ?search=keyword
//   ?category=Plastic
//   ?difficulty=Easy
//   ?sort=newest|popular          (default: newest)
//   ?page=1&limit=12             (pagination)

require_once '../config.php';

$search     = trim($_GET['search']     ?? '');
$category   = trim($_GET['category']   ?? '');
$difficulty = trim($_GET['difficulty'] ?? '');
$sort       = trim($_GET['sort']       ?? 'newest');
$page       = max(1, (int)($_GET['page']  ?? 1));
$limit      = min(24, max(1, (int)($_GET['limit'] ?? 12)));
$offset     = ($page - 1) * $limit;

try {
    $pdo    = getDB();
    $where  = ['is_approved = 1'];
    $params = [];

    if ($search !== '') {
        $where[]          = '(title LIKE :s OR description LIKE :s2 OR materials LIKE :s3 OR author LIKE :s4)';
        $params[':s']     = "%$search%";
        $params[':s2']    = "%$search%";
        $params[':s3']    = "%$search%";
        $params[':s4']    = "%$search%";
    }
    if ($category !== '') {
        $where[]            = 'category = :cat';
        $params[':cat']     = $category;
    }
    if ($difficulty !== '') {
        $where[]            = 'difficulty = :diff';
        $params[':diff']    = $difficulty;
    }

    $whereSQL = 'WHERE ' . implode(' AND ', $where);
    $orderSQL = match ($sort) {
        'popular' => 'ORDER BY likes DESC, created_at DESC',
        default   => 'ORDER BY created_at DESC',
    };

    // Total count for pagination
    $cntStmt = $pdo->prepare("SELECT COUNT(*) FROM reuse_ideas $whereSQL");
    $cntStmt->execute($params);
    $total = (int)$cntStmt->fetchColumn();

    // Fetch rows
    $stmt = $pdo->prepare(
        "SELECT id, title, category, difficulty, time_required, description,
                materials, image, author, likes, created_at
         FROM reuse_ideas
         $whereSQL $orderSQL
         LIMIT :lim OFFSET :off"
    );
    foreach ($params as $k => $v) $stmt->bindValue($k, $v);
    $stmt->bindValue(':lim', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':off', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $ideas = $stmt->fetchAll();

    // Trim description for card display
    foreach ($ideas as &$idea) {
        $idea['description_short'] = mb_substr(strip_tags($idea['description']), 0, 120) . '…';
        $idea['materials_list']    = array_map('trim', explode(',', $idea['materials']));
        unset($idea['materials']);   // full list only in single view
    }

    jsonOut([
        'ideas'      => $ideas,
        'total'      => $total,
        'page'       => $page,
        'limit'      => $limit,
        'pages'      => (int)ceil($total / $limit),
    ]);

} catch (Exception $e) {
    jsonOut(['error' => $e->getMessage()], 500);
}
