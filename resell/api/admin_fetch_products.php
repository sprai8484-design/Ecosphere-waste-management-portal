<?php
// api/admin_fetch_products.php — Admin: all products

session_start();
require_once '../config.php';
if (!isAdmin()) jsonOut(['error' => 'Unauthorised'], 401);

$status   = trim($_GET['status']   ?? 'all');
$search   = trim($_GET['search']   ?? '');
$page     = max(1, (int)($_GET['page'] ?? 1));
$limit    = 20;
$offset   = ($page - 1) * $limit;

try {
    $pdo    = getDB();
    $conds  = [];
    $params = [];
    $statuses = ['Pending','Approved','Sold','Rejected'];

    if ($status !== 'all' && in_array($status, $statuses)) {
        $conds[]          = 'status=:s';
        $params[':s']     = $status;
    }
    if ($search !== '') {
        $conds[]          = '(product_name LIKE :q OR seller_name LIKE :q2 OR product_uid LIKE :q3)';
        $params[':q']     = "%$search%";
        $params[':q2']    = "%$search%";
        $params[':q3']    = "%$search%";
    }

    $where = $conds ? 'WHERE ' . implode(' AND ', $conds) : '';

    $cntStmt = $pdo->prepare("SELECT COUNT(*) FROM resell_products $where");
    $cntStmt->execute($params);
    $total = (int)$cntStmt->fetchColumn();

    $stmt = $pdo->prepare("
        SELECT id, product_uid, seller_name, seller_email, product_name, category,
               `condition`, quantity, price, image, status, admin_note, reviewed_by, created_at
        FROM resell_products $where
        ORDER BY FIELD(status,'Pending','Rejected','Approved','Sold'), created_at DESC
        LIMIT :lim OFFSET :off
    ");
    foreach ($params as $k => $v) $stmt->bindValue($k, $v);
    $stmt->bindValue(':lim', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':off', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $products = $stmt->fetchAll();

    // Counts per status
    $counts = $pdo->query("SELECT status, COUNT(*) cnt FROM resell_products GROUP BY status")->fetchAll();
    $sc = ['all' => 0, 'Pending' => 0, 'Approved' => 0, 'Sold' => 0, 'Rejected' => 0];
    foreach ($counts as $c) { $sc[$c['status']] = (int)$c['cnt']; $sc['all'] += (int)$c['cnt']; }

    // Transaction stats
    $txnCount    = $pdo->query("SELECT COUNT(*) FROM resell_transactions")->fetchColumn();
    $totalRevenue = $pdo->query("SELECT COALESCE(SUM(total_price),0) FROM resell_transactions WHERE payment_status='Success'")->fetchColumn();

    foreach ($products as &$p) {
        $p['price_fmt'] = CURRENCY . number_format((float)$p['price'], 2);
    }

    jsonOut(['products' => $products, 'total' => $total,
             'page' => $page, 'pages' => (int)ceil($total/$limit),
             'counts' => $sc, 'txn_count' => $txnCount,
             'total_revenue' => (float)$totalRevenue]);
} catch (Exception $e) {
    jsonOut(['error' => $e->getMessage()], 500);
}
