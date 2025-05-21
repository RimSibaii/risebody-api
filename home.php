<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>AI Fitness - Home</title>
  <link rel="stylesheet" href="home.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

</head>
<body>
<?php include 'preloader.php'; ?>


  <!-- NAVIGATION BAR -->
  <header>
    <div class="logo">RiseBody</div>
    <nav>
      <ul class="nav-links">
        <li><a href="home.php">Home</a></li>
        <li><a href="#how-it-works">How it Works</a></li>
        <li><a href="signup.php">Start Your Analysis</a></li>
        <li><a href="aboutus.php">About Us</a></li>
      </ul>
    </nav>
    <div class="cta-button">
  <a href="signup.php" class="btn-primary"><i class="fas fa-user-plus"></i> Join Now</a>
  <a href="login.php" class="btn-primary"><i class="fas fa-sign-in-alt"></i> Login</a>
</div>

  </header>

  <!-- HERO SLIDER SECTION -->
  <section class="slider">
    <div class="slides">
      <div class="slide active" style="background-image: url('images/photo1.jpg')">
        <div class="overlay">
          <h2>Looking To Lose Weight?</h2>
          <a class="analyze-btn" href="#">Start Your AI Analysis</a>
        </div>
      </div>
      <div class="slide" style="background-image: url('images/photo2.jpg')">
        <div class="overlay">
          <h2>Want To Build Muscles?</h2>
          <a class="analyze-btn" href="#">Start Your AI Analysis</a>
        </div>
      </div>
      <div class="slide" style="background-image: url('images/photo3.jpg')">
        <div class="overlay">
          <h2>Your Body. Your Program.</h2>
          <a class="analyze-btn" href="#">Start Your AI Analysis</a>
        </div>
      </div>
      <div class="slide" style="background-image: url('images/photo4.jpg')">
        <div class="overlay">
          <h2>You. AI. Results.</h2>
          <a class="analyze-btn" href="#">Start Your AI Analysis</a>
        </div>
      </div>
    </div>
  </section>

  <section id="how-it-works" class="how-it-works">
  <div class="how-container">
    <div class="how-text">
      <h2>How It Works</h2>
      <ul>
  <li><strong>Step 1:</strong> Create your account and set your fitness goals.</li>
  <li><strong>Step 2:</strong> Start your AI assessment — answer quick body profile questions.</li>
  <li><strong>Step 3:</strong> Our smart AI calculates your needs and builds a personalized plan.</li>
  <li><strong>Step 4:</strong> Instantly receive a 7-day meal & customized workout plan tailored to your body.</li>
  <li><strong>Step 5:</strong> Save it as a PDF, apply it, and track your transformation.</li>
</ul>

    </div>

    <div class="how-image-row">
      <div class="img-wrapper">
        <img src="images/how1.jpg" alt="AI fitness analysis" />
      </div>
      <div class="img-wrapper">
        <img src="images/how2.jpg" alt="Personalized plan preview" />
      </div>
    </div>
  </div>
</section>

<section id="testimonials" class="testimonials">
  <h2>Success Stories</h2>
  <p class="section-subtitle">Real people. Real results.</p>

  <div class="testimonial-cards">
    <?php
      include 'config.php';

      // Fetch testimonials with images
      $stmt = $conn->prepare("SELECT * FROM testimonials ORDER BY created_at DESC LIMIT 6");
      $stmt->execute();
      $result = $stmt->get_result();

      while ($testimonial = $result->fetch_assoc()):
    ?>
      <div class="testimonial-card">
        <img src="<?= htmlspecialchars($testimonial['image_path']) ?>" alt="Before and After - <?= htmlspecialchars($testimonial['user_name']) ?>" />
        <p>"<?= htmlspecialchars($testimonial['testimonial_text']) ?>"</p>
        <h4>- <?= htmlspecialchars($testimonial['user_name']) ?></h4>
      </div>
    <?php endwhile; ?>
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


  <script src="home.js"></script>
</body>
</html>
