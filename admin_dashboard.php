<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}
include 'config.php';

// Fetch Testimonials
$testimonials = $conn->query("SELECT * FROM testimonials ORDER BY created_at DESC");

// Fetch Users
$users = $conn->query("SELECT * FROM users ORDER BY user_id ASC");

// Fetch Assessments with user info
$assessments = $conn->query("
  SELECT a.*, u.full_name, u.email
  FROM assessment a
  LEFT JOIN users u ON a.user_id = u.user_id
  ORDER BY a.created_at DESC
");

// Fetch Meal Plans with user info
$mealplans = $conn->query("
  SELECT mp.*, m.assessment_id, m.user_id, u.full_name, u.email
  FROM meal_plan mp
  LEFT JOIN meal m ON mp.meal_id = m.meal_id
  LEFT JOIN users u ON m.user_id = u.user_id
  ORDER BY m.assessment_id, mp.day, mp.id
");

// Fetch Fitness Plans with user info
$fitnessplans = $conn->query("
  SELECT wp.*, w.workout_id, w.assessment_id, w.user_id, u.full_name, u.email
  FROM workout_plan wp
  LEFT JOIN workout w ON wp.workout_id = w.workout_id
  LEFT JOIN users u ON w.user_id = u.user_id
  ORDER BY w.assessment_id, wp.day, wp.id
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>RiseBody Admin Dashboard</title>
<style>
  body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: #f5f7fc;
    margin: 0;
    padding: 20px;
    color: #1e2a78;
  }
  h1 {
    text-align: center;
    margin-bottom: 30px;
    font-weight: 700;
  }
  .dashboard {
    max-width: 1200px;
    margin: 0 auto;
  }
  .tabs {
    display: flex;
    border-bottom: 3px solid #2979ff;
    margin-bottom: 20px;
    user-select: none;
  }
  .tab {
    padding: 14px 30px;
    cursor: pointer;
    font-weight: 600;
    color: #2979ff;
    border-bottom: 3px solid transparent;
    transition: border-color 0.3s;
  }
  .tab.active {
    border-bottom-color: #2979ff;
    background: #e3e9ff;
    color: #004ecb;
  }
  .subtabs {
    display: flex;
    margin-top: 10px;
    margin-bottom: 20px;
    border-bottom: 2px solid #90a4ff;
  }
  .subtab {
    padding: 10px 25px;
    cursor: pointer;
    font-weight: 600;
    color: #4f64ff;
    border-bottom: 2px solid transparent;
    transition: border-color 0.3s;
  }
  .subtab.active {
    border-bottom-color: #4f64ff;
    background: #d7dcff;
    color: #2a3fff;
  }
  .content {
    background: white;
    padding: 25px;
    border-radius: 10px;
    box-shadow: 0 4px 12px rgb(0 0 0 / 0.1);
    overflow-x: auto;
  }
  table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
    min-width: 900px;
  }
  th, td {
    padding: 12px 15px;
    border: 1px solid #d3d9f7;
    text-align: left;
    vertical-align: middle;
  }
  th {
    background-color: #4f64ff;
    color: white;
    font-weight: 600;
  }
  tr:nth-child(even) {
    background-color: #f0f4ff;
  }
  tr:hover {
    background-color: #e1e8ff;
  }
  button, .btn-link {
    background-color: #2979ff;
    border: none;
    color: white;
    padding: 7px 15px;
    border-radius: 6px;
    cursor: pointer;
    font-weight: 600;
    transition: background-color 0.3s ease;
    text-decoration: none;
    display: inline-block;
  }
  button:hover, .btn-link:hover {
    background-color: #004ecb;
  }
  .btn-link {
    padding: 5px 12px;
  }
  .message {
    color: green;
    font-weight: 700;
    margin-bottom: 15px;
  }
  @media (max-width: 1024px) {
  .dashboard {
    padding: 0 15px;
  }

  .tab, .subtab {
    padding: 10px 15px;
    font-size: 14px;
  }

  .content {
    padding: 15px;
  }

  table {
    font-size: 14px;
    min-width: 100%;
  }

  th, td {
    padding: 10px;
  }
}

@media (max-width: 768px) {
  h1 {
    font-size: 20px;
  }

  .tabs, .subtabs {
    flex-direction: column;
    border-bottom: none;
  }

  .tab, .subtab {
    border-bottom: 2px solid #ddd;
    border-left: 4px solid transparent;
    padding: 12px;
    font-size: 15px;
  }

  .tab.active, .subtab.active {
    border-left-color: #2979ff;
    background: #e9f2ff;
  }

  table {
    font-size: 13px;
    display: block;
    overflow-x: auto;
    white-space: nowrap;
  }

  th, td {
    padding: 8px 10px;
  }

  .btn-link, button {
    font-size: 13px;
    padding: 6px 10px;
  }
  
}

</style>
</head>
<body>

<div class="dashboard">
  <h1>RiseBody Admin Dashboard</h1>

  <?php if (isset($_GET['msg'])): ?>
    <div class="message"><?= htmlspecialchars($_GET['msg']) ?></div>
  <?php endif; ?>

  <div class="tabs" role="tablist">
    <div class="tab active" data-tab="testimonials" role="tab" tabindex="0" aria-selected="true">Manage Testimonials</div>
    <div class="tab" data-tab="users" role="tab" tabindex="-1" aria-selected="false">View Users</div>
    <div class="tab" data-tab="reports" role="tab" tabindex="-1" aria-selected="false">View Reports</div>
  </div>

  <div id="testimonials" class="content" role="tabpanel">
    <h2>Manage Testimonials</h2>
    <p><a href="add_testimonial.php" class="btn-link">+ Add Testimonial</a>
    <table>
      <thead>
        <tr><th>ID</th><th>User</th><th>Text</th><th>Image</th><th>Actions</th></tr>
      </thead>
      <tbody>
        <?php while ($row = $testimonials->fetch_assoc()): ?>
        <tr>
          <td><?= $row['id'] ?></td>
          <td><?= htmlspecialchars($row['user_name']) ?></td>
          <td><?= htmlspecialchars($row['testimonial_text']) ?></td>
          <td><img src="<?= htmlspecialchars($row['image_path']) ?>" alt="<?= htmlspecialchars($row['user_name']) ?>" style="height:40px;"></td>
          <td>
            <a href="edit_testimonial.php?id=<?= $row['id'] ?>" class="btn-link">Edit</a>
            <a href="delete_testimonial.php?id=<?= $row['id'] ?>" class="btn-link" onclick="return confirm('Are you sure?')">Delete</a>
          </td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>

  <div id="users" class="content" role="tabpanel" hidden>
    <h2>View Users</h2>
    <table>
      <thead>
        <tr>
          <th>ID</th><th>Full Name</th><th>Email</th><th>Date of Birth</th><th>Gender</th><th>Fitness Goal</th><th>Injuries</th><th>Meal Type</th><th>Dietary Restrictions</th><th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $users->fetch_assoc()): ?>
        <tr>
          <td><?= $row['user_id'] ?></td>
          <td><?= htmlspecialchars($row['full_name']) ?></td>
          <td><?= htmlspecialchars($row['email']) ?></td>
          <td><?= htmlspecialchars($row['date_of_birth']) ?></td>
          <td><?= htmlspecialchars($row['gender']) ?></td>
          <td><?= htmlspecialchars($row['fitness_goal']) ?></td>
          <td><?= htmlspecialchars($row['injuries']) ?></td>
          <td><?= htmlspecialchars($row['meal_type']) ?></td>
          <td><?= htmlspecialchars($row['dietary_restrictions']) ?></td>
          <td>
            <a href="edit_user.php?id=<?= $row['user_id'] ?>" class="btn-link">Edit</a>
            <a href="delete_user.php?id=<?= $row['user_id'] ?>" class="btn-link" onclick="return confirm('Are you sure?')">Delete</a>
          </td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>

  <div id="reports" class="content" role="tabpanel" hidden>
    <h2>View Reports</h2>
    <div class="subtabs" role="tablist" aria-label="Reports subtabs">
      <div class="subtab active" data-subtab="assessment" role="tab" tabindex="0" aria-selected="true">Assessment</div>
      <div class="subtab" data-subtab="mealplan" role="tab" tabindex="-1" aria-selected="false">Meal Plan</div>
      <div class="subtab" data-subtab="fitnessplan" role="tab" tabindex="-1" aria-selected="false">Fitness Plan</div>
    </div>

    <div id="assessment" class="subcontent" role="tabpanel">
      <table>
        <thead>
          <tr>
            <th>Assessment ID</th><th>User ID</th><th>Full Name</th><th>Email</th><th>Body Shape</th><th>Fat Distribution</th><th>Focus Area</th><th>Meal Structure</th><th>Training Days/Week</th><th>Fitness Level</th><th>Metabolism Type</th><th>Height (cm)</th><th>Weight (kg)</th><th>Created At</th><th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = $assessments->fetch_assoc()): ?>
          <tr>
            <td><?= $row['assessment_id'] ?></td>
            <td><?= htmlspecialchars($row['user_id'] ?? 'N/A') ?></td>
            <td><?= htmlspecialchars($row['full_name'] ?? 'N/A') ?></td>
            <td><?= htmlspecialchars($row['email'] ?? 'N/A') ?></td>
            <td><?= htmlspecialchars($row['body_shape'] ?? 'N/A') ?></td>
            <td><?= htmlspecialchars($row['fat_distribution'] ?? 'N/A') ?></td>
            <td><?= htmlspecialchars($row['focus_area'] ?? 'N/A') ?></td>
            <td><?= htmlspecialchars($row['meal_structure'] ?? 'N/A') ?></td>
            <td><?= htmlspecialchars($row['training_days_per_week'] ?? 'N/A') ?></td>
            <td><?= htmlspecialchars($row['fitness_level'] ?? 'N/A') ?></td>
            <td><?= htmlspecialchars($row['metabolism_type'] ?? 'N/A') ?></td>
            <td><?= htmlspecialchars($row['height_cm'] ?? 'N/A') ?></td>
            <td><?= htmlspecialchars($row['weight_kg'] ?? 'N/A') ?></td>
            <td><?= htmlspecialchars($row['created_at'] ?? 'N/A') ?></td>
            <td>
              <a href="edit_assessment.php?id=<?= $row['assessment_id'] ?>" class="btn-link">Edit</a>
              <a href="delete_assessment.php?id=<?= $row['assessment_id'] ?>" class="btn-link" onclick="return confirm('Are you sure?')">Delete</a>
            </td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>

    <div id="mealplan" class="subcontent" role="tabpanel" hidden>
      <table>
        <thead>
          <tr>
            <th>ID</th><th>Meal ID</th><th>Assessment ID</th><th>User ID</th><th>Full Name</th><th>Email</th><th>Day</th><th>Meal Type</th><th>Meal</th><th>Calories</th><th>Description</th><th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = $mealplans->fetch_assoc()): ?>
          <tr>
            <td><?= $row['id'] ?></td>
            <td><?= $row['meal_id'] ?></td>
            <td><?= $row['assessment_id'] ?></td>
            <td><?= htmlspecialchars($row['user_id'] ?? 'N/A') ?></td>
            <td><?= htmlspecialchars($row['full_name'] ?? 'N/A') ?></td>
            <td><?= htmlspecialchars($row['email'] ?? 'N/A') ?></td>
            <td><?= htmlspecialchars($row['day'] ?? 'N/A') ?></td>
            <td><?= htmlspecialchars($row['meal_type'] ?? 'N/A') ?></td>
            <td><?= htmlspecialchars($row['meal'] ?? 'N/A') ?></td>
            <td><?= htmlspecialchars($row['calories'] ?? 'N/A') ?></td>
            <td><?= nl2br(htmlspecialchars($row['description'] ?? 'N/A')) ?></td>
            <td>
              <a href="edit_meal_plan.php?id=<?= $row['id'] ?>" class="btn-link">Edit</a>
              <a href="delete_meal_plan.php?id=<?= $row['id'] ?>" class="btn-link" onclick="return confirm('Are you sure?')">Delete</a>
            </td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>

    <div id="fitnessplan" class="subcontent" role="tabpanel" hidden>
      <table>
        <thead>
          <tr>
            <th>ID</th><th>Workout ID</th><th>Assessment ID</th><th>User ID</th><th>Full Name</th><th>Email</th><th>Day</th><th>Exercise</th><th>Focus Area</th><th>Rounds</th><th>Sets</th><th>Duration</th><th>Description</th><th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = $fitnessplans->fetch_assoc()): ?>
          <tr>
            <td><?= $row['id'] ?></td>
            <td><?= $row['workout_id'] ?></td>
            <td><?= $row['assessment_id'] ?></td>
            <td><?= htmlspecialchars($row['user_id'] ?? 'N/A') ?></td>
            <td><?= htmlspecialchars($row['full_name'] ?? 'N/A') ?></td>
            <td><?= htmlspecialchars($row['email'] ?? 'N/A') ?></td>
            <td><?= htmlspecialchars($row['day'] ?? 'N/A') ?></td>
            <td><?= htmlspecialchars($row['exercise'] ?? 'N/A') ?></td>
            <td><?= htmlspecialchars($row['focus_area'] ?? 'N/A') ?></td>
            <td><?= htmlspecialchars($row['rounds'] ?? 'N/A') ?></td>
            <td><?= htmlspecialchars($row['sets'] ?? 'N/A') ?></td>
            <td><?= htmlspecialchars($row['duration'] ?? 'N/A') ?></td>
            <td><?= nl2br(htmlspecialchars($row['description'] ?? 'N/A')) ?></td>
            <td>
              <a href="edit_workout_plan.php?id=<?= $row['id'] ?>" class="btn-link">Edit</a>
              <a href="delete_workout_plan.php?id=<?= $row['id'] ?>" class="btn-link" onclick="return confirm('Are you sure?')">Delete</a>
            </td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>

  </div>
</div>

<script>
  // Main tab switching
  const tabs = document.querySelectorAll('.tab');
  const contents = document.querySelectorAll('.content');

  tabs.forEach(tab => {
    tab.addEventListener('click', () => {
      tabs.forEach(t => {
        t.classList.remove('active');
        t.setAttribute('aria-selected', 'false');
      });
      contents.forEach(c => c.hidden = true);

      tab.classList.add('active');
      tab.setAttribute('aria-selected', 'true');
      document.getElementById(tab.dataset.tab).hidden = false;
    });
  });

  // Report subtabs switching
  const subtabs = document.querySelectorAll('.subtab');
  const subcontents = document.querySelectorAll('.subcontent');

  subtabs.forEach(subtab => {
    subtab.addEventListener('click', () => {
      subtabs.forEach(st => {
        st.classList.remove('active');
        st.setAttribute('aria-selected', 'false');
      });
      subcontents.forEach(sc => sc.hidden = true);

      subtab.classList.add('active');
      subtab.setAttribute('aria-selected', 'true');
      document.getElementById(subtab.dataset.subtab).hidden = false;
    });
  });
</script>

</body>
</html>
