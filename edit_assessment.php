<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

include 'config.php';

if (!isset($_GET['id'])) {
    die("Assessment ID is required");
}

$assessment_id = intval($_GET['id']);
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get and sanitize inputs
    $body_shape = $_POST['body_shape'] ?? '';
    $fat_distribution = $_POST['fat_distribution'] ?? '';
    $focus_area = $_POST['focus_area'] ?? '';
    $meal_structure = $_POST['meal_structure'] ?? '';
    $training_days_per_week = intval($_POST['training_days_per_week'] ?? 0);
    $fitness_level = $_POST['fitness_level'] ?? '';
    $metabolism_type = $_POST['metabolism_type'] ?? '';
    $height_cm = floatval($_POST['height_cm'] ?? 0);
    $weight_kg = floatval($_POST['weight_kg'] ?? 0);

    // Update statement
    $stmt = $conn->prepare("
        UPDATE assessment SET
          body_shape = ?, fat_distribution = ?, focus_area = ?, meal_structure = ?,
          training_days_per_week = ?, fitness_level = ?, metabolism_type = ?,
          height_cm = ?, weight_kg = ?
        WHERE assessment_id = ?
    ");
    $stmt->bind_param("ssssissddi",
        $body_shape, $fat_distribution, $focus_area, $meal_structure,
        $training_days_per_week, $fitness_level, $metabolism_type,
        $height_cm, $weight_kg, $assessment_id
    );
    if ($stmt->execute()) {
        $message = "Assessment updated successfully.";
    } else {
        $message = "Error updating assessment.";
    }
}

// Fetch current data
$stmt = $conn->prepare("SELECT * FROM assessment WHERE assessment_id = ?");
$stmt->bind_param("i", $assessment_id);
$stmt->execute();
$result = $stmt->get_result();
$assessment = $result->fetch_assoc();

if (!$assessment) {
    die("Assessment not found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Edit Assessment #<?= $assessment_id ?></title>
<style>
  body { font-family: Arial, sans-serif; margin: 40px; color: #1e2a78; background: #f0f4f9; }
  form { max-width: 600px; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 0 15px rgba(0,0,0,0.1); }
  label { display: block; margin-top: 15px; font-weight: 600; }
  input[type="text"], input[type="number"], textarea {
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

<h2>Edit Assessment #<?= $assessment_id ?></h2>

<?php if ($message): ?>
  <div class="message"><?= htmlspecialchars($message) ?></div>
<?php endif; ?>

<form method="POST">
  <label>Body Shape</label>
  <input type="text" name="body_shape" value="<?= htmlspecialchars($assessment['body_shape']) ?>" required>

  <label>Fat Distribution</label>
  <input type="text" name="fat_distribution" value="<?= htmlspecialchars($assessment['fat_distribution']) ?>" required>

  <label>Focus Area</label>
  <input type="text" name="focus_area" value="<?= htmlspecialchars($assessment['focus_area']) ?>" required>

  <label>Meal Structure</label>
  <textarea name="meal_structure" rows="3" required><?= htmlspecialchars($assessment['meal_structure']) ?></textarea>

  <label>Training Days Per Week</label>
  <input type="number" name="training_days_per_week" value="<?= (int)$assessment['training_days_per_week'] ?>" min="0" required>

  <label>Fitness Level</label>
  <input type="text" name="fitness_level" value="<?= htmlspecialchars($assessment['fitness_level']) ?>" required>

  <label>Metabolism Type</label>
  <input type="text" name="metabolism_type" value="<?= htmlspecialchars($assessment['metabolism_type']) ?>" required>

  <label>Height (cm)</label>
  <input type="number" step="0.01" name="height_cm" value="<?= htmlspecialchars($assessment['height_cm']) ?>" required>

  <label>Weight (kg)</label>
  <input type="number" step="0.01" name="weight_kg" value="<?= htmlspecialchars($assessment['weight_kg']) ?>" required>

  <button type="submit">Update Assessment</button>
</form>

<p><a href="admin_dashboard.php#assessment" class="back-link">← Back to Fitness Plan Reports</a></p>

</body>
</html>
