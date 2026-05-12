<?php
require_once 'auth.php';
$pageTitle = 'Requests';
$pdo = getDB();
$msg = '';
$msgType = '';

// Update status
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $id     = (int)$_POST['req_id'];
    $status = (int)$_POST['status'];
    $notes  = clean(trim($_POST['admin_notes'] ?? ''));
    if ($status >= 1 && $status <= 6 && $id > 0) {
        $pdo->prepare("UPDATE recycle_requests SET status=:s, admin_notes=:n WHERE id=:id")
            ->execute([':s'=>$status,':n'=>$notes,':id'=>$id]);
        $msg = 'Status updated successfully.'; $msgType = 'success';
    }
}

// Filter
$filterStatus = isset($_GET['status']) ? (int)$_GET['status'] : 0;
$filterWaste  = trim($_GET['waste'] ?? '');
$search       = trim($_GET['q'] ?? '');

$sql    = "SELECT * FROM recycle_requests WHERE 1=1";
$params = [];
if ($filterStatus > 0)       { $sql .= " AND status=:s";   $params[':s']=$filterStatus; }
if ($filterWaste)             { $sql .= " AND waste_type=:w"; $params[':w']=$filterWaste; }
if ($search)                  { $sql .= " AND (tracking_id LIKE :q OR user_name LIKE :q2 OR email LIKE :q3)";
  $params[':q']='%'.$search.'%'; $params[':q2']='%'.$search.'%'; $params[':q3']='%'.$search.'%'; }
$sql .= " ORDER BY created_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$requests = $stmt->fetchAll();

// Single view
$viewReq = null;
if (isset($_GET['view'])) {
    $sv = $pdo->prepare("SELECT * FROM recycle_requests WHERE id=:id");
    $sv->execute([':id'=>(int)$_GET['view']]);
    $viewReq = $sv->fetch();
}

require_once '_layout.php';
?>

<?php if($msg): ?>
<div class="<?= $msgType==='success'?'msg-success':'msg-error' ?>"><?= htmlspecialchars($msg) ?></div>
<?php endif; ?>

<?php if($viewReq): ?>
<!-- Detail View -->
<div style="margin-bottom:20px">
  <a href="requests.php" style="font-size:.85rem;color:var(--leaf);font-weight:600">← Back to All Requests</a>
