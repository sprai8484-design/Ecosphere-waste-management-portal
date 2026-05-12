<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}
?>

<?php include 'includes/header.php'; ?>

<section class="dashboard">
  <h2>User Dashboard</h2>
  <p class="sub-text">Choose an action to contribute towards a cleaner environment.</p>

  <div class="dashboard-grid">

    <a href="../report/report.php" class="dash-card">
      <h3>Report Waste</h3>
      <p>Report unclean locations with image and location.</p>
    </a>

    <a href="../reuse/reuse.php" class="dash-card">
      <h3>Reuse / Resell</h3>
      <p>Give usable items a second life.</p>
    </a>

    <a href="../recycle/recycle.php" class="dash-card">
      <h3>Recycle Near Me</h3>
      <p>Locate nearby recycling centers.</p>
    </a>

    <a href="../report/my_reports.php" class="dash-card">
      <h3>My Reports</h3>
      <p>View status of your submitted complaints.</p>
    </a>

    <a href="../blog/blog.php" class="dash-card">
      <h3>Awareness & Blog</h3>
      <p>Learn sustainable habits and civic sense.</p>
    </a>

  </div>
</section>

<?php include 'includes/footer.php'; ?>
