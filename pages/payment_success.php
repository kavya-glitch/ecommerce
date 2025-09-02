<?php
$order_id = isset($_GET['order_id']) ? $_GET['order_id'] : '';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Payment Success</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f2f2f2;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background: #fff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.2);
            text-align: center;
            width: 400px;
        }
        h2 {
            color: #4CAF50;
            margin-bottom: 20px;
        }
        p {
            font-size: 18px;
            margin-bottom: 30px;
        }
        a {
            text-decoration: none;
            background: #4CAF50;
            color: #fff;
            padding: 12px 25px;
            border-radius: 8px;
            transition: background 0.3s;
        }
        a:hover {
            background: #45a049;
        }
        .order-id {
            font-weight: bold;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Payment Successful!</h2>
        <p>Your order has been placed successfully.</p>
        <?php if($order_id): ?>
            <p>Your Order ID: <span class="order-id"><?= htmlspecialchars($order_id) ?></span></p>
        <?php endif; ?>
        <a href="../index.php">Go to Home</a>
    </div>
</body>
</html>

