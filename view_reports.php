<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>View Reports</title>
<style>
  body { font-family: Arial; margin: 20px; }
  ul { list-style-type: none; padding: 0; }
  li { margin: 10px 0; }
  a { font-size: 18px; text-decoration: none; color: #337ab7; }
  a:hover { text-decoration: underline; }
</style>
</head>
<body>

<h2>Reports Dashboard</h2>
<ul>
  <li><a href="report_assessment.php">Assessment Reports</a></li>
  <li><a href="report_mealplan.php">Meal Plan Reports</a></li>
  <li><a href="report_fitnessplan.php">Fitness Plan Reports</a></li>
</ul>

<p><a href="admin_dashboard.php">← Back to Dashboard</a></p>

</body>
</html>
