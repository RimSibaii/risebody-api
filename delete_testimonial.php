<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}
include 'config.php';

if (!isset($_GET['id'])) {
    die("Testimonial ID missing.");
}

$id = intval($_GET['id']);

$stmt = $conn->prepare("DELETE FROM testimonials WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: admin_dashboard.php?msg=Testimonial+deleted+successfully#testimonials");
exit();
