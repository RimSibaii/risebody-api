<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}
include 'config.php';

if (!isset($_GET['id'])) {
    die("User ID missing.");
}

$id = intval($_GET['id']);

$stmt = $conn->prepare("DELETE FROM users WHERE user_id=?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: admin_dashboard.php?msg=User+deleted+successfully#users");
exit();
