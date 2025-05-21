<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$plan_type = $_POST['plan_type'];
$day_number = intval($_POST['day_number']);
$assessment_id = intval($_POST['assessment_id']);

$stmt = $conn->prepare("
  INSERT INTO user_progress (user_id, plan_type, day_number, is_done, assessment_id)
  VALUES (?, ?, ?, 1, ?)
  ON DUPLICATE KEY UPDATE is_done = 1
");
$stmt->bind_param("isii", $user_id, $plan_type, $day_number, $assessment_id);
$stmt->execute();
$stmt->close();

header("Location: " . ($plan_type === 'meal' ? 'mealplan.php' : 'fitnessplan.php'));
exit();
?>
