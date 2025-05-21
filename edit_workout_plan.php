<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

include 'config.php';

if (!isset($_GET['id'])) {
    die("Workout Plan ID required.");
}

$id = intval($_GET['id']);
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $day = $_POST['day'] ?? '';
    $exercise = $_POST['exercise'] ?? '';
    $focus_area = $_POST['focus_area'] ?? '';
    $description = $_POST['description'] ?? '';
    $rounds = $_POST['rounds'] ?? '';
    $sets = $_POST['sets'] ?? '';
    $duration = $_POST['duration'] ?? '';

    $stmt = $conn->prepare("
        UPDATE workout_plan SET day=?, exercise=?, focus_area=?, description=?, rounds=?, sets=?, duration=? WHERE id=?
    ");
    $stmt->bind_param("sssssssi", $day, $exercise, $focus_area, $description, $rounds, $sets, $duration, $id);

    if ($stmt->execute()) {
        $message = "Workout plan updated successfully.";
    } else {
        $message = "Error updating workout plan.";
    }
}

// Fetch existing data
$stmt = $conn->prepare("SELECT * FROM workout_plan WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$workout_plan = $result->fetch_assoc();

if (!$workout_plan) {
    die("Workout plan entry not found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Edit Workout Plan #<?= $id ?></title>
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

<h2>Edit Workout Plan #<?= $id ?></h2>

<?php if ($message): ?>
  <div class="message"><?= htmlspecialchars($message) ?></div>
<?php endif; ?>

<form method="POST">
  <label>Day</label>
  <input type="text" name="day" value="<?= htmlspecialchars($workout_plan['day']) ?>" required>

  <label>Exercise</label>
  <input type="text" name="exercise" value="<?= htmlspecialchars($workout_plan['exercise']) ?>" required>

  <label>Focus Area</label>
  <input type="text" name="focus_area" value="<?= htmlspecialchars($workout_plan['focus_area']) ?>" required>

  <label>Description</label>
  <textarea name="description" rows="4"><?= htmlspecialchars($workout_plan['description']) ?></textarea>

  <label>Rounds</label>
  <input type="text" name="rounds" value="<?= htmlspecialchars($workout_plan['rounds']) ?>">

  <label>Sets</label>
  <input type="text" name="sets" value="<?= htmlspecialchars($workout_plan['sets']) ?>">

  <label>Duration</label>
  <input type="text" name="duration" value="<?= htmlspecialchars($workout_plan['duration']) ?>">

  <button type="submit">Update Workout Plan</button>
</form>

<p><a href="admin_dashboard.php#fitnessplan" class="back-link">← Back to Admin Dashboard</a></p>

</body>
</html>
