<?php
session_start();

require_once __DIR__ . '/../includes/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch cart items
$stmt = $conn->prepare("SELECT c.product_id, p.name, p.price, c.quantity 
                        FROM cart c 
                        JOIN products p ON c.product_id = p.id 
                        WHERE c.user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$cart_items = [];
$total = 0;

while ($row = $result->fetch_assoc()) {
    $cart_items[] = $row;
    $total += $row['price'] * $row['quantity'];
}

// Handle payment
if (isset($_POST['pay_now'])) {
    // 1️⃣ Insert into orders table
    $stmt = $conn->prepare("INSERT INTO orders (user_id, total_amount, status, created_at) VALUES (?, ?, ?, NOW())");
    if (!$stmt) {
        die("Prepare failed (orders): " . $conn->error);
    }

    $status = 'Paid';
    $stmt->bind_param("ids", $user_id, $total, $status);
    $stmt->execute();
    $order_id = $stmt->insert_id;

    // 2️⃣ Insert each cart item into order_items
    foreach ($cart_items as $item) {
        $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
        if (!$stmt) {
            die("Prepare failed (order_items): " . $conn->error);
        }
        $stmt->bind_param("iiid", $order_id, $item['product_id'], $item['quantity'], $item['price']);
        $stmt->execute();
    }

    // 3️⃣ Clear the cart
    $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
    if (!$stmt) {
        die("Prepare failed (clear cart): " . $conn->error);
    }
    $stmt->bind_param("i", $user_id);
    $stmt->execute();

    // 4️⃣ Redirect to success page
    header("Location: payment_success.php?order_id=" . $order_id);
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Payment</title>
    <style>
        body { font-family: Arial; background: #f2f2f2; }
        .container { width: 500px; margin: 50px auto; background: #fff; padding: 20px; border-radius: 10px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { padding: 10px; border-bottom: 1px solid #ddd; text-align: left; }
        .total { text-align: right; font-weight: bold; }
        button { padding: 10px 20px; background: green; color: white; border: none; border-radius: 5px; cursor: pointer; }
        button:hover { background: darkgreen; }
    </style>
</head>
<body>
<div class="container">
    <h2>Payment Summary</h2>
    <table>
        <tr>
            <th>Product</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Subtotal</th>
        </tr>
        <?php foreach ($cart_items as $item): ?>
            <tr>
                <td><?= htmlspecialchars($item['name']) ?></td>
                <td><?= number_format($item['price'], 2) ?></td>
                <td><?= $item['quantity'] ?></td>
                <td><?= number_format($item['price'] * $item['quantity'], 2) ?></td>
            </tr>
        <?php endforeach; ?>
        <tr>
            <td colspan="3" class="total">Total:</td>
            <td><?= number_format($total, 2) ?></td>
        </tr>
    </table>
     
    <form method="POST">
        <button type="submit" name="pay_now">Pay Now</button>
    </form>
</div>
</body>
</html>
