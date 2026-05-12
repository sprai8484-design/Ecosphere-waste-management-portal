<?php
require_once 'auth.php';
$pageTitle = 'Dashboard';
$pdo = getDB();

// Stats
$totalRequests = $pdo->query("SELECT COUNT(*) FROM recycle_requests")->fetchColumn();
$totalCenters  = $pdo->query("SELECT COUNT(*) FROM recycle_centers WHERE is_active=1")->fetchColumn();
$pending       = $pdo->query("SELECT COUNT(*) FROM recycle_requests WHERE status = 1")->fetchColumn();
$completed     = $pdo->query("SELECT COUNT(*) FROM recycle_requests WHERE status >= 4")->fetchColumn();

// Recent 8 requests
$recent = $pdo->query("SELECT * FROM recycle_requests ORDER BY created_at DESC LIMIT 8")->fetchAll();

// Status distribution
$statusDist = $pdo->query("SELECT status, COUNT(*) as cnt FROM recycle_requests GROUP BY status ORDER BY status")->fetchAll();

require_once '_layout.php';
?>

<!-- Stats Row -->
<div class="stats-row">
  <div class="stat-card">
    <span class="sc-icon">📦</span>
    <div class="sc-num"><?= $totalRequests ?></div>
    <div class="sc-lbl">Total Requests</div>
  </div>
  <div class="stat-card">
    <span class="sc-icon">🏭</span>
    <div class="sc-num"><?= $totalCenters ?></div>
    <div class="sc-lbl">Active Centers</div>
  </div>
  <div class="stat-card">
    <span class="sc-icon">⏳</span>
    <div class="sc-num"><?= $pending ?></div>
    <div class="sc-lbl">Pending Pickup</div>
  </div>
  <div class="stat-card">
    <span class="sc-icon">✅</span>
    <div class="sc-num"><?= $completed ?></div>
    <div class="sc-lbl">Completed</div>
  </div>
</div>

<!-- Status Distribution -->
<div style="margin-bottom:24px;background:#fff;border-radius:var(--r);padding:20px;box-shadow:var(--shadow);border:1px solid rgba(74,140,92,.09)">
  <div style="font-weight:600;color:var(--forest);margin-bottom:14px;font-size:.9rem">Status Distribution</div>
  <div style="display:flex;gap:10px;flex-wrap:wrap">
    <?php foreach ($statusDist as $sd): ?>
      <?php $colors = ['#7ab88a', '#4a8c5c', '#d4a843', '#2d5a3d', '#c4956a', '#1a3a2a'];
      $c = $colors[min((int)$sd['status'] - 1, 5)]; ?>
      <div style="flex:1;min-width:100px;background:<?= $c ?>18;border:1px solid <?= $c ?>44;border-radius:8px;padding:12px;text-align:center">
        <div style="font-size:1.2rem;font-weight:700;color:<?= $c ?>"><?= $sd['cnt'] ?></div>
        <div style="font-size:.7rem;color:var(--soft);margin-top:2px"><?= statusLabel((int)$sd['status']) ?></div>
      </div>
    <?php endforeach; ?>
  </div>
</div>

<!-- Recent Requests -->
<div class="table-wrap">
  <div class="table-header">
    <div class="table-title">Recent Requests</div>
    <a href="requests.php" style="font-size:.82rem;color:var(--leaf);font-weight:600">View All →</a>
  </div>
  <div style="overflow-x:auto">
    <table>
      <thead>
        <tr>
          <th>Tracking ID</th>
          <th>Name</th>
          <th>Waste Type</th>
          <th>Method</th>
          <th>Status</th>
          <th>Date</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($recent)): ?>
          <tr>
            <td colspan="7" style="text-align:center;color:var(--soft);padding:28px">No requests yet.</td>
          </tr>
        <?php else: ?>
          <?php foreach ($recent as $r):
            $colors = ['#7ab88a', '#4a8c5c', '#d4a843', '#2d5a3d', '#c4956a', '#1a3a2a'];
            $sc = $colors[min((int)$r['status'] - 1, 5)];
          ?>
            <tr>
              <td><span class="tid"><?= htmlspecialchars($r['tracking_id']) ?></span></td>
              <td><?= htmlspecialchars($r['user_name']) ?></td>
              <td><span class="waste-chip"><?= htmlspecialchars($r['waste_type']) ?></span></td>
              <td><?= $r['method'] === 'pickup' ? '🚚 Pickup' : '🚶 Drop' ?></td>
              <td><span class="status-badge" style="background:<?= $sc ?>18;color:<?= $sc ?>"><?= statusLabel((int)$r['status']) ?></span></td>
              <td><?= date('d M Y', strtotime($r['created_at'])) ?></td>
              <td><a href="requests.php?view=<?= $r['id'] ?>" class="btn-edit btn-sm">View</a></td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php require_once '_layout_end.php'; ?>