<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

// Create a new assessment row with today's scan_date
$user_id = $_SESSION['user_id'];
$scan_date = date("Y-m-d");

$stmt = $conn->prepare("INSERT INTO assessment (user_id, scan_date) VALUES (?, ?)");
$stmt->bind_param("is", $user_id, $scan_date);
$stmt->execute();
$_SESSION['assessment_id'] = $conn->insert_id;
$stmt->close();

// Get user's gender
$stmt = $conn->prepare("SELECT gender FROM users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$gender = $user['gender'];
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Start My Analysis - Step 1</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(to bottom right, #f2f9ff, #d9efff);
      margin: 0;
      padding: 0;
    }
    .analysis-container {
      max-width: 1000px;
      margin: 100px auto;
      background: white;
      padding: 40px;
      border-radius: 16px;
      box-shadow: 0 6px 25px rgba(0, 0, 0, 0.1);
    }
    .progress-bar {
      height: 12px;
      background: #eee;
      border-radius: 6px;
      margin-bottom: 30px;
      overflow: hidden;
    }
    .progress-bar-fill {
      width: 15%;
      height: 100%;
      background: #00bfff;
      transition: width 0.3s ease;
    }
    h2 {
      color: #00bfff;
      margin-bottom: 25px;
      text-align: center;
    }
    .image-options {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      align-items: flex-start;
      gap: 30px;
      margin-bottom: 40px;
    }
    .image-option {
      flex: 1 1 160px;
      max-width: 180px;
      text-align: center;
      cursor: pointer;
      border: 2px solid transparent;
      padding: 12px;
      border-radius: 12px;
      transition: all 0.3s ease;
      box-sizing: border-box;
    }
    .image-option:hover,
    .image-option input:checked + img {
      border-color: #00bfff;
    }
    .image-option input {
      display: none;
    }
    .image-option img {
      width: 100%;
      max-height: 150px;
      object-fit: contain;
      margin-bottom: 10px;
      border-radius: 8px;
      border: 2px solid transparent;
    }
    .button-group {
      display: flex;
      justify-content: center;
      gap: 20px;
      margin-top: 40px;
    }
    .btn {
      background: #00bfff;
      color: white;
      border: none;
      padding: 12px 24px;
      border-radius: 6px;
      font-size: 16px;
      cursor: pointer;
      transition: background 0.3s;
      min-width: 120px;
    }
    .btn:hover {
      background: #0099cc;
    }
    @media (max-width: 1024px) {
  .analysis-container {
    margin: 60px 20px;
    padding: 30px 20px;
  }

  .image-options {
    gap: 20px;
  }

  .btn {
    min-width: 100px;
    padding: 10px 20px;
    font-size: 15px;
  }
}

@media (max-width: 768px) {
  .analysis-container {
    margin: 40px 15px;
    padding: 25px 20px;
    border-radius: 14px;
  }

  h2 {
    font-size: 20px;
    text-align: center;
  }

  .image-options {
    flex-direction: column;
    gap: 20px;
    align-items: center;
  }

  .image-option {
    width: 100%;
    max-width: 240px;
    padding: 12px;
  }

  .image-option img {
    max-height: 140px;
  }

  input[type="number"],
  input[type="text"],
  textarea,
  select {
    width: 100%;
    padding: 12px;
    font-size: 15px;
  }

  .button-group,
  .btn-group {
    flex-direction: column;
    gap: 12px;
  }

  .btn {
    width: 100%;
    font-size: 15px;
    padding: 12px;
  }

  .progress-bar {
    height: 10px;
  }

  .progress-bar-fill {
    height: 100%;
  }
}



  </style>
</head>
<body>
<div class="analysis-container">
  <div class="progress-bar">
    <div class="progress-bar-fill"></div>
  </div>

  <form action="save_step1.php" method="POST" id="analysisForm">
    <input type="hidden" name="gender" id="gender" value="<?php echo htmlspecialchars($gender); ?>">

    <?php if ($gender === 'female'): ?>
      <h2>Select Your Body Shape</h2>
      <div class="image-options">
        <label class="image-option">
          <input type="radio" name="body_shape" value="Hourglass" required>
          <img src="Queries/4.png" alt="Hourglass">
          <div>Hourglass</div>
        </label>
        <label class="image-option">
          <input type="radio" name="body_shape" value="Pear">
          <img src="Queries/3.png" alt="Pear/Triangle">
          <div>Pear/Triangle</div>
        </label>
        <label class="image-option">
          <input type="radio" name="body_shape" value="Apple">
          <img src="Queries/5.png" alt="Apple">
          <div>Apple</div>
        </label>
        <label class="image-option">
          <input type="radio" name="body_shape" value="Rectangle">
          <img src="Queries/1.png" alt="Rectangle">
          <div>Rectangle</div>
        </label>
        <label class="image-option">
          <input type="radio" name="body_shape" value="Inverted Triangle">
          <img src="Queries/2.png" alt="Inverted Triangle">
          <div>Inverted Triangle</div>
        </label>
      </div>
    <?php else: ?>
      <h2>Where Does Your Body Store Fat?</h2>
      <div class="image-options">
        <label class="image-option">
          <input type="radio" name="fat_distribution" value="High in fat in upper body" required>
          <img src="Queries/8.png" alt="Upper Body">
          <div>Upper Body</div>
        </label>
        <label class="image-option">
          <input type="radio" name="fat_distribution" value="High in fat in lower body">
          <img src="Queries/9.png" alt="Lower Body">
          <div>Lower Body</div>
        </label>
        <label class="image-option">
          <input type="radio" name="fat_distribution" value="High in fat in abdominal area">
          <img src="Queries/6.png" alt="Belly">
          <div>Belly</div>
        </label>
        <label class="image-option">
          <input type="radio" name="fat_distribution" value="High in fat around the waistline and back">
          <img src="Queries/7.png" alt="Love Handles and Back">
          <div>Love Handles and Back</div>
        </label>
        <label class="image-option">
          <input type="radio" name="fat_distribution" value="Athletic">
          <img src="Queries/10.png" alt="Athletic">
          <div>Athletic</div>
        </label>
      </div>
    <?php endif; ?>

    <div class="button-group">
      <a href="dashboard.php" class="btn">Previous</a>
      <button type="submit" class="btn" onclick="redirectByGender(event)">Next</button>
    </div>
  </form>
</div>

<script>
function redirectByGender(event) {
  const gender = document.getElementById('gender').value;
  const form = document.getElementById('analysisForm');

  if (gender === 'male') {
    event.preventDefault();
    form.action = 'save_step1.php?skipStep2=1';
    form.submit();
  }
}
</script>
</body>
</html>
