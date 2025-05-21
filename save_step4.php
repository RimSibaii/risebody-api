<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id']) || !isset($_SESSION['assessment_id'])) {
  header("Location: login.php");
  exit();
}

$user_id = $_SESSION['user_id'];
$assessment_id = $_SESSION['assessment_id'];

$fitness_level = $_POST['fitness_level'] ?? null;
$training_days = $_POST['training_days'] ?? null;
$metabolism = $_POST['metabolism'] ?? null;

$meal_structure = $_POST['meal_structure'] === 'custom'
  ? ($_POST['custom_meal_structure'] ?? null)
  : $_POST['meal_structure'];

$scan_date = date("Y-m-d");

if ($fitness_level && $training_days && $metabolism && $meal_structure) {
  $update = $conn->prepare("UPDATE assessment SET fitness_level = ?, training_days_per_week = ?, metabolism_type = ?, meal_structure = ?, scan_date = ? WHERE assessment_id = ?");
  $update->bind_param("sisssi", $fitness_level, $training_days, $metabolism, $meal_structure, $scan_date, $assessment_id);
  if ($update->execute()) {
    $update->close();
    $conn->close();

    // Call Flask AI API
    $apiData = json_encode(['user_id' => $user_id]);

    $options = [
      'http' => [
        'method'  => 'POST',
        'header'  => "Content-Type: application/json",
        'content' => $apiData,
      ]
    ];

    $context = stream_context_create($options);
    $response = @file_get_contents('http://127.0.0.1:5000/generate-plan', false, $context);

    if ($response === FALSE) {
      echo "<script>alert('Assessment saved, but AI plan generation failed.'); window.location.href='dashboard.php';</script>";
    } else {
      echo "<script>alert('Assessment complete! AI plan has been generated.'); window.location.href='dashboard.php';</script>";
    }
    exit();

  } else {
    echo "<script>alert('Failed to save assessment.'); window.location.href='step4.php';</script>";
    exit();
  }
} else {
  echo "<script>alert('Please fill in all fields.'); window.location.href='step4.php';</script>";
  exit();
}
?>
