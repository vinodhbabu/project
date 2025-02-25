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

    // Retrieve cart items
    $stmt = $pdo->prepare("SELECT p.id AS product_id, p.name, p.price, p.image_url, c.quantity 
                           FROM cart c
                           JOIN products p ON c.product_id = p.id
                           WHERE c.user_id = ?");
    $stmt->execute([$user_id]);
    $cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Calculate the total price
    $total_price = 0;
    foreach ($cart_items as $item) {
        $total_price += $item['price'] * $item['quantity'];
    }
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

if (empty($cart_items)) {
    echo "<h2>Your cart is empty. Please add items to your cart.</h2>";
    exit;
}

// Define UPI details
$upi_id = "9182804051@ybl";  // Replace with your UPI ID
$payee_name = "Ecommerce Tharun";  // Replace with your merchant name
$transaction_note = "Payment for Order";
$currency_code = "INR";
$order_id = uniqid("ORDER_");

// Generate UPI URL for payment
$upi_url = "upi://pay?pa=$upi_id&pn=" . urlencode($payee_name) . "&tid=$order_id&tr=$order_id&tn=" . urlencode($transaction_note) . "&am=$total_price&cu=$currency_code";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment - Checkout</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
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
            padding: 15px;
            text-align: center;
            font-size: 24px;
            font-weight: bold;
        }

        .payment-container {
            width: 80%;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        .cart-summary table {
            width: 100%;
            border-collapse: collapse;
        }

        .cart-summary th, .cart-summary td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .cart-summary th {
            background-color: #f7f7f7;
        }

        .total-price {
            margin-top: 20px;
            font-size: 18px;
            font-weight: bold;
            text-align: right;
        }

        .qr-container {
            margin-top: 20px;
            text-align: center;
        }

        .payment-details {
            margin-top: 20px;
            background-color: #f7f7f7;
            padding: 15px;
            border-radius: 8px;
        }

        .payment-details p {
            font-size: 16px;
            margin: 5px 0;
        }

        .payment-details strong {
            font-weight: 600;
        }

        #paymentForm {
            display: none;
        }
    </style>
</head>
<body>

<header>
    Checkout - Payment
</header>

<div class="payment-container">
    <h2>Order Summary</h2>
    
    <div class="cart-summary">
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cart_items as $item): ?>
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

    <div class="total-price">
        <h3>Total: ₹<?php echo number_format($total_price, 2); ?></h3>
    </div>

    <!-- QR Code for UPI Payment -->
    <div class="qr-container">
        <h3>Scan this QR Code to Pay via UPI</h3>
        <div id="qrcode"></div>
    </div>

    <div class="payment-details">
        <p><strong>UPI ID:</strong> <?php echo htmlspecialchars($upi_id); ?></p>
        <p><strong>Payee Name:</strong> <?php echo htmlspecialchars($payee_name); ?></p>
        <p><strong>Order ID:</strong> <?php echo htmlspecialchars($order_id); ?></p>
        <p><strong>Total Amount:</strong> ₹<?php echo number_format($total_price, 2); ?></p>
    </div>

    <form id="paymentForm" action="process_payment.php" method="POST">
        <input type="hidden" name="order_id" value="<?php echo $order_id; ?>">
        <input type="hidden" name="total_price" value="<?php echo $total_price; ?>">
    </form>

    <script>
        const upiUrl = `<?php echo $upi_url; ?>`;
        new QRCode(document.getElementById("qrcode"), {
            text: upiUrl,
            width: 256,
            height: 256,
            colorDark: "#000000",
            colorLight: "#ffffff",
            correctLevel: QRCode.CorrectLevel.H
        });

        // Automatically submit the payment form after 5 seconds
        setTimeout(function() {
            document.getElementById("paymentForm").submit();
        }, 10000); // Adjust time if necessary
    </script>
</div>

</body>
</html>

