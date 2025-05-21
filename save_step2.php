<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id']) || !isset($_SESSION['assessment_id'])) {
  header("Location: login.php");
  exit();
}

$assessment_id = $_SESSION['assessment_id'];
$fat_distribution = $_POST['fat_distribution'] ?? null;

if ($fat_distribution !== null) {
  $update = $conn->prepare("UPDATE assessment SET fat_distribution = ? WHERE assessment_id = ?");
  $update->bind_param("si", $fat_distribution, $assessment_id);
  $update->execute();
  $update->close();
}

$conn->close();
header("Location: step3.php");
exit();
?>
