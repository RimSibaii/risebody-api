<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>About Us - RiseBody</title>
  <link rel="stylesheet" href="aboutus.css" />
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
        <li><a href="aboutus.php" class="active">About Us</a></li>
      </ul>
    </nav>
    <div class="cta-button">
  <a href="signup.php" class="btn-primary"><i class="fas fa-user-plus"></i> Join Now</a>
  <a href="login.php" class="btn-primary"><i class="fas fa-sign-in-alt"></i> Login</a>
</div>


  </header>

  <section class="about-us">
  <div class="about-hero">
    <h1>Built for Transformation.</h1>
    <p>AI-crafted fitness & nutrition plans tailored to your body, goals, and life.</p>
  </div>

  <div class="about-content">
    <div class="about-left">
      <img src="images/about.jpg" alt="RiseBody Philosophy" />
    </div>
    <div class="about-right">
      <h2>Why RiseBody?</h2>
      <ul>
        <li><i class="fas fa-brain"></i> AI-driven precision — no more guessing</li>
        <li><i class="fas fa-dumbbell"></i> Workouts designed for real results</li>
        <li><i class="fas fa-utensils"></i> Meal plans that fit your lifestyle</li>
        <li><i class="fas fa-bolt"></i> Fast, adaptive, and personalized</li>
      </ul>
    </div>
  </div>
  <div class="about-stats">
  <div class="stat-box">
    <h3>12,000+</h3>
    <p>Active Users</p>
  </div>
  <div class="stat-box">
    <h3>7-Day</h3>
    <p>Smart Plans</p>
  </div>
  <div class="stat-box">
    <h3>96%</h3>
    <p>Success Rate</p>
  </div>
  <div class="stat-box">
    <h3>100%</h3>
    <p>Personalization</p>
  </div>
</div>

</section>
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
        <li><a href="#">Start Your Analysis</a></li>
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


  <script src="aboutus.js"></script>
</body>
</html>
