<?php
// Start session (if user login is involved)
session_start();

// Database connection
$host = 'localhost';
$dbname = 'ecommerce_db';
$username = 'root';  // Your MySQL username
$password = '';  // Your MySQL password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

// Retrieve the user's orders
$query = "SELECT * FROM orders";
$stmt = $pdo->prepare($query);
$stmt->execute();
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders</title>
    <style>
        /* Styling for the page */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        .order-container {
            max-width: 800px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .order {
            border-bottom: 1px solid #ddd;
            padding: 10px 0;
        }
        .order:last-child {
            border-bottom: none;
        }
        .order h3 {
            margin: 0;
            font-size: 20px;
        }
        .order p {
            margin: 5px 0;
            color: #555;
        }
        .order img {
            max-width: 100px;
            display: block;
            margin-bottom: 10px;
        }
        .no-orders {
            text-align: center;
            color: #888;
        }
        .cancel-btn {
            background-color: #ff4c4c;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
        }
        .cancel-btn:hover {
            background-color: #ff0000;
        }
    </style>
</head>
<body>

<h1>My Orders</h1>

<div class="order-container">
    <?php if (count($orders) > 0): ?>
        <?php foreach ($orders as $order): ?>
            <div class="order">
                <h3>Product: <?php echo htmlspecialchars($order['product_name']); ?></h3>
                <p><strong>Price:</strong> ₹<?php echo htmlspecialchars($order['product_price']); ?></p>
                <p><strong>Order Date:</strong> <?php echo htmlspecialchars($order['order_date']); ?></p>
                <p><strong>Address:</strong> <?php echo htmlspecialchars($order['village'] . ', ' . $order['mandel'] . ', ' . $order['district'] . ', ' . $order['state'] . ', ' . $order['pincode']); ?></p>
                <p><strong>Phone:</strong> <?php echo htmlspecialchars($order['phone']); ?></p>
                <?php if ($order['product_image']): ?>
                    <img src="<?php echo htmlspecialchars($order['product_image']); ?>" alt="Product Image">
                <?php endif; ?>

                <!-- Cancel button form -->
                <button class="cancel-btn" data-order-id="<?php echo $order['order_id']; ?>">Cancel Order</button>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p class="no-orders">You have no orders yet.</p>
    <?php endif; ?>
</div>

<script>
document.addEventListener('click', function (event) {
    if (event.target.classList.contains('cancel-btn')) {
        const orderId = event.target.getAttribute('data-order-id');

        if (confirm('Are you sure you want to cancel this order?')) {
            fetch('delete_product.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ order_id: orderId })
            })
            .then(response => response.text())
            .then(result => {
                alert(result);
                // Reload the page to reflect changes
                window.location.reload();
            })
            .catch(error => console.error('Error:', error));
        }
    }
});
</script>

</body>
</html>

