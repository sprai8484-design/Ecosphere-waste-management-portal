<?php
// fetch_centers.php — Returns JSON list of recycling centers
// Called via: fetch_centers.php?location=Mumbai&waste_types=Plastic,E-Waste

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once 'config.php';

try {
    $pdo = getDB();

    $location    = isset($_GET['location'])    ? trim($_GET['location'])    : '';
    $wasteFilter = isset($_GET['waste_types']) ? trim($_GET['waste_types']) : '';

    // Base query
    $sql    = "SELECT * FROM recycle_centers WHERE is_active = 1";
    $params = [];

    // Location filter: match city OR pincode (case-insensitive)
    if ($location !== '') {
        $sql .= " AND (city LIKE :loc OR pincode LIKE :pin)";
        $params[':loc'] = '%' . $location . '%';
        $params[':pin'] = '%' . $location . '%';
    }

    $sql .= " ORDER BY name ASC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $centers = $stmt->fetchAll();

    // Waste type filter (in PHP — SQLite LIKE on comma-separated strings is tricky)
    if ($wasteFilter !== '') {
        $filterTypes = array_map('trim', explode(',', $wasteFilter));
        $centers = array_filter($centers, function ($c) use ($filterTypes) {
            foreach ($filterTypes as $ft) {
                if (stripos($c['waste_types'], $ft) === false) {
                    return false; // must match ALL selected filters
                }
            }
            return true;
        });
        $centers = array_values($centers); // re-index
    }

    echo json_encode($centers);

} catch (Exception $e) {
    jsonResponse(['error' => 'Failed to load centers: ' . $e->getMessage()], 500);
}
