<?php
session_start();

// Database connection details
$host = 'localhost';
$dbname = 'ecommerce';  // Your database name
$username = 'root';  // Your MySQL username
$password = '';  // Your MySQL password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch products
    $sql = "SELECT * FROM products";
    $stmt = $pdo->query($sql);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - E-Shop</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Welcome to E-Shop</h1>
    </header>
    <main>
        <div class="products">
            <?php foreach ($products as $product): ?>
                <div class="product">
                    <img src="images/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                    <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                    <p><?php echo htmlspecialchars($product['price']); ?> USD</p>
                    <a href="address.php?name=<?php echo urlencode($product['name']); ?>&price=<?php echo urlencode($product['price']); ?>&image=<?php echo urlencode($product['image']); ?>">Buy Now</a>
                </div>
            <?php endforeach; ?>
        </div>
    </main>
</body>
</html>

