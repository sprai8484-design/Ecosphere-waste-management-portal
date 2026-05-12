<?php
require_once 'auth.php';
$pageTitle = 'Recycle Centers';
$pdo = getDB();
$msg = '';
$msgType = '';

// Handle actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'add' || $action === 'edit') {
        $name    = clean(trim($_POST['name']   ?? ''));
        $address = clean(trim($_POST['address'] ?? ''));
        $city    = clean(trim($_POST['city']    ?? ''));
        $pincode = clean(trim($_POST['pincode'] ?? ''));
        $contact = clean(trim($_POST['contact'] ?? ''));
        $email   = clean(trim($_POST['email']   ?? ''));
        $timings = clean(trim($_POST['timings'] ?? ''));
        $wt      = isset($_POST['waste_types']) ? implode(',', array_map('trim', $_POST['waste_types'])) : '';

        if (!$name || !$address || !$city || !$pincode || !$contact || !$wt) {
            $msg = 'Please fill all required fields.'; $msgType = 'error';
        } elseif ($action === 'add') {
            $pdo->prepare("INSERT INTO recycle_centers (name,address,city,pincode,waste_types,contact,email,timings) VALUES (?,?,?,?,?,?,?,?)")
                ->execute([$name,$address,$city,$pincode,$wt,$contact,$email,$timings]);
            $msg = 'Center added successfully!'; $msgType = 'success';
        } else {
            $id = (int)$_POST['center_id'];
            $pdo->prepare("UPDATE recycle_centers SET name=?,address=?,city=?,pincode=?,waste_types=?,contact=?,email=?,timings=? WHERE id=?")
                ->execute([$name,$address,$city,$pincode,$wt,$contact,$email,$timings,$id]);
            $msg = 'Center updated successfully!'; $msgType = 'success';
        }
    }

    if ($action === 'delete') {
        $id = (int)$_POST['center_id'];
        $pdo->prepare("UPDATE recycle_centers SET is_active=0 WHERE id=?")
            ->execute([$id]);
        $msg = 'Center deactivated.'; $msgType = 'success';
    }

    if ($action === 'restore') {
        $id = (int)$_POST['center_id'];
        $pdo->prepare("UPDATE recycle_centers SET is_active=1 WHERE id=?")
            ->execute([$id]);
        $msg = 'Center restored.'; $msgType = 'success';
    }
}

// Load centers
$centers = $pdo->query("SELECT * FROM recycle_centers ORDER BY city, name")->fetchAll();

// Edit mode
$editCenter = null;
if (isset($_GET['edit'])) {
    $ev = $pdo->prepare("SELECT * FROM recycle_centers WHERE id=?");
    $ev->execute([(int)$_GET['edit']]);
    $editCenter = $ev->fetch();
}

require_once '_layout.php';
?>

<?php if($msg): ?>
<div class="<?= $msgType==='success'?'msg-success':'msg-error' ?>"><?= htmlspecialchars($msg) ?></div>
<?php endif; ?>

<div style="display:grid;grid-template-columns:1fr 380px;gap:20px;align-items:start">
<!-- Centers Table -->
<div>
<div class="table-wrap">
  <div class="table-header">
    <div class="table-title">All Centers <span style="font-size:.78rem;color:var(--soft);font-weight:400">(<?= count($centers) ?>)</span></div>
  </div>
  <div style="overflow-x:auto">
  <table>
    <thead>
      <tr><th>Name</th><th>City</th><th>Waste Types</th><th>Contact</th><th>Status</th><th>Actions</th></tr>
    </thead>
    <tbody>
      <?php if(empty($centers)): ?>
      <tr><td colspan="6" style="text-align:center;color:var(--soft);padding:28px">No centers yet.</td></tr>
      <?php else: ?>
      <?php foreach($centers as $c): ?>
      <tr style="<?= !$c['is_active']?'opacity:.5':'' ?>">
        <td>
          <div style="font-weight:600;color:var(--forest);font-size:.88rem"><?= htmlspecialchars($c['name']) ?></div>
          <div style="font-size:.75rem;color:var(--soft)"><?= htmlspecialchars($c['address']) ?></div>
        </td>
        <td><?= htmlspecialchars($c['city']) ?><br><span style="font-size:.75rem;color:var(--soft)"><?= htmlspecialchars($c['pincode']) ?></span></td>
        <td>
          <?php foreach(array_map('trim',explode(',',$c['waste_types'])) as $wt): ?>
          <span class="waste-chip"><?= htmlspecialchars($wt) ?></span>
          <?php endforeach; ?>
        </td>
        <td style="font-size:.8rem"><?= htmlspecialchars($c['contact']) ?></td>
        <td>
          <?php if($c['is_active']): ?>
          <span class="status-badge" style="background:rgba(74,140,92,.1);color:var(--leaf)">● Active</span>
          <?php else: ?>
          <span class="status-badge" style="background:rgba(212,116,90,.1);color:var(--terra)">● Inactive</span>
          <?php endif; ?>
        </td>
        <td>
          <div style="display:flex;gap:6px">
          <a href="centers.php?edit=<?= $c['id'] ?>" class="btn-edit btn-sm">Edit</a>
          <form method="POST" style="display:inline" onsubmit="return confirm('Are you sure?')">
            <input type="hidden" name="center_id" value="<?= $c['id'] ?>">
            <input type="hidden" name="action" value="<?= $c['is_active']?'delete':'restore' ?>">
            <button class="<?= $c['is_active']?'btn-danger':'btn-edit' ?> btn-sm" type="submit"><?= $c['is_active']?'Deactivate':'Restore' ?></button>
          </form>
          </div>
        </td>
      </tr>
      <?php endforeach; ?>
      <?php endif; ?>
    </tbody>
  </table>
  </div>
