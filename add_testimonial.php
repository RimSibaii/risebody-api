<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

include 'config.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_name = $_POST['user_name'] ?? '';
    $testimonial_text = $_POST['testimonial_text'] ?? '';

    // Handle image upload
    $image_path = '';
    $upload_dir = 'images/';

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $tmp_name = $_FILES['image']['tmp_name'];
        $filename = basename($_FILES['image']['name']);
        $target_file = $upload_dir . time() . '_' . $filename;

        if (move_uploaded_file($tmp_name, $target_file)) {
            $image_path = $target_file;
        } else {
            $message = "Failed to upload image.";
        }
    } else {
        $message = "Image is required.";
    }

    if (!$message) {
        $stmt = $conn->prepare("INSERT INTO testimonials (user_name, testimonial_text, image_path) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $user_name, $testimonial_text, $image_path);

        if ($stmt->execute()) {
            $message = "Testimonial added successfully.";
            // Optionally redirect back to dashboard
            header("Location: admin_dashboard.php?msg=Testimonial+added+successfully#testimonials");
            exit();
        } else {
            $message = "Database error: Could not add testimonial.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Add Testimonial</title>
<style>
  body { font-family: Arial; margin: 40px; background: #f0f4f9; color: #1e2a78; }
  form { max-width: 600px; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 0 15px rgba(0,0,0,0.1); }
  label { display: block; margin-top: 15px; font-weight: 600; }
  input[type="text"], textarea {
    width: 100%; padding: 8px; margin-top: 6px; border: 1px solid #ccc; border-radius: 4px;
  }
  input[type="file"] {
    margin-top: 6px;
  }
  button {
    margin-top: 20px; background-color: #2979ff; color: white; border: none; padding: 10px 18px; font-weight: 600; border-radius: 6px; cursor: pointer;
  }
  button:hover {
    background-color: #004ecb;
  }
  .message {
    margin-top: 20px; font-weight: 700; color: red;
  }
  a.back-link {
    display: inline-block; margin-top: 20px; color: #2979ff; text-decoration: none; font-weight: 600;
  }
  a.back-link:hover {
    text-decoration: underline;
  }
</style>
</head>
<body>

<h2>Add Testimonial</h2>

<?php if ($message): ?>
  <div class="message"><?= htmlspecialchars($message) ?></div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data">
  <label>User Name</label>
  <input type="text" name="user_name" required>

  <label>Testimonial Text</label>
  <textarea name="testimonial_text" rows="4" required></textarea>

  <label>Upload Image</label>
  <input type="file" name="image" accept="image/*" required>

  <button type="submit">Add Testimonial</button>
</form>

<p><a href="admin_dashboard.php#testimonials" class="back-link">← Back to Admin Dashboard</a></p>

</body>
</html>
