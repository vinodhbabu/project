<?php
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
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit();
}

// Fetch all order details from the payments table using PDO
$sql = "SELECT id, order_id, payment_date, amount, payment_method, status FROM payments";
$stmt = $pdo->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Orders - Admin</title>
    <style>
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

        @keyframes buttonHover {
            0% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
            100% {
                transform: scale(1);
            }
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            background: url('bgadm.png') no-repeat center center fixed;
    background-size: cover;
            animation: fadeIn 1.5s ease-in-out;
        }

        .container {
            width: 90%;
            margin: 30px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            animation: fadeIn 2s ease-in-out;
        }

        h1 {
            font-size: 2.5em;
            color: #333;
            text-align: center;
            margin-bottom: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            animation: fadeIn 1.8s ease-in-out;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #343a40;
            color: #fff;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #f1f1f1;
            transition: background-color 0.3s;
        }

        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .button:hover {
            background-color: #45a049;
            animation: buttonHover 0.6s ease-in-out;
        }

        .logout {
            background-color: #f44336;
        }

        .logout:hover {
            background-color: #e53935;
        }

        .print-button {
            background-color: #ff9800;
        }

        .print-button:hover {
            background-color: #fb8c00;
        }

        .admin-actions {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
        }

        footer {
            text-align: center;
            margin-top: 40px;
            font-size: 14px;
            color: #777;
        }
    </style>

    <script>
        function printInvoice(orderId) {
            // Find the row content using the orderId
            var orderDetails = document.getElementById("order-details-" + orderId).innerHTML;

            // Open a new window for printing
            var printWindow = window.open('', '', 'height=600,width=800');

            // Add the necessary HTML structure for printing
            printWindow.document.write('<html><head><title>Invoice - Order ID: ' + orderId + '</title>');
            printWindow.document.write('<style>body {font-family: Arial, sans-serif;}</style>');
            printWindow.document.write('</head><body>');
            printWindow.document.write('<h1>Invoice for Order ID: ' + orderId + '</h1>');
            printWindow.document.write('<div class="invoice-content">' + orderDetails + '</div>');
            printWindow.document.write('</body></html>');
            printWindow.document.close(); // Close the document to trigger loading

            // Trigger the print dialog
            printWindow.print();
        }
    </script>
</head>
<body>
    <div class="container">
        <h1>Order Details</h1>

        <table>
            <thead>
                <tr>
                    <th>Payment ID</th>
                    <th>Order ID</th>
                    <th>Payment Date</th>
                    <th>Amount</th>
                    <th>Payment Method</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($stmt->rowCount() > 0) {
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<tr>
                                <td>" . htmlspecialchars($row['id']) . "</td>
                                <td>" . htmlspecialchars($row['order_id']) . "</td>
                                <td>" . htmlspecialchars($row['payment_date']) . "</td>
                                <td>" . htmlspecialchars($row['amount']) . "</td>
                                <td>" . htmlspecialchars($row['payment_method']) . "</td>
                                <td>" . htmlspecialchars($row['status']) . "</td>
                                <td><a href='print_invoice.php?order_id=" . $row['order_id'] . "' class='button'>Print Invoice</a></td>
                              </tr>";

                        echo "<div id='order-details-" . $row['order_id'] . "' style='display:none;'>
                                <h2>Invoice for Payment ID: " . htmlspecialchars($row['id']) . "</h2>
                                <p><strong>Order ID:</strong> " . htmlspecialchars($row['order_id']) . "</p>
                                <p><strong>Payment Date:</strong> " . htmlspecialchars($row['payment_date']) . "</p>
                                <p><strong>Amount:</strong> " . htmlspecialchars($row['amount']) . "</p>
                                <p><strong>Payment Method:</strong> " . htmlspecialchars($row['payment_method']) . "</p>
                                <p><strong>Status:</strong> " . htmlspecialchars($row['status']) . "</p>
                              </div>";
                    }
                } else {
                    echo "<tr><td colspan='7'>No orders found</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <div class="admin-actions">
            <a href="admin_dashboard.php" class="button">Back to Dashboard</a>
            <a href="logout.php" class="button logout">Logout</a>
        </div>
    </div>

    <footer>
        &copy; 2024 Vehicle Sales Management System. All Rights Reserved.
    </footer>
</body>
</html>

<?php
// Close the database connection
$pdo = null;
?>
