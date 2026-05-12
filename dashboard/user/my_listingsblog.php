<?php 
session_start();

// Root path calculation
$root_path = $_SERVER['DOCUMENT_ROOT'] . '/Project/';

include($root_path . 'config/db.php'); 
include($root_path . 'includes/header.php'); 

// Security Check
if (!isset($_SESSION['user_id'])) {
    echo "<script>window.location.href='/Project/auth/login.php';</script>";
    exit;
}

$user_id = $_SESSION['user_id'];

// Database Query
$query = "SELECT * FROM marketplace_items WHERE user_id = '$user_id' ORDER BY id DESC";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Database Error: " . mysqli_error($conn));
}
?>

<link rel="stylesheet" href="/Project/assets/css/resell.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<div class="resell-app-shell" style="margin-top: 120px; padding: 40px 8%; min-height: 80vh;">
    <div class="section-intro" style="margin-bottom: 30px; display: flex; justify-content: space-between; align-items: flex-end;">
        <div>
            <h2 style="color: #166534; font-size: 2rem; margin-bottom: 10px;">Your Selling Activity</h2>
            <p style="color: #64748b;">Track your items from submission to approval.</p>
        </div>
        <a href="/Project/resell/sell.php" class="btn-pill" style="text-decoration: none;">+ List New Item</a>
    </div>

    <?php if(isset($_GET['msg'])): ?>
        <div style="padding: 15px; background: #ecfdf5; color: #10b981; border-radius: 10px; margin-bottom: 20px; border: 1px solid #10b981;">
            <?= htmlspecialchars($_GET['msg']) ?>
        </div>
    <?php endif; ?>

    <div class="history-container" style="background: #fff; border-radius: 20px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); overflow: hidden; border: 1px solid #e2e8f0;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead style="background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                <tr>
                    <th style="padding: 20px; text-align: left; color: #475569; font-weight: 600;">Item Details</th>
                    <th style="text-align: left; color: #475569; font-weight: 600;">Price</th>
                    <th style="text-align: left; color: #475569; font-weight: 600;">Status</th>
                    <th style="text-align: left; color: #475569; font-weight: 600;">Admin Feedback</th>
                    <th style="padding: 20px; text-align: center; color: #475569; font-weight: 600;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if(mysqli_num_rows($result) > 0): ?>
                    <?php while($row = mysqli_fetch_assoc($result)): ?>
                    <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.3s;" onmouseover="this.style.background='#fcfdfd'" onmouseout="this.style.background='white'">
                        <td style="padding: 20px; display: flex; align-items: center; gap: 15px;">
                            <img src="/Project/uploads/<?= htmlspecialchars($row['image_url']) ?>" 
                                 style="width: 70px; height: 70px; border-radius: 12px; object-fit: cover; border: 1px solid #eee;"
                                 onerror="this.src='https://placehold.co/70x70?text=Product'">
                            <div>
                                <strong style="color: #1e293b; font-size: 1.1rem;"><?= htmlspecialchars($row['product_name']) ?></strong><br>
                                <span style="background: #f1f5f9; color: #64748b; padding: 2px 8px; border-radius: 4px; font-size: 0.8rem;"><?= $row['category'] ?></span>
                            </div>
                        </td>
                        <td style="font-weight: 700; color: #334155;">₹<?= number_format($row['price'], 2) ?></td>
                        <td>
                            <?php 
                                $status = strtolower($row['status']);
                                if($status == 'approved') { $color = '#10b981'; $bg = '#ecfdf5'; }
                                elseif($status == 'rejected') { $color = '#ef4444'; $bg = '#fef2f2'; }
                                else { $color = '#f59e0b'; $bg = '#fffbeb'; }
                            ?>
                            <span style="color: <?= $color ?>; background: <?= $bg ?>; padding: 6px 16px; border-radius: 50px; font-size: 0.75rem; font-weight: 800; border: 1px solid <?= $color ?>33;">
                                <?= strtoupper($status) ?>
                            </span>
                        </td>
                        <td style="color: #64748b; font-size: 0.9rem; max-width: 200px;">
                            <i class="fas fa-comment-dots" style="margin-right: 5px; opacity: 0.5;"></i>
                            <?= !empty($row['admin_remarks']) ? htmlspecialchars($row['admin_remarks']) : '<span style="opacity:0.5">Waiting for review...</span>' ?>
                        </td>

                        <td style="text-align: center; padding: 20px;">
                            <a href="/Project/resell/delete_item.php?id=<?= $row['id'] ?>" 
                               onclick="return confirm('Are you sure you want to delete this item?');" 
                               style="color: #ef4444; font-size: 1.1rem; transition: 0.3s;">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        </td>

                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" style="padding: 60px; text-align: center;">
                            <img src="https://cdn-icons-png.flaticon.com/512/4076/4076549.png" style="width: 80px; opacity: 0.2; margin-bottom: 15px;"><br>
                            <p style="color: #94a3b8;">You haven't listed any items yet.</p>
                            <a href="/Project/resell/sell.php" style="color: #10b981; font-weight: 600; text-decoration: none;">Sell your first item →</a>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include($root_path . 'includes/footer.php'); ?>