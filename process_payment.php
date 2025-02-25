<?php
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Database connection details
$host = 'localhost';
$dbname = 'ecommerce';
$username = 'root';
$password = '';

try {
    // Establish database connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Retrieve cart items for the user
    $cart_stmt = $pdo->prepare("SELECT c.product_id, c.quantity, p.price 
                                FROM cart c
                                JOIN products p ON c.product_id = p.id
                                WHERE c.user_id = ?");
    $cart_stmt->execute([$user_id]);
    $cart_items = $cart_stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($cart_items)) {
        echo "Your cart is empty.";
        exit;
    }

    // Calculate total price
    $total_price = 0;
    foreach ($cart_items as $item) {
        $total_price += $item['quantity'] * $item['price'];
    }

    // Generate a unique order ID
    $order_id = uniqid("ORDER_");

    // Insert order details into the orders table
    $order_stmt = $pdo->prepare("INSERT INTO orders (order_id, user_id, total_amount, order_date,status) 
                                 VALUES (?, ?, ?, NOW(),'succes')");
    $order_stmt->execute([$order_id, $user_id, $total_price]);

    // Insert order items into the order_items table
    $order_items_stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) 
                                       VALUES (?, ?, ?, ?)");
    foreach ($cart_items as $item) {
        $order_items_stmt->execute([
            $order_id,
            $item['product_id'],
            $item['quantity'],
            $item['price']
        ]);
    }

    // Insert payment record into the payments table
    $payment_stmt = $pdo->prepare("INSERT INTO payments (order_id, amount, payment_method, status) 
                                   VALUES (?, ?, 'UPI', 'Success')");
    $payment_stmt->execute([$order_id, $total_price]);

    // Clear the user's cart after successful payment
    $clear_cart_stmt = $pdo->prepare("DELETE FROM cart WHERE user_id = ?");
    $clear_cart_stmt->execute([$user_id]);

    // Redirect to the order confirmation page
    header("Location: order_confirmation.php?order_id=" . $order_id);
    exit();
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>

