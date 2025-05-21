<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Login</title>
  <style>
    * {
      box-sizing: border-box;
    }

    body {
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(to bottom right, #f2f9ff, #d9efff);
      margin: 0;
      padding: 0;
      display: flex;
      align-items: center;
      justify-content: center;
      min-height: 100vh;
    }

    form {
      background: white;
      padding: 30px 25px;
      border-radius: 12px;
      box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
      width: 100%;
      max-width: 350px;
    }

    h2 {
      text-align: center;
      margin-bottom: 25px;
      color: #00bfff;
    }

    input[type="text"],
    input[type="password"] {
      width: 100%;
      padding: 12px;
      margin-bottom: 15px;
      border: 1px solid #ccc;
      border-radius: 6px;
      font-size: 14px;
    }

    button {
      width: 100%;
      padding: 12px;
      background: #00bfff;
      color: white;
      border: none;
      border-radius: 6px;
      font-size: 16px;
      cursor: pointer;
      transition: background 0.3s;
    }

    button:hover {
      background: #0099cc;
    }

    p {
      text-align: center;
      font-size: 14px;
    }

    @media (max-width: 480px) {
      form {
        padding: 20px;
      }

      h2 {
        font-size: 20px;
      }

      input,
      button {
        font-size: 14px;
      }
    }
  </style>
</head>
<body>

<form method="POST">
  <h2>Admin Login</h2>
  <input type="text" name="username" placeholder="Username" required>
  <input type="password" name="password" placeholder="Password" required>
  <button type="submit" name="login">Login</button>

  <?php
  session_start();
  include 'config.php';

  if (isset($_POST['login'])) {
      $username = $_POST['username'];
      $password = $_POST['password'];

      $stmt = $conn->prepare("SELECT * FROM admin WHERE username=?");
      $stmt->bind_param("s", $username);
      $stmt->execute();
      $result = $stmt->get_result();

      if ($result->num_rows === 1) {
          $admin = $result->fetch_assoc();
          if (password_verify($password, $admin['password'])) {
              $_SESSION['admin_id'] = $admin['id'];
              header("Location: admin_dashboard.php");
              exit();
          } else {
              echo "<p style='color:red;'>Incorrect password!</p>";
          }
      } else {
          echo "<p style='color:red;'>Admin not found!</p>";
      }
  }
  ?>
</form>

</body>
</html>
