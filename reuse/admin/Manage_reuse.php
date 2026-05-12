<?php
session_start();
require_once '../config.php';

// Admin check logic
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

$adminUser = $_SESSION['admin_user'] ?? 'Admin';

try {
    $pdo = getDB();
    // Stats calculation
    $stats = $pdo->query("SELECT 
        (SELECT COUNT(*) FROM reuse_ideas WHERE is_approved = 0) as pending,
        (SELECT COUNT(*) FROM reuse_ideas WHERE is_approved = 1) as approved,
        (SELECT COUNT(*) FROM reuse_ideas WHERE is_approved = 2) as rejected
    ")->fetch();

    // Fetch all ideas for the table
    $ideas = $pdo->query("SELECT * FROM reuse_ideas ORDER BY created_at DESC")->fetchAll();
} catch (Exception $e) {
    $stats = ['pending' => 0, 'approved' => 0, 'rejected' => 0];
    $ideas = [];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Reuse Management | Ecosphere</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --forest: #1a3a2a;
            --moss: #2d5a3d;
            --leaf: #4a8c5c;
            --sage: #7ab88a;
            --mint: #a8d5b5;
            --cream: #f5f0e8;
            --sand: #e8e0d0;
            --terra: #d4745a;
            --gold: #d4a843;
            --charcoal: #2c2c2c;
            --warm: #faf8f3
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--warm);
            margin: 0;
            display: grid;
            grid-template-columns: 240px 1fr;
            min-height: 100vh
        }

        /* Sidebar Styles */
        .sidebar {
            background: var(--forest);
            color: white;
            padding: 20px;
            position: sticky;
            top: 0;
            height: 100vh;
        }

        .sb-logo {
            font-size: 1.2rem;
            font-weight: bold;
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            gap: 10px
        }

        .sb-link {
            display: block;
            padding: 12px;
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            border-radius: 8px;
            margin-bottom: 5px;
            transition: 0.3s;
        }

        .sb-link:hover {
            background: rgba(255, 255, 255, 0.05);
            color: white;
        }

        .sb-link.active {
            background: rgba(255, 255, 255, 0.1);
            color: white
        }

        /* Main Content */
        .main {
            padding: 30px
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px
        }

        /* Stats Cards */
        .stats-row {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 30px
        }

        .sc {
            background: white;
            padding: 20px;
            border-radius: 12px;
            border: 1px solid var(--sand);
            border-top: 4px solid var(--forest)
        }

        .sc-num {
            font-size: 2rem;
            font-weight: bold;
            display: block;
            color: var(--forest)
        }

        .sc-lbl {
            font-size: 0.8rem;
            color: var(--charcoal);
            text-transform: uppercase;
            letter-spacing: 1px
        }

        /* Table Styling */
        .table-card {
            background: white;
            border-radius: 12px;
            border: 1px solid var(--sand);
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05)
        }

        table {
            width: 100%;
            border-collapse: collapse
        }

        th {
            background: var(--cream);
            text-align: left;
            padding: 15px;
            font-size: 0.85rem;
            color: var(--moss);
            border-bottom: 1px solid var(--sand)
        }

        td {
            padding: 15px;
            border-bottom: 1px solid var(--sand);
            vertical-align: middle;
            font-size: 0.9rem;
        }

        /* Status Badges */
        .status-badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: bold;
            text-transform: uppercase;
            display: inline-block;
        }

        .sb-0 {
            background: #fff8e6;
            color: #d4a843;
            border: 1px solid #ffeeba;
        }

        /* Pending */
        .sb-1 {
            background: #eef7f1;
            color: #4a8c5c;
            border: 1px solid #d4edda;
        }

        /* Approved */
        .sb-2 {
            background: #fdf2f0;
            color: #d4745a;
            border: 1px solid #f8d7da;
        }

        /* Rejected */

        /* Buttons */
        .action-btns {
            display: flex;
            gap: 8px
        }

        .btn {
            padding: 8px 12px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            font-weight: 600;
            font-size: 0.75rem;
            transition: 0.2s;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .btn-a {
            background: var(--leaf);
            color: white
        }

        .btn-r {
            background: var(--terra);
            color: white
        }

        .btn-a:hover {
            background: var(--moss)
        }

        .btn-confirm {
            background: var(--forest);
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
        }

        /* Modal */
        .overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            display: none;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(4px);
            z-index: 1000;
        }

        .overlay.open {
            display: flex
        }

        .modal {
            background: white;
            padding: 25px;
            border-radius: 15px;
            width: 400px;
            border-top: 5px solid var(--gold);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .note-ta {
            width: 100%;
            height: 100px;
            margin: 15px 0;
            border: 1px solid var(--sand);
            padding: 10px;
            border-radius: 8px;
            font-family: inherit;
            resize: none;
            box-sizing: border-box;
        }
    </style>
</head>

<body>

    <aside class="sidebar">
        <div class="sb-logo">🌿 Ecosphere</div>
        <nav>
            <a href="#" class="sb-link active"><i class="fas fa-recycle"></i> Reuse Ideas</a>
        </nav>
    </aside>

    <div class="main">
        <div class="topbar">
            <h2>Reuse Ideas Management</h2>
            <span style="color:var(--leaf); font-weight: bold;">Admin: <?= htmlspecialchars($adminUser) ?></span>
        </div>

        <div class="stats-row">
            <div class="sc"><span class="sc-num" style="color:var(--gold)"><?= $stats['pending'] ?></span>
                <div class="sc-lbl">Pending Review</div>
            </div>
            <div class="sc"><span class="sc-num" style="color:var(--leaf)"><?= $stats['approved'] ?></span>
                <div class="sc-lbl">Approved & Live</div>
            </div>
            <div class="sc"><span class="sc-num" style="color:var(--terra)"><?= $stats['rejected'] ?></span>
                <div class="sc-lbl">Rejected</div>
            </div>
        </div>

        <div class="table-card">
            <table>
                <thead>
                    <tr>
                        <th>DIY Idea Title</th>
                        <th>Category</th>
                        <th>Author</th>
                        <th>Status</th>
                        <th>Admin Note</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($ideas as $i): ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($i['title']) ?></strong></td>
                            <td><?= htmlspecialchars($i['category']) ?></td>
                            <td><?= htmlspecialchars($i['author']) ?></td>
                            <td>
                                <span class="status-badge sb-<?= $i['is_approved'] ?>">
                                    <?= $i['is_approved'] == 0 ? 'Pending' : ($i['is_approved'] == 1 ? 'Approved' : 'Rejected') ?>
                                </span>
                            </td>
                            <td style="color: #666; font-style: italic; max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                <?= htmlspecialchars($i['admin_note'] ?? '-') ?>
                            </td>
                            <td>
                                <div class="action-btns">
                                    <button class="btn btn-a" onclick="openReview(<?= $i['id'] ?>, 'approve')">
                                        <i class="fas fa-check"></i> Approve
                                    </button>
                                    <button class="btn btn-r" onclick="openReview(<?= $i['id'] ?>, 'reject')">
                                        <i class="fas fa-times"></i> Reject
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($ideas)): ?>
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 20px;">No ideas found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="overlay" id="reviewOverlay">
        <div class="modal">
            <h3 id="modalTitle" style="margin-top: 0; color: var(--forest);">Review Decision</h3>
            <p style="font-size:0.8rem; color:var(--charcoal)">Write a note for the user (optional):</p>
            <textarea id="adminNote" class="note-ta" placeholder="e.g. Great work! or Please add more detail..."></textarea>
            <div style="display:flex; gap:10px">
                <button onclick="submitDecision()" class="btn-confirm" style="flex:1">Confirm</button>
                <button onclick="closeModal()" class="btn" style="background:#eee; flex:1">Cancel</button>
            </div>
        </div>
    </div>

    <script>
        let currentId = null;
        let currentAction = null;
        const overlay = document.getElementById('reviewOverlay');

        function openReview(id, action) {
            currentId = id;
            currentAction = action;
            document.getElementById('modalTitle').innerText = action.toUpperCase() + " IDEA";
            overlay.classList.add('open');
        }

        function closeModal() {
            overlay.classList.remove('open');
            document.getElementById('adminNote').value = '';
        }

        async function submitDecision() {
            const note = document.getElementById('adminNote').value;
            try {
                const response = await fetch('../api/admin_reuse_action.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        idea_id: currentId,
                        action: currentAction,
                        admin_note: note
                    })
                });
                const result = await response.json();
                if (result.success) {
                    location.reload();
                } else {
                    alert(result.error || "Failed to update status");
                }
            } catch (e) {
                alert("Server error or API not found");
            }
        }
    </script>

</body>

</html>