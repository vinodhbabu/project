<?php
// Database connection details
$host = 'localhost';
$dbname = 'ecommerce_db';
$username = 'root';
$password = '';

try {
    // Create a connection
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Decode JSON data from the POST request
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['productId'])) {
        $productId = $data['productId'];

        // Begin a transaction
        $conn->beginTransaction();

        // Delete from cart table first
        $deleteCartStmt = $conn->prepare("DELETE FROM cart WHERE product_id = :productId");
        $deleteCartStmt->bindParam(':productId', $productId, PDO::PARAM_INT);
        $deleteCartStmt->execute();

        // Delete from products table
        $deleteProductStmt = $conn->prepare("DELETE FROM products WHERE id = :productId");
        $deleteProductStmt->bindParam(':productId', $productId, PDO::PARAM_INT);
        $deleteProductStmt->execute();

        // Commit transaction
        $conn->commit();

        echo json_encode(['success' => true, 'message' => 'Product deleted successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid product ID.']);
    }
} catch (PDOException $e) {
    // Rollback on error
    $conn->rollBack();
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>

