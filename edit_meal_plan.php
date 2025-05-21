<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

include 'config.php';

if (!isset($_GET['id'])) {
    die("Meal Plan ID required.");
}

$id = intval($_GET['id']);
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $day = $_POST['day'] ?? '';
    $meal_type = $_POST['meal_type'] ?? '';
    $meal = $_POST['meal'] ?? '';
    $calories = $_POST['calories'] ?? '';
    $description = $_POST['description'] ?? '';

    $stmt = $conn->prepare("
        UPDATE meal_plan SET day=?, meal_type=?, meal=?, calories=?, description=? WHERE id=?
    ");
    $stmt->bind_param("sssssi", $day, $meal_type, $meal, $calories, $description, $id);

    if ($stmt->execute()) {
        $message = "Meal plan updated successfully.";
    } else {
        $message = "Error updating meal plan.";
    }
}

// Fetch existing data
$stmt = $conn->prepare("SELECT * FROM meal_plan WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$meal_plan = $result->fetch_assoc();

if (!$meal_plan) {
    die("Meal plan entry not found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Edit Meal Plan #<?= $id ?></title>
<style>
  body { font-family: Arial; margin: 40px; background: #f0f4f9; color: #1e2a78; }
  form { max-width: 600px; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 0 15px rgba(0,0,0,0.1); }
  label { display: block; margin-top: 15px; font-weight: 600; }
  input[type="text"], textarea {
    width: 100%; padding: 8px; margin-top: 6px; border: 1px solid #ccc; border-radius: 4px;
  }
  button {
    margin-top: 20px; background-color: #2979ff; color: white; border: none; padding: 10px 18px; font-weight: 600; border-radius: 6px; cursor: pointer;
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

<h2>Edit Meal Plan #<?= $id ?></h2>

<?php if ($message): ?>
  <div class="message"><?= htmlspecialchars($message) ?></div>
<?php endif; ?>

<form method="POST">
  <label>Day</label>
  <input type="text" name="day" value="<?= htmlspecialchars($meal_plan['day']) ?>" required>

  <label>Meal Type</label>
  <input type="text" name="meal_type" value="<?= htmlspecialchars($meal_plan['meal_type']) ?>" required>

  <label>Meal</label>
  <input type="text" name="meal" value="<?= htmlspecialchars($meal_plan['meal']) ?>" required>

  <label>Calories</label>
  <input type="text" name="calories" value="<?= htmlspecialchars($meal_plan['calories']) ?>">

  <label>Description</label>
  <textarea name="description" rows="4"><?= htmlspecialchars($meal_plan['description']) ?></textarea>

  <button type="submit">Update Meal Plan</button>
</form>

<p><a href="admin_dashboard.php#mealplan" class="back-link">← Back to Admin Dashboard</a></p>

</body>
</html>
