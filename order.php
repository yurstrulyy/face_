<?php   
session_start();
if (!isset($_SESSION['ulogin']) || !isset($_SESSION['fname'])) {
    header("Location: login.php");
    exit();
}
$name = $_SESSION['fname'];

// Handle redirect after message
if (isset($_GET['cleared']) && $_GET['cleared'] == 1) {
    unset($_SESSION['cart']);
    unset($_SESSION['paid']);
    echo "<script>alert('✅ Checkout complete! Thank you for your order.');</script>";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['checkout'])) {
        $_SESSION['paid'] = true;
        echo "<script>
            setTimeout(function() {
                window.location.href = 'order.php?cleared=1';
            }, 500);
        </script>";
        exit();
    } elseif (isset($_POST['clear_orders'])) {
        unset($_SESSION['cart']);
        unset($_SESSION['paid']);
    } elseif (isset($_POST['update_qty'])) {
        $index = $_POST['index'];
        $action = $_POST['action'];

        if (isset($_SESSION['cart'][$index])) {
            if ($action === 'plus') {
                $_SESSION['cart'][$index]['qty'] += 1;
            } elseif ($action === 'minus' && $_SESSION['cart'][$index]['qty'] > 1) {
                $_SESSION['cart'][$index]['qty'] -= 1;
            }
        }
    }
}

$cartItems = $_SESSION['cart'] ?? [];
$paid = $_SESSION['paid'] ?? false;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Orders</title>
  <style>
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
      margin: 5px 10px;
      font-size: 1rem;
    }
    nav a:hover {
      color: #00f0ff;
    }
    .main-content {
      padding: 40px;
      max-width: 800px;
      margin: 0 auto;
    }
    .order-card {
      background-color: #1c1c1c;
      border-radius: 12px;
      padding: 20px;
      margin-bottom: 20px;
      box-shadow: 0 0 10px rgba(255,255,255,0.05);
    }
    .order-card h3 {
      color: #00aaff;
    }
    .order-card p {
      color: #ccc;
    }
    .qty-controls {
      display: flex;
      align-items: center;
      margin-top: 10px;
    }
    .qty-controls form {
      margin: 0 5px;
    }
    .qty-btn {
      background-color: #333;
      color: white;
      padding: 6px 12px;
      border: none;
      border-radius: 6px;
      font-size: 1rem;
      cursor: pointer;
    }
    .checkout-btn, .clear-btn {
      background-color: #00aaff;
      color: white;
      padding: 12px 20px;
      border: none;
      border-radius: 8px;
      font-size: 1rem;
      cursor: pointer;
      margin-top: 20px;
      margin-right: 10px;
    }
    .clear-btn {
      background-color: #ff4d4d;
    }
    .paid-status {
      color: #00ff7f;
      font-weight: bold;
      font-size: 1.2rem;
      margin-top: 20px;
    }
  </style>
</head>
<body>
<header>
  <div class="logo">Your Orders</div>
  <nav>
    <a href="welcome.php">Home</a>
    <a href="dashboard.php">Dashboard</a>
    <a href="user.php">User</a>
    <a href="logout.php" style="background-color: #5a54ff; padding: 8px 16px; border-radius: 20px;">Sign Out</a>
  </nav>
</header>

<div class="main-content">
  <h1>Your Cart</h1>

  <?php if (!empty($cartItems)): ?>
    <?php foreach ($cartItems as $index => $item): ?>
      <div class="order-card">
        <h3><?= htmlspecialchars($item['name']) ?></h3>
        <p><?= htmlspecialchars($item['desc']) ?></p>
        <div class="qty-controls">
          <form method="POST">
            <input type="hidden" name="index" value="<?= $index ?>">
            <input type="hidden" name="action" value="minus">
            <button type="submit" name="update_qty" class="qty-btn">-</button>
          </form>
          <span>Qty: <?= $item['qty'] ?? 1 ?></span>
          <form method="POST">
            <input type="hidden" name="index" value="<?= $index ?>">
            <input type="hidden" name="action" value="plus">
            <button type="submit" name="update_qty" class="qty-btn">+</button>
          </form>
        </div>
      </div>
    <?php endforeach; ?>

    <?php if ($paid): ?>
      <div class="paid-status">✅ Payment Received. Order is marked as PAID.</div>
    <?php else: ?>
      <form method="POST">
        <button type="submit" name="checkout" class="checkout-btn">Checkout</button>
        <button type="submit" name="clear_orders" class="clear-btn">Clear Orders</button>
      </form>
    <?php endif; ?>
  <?php else: ?>
    <p>Your cart is empty.</p>
  <?php endif; ?>
</div>
</body>
</html>
