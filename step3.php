<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Step 3</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(to bottom right, #f2f9ff, #d9efff);
      margin: 0;
      padding: 0;
    }
    .analysis-container {
      max-width: 900px;
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
      width: 75%;
      height: 100%;
      background: #00bfff;
      transition: width 0.3s ease;
    }
    h2 {
      color: #00bfff;
      margin-bottom: 20px;
      text-align: center;
    }
    form {
      display: flex;
      flex-direction: column;
      gap: 20px;
    }
    label {
      font-weight: 500;
      margin-bottom: 5px;
    }
    input[type="number"],
    textarea {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 6px;
      font-size: 14px;
    }
    .btn-group {
      display: flex;
      justify-content: space-between;
      margin-top: 30px;
    }
    .btn {
      background: #00bfff;
      color: white;
      border: none;
      padding: 12px 24px;
      border-radius: 6px;
      font-size: 16px;
      cursor: pointer;
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

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}
?>
<div class="analysis-container">
  <div class="progress-bar">
    <div class="progress-bar-fill"></div>
  </div>
  <form action="save_step3.php" method="POST">
    <h2>Tell Us About Your Current Stats</h2>

    <div>
      <label for="height">Height (in cm):</label>
      <input type="number" name="height_cm" id="height" required>
    </div>

    <div>
      <label for="weight">Weight (in kg):</label>
      <input type="number" name="weight_kg" id="weight" required>
    </div>

    <div>
      <label for="focus">Which area(s) do you want to focus on?</label>
      <textarea name="focus_area" id="focus" rows="3" placeholder="e.g., Glutes, Arms, Core..." required></textarea>
    </div>

    <div class="btn-group">
      <a href="start_analysis.php" class="btn">Previous</a>
      <button type="submit" class="btn">Next</button>
    </div>
  </form>
</div>
</body>
</html>
