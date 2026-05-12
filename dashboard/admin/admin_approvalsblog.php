<?php 
session_start();
$root_path = $_SERVER['DOCUMENT_ROOT'] . '/Project/';

include($root_path . 'config/db.php'); 
include($root_path . 'includes/header.php'); 

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    echo "<script>alert('Access Denied'); window.location='/Project/auth/login.php';</script>";
    exit;
}

// Fetching with Description and Seller Name
$query = "SELECT m.*, u.name 
          FROM marketplace_items m 
          LEFT JOIN users u ON m.user_id = u.id 
          WHERE m.status = 'pending' 
          ORDER BY m.id ASC";

$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query Error: " . mysqli_error($conn));
}
?>

<div class="admin-container" style="margin-top: 120px; padding: 40px 5%;">
    <div style="margin-bottom: 30px; border-left: 5px solid #166534; padding-left: 15px;">
        <h2 style="color: #1e293b; margin: 0;">Ecosphere: Marketplace Approval Queue</h2>
        <p style="color: #64748b;">Review submissions and provide feedback to sellers.</p>
    </div>

    <div style="background: white; border-radius: 15px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); overflow: hidden; border: 1px solid #e2e8f0;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead style="background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                <tr>
                    <th style="padding: 20px; text-align: left;">Product & Seller</th>
                    <th style="text-align: left;">Description</th> <th>Price</th>
                    <th style="width: 320px;">Decision & Remarks</th>
                </tr>
            </thead>
            <tbody>
                <?php if(mysqli_num_rows($result) > 0): ?>
                    <?php while($row = mysqli_fetch_assoc($result)): ?>
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 20px; display: flex; align-items: center; gap: 15px;">
                            <img src="/Project/uploads/<?= htmlspecialchars($row['image_url']) ?>" style="width: 70px; height: 70px; border-radius: 10px; object-fit: cover;">
                            <div>
                                <strong style="color: #1e293b;"><?= htmlspecialchars($row['product_name']) ?></strong><br>
                                <small style="color: #166534; font-weight: 600;">Seller: <?= htmlspecialchars($row['name'] ?? 'Unknown') ?></small>
                            </div>
                        </td>
                        
                        <td style="padding: 20px; max-width: 250px;">
                            <p style="font-size: 0.85rem; color: #475569; margin: 0; line-height: 1.4;">
                                <?php 
                                    $desc = htmlspecialchars($row['description']);
                                    echo (strlen($desc) > 80) ? substr($desc, 0, 80) . "..." : $desc; 
                                ?>
                            </p>
                            <?php if(strlen($desc) > 80): ?>
                                <a href="#" onclick="alert('Full Description:\n\n<?= addslashes($desc) ?>'); return false;" style="font-size: 0.75rem; color: #166534; text-decoration: underline;">Read Full</a>
                            <?php endif; ?>
                        </td>

                        <td><strong>₹<?= number_format($row['price'], 2) ?></strong></td>
                        
                        <td style="padding: 20px;">
                            <form action="update_marketplace_status.php" method="POST" style="display: flex; flex-direction: column; gap: 8px;">
                                <input type="hidden" name="item_id" value="<?= $row['id'] ?>">
                                <input type="text" name="admin_remarks" placeholder="Feedback for seller..." style="padding: 10px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 0.85rem;">
                                
                                <div style="display: flex; gap: 10px;">
                                    <button type="submit" name="status" value="approved" style="flex: 1; background: #10b981; color: white; border: none; padding: 10px; border-radius: 8px; cursor: pointer; font-weight: 600;">Approve</button>
                                    <button type="submit" name="status" value="rejected" style="flex: 1; background: #ef4444; color: white; border: none; padding: 10px; border-radius: 8px; cursor: pointer; font-weight: 600;">Reject</button>
                                </div>
                            </form>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="4" style="padding: 60px; text-align: center; color: #94a3b8;">🎉 No pending items to judge.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include($root_path . 'includes/footer.php'); ?>