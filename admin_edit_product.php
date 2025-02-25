<?php
session_start();

// Check if the user is logged in as admin
// You should uncomment this in a real application
// if ($_SESSION['role'] !== 'admin') {
//     header('Location: login.php');
//     exit();
// Database connection details
$host = 'localhost';
$dbname = 'ecommerce';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch the product details based on the product ID passed in the URL
    if (isset($_GET['id'])) {
        $product_id = $_GET['id'];
        $stmt = $pdo->prepare("SELECT * FROM products WHERE id = :id");
        $stmt->bindParam(':id', $product_id);
        $stmt->execute();
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Check if product exists
        if (!$product) {
            die('Product not found.');
        }
    } else {
        die('Product ID is required.');
    }

    // Handle form submission for updating the product
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = $_POST['name'];
        $price = $_POST['price'];
        $description = $_POST['description'];
        $image_url = $_POST['image_url'];
        $rating = $_POST['rating'];

        // Update the product details in the database
        $updateStmt = $pdo->prepare("UPDATE products SET name = :name, price = :price, description = :description, image_url = :image_url, rating = :rating WHERE id = :id");
        $updateStmt->bindParam(':name', $name);
        $updateStmt->bindParam(':price', $price);
        $updateStmt->bindParam(':description', $description);
        $updateStmt->bindParam(':image_url', $image_url);
        $updateStmt->bindParam(':rating', $rating);
        $updateStmt->bindParam(':id', $product_id);

        if ($updateStmt->execute()) {
            echo "<script>alert('Product updated successfully!'); window.location.href='admin_manage_products.php';</script>";
        } else {
            echo "<script>alert('Error updating product.');</script>";
        }
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
    <title>Edit Product</title>
    <link rel="stylesheet" href="style.css"> <!-- Link to your existing CSS -->
    <style>
        /* Custom Styles */
        body {
            background: linear-gradient(to right, #1e3c72, #2a5298);
            color: #fff;
            font-size: 16px;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
            width: 400px;
            text-align: center;
        }

        h1 {
            font-size: 2rem;
            margin-bottom: 1.5rem;
            color: #f0f0f0;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            font-size: 1rem;
            margin-bottom: 0.5rem;
            text-align: left;
            color: #ddd;
        }

        input, textarea {
            margin-bottom: 1rem;
            padding: 0.75rem;
            border: none;
            border-radius: 5px;
            background-color: #f5f5f5;
            font-size: 1rem;
            color: #333;
            outline: none;
        }

        input:focus, textarea:focus {
            background-color: #e0e0e0;
            box-shadow: 0 0 5px #007bff;
        }

        .rating {
            margin-bottom: 1rem;
            text-align: left;
        }

        .rating input {
            margin-right: 5px;
        }

        button {
            background-color: #007bff;
            color: #fff;
            padding: 0.75rem;
            font-size: 1rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Edit Product</h1>
        <form method="POST" action="">
            <label for="name">Product Name:</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required>

            <label for="price">Price:</label>
            <input type="number" id="price" name="price" value="<?php echo htmlspecialchars($product['price']); ?>" required>

            <label for="description">Description:</label>
            <textarea id="description" name="description" required><?php echo htmlspecialchars($product['description']); ?></textarea>

            <label for="image_url">Image URL:</label>
            <input type="text" id="image_url" name="image_url" value="<?php echo htmlspecialchars($product['image_url']); ?>" required>

            <!-- Rating Selection -->
            <div class="rating">
                <label>Rating:</label>
                <?php for ($i = 1; $i <= 5; $i++): ?>
                    <input type="radio" id="rating_<?php echo $i; ?>" name="rating" value="<?php echo $i; ?>" <?php if ($product['rating'] == $i) echo 'checked'; ?>>
                    <label for="rating_<?php echo $i; ?>"><?php echo $i; ?> Star</label>
                <?php endfor; ?>
            </div>

            <button type="submit">Update Product</button>
        </form>
    </div>
</body>
</html>
