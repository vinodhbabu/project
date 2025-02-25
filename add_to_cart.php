<?php
// Database connection details
$host = 'localhost';
$dbname = 'ecommerce';
$username = 'root';
$password = '';

session_start();

// Ensure the user is logged in
//if (!isset($_SESSION['user_id'])) {
  //  echo "User not logged in!";
    //exit;
//}

$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the raw POST data
    $data = json_decode(file_get_contents("php://input"), true);
    $productId = $data['productId'];
    $quantity = isset($data['quantity']) ? $data['quantity'] : 1; // Default quantity is 1

    try {
        // Create a connection
        $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Fetch product details from the products table
        $stmt = $conn->prepare("SELECT id, price FROM products WHERE id = :id");
        $stmt->bindParam(':id', $productId);
        $stmt->execute();
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($product) {
            // Check if the product is already in the cart
            $stmt = $conn->prepare("SELECT * FROM cart WHERE user_id = :user_id AND product_id = :product_id");
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':product_id', $product['id']);
            $stmt->execute();
            $existingProduct = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($existingProduct) {
                // Update quantity if product exists in the cart
                $stmt = $conn->prepare("UPDATE cart SET quantity = quantity + :quantity WHERE user_id = :user_id AND product_id = :product_id");
                $stmt->bindParam(':quantity', $quantity);
                $stmt->bindParam(':user_id', $user_id);
                $stmt->bindParam(':product_id', $product['id']);
                $stmt->execute();
            } else {
                // Add product to the cart
                $stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, price, quantity) 
                                        VALUES (:user_id, :product_id, :price, :quantity)");
                $stmt->bindParam(':user_id', $user_id);
                $stmt->bindParam(':product_id', $product['id']);
                $stmt->bindParam(':price', $product['price']);
                $stmt->bindParam(':quantity', $quantity);
                $stmt->execute();
            }

            echo "Product added to cart!";
        } else {
            echo "Product not found!";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

