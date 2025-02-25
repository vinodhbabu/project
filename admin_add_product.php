<?php
session_start();

// Ensure the user is logged in as admin
//if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 'admin') {
  //  header('Location: login.php');
    //exit;
//}

$host = 'localhost';
$dbname = 'ecommerce';  // Your database name
$username = 'root';  // Your MySQL username
$password = '';  // Your MySQL password

try {
    // Create a PDO connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Get form data
        $name = $_POST['name'];
        $price = $_POST['price'];
        $description = $_POST['description'];
        $image_url = $_POST['image_url'];
        $rating = $_POST['rating'];

        // Insert product into the database
        $stmt = $pdo->prepare("INSERT INTO products (name, price, description, image_url, rating, created_at) VALUES (:name, :price, :description, :image_url, :rating, NOW())");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':image_url', $image_url);
        $stmt->bindParam(':rating', $rating);

        if ($stmt->execute()) {
            echo "<script>alert('Product added successfully!'); window.location.href='admin_manage_products.php';</script>";
        } else {
            echo "<script>alert('Error occurred while adding the product. Please try again.');</script>";
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
    <title>Add Product - Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #0072ff;
            color: white;
            padding: 20px;
            text-align: center;
            font-size: 24px;
            font-weight: 600;
        }

        .container {
            width: 50%;
            margin: 30px auto;
            background-color: white;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 8px;
            font-weight: 600;
        }

        input[type="text"], input[type="number"], input[type="range"] {
            padding: 10px;
            font-size: 16px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        input[type="submit"] {
            background-color: #0072ff;
            color: white;
            padding: 10px;
            font-size: 18px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #005bb5;
        }
    </style>
</head>
<body>

<header>
    Admin Dashboard - Add New Product
</header>

<div class="container">
    <form method="POST" action="">
        <label for="name">Product Name:</label>
        <input type="text" id="name" name="name" required>

        <label for="price">Price:</label>
        <input type="number" id="price" name="price" required>

        <label for="description">Description:</label>
        <input type="text" id="description" name="description" required>

        <label for="image_url">Image URL:</label>
        <input type="text" id="image_url" name="image_url" required>

        <label for="rating">Rating (1-5):</label>
<div id="rating">
    <label>
        <input type="radio" name="rating" value="1" required> 1
    </label>
    <label>
        <input type="radio" name="rating" value="2"> 2
    </label>
    <label>
        <input type="radio" name="rating" value="3" checked> 3
    </label>
    <label>
        <input type="radio" name="rating" value="4"> 4
    </label>
    <label>
        <input type="radio" name="rating" value="5"> 5
    </label>
</div>


        <input type="submit" value="Add Product">
    </form>
</div>

</body>
</html>

