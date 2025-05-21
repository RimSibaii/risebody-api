<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Sign Up - RiseBody</title>
  <link rel="stylesheet" href="signup.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
  <?php include 'preloader.php'; ?>

  <header>
    <div class="logo">RiseBody</div>
    <nav>
      <ul class="nav-links">
        <li><a href="home.php">Home</a></li>
        <li><a href="home.php#how-it-works">How It Works</a></li>
        <li><a href="signup.php">Start Your Analysis</a></li>
        <li><a href="aboutus.php">About Us</a></li>
      </ul>
    </nav>
    <div class="cta-button">
      <a href="signup.php" class="btn-primary"><i class="fas fa-user-plus"></i> Join Now</a>
      <a href="login.php" class="btn-primary"><i class="fas fa-sign-in-alt"></i> Login</a>
    </div>
  </header>

  <div class="signup-wrapper" style="background-image: linear-gradient(to bottom right, rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.7)), url('images/signup-bg.jpg'); padding-top: 120px; padding-bottom: 60px; min-height: 100vh; display: flex; justify-content: center; align-items: center; position: relative; z-index: 0;">
    <div class="signup-container" style="background: #fff; padding: 30px 25px; border-radius: 12px; box-shadow: 0 6px 24px rgba(0, 0, 0, 0.15); width: 100%; max-width: 400px; text-align: center; z-index: 1; position: relative;">
      <h2>Create Your Account</h2>
      
<?php
$success = $error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once 'config.php';

    // ✅ Step 1: Get inputs from the form
    $full_name = $conn->real_escape_string($_POST['full_name']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];
    

    // ✅ Step 2: Check strength and confirm match
    $pattern = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/";
    if (!preg_match($pattern, $password)) {
        $error = "Password must be at least 8 characters long and include uppercase, lowercase, number, and symbol.";

    } else {
        // ✅ Step 3: Hash the password
        $hashed = password_hash($password, PASSWORD_DEFAULT);

        // ✅ Step 4: Continue collecting other fields
        $dob = $_POST['dob'];
        $gender = $_POST['gender'];
        $fitness_goal = $conn->real_escape_string($_POST['fitness_goal']);
        $injuries = $conn->real_escape_string($_POST['injuries']);
        $meal_type = $_POST['meal_type'] === 'custom' ? $conn->real_escape_string($_POST['custom_meal_type']) : $_POST['meal_type'];
        $restrictions = $conn->real_escape_string($_POST['dietary_restrictions']);

        // ✅ Step 5: Insert into database using hashed password
        $sql = "INSERT INTO users (full_name, email, password, date_of_birth, gender, fitness_goal, injuries, meal_type, dietary_restrictions)
                VALUES ('$full_name', '$email', '$hashed', '$dob', '$gender', '$fitness_goal', '$injuries', '$meal_type', '$restrictions')";

        if ($conn->query($sql)) {
            echo "<script>alert('Account created successfully! You can now log in.'); window.location.href='login.php';</script>";
        } else {
            echo "<script>alert('There was an error creating your account. Please try again.');</script>";
        }

        $conn->close();
    }
}

?>

<form action="signup.php" method="POST">
        <input type="text" name="full_name" placeholder="Full Name" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="password" name="confirm_password" placeholder="Confirm Password" required>
        <input type="date" name="dob" required>

        <select name="gender" required>
          <option value="">Select Gender</option>
          <option value="female">Female</option>
          <option value="male">Male</option>
        </select>

        <input type="text" name="fitness_goal" placeholder="Your Fitness Goal" required>
        <input type="text" name="injuries" placeholder="Any Injuries? (Optional)">

        <select name="meal_type" id="meal_type" required onchange="toggleCustomMealInput()">
          <option value="">Meal Preference</option>
          <option value="balanced">Balanced</option>
          <option value="high protein">High Protein</option>
          <option value="vegetarian">Vegetarian</option>
          <option value="low carb">Low Carb</option>
          <option value="gluten free">Gluten-Free</option>
          <option value="mediterranean">Mediterranean</option>
          <option value="custom">Custom / Other</option>
        </select>

        <input type="text" name="custom_meal_type" id="custom_meal_type" placeholder="Please specify your meal preference" style="display:none; margin-top:10px;">

        <input type="text" name="dietary_restrictions" placeholder="Dietary Restrictions (Optional)">

        <button type="submit">Sign Up</button>
      </form>
      <p>Already have an account? <a href="login.php">Login here</a></p>
    </div>
  </div>

  <footer class="site-footer">
    <div class="footer-container">
      <div class="footer-left">
        <h3>RiseBody</h3>
        <p>Your AI-powered fitness & meal guide. Personalized, powerful, proven.</p>
      </div>
      <div class="footer-center">
        <ul>
          <li><a href="home.php">Home</a></li>
          <li><a href="#how-it-works">How It Works</a></li>
          <li><a href="signup.php">Start Your Analysis</a></li>
          <li><a href="aboutus.php">About Us</a></li>
        </ul>
      </div>
      <div class="footer-right">
        <div class="social-icons">
          <a href="#"><i class="fab fa-instagram"></i></a>
          <a href="#"><i class="fab fa-facebook-f"></i></a>
          <a href="mailto:info@risebody.com"><i class="fas fa-envelope"></i></a>
        </div>
        <p>&copy; 2025 RiseBody. All rights reserved.</p>
      </div>
    </div>
  </footer>

  
    <!-- function toggleCustomMealInput() {
      const mealType = document.getElementById("meal_type");
      const customInput = document.getElementById("custom_meal_type");
      customInput.style.display = mealType.value === "custom" ? "block" : "none";
    } -->
    <script src="signup.js"></script>
  
</body>
</html>
