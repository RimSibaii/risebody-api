<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

include 'config.php';

if (!isset($_GET['id'])) {
    die("User ID required.");
}

$id = intval($_GET['id']);
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = $_POST['full_name'] ?? '';
    $email = $_POST['email'] ?? '';
    $date_of_birth = $_POST['date_of_birth'] ?? '';
    $gender = $_POST['gender'] ?? '';
    $fitness_goal = $_POST['fitness_goal'] ?? '';
    $injuries = $_POST['injuries'] ?? '';
    $meal_type = $_POST['meal_type'] ?? '';
    $dietary_restrictions = $_POST['dietary_restrictions'] ?? '';

    $stmt = $conn->prepare("
        UPDATE users SET full_name=?, email=?, date_of_birth=?, gender=?, fitness_goal=?, injuries=?, meal_type=?, dietary_restrictions=? WHERE user_id=?
    ");
    $stmt->bind_param("ssssssssi", $full_name, $email, $date_of_birth, $gender, $fitness_goal, $injuries, $meal_type, $dietary_restrictions, $id);

    if ($stmt->execute()) {
        $message = "User updated successfully.";
    } else {
        $message = "Error updating user.";
    }
}

// Fetch current user data
$stmt = $conn->prepare("SELECT * FROM users WHERE user_id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    die("User not found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Edit User #<?= $id ?></title>
<style>
  body { font-family: Arial; margin: 40px; background: #f0f4f9; color: #1e2a78; }
  form { max-width: 600px; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 0 15px rgba(0,0,0,0.1); }
  label { display: block; margin-top: 15px; font-weight: 600; }
  input, textarea, select {
    width: 100%; padding: 8px; margin-top: 6px; border: 1px solid #ccc; border-radius: 4px;
  }
  button {
    margin-top: 20px; background-color: #2979ff; color: white; border: none; padding: 10px 18px;
    font-weight: 600; border-radius: 6px; cursor: pointer;
  }
  button:hover {
    background-color: #004ecb;
  }
  .message {
    margin-top: 20px; font-weight: 700; color: green;
  }
  a.back-link {
    display: inline-block; margin-top: 20px; color: #2979ff; text-decoration: none; font-weight: 600;
  }
  a.back-link:hover {
    text-decoration: underline;
  }
</style>
</head>
<body>

<h2>Edit User #<?= $id ?></h2>

<?php if ($message): ?>
  <div class="message"><?= htmlspecialchars($message) ?></div>
<?php endif; ?>

<form method="POST">
  <label>Full Name</label>
  <input type="text" name="full_name" required value="<?= htmlspecialchars($user['full_name']) ?>">

  <label>Email</label>
  <input type="email" name="email" required value="<?= htmlspecialchars($user['email']) ?>">

  <label>Date of Birth</label>
  <input type="date" name="date_of_birth" value="<?= htmlspecialchars($user['date_of_birth']) ?>">

  <label>Gender</label>
  <select name="gender" required>
    <option value="Male" <?= $user['gender'] === 'Male' ? 'selected' : '' ?>>Male</option>
    <option value="Female" <?= $user['gender'] === 'Female' ? 'selected' : '' ?>>Female</option>
    <option value="Other" <?= $user['gender'] === 'Other' ? 'selected' : '' ?>>Other</option>
  </select>

  <label>Fitness Goal</label>
  <input type="text" name="fitness_goal" value="<?= htmlspecialchars($user['fitness_goal']) ?>">

  <label>Injuries</label>
  <textarea name="injuries" rows="3"><?= htmlspecialchars($user['injuries']) ?></textarea>

  <label>Meal Type</label>
  <input type="text" name="meal_type" value="<?= htmlspecialchars($user['meal_type']) ?>">

  <label>Dietary Restrictions</label>
  <textarea name="dietary_restrictions" rows="3"><?= htmlspecialchars($user['dietary_restrictions']) ?></textarea>

  <button type="submit">Update User</button>
</form>

<p><a href="admin_dashboard.php#users" class="back-link">← Back to Admin Dashboard</a></p>

</body>
</html>
