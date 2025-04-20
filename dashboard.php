<?php
session_start();
if (!isset($_SESSION['ulogin']) || !isset($_SESSION['fname'])) {
    header("Location: login.php");
    exit();
}
$name = $_SESSION['fname'];

// Initialize cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle Add to Cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_name'], $_POST['product_desc'])) {
    $productName = $_POST['product_name'];
    $productDesc = $_POST['product_desc'];

    $found = false;

    // Check if product already exists in cart
    foreach ($_SESSION['cart'] as $index => $item) {
        if ($item['name'] === $productName) {
            $_SESSION['cart'][$index]['qty'] += 1;
            $found = true;
            break;
        }
    }

    // If not found, add new product with qty 1
    if (!$found) {
        $_SESSION['cart'][] = [
            'name' => $productName,
            'desc' => $productDesc,
            'qty' => 1
        ];
    }

    // Redirect to avoid form resubmission
    header("Location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Product Dashboard</title>
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
      flex-wrap: wrap;
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
      padding: 60px 20px;
      text-align: center;
    }
    .main-content h1 {
      font-size: 2.5rem;
      margin-bottom: 20px;
    }
    .products {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 30px;
      margin-top: 40px;
    }
    .product-card {
      background-color: #1c1c1c;
      border-radius: 12px;
      padding: 20px;
      width: 280px;
      box-shadow: 0 0 10px rgba(255,255,255,0.05);
      transition: transform 0.2s ease;
    }
    .product-card:hover {
      transform: translateY(-5px);
    }
    .product-card img {
      width: 100%;
      height: 160px;
      object-fit: cover;
      border-radius: 8px;
      margin-bottom: 15px;
    }
    .product-card h3 {
      color: #00aaff;
      margin-bottom: 10px;
    }
    .product-card p {
      color: #ccc;
      font-size: 0.95rem;
    }
    .product-card form button {
      margin-top: 10px;
      padding: 8px 12px;
      background-color: #00aaff;
      color: #fff;
      border: none;
      border-radius: 6px;
      cursor: pointer;
    }
  </style>
</head>
<body>
  <header>
    <div class="logo">Product Dashboard</div>
    <nav>
      <a href="welcome.php">Home</a>
      <a href="order.php">Orders (<?php echo count($_SESSION['cart']); ?>)</a>
      <a href="user.php">User</a>
      <a href="logout.php" style="background-color: #5a54ff; padding: 8px 16px; border-radius: 20px;">Sign Out</a>
    </nav>
  </header>

  <div class="main-content">
    <h1>Welcome to your Product Dashboard, <?php echo htmlspecialchars($name); ?>!</h1>
    <div class="products">
      <div class="product-card">
        <img src="images/rare.png" alt="Rare Beauty">
        <h3>Rare Beauty</h3>
        <p>A luxurious, anti-aging serum with botanical extracts and peptides that smooths fine lines and restores youthful radiance.</p>
        <form method="post">
          <input type="hidden" name="product_name" value="Rare Beauty">
          <input type="hidden" name="product_desc" value="A luxurious, anti-aging serum with botanical extracts and peptides.">
          <button type="submit">Add to Cart</button>
        </form>
      </div>
      <div class="product-card">
        <img src="images/skin.png" alt="Rhode">
        <h3>Rhode</h3>
        <p>A natural, everyday moisturizer with aloe and green tea, designed to nourish and hydrate without harsh chemicals.</p>
        <form method="post">
          <input type="hidden" name="product_name" value="Rhode">
          <input type="hidden" name="product_desc" value="A natural, everyday moisturizer with aloe and green tea.">
          <button type="submit">Add to Cart</button>
        </form>
      </div>
      <div class="product-card">
        <img src="images/white.png" alt="Ordinary">
        <h3>Ordinary</h3>
        <p>A minimalist, fragrance-free formula with ceramides and niacinamide, perfect for sensitive skin needing hydration and barrier support.</p>
        <form method="post">
          <input type="hidden" name="product_name" value="Ordinary">
          <input type="hidden" name="product_desc" value="A minimalist, fragrance-free formula with ceramides and niacinamide.">
          <button type="submit">Add to Cart</button>
        </form>
      </div>
    </div>
  </div>
</body>
</html>