</div>
</div>

<!-- Add / Edit Form -->
<div>
<div class="form-card" style="max-width:none">
  <div style="font-weight:600;color:var(--forest);margin-bottom:18px"><?= $editCenter?'✏️ Edit Center':'➕ Add New Center' ?></div>
  <form method="POST">
    <input type="hidden" name="action" value="<?= $editCenter?'edit':'add' ?>">
    <?php if($editCenter): ?>
    <input type="hidden" name="center_id" value="<?= $editCenter['id'] ?>">
    <?php endif; ?>
    <div style="display:flex;flex-direction:column;gap:13px">
      <div class="fg">
        <label>Center Name <span>*</span></label>
        <input class="fi" type="text" name="name" placeholder="GreenCycle Hub Mumbai" value="<?= htmlspecialchars($editCenter['name']??'') ?>" required>
      </div>
      <div class="fg">
        <label>Address <span>*</span></label>
        <input class="fi" type="text" name="address" placeholder="14, Linking Road, Bandra West" value="<?= htmlspecialchars($editCenter['address']??'') ?>" required>
      </div>
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px">
        <div class="fg">
          <label>City <span>*</span></label>
          <input class="fi" type="text" name="city" placeholder="Mumbai" value="<?= htmlspecialchars($editCenter['city']??'') ?>" required>
        </div>
        <div class="fg">
          <label>Pincode <span>*</span></label>
          <input class="fi" type="text" name="pincode" placeholder="400050" value="<?= htmlspecialchars($editCenter['pincode']??'') ?>" required>
        </div>
      </div>
      <div class="fg">
        <label>Phone <span>*</span></label>
        <input class="fi" type="text" name="contact" placeholder="+91 98765 43210" value="<?= htmlspecialchars($editCenter['contact']??'') ?>" required>
      </div>
      <div class="fg">
        <label>Email</label>
        <input class="fi" type="email" name="email" placeholder="center@example.com" value="<?= htmlspecialchars($editCenter['email']??'') ?>">
      </div>
      <div class="fg">
        <label>Timings</label>
        <input class="fi" type="text" name="timings" placeholder="Mon–Sat 9am–6pm" value="<?= htmlspecialchars($editCenter['timings']??'Mon–Sat 9am–6pm') ?>">
      </div>
      <div class="fg">
        <label>Waste Types Accepted <span>*</span></label>
        <?php
        $selTypes = $editCenter ? array_map('trim', explode(',', $editCenter['waste_types'])) : [];
        foreach(wasteTypes() as $wt):
          $checked = in_array($wt, $selTypes) ? 'checked' : '';
        ?>
        <label style="display:flex;align-items:center;gap:8px;font-size:.85rem;font-weight:400;text-transform:none;letter-spacing:0;color:var(--charcoal);cursor:pointer;margin-bottom:4px">
          <input type="checkbox" name="waste_types[]" value="<?= $wt ?>" <?= $checked ?> style="accent-color:var(--leaf)">
          <?= $wt ?>
        </label>
        <?php endforeach; ?>
      </div>
      <button class="btn-save" type="submit"><?= $editCenter?'💾 Save Changes':'➕ Add Center' ?></button>
      <?php if($editCenter): ?>
      <a href="centers.php" class="btn-edit" style="display:block;text-align:center;padding:10px">✕ Cancel Edit</a>
      <?php endif; ?>
    </div>
  </form>
</div>
</div>
</div>

<?php require_once '_layout_end.php'; ?>
