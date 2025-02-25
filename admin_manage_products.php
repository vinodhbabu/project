<?php
session_start();

// Ensure the user is logged in as admin
 

$host = 'localhost';
$dbname = 'ecommerce';  // Your database name
$username = 'root';  // Your MySQL username
$password = '';  // Your MySQL password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Build the query with search filters
    $query = "SELECT * FROM products WHERE 1=1";
    $params = [];

    if (!empty($_GET['name'])) {
        $query .= " AND name LIKE :name";
        $params[':name'] = '%' . $_GET['name'] . '%';
    }

    if (!empty($_GET['min_price'])) {
        $query .= " AND price >= :min_price";
        $params[':min_price'] = $_GET['min_price'];
    }

    if (!empty($_GET['max_price'])) {
        $query .= " AND price <= :max_price";
        $params[':max_price'] = $_GET['max_price'];
    }

    if (!empty($_GET['min_rating'])) {
        $query .= " AND rating >= :min_rating";
        $params[':min_rating'] = $_GET['min_rating'];
    }

    if (!empty($_GET['max_rating'])) {
        $query .= " AND rating <= :max_rating";
        $params[':max_rating'] = $_GET['max_rating'];
    }

    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $products = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products - Admin</title>
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
            width: 80%;
            margin: 30px auto;
        }

        form {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }

        form input[type="text"],
        form input[type="number"],
        form select {
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        form button {
            background-color: #0072ff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        form button:hover {
            background-color: #005bb5;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #0072ff;
            color: white;
        }

        button {
            background-color: #0072ff;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }

        button:hover {
            background-color: #005bb5;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
        }

        .add-product-btn {
            background-color: #28a745;
        }

        .add-product-btn:hover {
            background-color: #218838;
        }

        .back-to-dashboard-btn {
            background-color: #ff5733;
            margin-top: 20px;
            display: block;
            width: 200px;
            text-align: center;
            margin: 20px auto;
        }

        .back-to-dashboard-btn:hover {
            background-color: #c23616;
        }
    </style>
</head>
<body>

<header>
    Admin Dashboard - Manage Products
</header>

<div class="container">
    <!-- Search Form -->
    <form method="GET" action="">
        <input type="text" name="name" placeholder="Product Name" value="<?php echo $_GET['name'] ?? ''; ?>">
        <input type="number" name="min_price" placeholder="Min Price" min="0" value="<?php echo $_GET['min_price'] ?? ''; ?>">
        <input type="number" name="max_price" placeholder="Max Price" min="0" value="<?php echo $_GET['max_price'] ?? ''; ?>">
        <input type="number" name="min_rating" placeholder="Min Rating (1-5)" min="1" max="5" value="<?php echo $_GET['min_rating'] ?? ''; ?>">
        <input type="number" name="max_rating" placeholder="Max Rating (1-5)" min="1" max="5" value="<?php echo $_GET['max_rating'] ?? ''; ?>">
        <button type="submit">Search</button>
        <button type="reset" onclick="window.location.href='admin_manage_products.php';">Reset</button>
    </form>

    <a href="admin_add_product.php">
        <button class="add-product-btn">Add New Product</button>
    </a>

    <a href="admin_dashboard.php">
        <button class="back-to-dashboard-btn">Back to Dashboard</button>
    </a>

    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Price</th>
            <th>Description</th>
            <th>Rating</th>
            <th>Actions</th>
        </tr>

        <?php if (empty($products)): ?>
        <tr>
            <td colspan="6" style="text-align: center;">No products found.</td>
        </tr>
        <?php else: ?>
        <?php foreach ($products as $product): ?>
        <tr>
            <td><?php echo $product['id']; ?></td>
            <td><?php echo $product['name']; ?></td>
            <td>₹<?php echo number_format($product['price'], 2); ?></td>
            <td><?php echo $product['description']; ?></td>
            <td><?php echo $product['rating']; ?> / 5</td>
            <td class="action-buttons">
                <a href="admin_edit_product.php?id=<?php echo $product['id']; ?>">
                    <button>Edit</button>
                </a>
                <a href="admin_delete_product.php?id=<?php echo $product['id']; ?>">
                    <button>Delete</button>
                </a>
            </td>
        </tr>
        <?php endforeach; ?>
        <?php endif; ?>
    </table>
</div>

</body>
</html>

