<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id']) || !isset($_SESSION['assessment_id'])) {
  header("Location: login.php");
  exit();
}

$assessment_id = $_SESSION['assessment_id'];
$body_shape = $_POST['body_shape'] ?? null;
$fat_distribution = $_POST['fat_distribution'] ?? null;

$update = $conn->prepare("UPDATE assessment SET body_shape = ?, fat_distribution = ? WHERE assessment_id = ?");
$update->bind_param("ssi", $body_shape, $fat_distribution, $assessment_id);
$update->execute();
$update->close();

$conn->close();

if (isset($_GET['skipStep2']) && $_GET['skipStep2'] == 1) {
  header("Location: step3.php"); // skip Step 2 if male
} else {
  header("Location: step2.php");
}
exit();

?>
