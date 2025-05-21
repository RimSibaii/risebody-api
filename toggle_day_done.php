<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
  http_response_code(401);
  exit('Unauthorized');
}

$user_id = $_SESSION['user_id'];
$plan_type = $_POST['plan_type'] ?? '';
$day_number = intval($_POST['day_number']);

if (!in_array($plan_type, ['meal', 'workout']) || $day_number <= 0) {
  http_response_code(400);
  exit('Bad request');
}

// Get current assessment_id for this user
$stmt = $conn->prepare("SELECT assessment_id FROM assessment WHERE user_id = ? ORDER BY created_at DESC LIMIT 1");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$assessment_id = 0;
if ($row = $result->fetch_assoc()) {
  $assessment_id = $row['assessment_id'];
}
$stmt->close();

// Check if this day is already marked as done
$stmt = $conn->prepare("SELECT is_done FROM user_progress WHERE user_id = ? AND plan_type = ? AND day_number = ? AND assessment_id = ?");
$stmt->bind_param("isii", $user_id, $plan_type, $day_number, $assessment_id);
$stmt->execute();
$result = $stmt->get_result();
$is_done = null;
if ($row = $result->fetch_assoc()) {
  $is_done = $row['is_done'];
}
$stmt->close();

if ($is_done === null) {
  // Insert new done record
  $stmt = $conn->prepare("INSERT INTO user_progress (user_id, plan_type, day_number, is_done, assessment_id) VALUES (?, ?, ?, 1, ?)");
  $stmt->bind_param("isii", $user_id, $plan_type, $day_number, $assessment_id);
  $stmt->execute();
  $stmt->close();
} else {
  // Toggle done status
  $new_status = $is_done ? 0 : 1;
  $stmt = $conn->prepare("UPDATE user_progress SET is_done = ? WHERE user_id = ? AND plan_type = ? AND day_number = ? AND assessment_id = ?");
  $stmt->bind_param("iisii", $new_status, $user_id, $plan_type, $day_number, $assessment_id);
  $stmt->execute();
  $stmt->close();
}

http_response_code(200);
echo 'OK';
