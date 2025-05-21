<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}
include 'config.php';

if (!isset($_GET['id'])) {
    die("Assessment ID missing.");
}

$id = intval($_GET['id']);

$stmt = $conn->prepare("DELETE FROM assessment WHERE assessment_id=?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: admin_dashboard.php?msg=Assessment+deleted+successfully#assessment");
exit();
