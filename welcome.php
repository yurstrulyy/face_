<?php
session_start();
if (!isset($_SESSION['ulogin']) || !isset($_SESSION['fname'])) {
    header("Location: login.php");
    exit();
}
$name = $_SESSION['fname'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Welcome</title>
  <style>
    * {
      box-sizing: border-box;
    }
    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background-color: #000;
      color: #fff;
    }
    header {
      background-color: #111;
      padding: 20px 40px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .logo {
      font-weight: bold;
      font-size: 1.4rem;
    }
    nav a {
      color: #fff;
      text-decoration: none;
      margin: 0 15px;
    }
    nav a:hover {
      color: #00f0ff;
    }
    .landing {
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      text-align: center;
      padding: 80px 20px;
    }
    .landing h1 {
      font-size: 4rem;
      margin-bottom: 20px;
    }
    .landing p {
      font-size: 1.2rem;
      color: #bbb;
      max-width: 600px;
    }
    .button-group {
      margin-top: 40px;
    }
    .button-group a {
      padding: 14px 28px;
      margin: 0 10px;
      background-color: #fff;
      color: #000;
      text-decoration: none;
      font-weight: bold;
      border-radius: 30px;
      transition: background 0.3s ease;
    }
    .button-group a:hover {
      background-color: #ccc;
    }
    .glow {
      position: absolute;
      top: 50%;
      left: 70%;
      width: 300px;
      height: 300px;
      background: radial-gradient(circle, #a500ff, transparent 60%);
      border-radius: 50%;
      filter: blur(80px);
      opacity: 0.4;
      z-index: -1;
    }
  </style>
</head>
<body>
  <header>
    <div class="logo">PuYaT</div>
    <nav>
      <a href="#">Home</a>
      <a href="about.php">About</a>
      <a href="services.php">Services</a>
      <a href="contact.php">Contact</a>
      <a href="logout.php" style="background-color: #5a54ff; padding: 8px 16px; border-radius: 20px;">Sign Out</a>
    </nav>
  </header>

  <div class="landing">
    <h1>Welcome, <?php echo htmlspecialchars($name); ?>!</h1>
    <p>Your email has been successfully verified. Enjoy exploring the site or get started with your dashboard right away.</p>
    <div class="button-group">
      <a href="dashboard.php">Go to Dashboard</a>
      <a href="#" onclick="alert('More content coming soon!')">Learn More</a>
    </div>
  </div>

  <div class="glow"></div>
</body>
</html>
