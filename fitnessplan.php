<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    echo "<p style='text-align:center;color:red;'>User not logged in. Please <a href='login.php'>log in</a>.</p>";
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch workout_id for the user
$workout_id = null;
$workout_title = '';
$sql = "SELECT workout_id, workout_title FROM workout WHERE user_id = ? ORDER BY created_at DESC LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    $workout_id = $row['workout_id'];
    $workout_title = $row['workout_title'];
} else {
    echo "<p style='text-align:center;'>No fitness plan found.</p>";
    exit();
}
$stmt->close();
// Fetch latest assessment_id
$assessment_id = 0;
$stmt = $conn->prepare("SELECT assessment_id FROM assessment WHERE user_id = ? ORDER BY created_at DESC LIMIT 1");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    $assessment_id = $row['assessment_id'];
}
$stmt->close();

// Fetch distinct days
$days = [];
$sql = "SELECT DISTINCT day FROM workout_plan WHERE workout_id = ? ORDER BY id ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $workout_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $days[] = $row['day'];
}
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Fitness Plan</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: url('images/signup-bg.jpg') no-repeat center center fixed;
            background-size: cover;
            color: #333;
            margin: 0;
            padding: 0;
        }
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            background: rgba(255, 255, 255, 0.9);
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }
        header img {
            height: 40px;
        }
        h2 {
            text-align: center;
            color: #1e90ff;
            margin-top: 20px;
        }
        .controls {
            text-align: center;
            margin: 20px;
        }
        .day-btn {
            background: #1e90ff;
            color: #fff;
            border: none;
            margin: 5px;
            padding: 10px 20px;
            border-radius: 25px;
            cursor: pointer;
        }
        .day-btn:hover {
            background: #007acc;
        }
        .back-btn {
            background: #6c757d;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            margin-right: 10px;
        }
        .pdf-button {
            background: black;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .workout-section {
            display: none;
            background: white;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            border-radius: 12px;
            padding: 25px;
            max-width: 900px;
            margin: 20px auto;
        }
        .workout-section.active {
            display: block;
        }
        .exercise-card {
            margin-bottom: 20px;
        }
        .exercise-title {
            font-size: 18px;
            font-weight: bold;
            color: #2d3436;
        }
        .exercise-details {
            font-size: 14px;
            color: #636e72;
        }
            .restart-button {
            background: #007acc;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .restart-button:hover {
            background: #17a589;
        }
        .logo {
  font-size: 24px;
  font-weight: bold;
  color: #00bfff;
}
@media (max-width: 1024px) {
  header {
    flex-direction: column;
    align-items: flex-start;
    gap: 10px;
  }

  .logo {
    font-size: 22px;
  }

  .controls {
    flex-wrap: wrap;
  }

  .day-btn,
  .back-btn,
  .restart-button,
  .pdf-button {
    padding: 10px 16px;
    font-size: 14px;
  }

  .workout-section {
    padding: 20px;
    margin: 15px;
  }
}

@media (max-width: 768px) {
  h2 {
    font-size: 20px;
  }

  .controls {
    display: flex;
    flex-direction: column;
    align-items: center;
  }

  .day-btn {
    width: 100%;
    max-width: 280px;
    margin: 8px 0;
  }

  .back-btn,
  .restart-button,
  .pdf-button {
    width: 100%;
    max-width: 280px;
    margin: 6px 0;
  }

  .exercise-title {
    font-size: 16px;
  }

  .exercise-details {
    font-size: 13px;
  }

  .workout-section {
    padding: 18px;
    margin: 10px;
  }
}

</style>
</head>
<body>
<header>
    <div class="logo">RiseBody</div>
    <div style="display: flex; gap: 10px; align-items: center;">
        <button class="restart-button" onclick="location.href='start_analysis.php'">↻ Restart My Analysis</button>
        <button class="pdf-button" onclick="downloadPDF()">Download PDF</button>
    </div>
</header>
<div id="fitness-content">
    <h2><?php echo htmlspecialchars($workout_title); ?></h2>
    <div class="controls">
        <button class="back-btn" onclick="location.href='dashboard.php'">← Back</button>
        
        <?php foreach ($days as $i => $day): ?>
            <button class="day-btn" onclick="showDay('<?php echo $day; ?>')">Day <?php echo $i + 1; ?></button>
        <?php endforeach; ?>
    </div>
    

    <?php foreach ($days as $day): ?>
        <div class="workout-section" id="<?php echo $day; ?>">
            <h3 style="text-align:center; color:#10ac84;">Day <?php echo substr($day, 4); ?> Workout</h3>
            <?php
            $sql = "SELECT * FROM workout_plan WHERE workout_id = ? AND day = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("is", $workout_id, $day);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($ex = $result->fetch_assoc()):
            ?>
                <div class="exercise-card">
                    <div class="exercise-title">
                        <?php echo $ex['exercise']; ?> <span style="color:#0984e3; font-size: 14px;">(<?php echo $ex['duration']; ?>)</span>
                    </div>
                    <div class="exercise-details">
                        Focus: <?php echo $ex['focus_area']; ?> | <?php echo $ex['description']; ?>
                        <?php if (!empty($ex['sets'])) echo '<br>Sets: ' . $ex['sets']; ?>
                    </div>
                </div>
            <?php endwhile; $stmt->close(); ?>
            <form method="POST" action="mark_day_done.php" style="text-align:center; margin-top: 15px;">
  <input type="hidden" name="plan_type" value="workout">
  <input type="hidden" name="day_number" value="<?php echo substr($day, 4); ?>">
  <input type="hidden" name="assessment_id" value="<?php echo $assessment_id; ?>">
  <button type="submit" class="day-btn" style="background: #00b894;">✓ Mark Day <?php echo substr($day, 4); ?> as Done</button>
</form>

        </div>
    <?php endforeach; ?>
</div>

<script>
function showDay(dayId) {
    document.querySelectorAll('.workout-section').forEach(section => section.classList.remove('active'));
    document.getElementById(dayId).classList.add('active');
}

document.querySelectorAll('.day-btn')[0]?.click();

function downloadPDF() {
    const allSections = document.querySelectorAll('.workout-section');
    const previouslyActive = document.querySelector('.workout-section.active');

    allSections.forEach(section => section.classList.add('active'));

    const element = document.getElementById('fitness-content');
    html2pdf().from(element).save('My_Fitness_Plan.pdf').then(() => {
        allSections.forEach(section => section.classList.remove('active'));
        if (previouslyActive) previouslyActive.classList.add('active');
    });
}
</script>
</body>
</html>
