<?php
session_start();

require_once __DIR__ . '/../includes/db.php';


// ✅ Check login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
$user_id = $_SESSION['user_id'] ?? 0;

$product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
$quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;


$stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW())");
$stmt->bind_param("iii", $user_id, $product_id, $quantity);
$stmt->execute();


// ✅ Update quantity
if (isset($_POST['update_cart'])) {
    $cart_id = (int)$_POST['cart_id'];
    $new_qty = max(1, (int)$_POST['quantity']);

    $update = $conn->prepare("UPDATE cart SET quantity = ? WHERE id = ? AND user_id = ?");
    $update->bind_param("iii", $new_qty, $cart_id, $user_id);
    $update->execute();

    header("Location: cart.php");
    exit();
}

// ✅ Remove item
if (isset($_POST['remove_item'])) {
    $cart_id = (int)$_POST['cart_id'];

    $delete = $conn->prepare("DELETE FROM cart WHERE id = ? AND user_id = ?");
    $delete->bind_param("ii", $cart_id, $user_id);
    $delete->execute();

    header("Location: cart.php");
    exit();
}

// ✅ Fetch cart items
$sql = "SELECT c.id AS cart_id, c.product_id, c.quantity, p.name, p.price 
        FROM cart c 
        JOIN products p ON c.product_id = p.id 
        WHERE c.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$cart_items = $result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>My Cart</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family: Arial, sans-serif; background: #f5f5f5; margin: 0; padding: 0; }
        h2 { text-align: center; padding: 20px 0; color: #333; }
        table { border-collapse: collapse; width: 90%; margin: 20px auto; background: #fff; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        th, td { padding: 12px; border: 1px solid #ddd; text-align: center; }
        th { background-color: #007BFF; color: #fff; font-weight: bold; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        input[type=number] { width: 60px; padding: 5px; }
        button { padding: 6px 12px; margin: 2px; background-color: #007BFF; border: none; color: white; cursor: pointer; border-radius: 4px; }
        button:hover { background-color: #0056b3; }
        .actions { display: flex; justify-content: center; flex-wrap: wrap; gap: 5px; }
        .cart-buttons { text-align: center; margin: 20px 0; display: flex; justify-content: center; flex-wrap: wrap; gap: 10px; }
        .cart-buttons a { text-decoration: none; }
        .cart-buttons .btn { display: inline-block; padding: 12px 25px; border-radius: 5px; color: #fff; font-size: 16px; font-weight: bold; text-align: center; }
        .back-btn { background: #6c757d; }
        .back-btn:hover { background: #5a6268; }
        .checkout-btn { background: #28a745; }
        .checkout-btn:hover { background: #218838; }
        p.empty-cart { text-align: center; font-size: 18px; color: #555; margin-top: 40px; }
        @media screen and (max-width: 768px) {
            table, thead, tbody, th, td, tr { display: block; }
            th { position: absolute; top: -9999px; left: -9999px; }
            tr { margin: 0 0 20px 0; border: 1px solid #ccc; padding: 10px; }
            td { border: none; position: relative; padding-left: 50%; text-align: left; }
            td:before {
                position: absolute;
                top: 12px;
                left: 12px;
                width: 45%;
                white-space: nowrap;
                font-weight: bold;
            }
            td:nth-of-type(1):before { content: "Product"; }
            td:nth-of-type(2):before { content: "Price"; }
            td:nth-of-type(3):before { content: "Quantity"; }
            td:nth-of-type(4):before { content: "Subtotal"; }
            td:nth-of-type(5):before { content: "Actions"; }
        }
    </style>
</head>
<body>
    <h2>My Shopping Cart</h2>

    <?php if (empty($cart_items)): ?>
        <p class="empty-cart">Your cart is empty</p>
        <div class="cart-buttons">
            <a class="btn back-btn" href="../index.php">Back to Shop</a>
        </div>
    <?php else: ?>
        <form method="POST" action="cart.php">
            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php 
                $total = 0;
                foreach ($cart_items as $item):
                    $subtotal = $item['price'] * $item['quantity'];
                    $total += $subtotal;
                ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['name']); ?></td>
                    <td><?php echo number_format($item['price'], 2); ?></td>
                    <td>
                        <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1">
                        <input type="hidden" name="cart_id" value="<?php echo $item['cart_id']; ?>">
                    </td>
                    <td><?php echo number_format($subtotal, 2); ?></td>
                    <td class="actions">
                        <button type="submit" name="update_cart">Update</button>
                        <button type="submit" name="remove_item">Remove</button>
                    </td>
                </tr>
                <?php endforeach; ?>
                <tr>
                    <th colspan="3">Total</th>
                    <th colspan="2"><?php echo number_format($total, 2); ?></th>
                </tr>
                </tbody>
            </table>
        </form>
        <div class="cart-buttons">
            <a class="btn back-btn" href="../index.php">Back to Shop</a>
            <a class="btn checkout-btn" href="checkout.php">Proceed to Checkout</a>


        </div>
    <?php endif; ?>
</body>
</html>

