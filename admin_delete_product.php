<?php
session_start();

// Ensure the user is logged in as admin
//if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 'admin') {
  //  header('Location: login.php');
    //exit();
//}

if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    // Database connection
    $host = 'localhost';
    $dbname = 'ecommerce';  // Your database name
    $username = 'root';  // Your MySQL username
    $password = '';  // Your MySQL password

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Delete the product from the database
        $stmt = $pdo->prepare("DELETE FROM products WHERE id = :id");
        $stmt->bindParam(':id', $product_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            // Redirect back to the products management page with a success message
            header('Location: admin_manage_products.php?success=1');
        } else {
            // Redirect back with an error message
            header('Location: admin_manage_products.php?error=1');
        }
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
} else {
    // Redirect to products management page if no product id is passed
    header('Location: admin_manage_products.php');
}
?>

