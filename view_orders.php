<?php
// Start session
session_start();

// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Database connection
$host = 'localhost';
$dbname = 'ecommerce';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch all details from payments table
    $sql = "SELECT id, order_id, payment_date, amount, payment_method, status FROM payments";
    $stmt = $pdo->query($sql);
    $payments = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Details</title>
    <style>
        /* Keyframe animation */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* General body styling */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            animation: fadeIn 1s ease-in-out;
            background: linear-gradient(120deg, #84fab0, #8fd3f4);
            color: #333;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        /* Container styling */
        .container {
            width: 90%;
            max-width: 900px;
            background: #fff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        /* Header */
        h1 {
            text-align: center;
            font-size: 2rem;
            margin-bottom: 20px;
            color: #007bff;
        }

        /* Table styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background: #007bff;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
            transition: 0.3s;
        }

        /* Buttons */
        .buttons {
            display: flex;
            justify-content: space-between;
            margin: 20px 0;
        }

        .button {
            display: inline-block;
            padding: 10px 20px;
            font-size: 1rem;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
            transition: 0.3s;
            cursor: pointer;
        }

        .button-dashboard {
            background: #007bff;
            color: white;
        }

        .button-dashboard:hover {
            background: #0056b3;
        }

        .button-shopping {
            background: #28a745;
            color: white;
        }

        .button-shopping:hover {
            background: #218838;
        }

        .no-payments {
            text-align: center;
            font-size: 1.2rem;
            color: #777;
            margin: 20px 0;
        }

        /* Footer */
        footer {
            text-align: center;
            margin-top: 30px;
            font-size: 0.9rem;
            color: #555;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Order & Payment Details</h1>

        <div class="buttons">
            <a href="customer_dashboard.php" class="button button-dashboard">Go to Dashboard</a>
            <a href="shop_products.php" class="button button-shopping">Go to Shopping</a>
        </div>

        <?php if (empty($payments)): ?>
            <div class="no-payments">No payment records available.</div>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Payment ID</th>
                        <th>Order ID</th>
                        <th>Date</th>
                        <th>Amount</th>
                        <th>Method</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($payments as $payment): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($payment['id']); ?></td>
                            <td><?php echo htmlspecialchars($payment['order_id']); ?></td>
                            <td><?php echo htmlspecialchars($payment['payment_date']); ?></td>
                            <td>₹<?php echo number_format($payment['amount'], 2); ?></td>
                            <td><?php echo htmlspecialchars($payment['payment_method']); ?></td>
                            <td><?php echo htmlspecialchars($payment['status']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
    <footer>
        &copy; 2024 E-Commerce Platform. All Rights Reserved.
    </footer>
</body>
</html>
