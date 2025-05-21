<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}
include 'config.php';

if (!isset($_GET['id'])) {
    die("Testimonial ID required.");
}

$id = intval($_GET['id']);
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_name = $_POST['user_name'] ?? '';
    $testimonial_text = $_POST['testimonial_text'] ?? '';

    // Image upload handling (optional)
    $image_path = '';
    $upload_dir = 'images/';

    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $tmp_name = $_FILES['image']['tmp_name'];
        $filename = basename($_FILES['image']['name']);
        $target_file = $upload_dir . time() . '_' . $filename;
        move_uploaded_file($tmp_name, $target_file);
        $image_path = $target_file;
    }

    if ($image_path) {
        $stmt = $conn->prepare("UPDATE testimonials SET user_name=?, testimonial_text=?, image_path=? WHERE id=?");
        $stmt->bind_param("sssi", $user_name, $testimonial_text, $image_path, $id);
    } else {
        $stmt = $conn->prepare("UPDATE testimonials SET user_name=?, testimonial_text=? WHERE id=?");
        $stmt->bind_param("ssi", $user_name, $testimonial_text, $id);
    }

    if ($stmt->execute()) {
        $message = "Testimonial updated successfully.";
    } else {
        $message = "Error updating testimonial.";
    }
}

// Fetch current data
$stmt = $conn->prepare("SELECT * FROM testimonials WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$testimonial = $result->fetch_assoc();

if (!$testimonial) {
    die("Testimonial not found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Edit Testimonial #<?= $id ?></title>
<style>
/* Add your styling here (similar to dashboard style) */
</style>
</head>
<style>
body {
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  background: #f5f7fc;
  margin: 0;
  padding: 40px 20px;
  color: #1e2a78;
  display: flex;
  justify-content: center;
}

form {
  background: white;
  padding: 30px 40px;
  border-radius: 10px;
  box-shadow: 0 6px 18px rgba(41, 121, 255, 0.15);
  max-width: 600px;
  width: 100%;
  box-sizing: border-box;
}

h2 {
  margin-bottom: 25px;
  font-weight: 700;
  color: #2979ff;
  text-align: center;
}

label {
  display: block;
  margin-bottom: 8px;
  font-weight: 600;
  color: #1e2a78;
}

input[type="text"],
input[type="email"],
input[type="date"],
input[type="number"],
textarea,
select,
input[type="file"] {
  width: 100%;
  padding: 12px 15px;
  margin-bottom: 20px;
  border: 2px solid #d3d9f7;
  border-radius: 8px;
  font-size: 1rem;
  transition: border-color 0.3s ease;
  box-sizing: border-box;
}

input[type="text"]:focus,
input[type="email"]:focus,
input[type="date"]:focus,
input[type="number"]:focus,
textarea:focus,
select:focus,
input[type="file"]:focus {
  border-color: #2979ff;
  outline: none;
  box-shadow: 0 0 8px rgba(41, 121, 255, 0.3);
}

textarea {
  resize: vertical;
  min-height: 100px;
}

button {
  background-color: #2979ff;
  color: white;
  font-weight: 700;
  font-size: 1.1rem;
  padding: 14px 0;
  border: none;
  border-radius: 10px;
  width: 100%;
  cursor: pointer;
  transition: background-color 0.3s ease;
  user-select: none;
}

button:hover {
  background-color: #004ecb;
}

.message {
  font-weight: 700;
  color: green;
  margin-bottom: 20px;
  text-align: center;
}

a.back-link {
  display: inline-block;
  margin-top: 20px;
  text-align: center;
  width: 100%;
  color: #2979ff;
  font-weight: 600;
  text-decoration: none;
}

a.back-link:hover {
  text-decoration: underline;
}
</style>
<body>
<h2>Edit Testimonial #<?= $id ?></h2>
<?php if ($message): ?>
  <div style="color:green;font-weight:bold;"><?= htmlspecialchars($message) ?></div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data">
  <label>User Name:</label>
  <input type="text" name="user_name" required value="<?= htmlspecialchars($testimonial['user_name']) ?>">

  <label>Testimonial Text:</label>
  <textarea name="testimonial_text" rows="4" required><?= htmlspecialchars($testimonial['testimonial_text']) ?></textarea>

  <label>Change Image (optional):</label>
  <input type="file" name="image" accept="image/*">

  <p>Current Image:</p>
  <img src="<?= htmlspecialchars($testimonial['image_path']) ?>" alt="Testimonial Image" style="max-width:200px;">

  <br><br>
  <button type="submit">Update Testimonial</button>
  <p><a href="admin_dashboard.php#testimonials" class="back-link">← Back to Dashboard</a></p>


</form>

</body>
</html>
