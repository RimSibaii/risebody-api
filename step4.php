<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Step 4</title>
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
      width: 100%;
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
    select,
    input[type="text"] {
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

  input[type="number"],
  select,
  input[type="text"] {
    font-size: 15px;
    padding: 12px;
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
  <form action="save_step4.php" method="POST">
    <h2>Tell Us About Your Fitness Profile</h2>

    <div>
      <label for="fitness_level">Fitness Level:</label>
      <select name="fitness_level" id="fitness_level" required>
        <option value="">Select...</option>
        <option value="Beginner">Beginner</option>
        <option value="Intermediate">Intermediate</option>
        <option value="Advanced">Advanced</option>
      </select>
    </div>

    <div>
      <label for="training_days">How many days do you train per week?</label>
      <input type="number" name="training_days" id="training_days" min="1" max="7" required>
    </div>

    <div>
      <label for="metabolism">How would you describe your metabolism?</label>
      <select name="metabolism" id="metabolism" required>
        <option value="">Select...</option>
        <option value="Slow">Slow</option>
        <option value="Normal">Normal</option>
        <option value="Fast">Fast</option>
      </select>
    </div>

    <div>
      <label for="meal_structure">How do you prefer to structure your meals?</label>
      <select name="meal_structure" id="meal_structure" onchange="toggleCustomInput(this.value)" required>
        <option value="">Select...</option>
        <option value="3 Main Meals">3 Main Meals (Breakfast, Lunch, Dinner)</option>
        <option value="3 Meals + 1 Snack">3 Meals + 1 Snack</option>
        <option value="5 Small Meals">5 Small Meals per Day</option>
        <option value="Fasting Window">Intermittent Fasting Window (e.g. 12pm–8pm)</option>
        <option value="custom">Other</option>
      </select>
      <input type="text" name="custom_meal_structure" id="custom_meal_structure" placeholder="Please specify..." style="display: none; margin-top: 10px;">
    </div>

    <div class="btn-group">
      <a href="step3.php" class="btn">Previous</a>
      <button type="submit" class="btn">Finish</button>
    </div>
  </form>
</div>
<script>
  function toggleCustomInput(value) {
    const customInput = document.getElementById('custom_meal_structure');
    customInput.style.display = value === 'custom' ? 'block' : 'none';
  }
</script>
</body>
</html>
