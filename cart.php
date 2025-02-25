<?php
session_start();

$host = 'localhost';
$dbname = 'ecommerce';  // Your database name
$username = 'root';  // Your MySQL username
$password = '';  // Your MySQL password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if the user is logged in
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit;
    }

    $user_id = $_SESSION['user_id'];

    // Fetch cart items including product image and rating
    $stmt = $pdo->prepare("SELECT c.id AS cart_id, p.name, p.price, c.quantity, p.image_url, p.rating
                           FROM cart c
                           JOIN products p ON c.product_id = p.id
                           WHERE c.user_id = ?");
    $stmt->execute([$user_id]);
    $cart_items = $stmt->fetchAll();

    // Handle delete functionality
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_id'])) {
        $cart_id = $_POST['delete_id'];
        $delete_stmt = $pdo->prepare("DELETE FROM cart WHERE id = ? AND user_id = ?");
        $delete_stmt->execute([$cart_id, $user_id]);
        header("Location: cart.php"); // Refresh the page to show updated cart
        exit;
    }

} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color:#edb7a8;
 
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #24110b;
            color: white;
            padding: 20px;
            text-align: center;
            font-size: 24px;
            font-weight: 600;
        }

        .cart-container {
            width: 90%;
            margin: 30px auto;
        }

        .cart-item {
            background-color: white;
            padding: 15px;
            margin: 10px 0;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .cart-item img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 8px;
            margin-right: 20px;
        }

        .cart-item .item-details {
            flex: 1;
        }

        .cart-item h3 {
            margin: 0;
            color: #333;
        }

        .cart-item p {
            margin: 5px 0;
            color: #666;
        }

        .cart-item .rating {
            font-size: 14px;
            color: #000000;
        }

        .cart-item .delete-btn {
            background-color: #f44336;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        .cart-item .delete-btn:hover {
            background-color: #486978;
        }

        .total-price {
            font-size: 20px;
            font-weight: bold;
            margin-top: 20px;
            text-align: right;
        }

        .checkout-btn {
            background-color: #28a745;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            float: right;
            font-size: 16px;
            margin-top: 10px;
            transition: background-color 0.3s ease;
        }

        .checkout-btn:hover {
            background-color: #218838;
        }

        .back-to-dashboard-btn {
            background-color: #0072ff;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            display: inline-block;
            text-align: center;
            font-size: 16px;
            margin: 20px auto;
            transition: background-color 0.3s ease;
        }

        .back-to-dashboard-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<header>
    Your Cart
</header>

<div class="cart-container">
    <?php if (empty($cart_items)): ?>
        <p>Your cart is empty. <a href="shop_products.php">Continue Shopping</a></p>
    <?php else: ?>
        <?php foreach ($cart_items as $item): ?>
        <div class="cart-item">
            <img src="<?php echo htmlspecialchars($item['image_url']); ?>" alt="Product Image">
            <div class="item-details">
                <h3><?php echo htmlspecialchars($item['name']); ?></h3>
                <p>Price: ₹<?php echo number_format($item['price'], 2); ?></p>
                <p>Quantity: <?php echo $item['quantity']; ?></p>
                <p>Total: ₹ <?php echo number_format($item['price'] * $item['quantity'], 2); ?></p>
                <p class="rating">Rating: <?php echo htmlspecialchars($item['rating']); ?> / 5</p>
            </div>
            <form method="POST" style="margin: 0;">
                <input type="hidden" name="delete_id" value="<?php echo $item['cart_id']; ?>">
                <button type="submit" class="delete-btn">Remove</button>
            </form>
        </div>
        <?php endforeach; ?>

        <div class="total-price">
            <?php
            $total_price = 0;
            foreach ($cart_items as $item) {
                $total_price += $item['price'] * $item['quantity'];
            }
            ?>
            <p>Total: ₹<?php echo number_format($total_price, 2); ?></p>
            <a href="payment.php"><button class="checkout-btn">Proceed to Checkout</button></a>
        </div>
    <?php endif; ?>
</div>

<div>
    <a href="customer_dashboard.php">
        <button class="back-to-dashboard-btn">Back to Dashboard</button>
    </a>
    <a href="shop_products.php">
        <button class="back-to-dashboard-btn">Continue Shopping</button>
    </a>
    <a href="what.html">
        <button class="back-to-dashboard-btn">Contact on WhatsApp</button>
    </a>
</div>

</body>
</html>
