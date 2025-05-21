<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Step 2</title>
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
      width: 25%;
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
<?php
session_start();
require_once 'config.php';

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT gender FROM users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$gender = $user['gender'];
?>
<div class="analysis-container">
  <div class="progress-bar">
    <div class="progress-bar-fill"></div>
  </div>
  <form action="save_step2.php" method="POST">

    <?php if ($gender === 'female'): ?>
      <h2>Where Does Your Body Store Fat?</h2>
      <div class="image-options">
        <label class="image-option">
          <input type="radio" name="fat_distribution" value="High fat in upper body" required>
          <img src="Queries/12.png" alt="Upper Body">
          <div>Upper Body</div>
        </label>
        <label class="image-option">
          <input type="radio" name="fat_distribution" value="High fat in lower body">
          <img src="Queries/11.png" alt="Lower Body">
          <div>Lower Body</div>
        </label>
        <label class="image-option">
          <input type="radio" name="fat_distribution" value="High fat in abdominal area">
          <img src="Queries/13.png" alt="Belly">
          <div>Belly</div>
        </label>
        <label class="image-option">
          <input type="radio" name="fat_distribution" value="High fat around the waistline">
          <img src="Queries/14.png" alt="Love Handles">
          <div>Love Handles</div>
        </label>
        <label class="image-option">
          <input type="radio" name="fat_distribution" value="Athletic">
          <img src="Queries/15.png" alt="Athletic">
          <div>Athletic</div>
        
        </label>
        <div class="btn-group">
      <a href="start_analysis.php" class="btn">Previous</a>
      <button type="submit" class="btn">Next</button>
    </div>
      </div>
    <?php endif; ?>

    
  </form>
</div>
</body>
</html>