</div>
<div style="display:grid;grid-template-columns:1fr 360px;gap:20px;align-items:start">
  <div>
  <div class="form-card" style="max-width:none">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px">
      <div>
        <div style="font-family:'Playfair Display',serif;font-size:1.1rem;color:var(--forest)"><?= htmlspecialchars($viewReq['user_name']) ?></div>
        <div style="margin-top:4px"><span class="tid"><?= htmlspecialchars($viewReq['tracking_id']) ?></span></div>
      </div>
      <?php $colors=['#7ab88a','#4a8c5c','#d4a843','#2d5a3d','#c4956a','#1a3a2a']; $sc=$colors[min((int)$viewReq['status']-1,5)]; ?>
      <span class="status-badge" style="background:<?=$sc?>18;color:<?=$sc?>;font-size:.82rem;padding:6px 14px"><?= statusLabel((int)$viewReq['status']) ?></span>
    </div>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;font-size:.85rem">
      <div><span style="color:var(--soft);font-size:.75rem;text-transform:uppercase;letter-spacing:.05em">Email</span><br><?= htmlspecialchars($viewReq['email']) ?></div>
      <div><span style="color:var(--soft);font-size:.75rem;text-transform:uppercase;letter-spacing:.05em">Phone</span><br><?= htmlspecialchars($viewReq['phone']) ?></div>
      <div style="grid-column:1/-1"><span style="color:var(--soft);font-size:.75rem;text-transform:uppercase;letter-spacing:.05em">Address</span><br><?= htmlspecialchars($viewReq['address'].', '.$viewReq['city'].' '.$viewReq['pincode']) ?></div>
      <div><span style="color:var(--soft);font-size:.75rem;text-transform:uppercase;letter-spacing:.05em">Waste Type</span><br><span class="waste-chip"><?= htmlspecialchars($viewReq['waste_type']) ?></span></div>
      <div><span style="color:var(--soft);font-size:.75rem;text-transform:uppercase;letter-spacing:.05em">Method</span><br><?= $viewReq['method']==='pickup'?'🚚 Pickup':'🚶 Self Drop' ?></div>
      <div style="grid-column:1/-1"><span style="color:var(--soft);font-size:.75rem;text-transform:uppercase;letter-spacing:.05em">Description</span><br><?= htmlspecialchars($viewReq['description']) ?></div>
      <?php if($viewReq['custom_request']): ?>
      <div style="grid-column:1/-1;background:rgba(212,168,67,.07);padding:12px;border-radius:8px">
        <span style="color:var(--soft);font-size:.75rem;text-transform:uppercase;letter-spacing:.05em">Custom Request</span><br>
        <span style="color:var(--earth)"><?= htmlspecialchars($viewReq['custom_request']) ?></span>
      </div>
      <?php endif; ?>
      <?php if($viewReq['image']): ?>
      <div style="grid-column:1/-1">
        <span style="color:var(--soft);font-size:.75rem;text-transform:uppercase;letter-spacing:.05em">Uploaded Image</span><br>
        <img src="../<?= htmlspecialchars($viewReq['image']) ?>" style="max-width:200px;border-radius:8px;margin-top:8px;border:1px solid rgba(74,140,92,.15)">
      </div>
      <?php endif; ?>
      <div><span style="color:var(--soft);font-size:.75rem;text-transform:uppercase;letter-spacing:.05em">Submitted</span><br><?= date('d M Y, H:i', strtotime($viewReq['created_at'])) ?></div>
    </div>
  </div>
  </div>
  <!-- Update Status Panel -->
  <div>
  <div class="form-card" style="max-width:none">
    <div style="font-weight:600;color:var(--forest);margin-bottom:16px">Update Status</div>
    <form method="POST">
      <input type="hidden" name="req_id" value="<?= $viewReq['id'] ?>">
      <div style="display:flex;flex-direction:column;gap:12px">
        <div class="fg">
          <label>Status</label>
          <select class="fs" name="status">
            <?php for($i=1;$i<=6;$i++): ?>
            <option value="<?=$i?>" <?= (int)$viewReq['status']===$i?'selected':'' ?>><?= statusLabel($i) ?></option>
            <?php endfor; ?>
          </select>
        </div>
        <div class="fg">
          <label>Admin Notes <span style="font-weight:400;color:var(--soft);text-transform:none">(optional)</span></label>
          <textarea class="fta" name="admin_notes" placeholder="Internal notes for this request…"><?= htmlspecialchars($viewReq['admin_notes']??'') ?></textarea>
        </div>
        <button class="btn-save" name="update_status" value="1">✅ Update Status</button>
      </div>
    </form>
  </div>
  <!-- Mini Timeline -->
  <div class="form-card" style="max-width:none;margin-top:16px">
    <div style="font-weight:600;color:var(--forest);margin-bottom:14px;font-size:.9rem">Progress</div>
    <?php for($i=1;$i<=6;$i++):
      $cur=(int)$viewReq['status'];
      $st=$i<$cur?'done':($i===$cur?'current':'pending');
      $colors2=['#7ab88a','#4a8c5c','#d4a843','#2d5a3d','#c4956a','#1a3a2a'];
      $c2=$colors2[min($i-1,5)];
    ?>
    <div style="display:flex;align-items:center;gap:10px;padding:6px 0;<?=$i<6?'border-bottom:1px dashed rgba(74,140,92,.1)':''?>">
      <div style="width:26px;height:26px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:.72rem;font-weight:700;flex-shrink:0;
        background:<?=$st==='pending'?'var(--parchment)':$c2.'22'?>;
        color:<?=$st==='pending'?'rgba(90,90,90,.4)':$c2?>;
        border:<?=$st==='current'?'2px solid '.$c2:'none'?>">
        <?= $i < $cur ? '✓' : $i ?>
      </div>
      <div style="font-size:.8rem;font-weight:<?=$st==='current'?'700':'400'?>;color:<?=$st==='pending'?'rgba(90,90,90,.35)':($st==='current'?$c2:'var(--forest)')?>">
        <?= statusLabel($i) ?>
      </div>
    </div>
    <?php endfor; ?>
  </div>
  </div>
