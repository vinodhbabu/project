<?php
// Start session
session_start();

// Database connection
$host = 'localhost';
$dbname = 'ecommerce';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch product details by ID
    if (isset($_GET['id'])) {
        $product_id = $_GET['id'];
        $sql = "SELECT * FROM products WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $product_id);
        $stmt->execute();
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        header('Location: product_list.php');
        exit();
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
    <title>Product Details</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Product Details</h1>
        <div class="product-detail">
            <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
            <h3><?php echo htmlspecialchars($product['name']); ?></h3>
            <p><?php echo htmlspecialchars($product['description']); ?></p>
            <p><strong>Price:</strong> $<?php echo htmlspecialchars($product['price']); ?></p>
        </div>
    </div>
</body>
</html>

