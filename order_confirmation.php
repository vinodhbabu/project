<?php
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Database connection details
$host = 'localhost';
$dbname = 'ecommerce';  
$username = 'root';  
$password = '';  

try {
    // Establish database connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Retrieve order details using order_id
    if (isset($_GET['order_id'])) {
        $order_id = $_GET['order_id'];

        // Get order summary
        $stmt = $pdo->prepare("SELECT o.order_id, o.total_amount, o.status, o.order_date, u.username as customer_name
                               FROM orders o
                               JOIN users u ON o.user_id = u.id
                               WHERE o.order_id = ?");
        $stmt->execute([$order_id]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$order) {
            echo "<h2>Order not found.</h2>";
            exit;
        }

        // Get order items
        $stmt = $pdo->prepare("SELECT p.name, p.price, oi.quantity
                               FROM order_items oi
                               JOIN products p ON oi.product_id = p.id
                               WHERE oi.order_id = ?");
        $stmt->execute([$order_id]);
        $order_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        echo "<h2>No order ID provided.</h2>";
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
    <title>Order Confirmation - Vehicle Sales</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f4f9;
            color: #333;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #007bff;
            color: white;
            padding: 20px;
            text-align: center;
            font-size: 28px;
            font-weight: bold;
        }

        .confirmation-container {
            width: 80%;
            margin: 20px auto;
            padding: 25px;
            background-color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            transform: scale(1);
            animation: slideIn 0.5s ease-in-out;
        }

        .order-details, .order-items {
            margin-top: 20px;
        }

        .order-items table {
            width: 100%;
            border-collapse: collapse;
        }

        .order-items th, .order-items td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .order-items th {
            background-color: #f7f7f7;
        }

        .thank-you {
            text-align: center;
            font-size: 22px;
            margin-top: 30px;
            font-weight: bold;
            color: #28a745;
        }

        .buttons {
            display: flex;
            justify-content: center;
            margin-top: 30px;
        }

        .buttons button {
            background-color: #007bff;
            color: white;
            font-size: 16px;
            border: none;
            padding: 12px 20px;
            margin: 5px;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .buttons button:hover {
            background-color: #0056b3;
        }

        .buttons .print-btn {
            background-color: #28a745;
        }

        .buttons .print-btn:hover {
            background-color: #218838;
        }

        @keyframes slideIn {
            0% {
                transform: scale(0);
                opacity: 0;
            }
            100% {
                transform: scale(1);
                opacity: 1;
            }
        }
    </style>
</head>
<body>

<header>
    Vehicle Sales Order Confirmation
</header>

<div class="confirmation-container">
    <div class="thank-you">
        Thank you for your order, <?php echo htmlspecialchars($order['customer_name']); ?>!
    </div>

    <div class="order-details">
        <h3>Order Summary</h3>
        <p><strong>Order ID:</strong> <?php echo htmlspecialchars($order['order_id']); ?></p>
        <p><strong>Total Price:</strong> ₹<?php echo number_format($order['total_amount'], 2); ?></p>
        <p><strong>Status:</strong> <?php echo htmlspecialchars($order['status']); ?></p>
        <p><strong>Order Date:</strong> <?php echo htmlspecialchars($order['order_date']); ?></p>
    </div>

    <div class="order-items">
        <h3>Items in Your Order</h3>
        <table>
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($order_items as $item): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['name']); ?></td>
                        <td>₹<?php echo number_format($item['price'], 2); ?></td>
                        <td><?php echo $item['quantity']; ?></td>
                        <td>₹<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="buttons">
        <button onclick="window.location.href='customer_dashboard.php'">Go to Dashboard</button>
        <button onclick="window.location.href='shop_products.php'">Continue Shopping</button>
        <button class="print-btn" onclick="window.print()">Print Receipt</button>
    </div>
</div>

</body>
</html>

