<?php
$host = "sql204.infinityfree.com";       // MySQL Host Name
$dbname = "if0_39037004_risebody";       // Database Name
$username = "if0_39037004";              // MySQL User Name
$password = "cFIUhj82G31";       // Replace this with your actual InfinityFree password

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
