<?php
// track.php — Returns JSON for a single request by tracking_id
// Called via: track.php?id=ECO-2025-0001

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once 'config.php';

if (!isset($_GET['id']) || trim($_GET['id']) === '') {
    jsonResponse(['error' => 'Tracking ID required'], 400);
}

$trackingId = clean(trim($_GET['id']));

try {
    $pdo  = getDB();
    $stmt = $pdo->prepare("
        SELECT r.*, c.name AS center_name, c.address AS center_address, c.contact AS center_contact
        FROM   recycle_requests r
        LEFT JOIN recycle_centers c ON r.center_id = c.id
        WHERE  r.tracking_id = :tid
        LIMIT  1
    ");
    $stmt->execute([':tid' => $trackingId]);
    $row = $stmt->fetch();

    if (!$row) {
        jsonResponse(['error' => 'Not found'], 404);
    }

    // Add status label for convenience
    $row['status_label'] = statusLabel((int)$row['status']);
    $row['status_color']  = statusColor((int)$row['status']);

    // Remove sensitive field
    unset($row['admin_notes']); // keep admin notes hidden unless needed — re-add if desired

    echo json_encode($row);

} catch (Exception $e) {
    jsonResponse(['error' => 'Database error: ' . $e->getMessage()], 500);
}
