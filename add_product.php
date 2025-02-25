<?php
$host = 'localhost';
$dbname = 'ecommerce_db';
$username = 'root'; // Use your MySQL username
$password = ''; // Use your MySQL password

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if form data is sent via POST
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $name = $_POST['name'];
        $price = $_POST['price'];
        $img = $_POST['img'];

        // Insert product into the database
        $stmt = $conn->prepare("INSERT INTO products (name, price, image) VALUES (:name, :price, :img)");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':img', $img);

        $stmt->execute();

        // Redirect back to shop.html after successful product addition
        header("Location: shop.php");
        exit();
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
