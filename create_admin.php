<?php
include 'config.php';

// Define admin credentials
$username = 'Rise1';
$password = password_hash('123456Rise_', PASSWORD_DEFAULT); // Replace 'yourpassword' with your desired admin password

// Insert admin user into database
$stmt = $conn->prepare("INSERT INTO admin (username, password) VALUES (?, ?)");
$stmt->bind_param("ss", $username, $password);

if ($stmt->execute()) {
    echo "Admin user created successfully!";
} else {
    echo "Error creating admin user: " . $stmt->error;
}
?>
