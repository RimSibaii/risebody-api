<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id']) || !isset($_SESSION['assessment_id'])) {
  header("Location: login.php");
  exit();
}

$assessment_id = $_SESSION['assessment_id'];
$height_cm = $_POST['height_cm'] ?? null;
$weight_kg = $_POST['weight_kg'] ?? null;
$focus_area = $_POST['focus_area'] ?? null;

if ($height_cm && $weight_kg && $focus_area) {
  $update = $conn->prepare("UPDATE assessment SET height_cm = ?, weight_kg = ?, focus_area = ? WHERE assessment_id = ?");
  $update->bind_param("ddsi", $height_cm, $weight_kg, $focus_area, $assessment_id);
  $update->execute();
  $update->close();

  $conn->close();
  header("Location: step4.php");
  exit();
} else {
  echo "Please fill out all required fields.";
}
?>
