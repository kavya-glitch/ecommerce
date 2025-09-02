<?php
session_start(); // start session to access cart

require_once __DIR__ . '/../includes/db.php';

// include your database connection
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;

// Fetch cart items for this user
$stmt = $conn->prepare("SELECT c.quantity, p.name, p.price 
                        FROM cart c 
                        JOIN products p ON c.product_id = p.id 
                        WHERE c.user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$cart_items = $result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Checkout</title>
</head>
 <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            padding: 20px;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        th, td {
            padding: 12px 15px;
            border: 1px solid #ddd;
            text-align: center;
        }
        th {
            background-color: #007BFF;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #e0e0e0;
        }
        .total-row td {
            font-weight: bold;
            background-color: #f9f9f9;
        }
        button {
            display: block;
            margin: 20px auto;
            padding: 12px 25px;
            background-color: #28a745;
            color: white;
            border: none;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
            transition: 0.3s;
        }
        button:hover {
            background-color: #218838;
        }
        p.empty-cart {
            text-align: center;
            font-size: 18px;
            color: #555;
        }
    </style>
<body>
    <h1>Checkout</h1>
    <?php if(!empty($cart_items)) { ?>
        <table boarder="1" cellpadding="10">
            <tr>
                <th>Product Name</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Subtotal</th>
            </tr>
            <?php 
            $total = 0;
            foreach($cart_items as $item) { 
                $subtotal = $item['price'] * $item['quantity'];
                $total += $subtotal;
            ?>
            <tr>
                <td><?php echo $item['name']; ?></td>
                <td><?php echo $item['price']; ?></td>
                <td><?php echo $item['quantity']; ?></td>
                <td><?php echo $subtotal; ?></td>
            </tr>
            <?php } ?>
            <tr>
                <td colspan="3"><strong>Total</strong></td>
                <td><strong><?php echo $total; ?></strong></td>
            </tr>
        </table>
        <br>
<form action="payment.php" method="POST">
   

    <button type="submit">Proceed to Payment</button>
</form>

    <?php } else { ?>
        <p>Your cart is empty!</p>
    <?php } ?>
</body>
</html>