</div>

<?php else: ?>
<!-- List View -->
<!-- Filters -->
<div style="background:#fff;border-radius:var(--r);padding:16px 20px;margin-bottom:20px;border:1px solid rgba(74,140,92,.09);box-shadow:var(--shadow)">
  <form method="GET" style="display:flex;gap:10px;flex-wrap:wrap;align-items:flex-end">
    <div style="display:flex;flex-direction:column;gap:5px;flex:1;min-width:160px">
      <label style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:var(--forest)">Search</label>
      <input class="fi" type="text" name="q" placeholder="Name, email, tracking ID…" value="<?= htmlspecialchars($search) ?>">
    </div>
    <div style="display:flex;flex-direction:column;gap:5px">
      <label style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:var(--forest)">Status</label>
      <select class="fs" name="status" style="min-width:150px">
        <option value="">All Statuses</option>
        <?php for($i=1;$i<=6;$i++): ?><option value="<?=$i?>" <?=$filterStatus===$i?'selected':''?>><?= statusLabel($i) ?></option><?php endfor; ?>
      </select>
    </div>
    <div style="display:flex;flex-direction:column;gap:5px">
      <label style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:var(--forest)">Waste Type</label>
      <select class="fs" name="waste">
        <option value="">All Types</option>
        <?php foreach(wasteTypes() as $wt): ?><option value="<?=$wt?>" <?=$filterWaste===$wt?'selected':''?>><?=$wt?></option><?php endforeach; ?>
      </select>
    </div>
    <button class="btn-save" type="submit">Filter</button>
    <a href="requests.php" class="btn-edit" style="display:inline-flex;align-items:center;padding:10px 16px">Clear</a>
  </form>
</div>

<div class="table-wrap">
  <div class="table-header">
    <div class="table-title">All Requests <span style="font-size:.8rem;color:var(--soft);font-weight:400">(<?= count($requests) ?>)</span></div>
  </div>
  <div style="overflow-x:auto">
  <table>
    <thead>
      <tr><th>Tracking ID</th><th>Name</th><th>Email</th><th>Waste</th><th>Method</th><th>Status</th><th>Date</th><th>Actions</th></tr>
    </thead>
    <tbody>
      <?php if(empty($requests)): ?>
      <tr><td colspan="8" style="text-align:center;color:var(--soft);padding:28px">No requests found.</td></tr>
      <?php else: ?>
      <?php foreach($requests as $r):
        $colors3=['#7ab88a','#4a8c5c','#d4a843','#2d5a3d','#c4956a','#1a3a2a'];
        $sc=$colors3[min((int)$r['status']-1,5)];
      ?>
      <tr>
        <td><span class="tid"><?= htmlspecialchars($r['tracking_id']) ?></span></td>
        <td><?= htmlspecialchars($r['user_name']) ?></td>
        <td style="font-size:.8rem;color:var(--soft)"><?= htmlspecialchars($r['email']) ?></td>
        <td><span class="waste-chip"><?= htmlspecialchars($r['waste_type']) ?></span></td>
        <td><?= $r['method']==='pickup'?'🚚':'🚶' ?> <?= ucfirst($r['method']) ?></td>
        <td><span class="status-badge" style="background:<?=$sc?>18;color:<?=$sc?>"><?= statusLabel((int)$r['status']) ?></span></td>
        <td style="font-size:.8rem;color:var(--soft)"><?= date('d M Y', strtotime($r['created_at'])) ?></td>
        <td>
          <a href="requests.php?view=<?= $r['id'] ?>" class="btn-edit btn-sm">View/Edit</a>
        </td>
      </tr>
      <?php endforeach; ?>
      <?php endif; ?>
    </tbody>
  </table>
  </div>
</div>
<?php endif; ?>

<?php require_once '_layout_end.php'; ?>
