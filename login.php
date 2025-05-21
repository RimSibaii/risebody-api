<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login - RiseBody</title>
  <link rel="stylesheet" href="signup.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
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
      <h2>Welcome Back</h2>
<form action="login_process.php" method="POST">
  <?php if (isset($_GET['error']) && $_GET['error'] == 'notfound'): ?>
    <p style="color: red; font-size: 14px;">Account not found. Please <a href='signup.php' style='color: #00bfff;'>sign up first</a>.</p>
  <?php endif; ?>
  <input type="email" name="email" placeholder="Email" required style="position: relative; z-index: 1;">
  <input type="password" name="password" id="password" placeholder="Password" required style="position: relative; z-index: 1;">
  <div style="display: flex; justify-content: space-between; align-items: center; font-size: 14px; gap: 10px; margin: 12px 0 10px;">
  <label><input type="checkbox" onclick="togglePassword()"> Show Password</label>
  <a href="forgotpassword.php">
  <button type="button">Forgot Password?</button>
</a>

</div>
  <button type="submit" style="margin-top: 10px; margin-bottom: 10px;">Login</button>
</form>
      <p>Don't have an account? <a href="signup.php">Sign up here</a></p>
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
  <script>
    function togglePassword() {
      const pwd = document.getElementById('password');
      pwd.type = pwd.type === 'password' ? 'text' : 'password';
    }
  </script>
</body>
</html>
