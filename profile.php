<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'config.php';
$user_id = $_SESSION['user_id'];

// Fetch user info
$stmt = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $dob = $_POST['date_of_birth'];
    $gender = $_POST['gender'];
    $goal = $_POST['fitness_goal'];
    $injuries = $_POST['injuries'];
    $meal_type = $_POST['meal_type'];
    $restrictions = $_POST['dietary_restrictions'];

    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Update query without password
    $update_sql = "UPDATE users SET full_name = ?, email = ?, date_of_birth = ?, gender = ?, fitness_goal = ?, injuries = ?, meal_type = ?, dietary_restrictions = ?";
if (!empty($new_password)) {
    $pattern = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/";
    if (!preg_match($pattern, $new_password)) {
        $message = "Password must be at least 8 characters long, include uppercase, lowercase, a number, and a special character.";
    } elseif ($new_password !== $confirm_password) {
        $message = "Passwords do not match.";
    } else {
        $hashed = password_hash($new_password, PASSWORD_DEFAULT);
        $update_sql .= ", password = ?";
    }
}

    // Append password update if needed
    if (!empty($new_password) && $new_password === $confirm_password) {
        $hashed = password_hash($new_password, PASSWORD_DEFAULT);
        $update_sql .= ", password = ?";
    }

    $update_sql .= " WHERE user_id = ?";

    $stmt = $conn->prepare($update_sql);

    if (!empty($new_password) && $new_password === $confirm_password) {
    // Add password update → total 10 values: 9 strings + 1 int
    $stmt = $conn->prepare("UPDATE users SET full_name = ?, email = ?, date_of_birth = ?, gender = ?, fitness_goal = ?, injuries = ?, meal_type = ?, dietary_restrictions = ?, password = ? WHERE user_id = ?");
    $stmt->bind_param("sssssssssi", $full_name, $email, $dob, $gender, $goal, $injuries, $meal_type, $restrictions, $hashed, $user_id);
} else {
    // No password update → total 9 values: 8 strings + 1 int
    $stmt = $conn->prepare("UPDATE users SET full_name = ?, email = ?, date_of_birth = ?, gender = ?, fitness_goal = ?, injuries = ?, meal_type = ?, dietary_restrictions = ? WHERE user_id = ?");
    $stmt->bind_param("ssssssssi", $full_name, $email, $dob, $gender, $goal, $injuries, $meal_type, $restrictions, $user_id);
}


    if ($stmt->execute()) {
        $message = "Profile updated successfully!";
        $user = array_merge($user, $_POST); // Update UI instantly
    } else {
        $message = "Failed to update profile.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Profile - RiseBody</title>
    <style>
        .back-button {
  display: inline-block;
  margin-top: 20px;
  padding: 10px 18px;
  background-color: #00bfff;
  color: white;
  text-decoration: none;
  border-radius: 6px;
  font-size: 14px;
  transition: background-color 0.3s ease;
}

.back-button:hover {
  background-color: #0099cc;
}

        body {
  font-family: Arial, sans-serif;
  background: linear-gradient(to bottom right, rgba(0,0,0,0.6), rgba(0,0,0,0.7)),
              url('images/signup-bg.jpg') center/cover no-repeat;
  margin: 0;
  padding: 0;
  min-height: 100vh;
  display: flex;
  justify-content: center;
  align-items: center;
}

        .container {
  background: white;
  padding: 30px;
  border-radius: 12px;
  box-shadow: 0 10px 20px rgba(0,0,0,0.15);
  width: 100%;
  max-width: 600px;
  margin: 40px auto;
}

        
        h2 { color: #00bfff; margin-bottom: 20px; text-align: center; }
        label { font-weight: bold; margin-top: 15px; display: block; }
        input, select, textarea {
            width: 100%; padding: 10px; margin-top: 5px;
            border: 1px solid #ccc; border-radius: 5px;
        }
        button {
            margin-top: 20px;
            background-color: #00bfff;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }
        .message {
            color: green;
            margin-top: 15px;
            font-weight: bold;
            text-align: center;
        }
    </style>
</head>
<body>
<div class="container">
    <a href="dashboard.php" class="back-button">← Back to Home</a>

    <h2>My Profile</h2>

    <?php if (isset($message)): ?>
        <p class="message"><?php echo $message; ?></p>
    <?php endif; ?>

    <form method="POST">
        <label>Full Name</label>
        <input type="text" name="full_name" value="<?php echo htmlspecialchars($user['full_name']); ?>" required>

        <label>Email</label>
        <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>

        <label>Date of Birth</label>
        <input type="date" name="date_of_birth" value="<?php echo htmlspecialchars($user['date_of_birth']); ?>">

        <label>Gender</label>
        <select name="gender">
            <option value="female" <?php if ($user['gender'] === 'female') echo 'selected'; ?>>Female</option>
            <option value="male" <?php if ($user['gender'] === 'male') echo 'selected'; ?>>Male</option>
        </select>

        <label>Fitness Goal</label>
        <textarea name="fitness_goal"><?php echo htmlspecialchars($user['fitness_goal']); ?></textarea>

        <label>Injuries</label>
        <textarea name="injuries"><?php echo htmlspecialchars($user['injuries']); ?></textarea>

        <label>Meal Type</label>
        <input type="text" name="meal_type" value="<?php echo htmlspecialchars($user['meal_type']); ?>">

        <label>Dietary Restrictions</label>
        <textarea name="dietary_restrictions"><?php echo htmlspecialchars($user['dietary_restrictions']); ?></textarea>

        <label>New Password (optional)</label>
        <input type="password" name="new_password" placeholder="Enter new password">

        <label>Confirm New Password</label>
        <input type="password" name="confirm_password" placeholder="Confirm new password">

        <button type="submit">Update Profile</button>
    </form>
</div>
<script>
document.querySelector("form").addEventListener("submit", function(e) {
    const newPassword = document.querySelector('input[name="new_password"]').value;
    const confirmPassword = document.querySelector('input[name="confirm_password"]').value;

    if (newPassword || confirmPassword) {
        const strongRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/;
        if (!strongRegex.test(newPassword)) {
            alert("Password must be at least 8 characters and include uppercase, lowercase, number, and special character.");
            e.preventDefault();
        } else if (newPassword !== confirmPassword) {
            alert("Passwords do not match.");
            e.preventDefault();
        }
    }
});
</script>

</body>
</html>
