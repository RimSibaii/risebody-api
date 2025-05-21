<?php
session_start();

// Redirect if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'config.php';

// Get user info
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT full_name, email FROM users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Fetch latest assessment and training days
$assessment_id = 0;
$training_days = 5; // default fallback
$stmt = $conn->prepare("SELECT assessment_id, training_days_per_week FROM assessment WHERE user_id = ? ORDER BY created_at DESC LIMIT 1");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    $assessment_id = $row['assessment_id'];
    $training_days = (int)$row['training_days_per_week'];
}
$stmt->close();

// Load meal progress (7 days)
$meal_data = array_fill(0, 7, 0);
$stmt = $conn->prepare("SELECT day_number FROM user_progress WHERE user_id = ? AND plan_type = 'meal' AND is_done = 1 AND assessment_id = ?");
$stmt->bind_param("ii", $user_id, $assessment_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $idx = $row['day_number'] - 1;
    if ($idx >= 0 && $idx < 7) $meal_data[$idx] = 1;
}
$stmt->close();

// Load workout progress (limited to training_days)
$workout_data = array_fill(0, $training_days, 0);
$stmt = $conn->prepare("SELECT day_number FROM user_progress WHERE user_id = ? AND plan_type = 'workout' AND is_done = 1 AND assessment_id = ?");
$stmt->bind_param("ii", $user_id, $assessment_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $idx = $row['day_number'] - 1;
    if ($idx >= 0 && $idx < $training_days) $workout_data[$idx] = 1;
}
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>User Dashboard - RiseBody</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #f4f4f4;
      background: url('images/55.png') no-repeat center center fixed;
            background-size: cover;
    }
    .navbar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      background-color: #fff;
      padding: 15px 30px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
      position: sticky;
      top: 0;
      z-index: 10;
    }
    .navbar .logo {
      font-size: 24px;
      font-weight: bold;
      color: #00bfff;
      text-decoration: none;
    }
    .navbar .nav-links {
      display: flex;
      gap: 25px;
    }
    .navbar .nav-links a {
      text-decoration: none;
      color: #333;
      font-weight: 500;
      transition: color 0.2s;
    }
    .navbar .nav-links a:hover {
      color: #00bfff;
    }
    .navbar .profile {
      display: flex;
      align-items: center;
      gap: 8px;
      font-weight: 600;
      color: #333;
    }
    .navbar .profile a {
      text-decoration: none;
      color: #333;
      cursor: pointer;
      transition: color 0.3s;
      display: flex;
      align-items: center;
      gap: 6px;
    }
    .navbar .profile a:hover {
      color: #e74c3c;
    }
    .navbar .profile .logout {
      font-size: 14px;
      margin-left: 15px;
      color: #e74c3c;
      font-weight: 500;
      cursor: pointer;
      text-decoration: none;
    }
    .container {
      max-width: 900px;
      margin: 50px auto;
      background: white;
      padding: 40px;
      border-radius: 12px;
      box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08);
      text-align: center;
    }
    h2 {
      margin-bottom: 5px;
      color: #333;
    }
    p.subtitle {
      margin-top: 0;
      margin-bottom: 30px;
      font-size: 1.1em;
      color: #555;
    }
    .progress-section {
      display: flex;
      justify-content: space-around;
      gap: 40px;
      flex-wrap: wrap;
    }
    .progress-box {
      flex: 1 1 300px;
      background: #f9f9f9;
      border-radius: 12px;
      padding: 20px;
      box-shadow: 0 4px 14px rgba(0,0,0,0.1);
      transition: transform 0.2s ease, box-shadow 0.2s ease;
      cursor: default;
    }
    .progress-box:hover {
      transform: translateY(-6px);
      box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    }
    .progress-box h3 {
      margin-bottom: 20px;
      color: #444;
    }
    .day-cards {
      display: flex;
      justify-content: center;
      gap: 12px;
      flex-wrap: wrap;
    }
    .day-card {
      width: 70px;
      height: 90px;
      border-radius: 10px;
      background: #eee;
      text-align: center;
      padding: 10px;
      user-select: none;
      transition: all 0.3s ease;
      position: relative;
      cursor: pointer;
    }
    .day-card.active {
      background: #00b894;
      color: white;
      box-shadow: 0 0 10px #00b894aa;
    }
    .day-card .circle {
      width: 35px;
      height: 35px;
      margin: 10px auto 8px;
      line-height: 35px;
      border-radius: 50%;
      background: #ccc;
      font-weight: bold;
      font-size: 18px;
      user-select: none;
    }
    .day-card.active .circle {
      background: white;
      color: #00b894;
    }
    .day-card .label {
      font-weight: 600;
      font-size: 14px;
      user-select: none;
    }
    /* Tooltip styling */
    .day-card[data-tooltip]:hover::after {
      content: attr(data-tooltip);
      position: absolute;
      bottom: 110%;
      left: 50%;
      transform: translateX(-50%);
      background: #333;
      color: white;
      padding: 5px 10px;
      border-radius: 4px;
      white-space: nowrap;
      font-size: 12px;
      opacity: 0.9;
      pointer-events: none;
      z-index: 10;
    }
    /* Pulse animation for active days */
    .day-card.pulse {
      animation: pulseAnim 1.2s ease-in-out infinite;
    }
    @keyframes pulseAnim {
      0%, 100% { box-shadow: 0 0 6px #00b894; }
      50% { box-shadow: 0 0 14px #00b894; }
    }
    @media (max-width: 1024px) {
  .container {
    margin: 30px 20px;
    padding: 30px 20px;
  }

  .navbar {
    flex-direction: column;
    align-items: flex-start;
    padding: 15px;
  }

  .navbar .nav-links {
    flex-direction: column;
    gap: 10px;
    margin-top: 10px;
    width: 100%;
  }

  .navbar .profile {
    margin-top: 10px;
    flex-direction: column;
    align-items: flex-start;
    width: 100%;
  }

  .progress-section {
    flex-direction: column;
    gap: 20px;
  }
}

@media (max-width: 600px) {
  .day-card {
    width: 60px;
    height: 80px;
  }

  .day-card .circle {
    width: 30px;
    height: 30px;
    font-size: 16px;
    line-height: 30px;
  }

  .day-card .label {
    font-size: 12px;
  }

  h2 {
    font-size: 20px;
  }

  .progress-box h3 {
    font-size: 16px;
  }

  .navbar .logo {
    font-size: 20px;
  }

  @media (max-width: 768px) {
  .navbar {
    flex-direction: column;
    align-items: flex-start;
    padding: 15px 20px;
  }

  .nav-links {
    flex-direction: column;
    gap: 12px;
    margin-top: 10px;
    width: 100%;
  }

  .nav-links a {
    width: 100%;
    display: block;
    padding: 10px 0;
  }

  .profile {
    flex-direction: column;
    align-items: flex-start;
    gap: 10px;
    margin-top: 10px;
    width: 100%;
  }

  .profile a {
    width: 100%;
    font-size: 15px;
  }
}

}

  </style>
</head>
<body>
  <div class="navbar">
    <a class="logo" href="dashboard.php">RiseBody</a>
    <div class="nav-links">
      <a href="dashboard.php">Home</a>
      <a href="start_analysis.php">Start Your Analysis</a>
      <a href="fitnessplan.php">Fitness Plan</a>
      <a href="mealplan.php">Meal Plan</a>
    </div>
    <div class="profile">
      <a href="profile.php" title="View Profile">
        <i class="fas fa-user-circle"></i>
        <?php echo htmlspecialchars($user['full_name']); ?>
      </a>
      <a href="logout.php" style="margin-left: 15px; font-size: 14px; color: #d9534f; text-decoration: none;">Logout</a>
    </div>
  </div>

  <div class="container">
    <h2>Welcome, <?php echo htmlspecialchars($user['full_name']); ?>!</h2>
    <p class="subtitle">Track your progress and stay motivated with your personalized plans.</p>

    <div class="progress-section">
      <div class="progress-box" title="Meal Plan Progress">
        <h3>Meal Plan Progress (7 Days)</h3>
        <div class="day-cards">
          <?php foreach ($meal_data as $i => $done): ?>
            <div class="day-card <?php echo $done ? 'active pulse' : ''; ?>" data-tooltip="<?php echo $done ? 'Completed' : 'Pending'; ?>">
              <div class="circle"><?php echo $i + 1; ?></div>
              <div class="label"><?php echo $done ? '✔ Completed' : 'Pending'; ?></div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>

      <div class="progress-box" title="Workout Plan Progress">
        <h3>Workout Plan Progress (<?php echo $training_days; ?> Days)</h3>
        <div class="day-cards">
          <?php foreach ($workout_data as $i => $done): ?>
            <div class="day-card <?php echo $done ? 'active pulse' : ''; ?>" data-tooltip="<?php echo $done ? 'Completed' : 'Pending'; ?>">
              <div class="circle"><?php echo $i + 1; ?></div>
              <div class="label"><?php echo $done ? '✔ Completed' : 'Pending'; ?></div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>

  <script>
    document.querySelectorAll('.day-card.active').forEach(card => {
      card.addEventListener('mouseenter', () => card.classList.add('pulse'));
      card.addEventListener('mouseleave', () => card.classList.remove('pulse'));
    });
  </script>
</body>
</html>
