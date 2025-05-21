<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

include 'config.php';

// Handle user deletion if requested
if (isset($_GET['delete'])) {
    $user_id = intval($_GET['delete']);
    // Delete user and cascade related data (make sure your DB has ON DELETE CASCADE)
    $stmt = $conn->prepare("DELETE FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    header("Location: view_users.php");
    exit();
}

// Fetch all users
$users = $conn->query("SELECT * FROM users ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>View Users</title>
<style>
  body { font-family: Arial; margin: 20px; }
  table { width: 100%; border-collapse: collapse; }
  th, td { padding: 10px; border: 1px solid #ccc; text-align: left; }
  th { background: #f4f4f4; }
  a { color: #d9534f; text-decoration: none; }
  a:hover { text-decoration: underline; }
</style>
</head>
<body>

<h2>Users List</h2>

<table>
  <thead>
    <tr>
      <th>ID</th>
      <th>Full Name</th>
      <th>Email</th>
      <th>Date of Birth</th>
      <th>Gender</th>
      <th>Fitness Goal</th>
      <th>Injuries</th>
      <th>Meal Type</th>
      <th>Dietary Restrictions</th>
      <th>Created At</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php while ($user = $users->fetch_assoc()): ?>
    <tr>
      <td><?= $user['user_id'] ?></td>
      <td><?= htmlspecialchars($user['full_name']) ?></td>
      <td><?= htmlspecialchars($user['email']) ?></td>
      <td><?= htmlspecialchars($user['date_of_birth']) ?></td>
      <td><?= htmlspecialchars($user['gender']) ?></td>
      <td><?= htmlspecialchars($user['fitness_goal']) ?></td>
      <td><?= htmlspecialchars($user['injuries']) ?></td>
      <td><?= htmlspecialchars($user['meal_type']) ?></td>
      <td><?= htmlspecialchars($user['dietary_restrictions']) ?></td>
      <td><?= $user['created_at'] ?></td>
      <td>
        <a href="?delete=<?= $user['user_id'] ?>" onclick="return confirm('Delete this user? This action cannot be undone.')">Delete</a>
      </td>
    </tr>
    <?php endwhile; ?>
  </tbody>
</table>

<p><a href="admin_dashboard.php">← Back to Dashboard</a></p>

</body>
</html>
