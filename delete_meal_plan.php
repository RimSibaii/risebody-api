<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}
include 'config.php';

if (!isset($_GET['id'])) {
    die("Meal Plan ID missing.");
}

$id = intval($_GET['id']);

$stmt = $conn->prepare("DELETE FROM meal_plan WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: admin_dashboard.php?msg=Meal+plan+deleted+successfully#mealplan");
exit();
