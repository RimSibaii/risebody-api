<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email']);

    // Check if the email exists
    $stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $token = bin2hex(random_bytes(50));
        $expires = date("Y-m-d H:i:s", time() + 3600);

        // ✅ Check if the insert succeeds
        $insert = $conn->prepare("INSERT INTO password_resets (email, token, expires_at) VALUES (?, ?, ?)");
        $insert->bind_param("sss", $email, $token, $expires);
        
        if ($insert->execute()) {
            echo "✅ Token inserted into table successfully.<br>";
            echo "📎 Token: $token<br>";
            echo "📅 Expires at: $expires<br>";
            echo "🔗 Link: http://localhost/AIFitness/resetpassword.php?token=$token";
        } else {
            echo "❌ Failed to insert token. Error: " . $insert->error;
        }
    } else {
        echo "⚠️ Email not found in users table.";
    }
}
?>
