<?php
session_start();

// Get product details from the form submission
$product_name = $_POST['product_name'];
$product_price = $_POST['product_price'];
$product_image = $_POST['product_image'];

$fullname = $_POST['fullname'];
$village = $_POST['village'];
$mandel = $_POST['mandel'];
$district = $_POST['district'];
$state = $_POST['state'];
$pincode = $_POST['pincode'];
$phone = $_POST['phone'];
$card_number = $_POST['card_number'];
$expiry = $_POST['expiry'];
$cvv = $_POST['cvv'];

// Database connection details
$host = 'localhost';
$dbname = 'ecommerce';  // Your database name
$username = 'root';  // Your MySQL username
$password = '';  // Your MySQL password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Insert order into database
    $sql = "INSERT INTO orders (product_name, product_price, product_image, fullname, village, mandel, district, state, pincode, phone, card_number, expiry, cvv) 
            VALUES (:product_name, :product_price, :product_image, :fullname, :village, :mandel, :district, :state, :pincode, :phone, :card_number, :expiry, :cvv)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':product_name', $product_name);
    $stmt->bindParam(':product_price', $product_price);
    $stmt->bindParam(':product_image', $product_image);
    $stmt->bindParam(':fullname', $fullname);
    $stmt->bindParam(':village', $village);
    $stmt->bindParam(':mandel', $mandel);
    $stmt->bindParam(':district', $district);
    $stmt->bindParam(':state', $state);
    $stmt->bindParam(':pincode', $pincode);
    $stmt->bindParam(':phone', $phone);
    $stmt->bindParam(':card_number', $card_number);
    $stmt->bindParam(':expiry', $expiry);
    $stmt->bindParam(':cvv', $cvv);

    if ($stmt->execute()) {
        echo "<h2>Order placed successfully!</h2>";
        echo "<p>Thank you for your purchase. Your order will be processed soon.</p>";
    } else {
        echo "<h2>Error occurred while processing your order. Please try again.</h2>";
    }
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>

