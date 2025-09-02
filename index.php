<?php
session_start();
require_once __DIR__ . '/includes/db.php';



// ✅ Fetch products
$sql = "SELECT * FROM products";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>WELCOME TO OUR STORE</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f9f9f9;
      margin: 0;
      padding: 0;
    }
    .navbar {
    background: linear-gradient(90deg, #6a11cb, #2575fc); /* Purple → Blue gradient */
    padding: 15px 30px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
  }
    .navbar h1 {
      color: white;
      margin: 0;
    }
    .nav-links a {
      color: white;
      margin-left: 20px;
      text-decoration: none;
      font-weight: bold;
    }
    .container {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
      gap: 20px;
      padding: 20px;
    }
    .product {
      background: #fff;
      border-radius: 10px;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
      padding: 15px;
      text-align: center;
    }
    .product img {
      max-width: 100%;
      height: 180px;
      object-fit: cover;
      border-radius: 8px;
    }
    .product h3 { margin: 10px 0; }
    .product p { color: #333; font-weight: bold; }
    .btn {
      background: #28a745;
      color: white;
      padding: 8px 16px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      text-decoration: none;
    }
    .btn:hover { background: #218838; }
  </style>
</head>
<body>

  <!-- ✅ Navbar -->
  <div class="navbar">
    <h1>🛍WELCOME TO OUR STORE</h1>
    <div class="nav-links">
      <a href="pages/login.php">Login</a>
      <a href="pages/register.php">Register</a>
      <a href="pages/cart.php">Cart</a>
      <a href="pages/logout.php">Logout</a>
    </div>
  </div>

  <!-- ✅ Products -->
  <div class="container">
    <?php while ($row = mysqli_fetch_assoc($result)): ?>
      <div class="product">
        <img src="images/<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['name']) ?>">
        <h3><?= htmlspecialchars($row['name']) ?></h3>
        <p>₹<?= number_format($row['price'], 2) ?></p>
        <form method="POST" action="pages/cart.php">
          <input type="hidden" name="product_id" value="<?= $row['id'] ?>">
          <button type="submit" name="add_to_cart" class="btn">Add to Cart</button>
        </form>
      </div>
    <?php endwhile; ?>
  </div>
   <footer>
        <p><center>&copy;<?= date('Y'); ?>  Online Store. All rights reserved.</center></p>
    </footer>
</body>
</html>
