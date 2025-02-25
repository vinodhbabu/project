<?php
session_start();
//require 'lib/phpqrcode/qrlib.php'; // Include the phpqrcode library

// Ensure the user is logged in
//if (!isset($_SESSION['user_id'])) {
//    header("Location: login.php");
 //   exit;
//}

$user_id = $_SESSION['user_id'];

// Fetch the total price from the payment process
if (!isset($_POST['total_price']) || empty($_POST['total_price'])) {
    echo "<h2>Error: Total price not provided.</h2>";
    exit;
}

$total_price = $_POST['total_price'];
$name = isset($_POST['name']) ? htmlspecialchars($_POST['name']) : "Anonymous";
$payment_id = uniqid('PAY_'); // Unique payment identifier

// Data to encode in the QR code
$qr_data = "Payment ID: $payment_id\n";
$qr_data .= "Name: $name\n";
$qr_data .= "Amount: $" . number_format($total_price, 2) . "\n";
$qr_data .= "Payment Status: Pending";

// QR Code file path (temporary storage)
$qr_file = 'temp/payment_qr_' . $payment_id . '.png';

// Generate the QR code
QRcode::png($qr_data, $qr_file, QR_ECLEVEL_L, 10);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment QR Code</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f7fc;
            margin: 0;
            padding: 0;
            text-align: center;
        }

        header {
            background-color: #0072ff;
            color: white;
            padding: 20px;
            font-size: 24px;
            font-weight: bold;
        }

        .qr-container {
            margin-top: 50px;
        }

        .qr-container img {
            width: 300px;
            height: 300px;
            border: 5px solid #0072ff;
            border-radius: 8px;
        }

        .details {
            margin-top: 20px;
            font-size: 18px;
            color: #333;
        }

        .back-btn {
            background-color: #0072ff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 30px;
            text-decoration: none;
        }

        .back-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<header>
    Payment QR Code
</header>

<div class="qr-container">
    <img src="<?php echo $qr_file; ?>" alt="Payment QR Code">
</div>

<div class="details">
    <p><strong>Payment ID:</strong> <?php echo $payment_id; ?></p>
    <p><strong>Name:</strong> <?php echo $name; ?></p>
    <p><strong>Total Amount:</strong> $<?php echo number_format($total_price, 2); ?></p>
    <p><strong>Payment Status:</strong> Pending</p>
</div>

<a href="cart.php" class="back-btn">Back to Cart</a>
</body>
</html>

