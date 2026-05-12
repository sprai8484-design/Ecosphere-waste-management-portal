<?php
// api/fetch_products.php — Public approved products with search/filter/pagination

require_once '../config.php';

$search   = trim($_GET['search']    ?? '');
$category = trim($_GET['category']  ?? '');
$cond     = trim($_GET['condition'] ?? '');
$sort     = trim($_GET['sort']      ?? 'newest');
$minPrice = (float)($_GET['min_price'] ?? 0);
$maxPrice = (float)($_GET['max_price'] ?? 0);
$page     = max(1, (int)($_GET['page']  ?? 1));
$limit    = min(24, max(1, (int)($_GET['limit'] ?? 9)));
$offset   = ($page - 1) * $limit;

try {
    $pdo    = getDB();
    $where  = ["status = 'Approved'"];
    $params = [];

    if ($search !== '') {
        $where[]       = "(product_name LIKE :s OR description LIKE :s2 OR seller_name LIKE :s3)";
        $params[':s']  = "%$search%";
        $params[':s2'] = "%$search%";
        $params[':s3'] = "%$search%";
    }
    if ($category !== '') {
        $where[]        = "category = :cat";
        $params[':cat'] = $category;
    }
    if ($cond !== '') {
        $where[]         = "`condition` = :cond";
        $params[':cond'] = $cond;
    }
    if ($minPrice > 0) {
        $where[]          = "price >= :minp";
        $params[':minp']  = $minPrice;
    }
    if ($maxPrice > 0) {
        $where[]          = "price <= :maxp";
        $params[':maxp']  = $maxPrice;
    }

    $whereSQL = 'WHERE ' . implode(' AND ', $where);
    $orderSQL = match ($sort) {
        'price_asc'  => 'ORDER BY price ASC',
        'price_desc' => 'ORDER BY price DESC',
        'popular'    => 'ORDER BY quantity DESC, created_at DESC',
        default      => 'ORDER BY created_at DESC',
    };

    // Count
    $cntStmt = $pdo->prepare("SELECT COUNT(*) FROM resell_products $whereSQL");
    $cntStmt->execute($params);
    $total = (int)$cntStmt->fetchColumn();

    // Fetch
    $stmt = $pdo->prepare("
        SELECT id, product_uid, seller_name, product_name, category, `condition`,
               quantity, price, image, created_at,
               LEFT(description, 160) AS excerpt
        FROM resell_products
        $whereSQL $orderSQL
        LIMIT :lim OFFSET :off
    ");
    foreach ($params as $k => $v) $stmt->bindValue($k, $v);
    $stmt->bindValue(':lim', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':off', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $products = $stmt->fetchAll();

    foreach ($products as &$p) {
        $p['excerpt']  = mb_substr(preg_replace('/\s+/', ' ', $p['excerpt']), 0, 155) . '…';
        $p['price_fmt'] = CURRENCY . number_format((float)$p['price'], 2);
    }

    jsonOut(['products' => $products, 'total' => $total, 'page' => $page,
             'pages' => (int)ceil($total / $limit)]);

} catch (Exception $e) {
    jsonOut(['error' => $e->getMessage()], 500);
}
