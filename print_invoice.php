<?php
session_start();

// Database connection
$host = 'localhost';
$dbname = 'ecommerce';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit();
}

// Get the order ID from the URL parameter
if (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];

    // Fetch the specific order details from the payments table
    $sql = "SELECT * FROM payments WHERE order_id = :order_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
    $stmt->execute();

    $order = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$order) {
        echo "Order not found.";
        exit();
    }
} else {
    echo "Invalid order ID.";
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - Order ID: <?php echo htmlspecialchars($order['order_id']); ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background-color: #f8f9fa;
            margin: 0;
            box-sizing: border-box;
            background: url('bgadm.png') no-repeat center center fixed;
    background-size: cover;
        }

        h1 {
            font-size: 2.5em;
            text-align: center;
            color: #333;
        }

        .invoice-details {
            margin-top: 20px;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .invoice-details p {
            font-size: 1.2em;
            margin: 5px 0;
            color: #555;
        }

        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
            transition: background-color 0.3s ease;
        }

        .button:hover {
            background-color: #45a049;
        }

        .admin-actions {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
        }

        .print-button {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
            transition: background-color 0.3s ease, transform 0.2s;
        }

        .print-button:hover {
            background-color: #45a049;
            transform: scale(1.05);
        }
    </style>
    <script>
        window.onload = function() {
            window.print();
        };
    </script>
</head>
<body>
    <h1>Invoice for Order ID: <?php echo htmlspecialchars($order['order_id']); ?></h1>

    <div class="invoice-details">
        <p><strong>Payment ID:</strong> <?php echo htmlspecialchars($order['id']); ?></p>
        <p><strong>Order ID:</strong> <?php echo htmlspecialchars($order['order_id']); ?></p>
        <p><strong>Payment Date:</strong> <?php echo htmlspecialchars($order['payment_date']); ?></p>
        <p><strong>Amount:</strong> <?php echo htmlspecialchars($order['amount']); ?></p>
        <p><strong>Payment Method:</strong> <?php echo htmlspecialchars($order['payment_method']); ?></p>
        <p><strong>Status:</strong> <?php echo htmlspecialchars($order['status']); ?></p>
    </div>

    <a href="#" class="print-button" onclick="window.print();">Print Invoice</a>

    <div class="admin-actions">
        <a href="admin_dashboard.php" class="button">Back to Dashboard</a>
        <a href="logout.php" class="button logout">Logout</a>
    </div>
</body>
</html>

<?php
// Close the database connection
$pdo = null;
?>
