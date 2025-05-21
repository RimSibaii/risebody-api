<?php
include 'db.php';

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Check if token is valid
   $stmt = $conn->prepare("SELECT email FROM password_resets WHERE token = ?");
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        $data = $result->fetch_assoc();
        $email = $data['email'];
        $expires_at = $data['expires_at'];

        // Check if token expired
        if (strtotime($expires_at) < time()) {
            echo "<h2 style='color:red; text-align:center;'>This reset link has expired.</h2>";
            exit;
        }

        // If token is valid and not expired, show password reset form
        ?>
        <form method="POST" action="resetlink.php">
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
            <h2 style="text-align:center;">Reset Your Password</h2>
            <div style="text-align:center;">
                <input type="password" name="new_password" placeholder="New Password" required><br><br>
                <input type="password" name="confirm_password" placeholder="Confirm Password" required><br><br>
                <button type="submit">Update Password</button>
            </div>
        </form>
        <?php
    } else {
        echo "<h2 style='color:red; text-align:center;'>Invalid reset link.</h2>";
    }
} elseif ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Handle password update
    $token = $_POST['token'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password !== $confirm_password) {
        echo "<h2 style='color:red; text-align:center;'>Passwords do not match.</h2>";
        exit;
    }

    // Re-check token and email
    $stmt = $conn->prepare("SELECT email FROM password_resets WHERE token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        $email = $result->fetch_assoc()['email'];
        $hashed = password_hash($new_password, PASSWORD_DEFAULT);

        // Update password in users table
        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
        $stmt->bind_param("ss", $hashed, $email);
        $stmt->execute();

        // Remove the reset token
        $stmt = $conn->prepare("DELETE FROM password_resets WHERE token = ?");
        $stmt->bind_param("s", $token);
        $stmt->execute();

        echo "<h2 style='color:green; text-align:center;'>Password updated successfully. You can <a href='login.php'>login</a> now.</h2>";
    } else {
        echo "<h2 style='color:red; text-align:center;'>Invalid request.</h2>";
    }
} else {
    echo "<h2 style='color:red; text-align:center;'>No reset token provided.</h2>";
}
?>
