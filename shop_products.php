<?php
session_start();

$host = 'localhost';
$dbname = 'ecommerce';  // Your database name
$username = 'root';  // Your MySQL username
$password = '';  // Your MySQL password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Initialize query and parameters
    $query = "SELECT * FROM products WHERE 1";
    $params = [];

    // Handle search functionality
    if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
        $search = trim($_GET['search']);
        $query .= " AND (name LIKE ? OR description LIKE ?)";
        $params[] = "%$search%";
        $params[] = "%$search%";
    }

    // Handle price range filter
    if (isset($_GET['min_price']) && is_numeric($_GET['min_price'])) {
        $min_price = floatval($_GET['min_price']);
        $query .= " AND price >= ?";
        $params[] = $min_price;
    }
    if (isset($_GET['max_price']) && is_numeric($_GET['max_price'])) {
        $max_price = floatval($_GET['max_price']);
        $query .= " AND price <= ?";
        $params[] = $max_price;
    }

    // Handle rating filter
    if (isset($_GET['min_rating']) && is_numeric($_GET['min_rating'])) {
        $min_rating = floatval($_GET['min_rating']);
        $query .= " AND rating >= ?";
        $params[] = $min_rating;
    }
    if (isset($_GET['max_rating']) && is_numeric($_GET['max_rating'])) {
        $max_rating = floatval($_GET['max_rating']);
        $query .= " AND rating <= ?";
        $params[] = $max_rating;
    }

    // Fetch filtered products
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $products = $stmt->fetchAll();

    // Handle add to cart
    if (isset($_POST['add_to_cart']) && isset($_SESSION['user_id'])) {
        $product_id = $_POST['product_id'];
        $price = $_POST['price'];
        $user_id = $_SESSION['user_id'];  // Get the logged-in user's ID
        $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;  // Default quantity is 1

        if ($quantity > 0) {
            // Check if the product already exists in the cart
            $stmt = $pdo->prepare("SELECT * FROM cart WHERE user_id = ? AND product_id = ?");
            $stmt->execute([$user_id, $product_id]);
            $existing_product = $stmt->fetch();

            if ($existing_product) {
                // If product is already in the cart, update quantity
                $stmt = $pdo->prepare("UPDATE cart SET quantity = quantity + ? WHERE user_id = ? AND product_id = ?");
                $stmt->execute([$quantity, $user_id, $product_id]);
            } else {
                // If product is not in the cart, insert it
                $stmt = $pdo->prepare("INSERT INTO cart (user_id, product_id, price, quantity) VALUES (?, ?, ?, ?)");
                $stmt->execute([$user_id, $product_id, $price, $quantity]);
            }
        }
        header('Location: cart.php');  // Redirect to the cart page
        exit;
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
    <title>Shop - Products</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: url('cart.jpg') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: rgba(0, 114, 255, 0.8);
            color: white;
            padding: 20px;
            text-align: center;
            font-size: 24px;
            font-weight: 600;
        }

        .filter-bar {
            margin: 20px auto;
            width: 80%;
            display: flex;
            justify-content: center;
            gap: 10px;
        }

        .filter-bar input, .filter-bar button {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }

        .filter-bar button {
            background-color: #0072ff;
            color: white;
            cursor: pointer;
        }

        .filter-bar button:hover {
            background-color: #0056b3;
        }

        .container {
            width: 80%;
            margin: 30px auto;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 30px;
        }

        .product-card {
            background-color: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .product-card img {
            width: 100%;
            height: auto;
            border-radius: 8px;
        }

        .product-card h3 {
            font-size: 18px;
            margin: 15px 0;
        }

        .product-card p {
            font-size: 16px;
            color: #555;
        }

        .price {
            font-size: 20px;
            font-weight: bold;
            color: #0072ff;
        }

        .rating {
            color: #ff0000;
            margin: 10px 0;
        }

        .add-to-cart-btn {
            background-color: #28a745;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .add-to-cart-btn:hover {
            background-color: #218838;
        }

        .back-to-dashboard-btn {
            display: block;
            width: 200px;
            text-align: center;
            margin: 20px auto;
            padding: 10px;
            background-color: #ff5733;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .back-to-dashboard-btn:hover {
            background-color: #c23616;
        }
    </style>
</head>
<body>

<header>
    Shop - Available Products
    <button id="voice-search-btn" style="font-size: 16px; padding: 5px 10px;">🎤 Voice Search</button>
</header>

<!-- Filter Bar -->
<div class="filter-bar">
    <form action="shop_products.php" method="get">
        <input type="text" id="search" name="search" placeholder="Search by name or description" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
        <input type="number" name="min_price" placeholder="Min Price" value="<?php echo isset($_GET['min_price']) ? htmlspecialchars($_GET['min_price']) : ''; ?>" step="0.01">
        <input type="number" name="max_price" placeholder="Max Price" value="<?php echo isset($_GET['max_price']) ? htmlspecialchars($_GET['max_price']) : ''; ?>" step="0.01">
        <input type="number" name="min_rating" placeholder="Min Rating (1-5)" value="<?php echo isset($_GET['min_rating']) ? htmlspecialchars($_GET['min_rating']) : ''; ?>" min="1" max="5">
        <input type="number" name="max_rating" placeholder="Max Rating (1-5)" value="<?php echo isset($_GET['max_rating']) ? htmlspecialchars($_GET['max_rating']) : ''; ?>" min="1" max="5">
        <button type="submit">Filter</button>
        <button type="reset" onclick="window.location.href='shop_products.php';">Reset</button>
    </form>
</div>

<!-- Products Container -->
<div class="container">
    <?php if ($products): ?>
        <?php foreach ($products as $product): ?>
        <div class="product-card">
            <img src="<?php echo $product['image_url']; ?>" alt="Product Image">
            <h3><?php echo $product['name']; ?></h3>
            <p><?php echo $product['description']; ?></p>
            <div class="price">₹<?php echo number_format($product['price'], 2); ?></div>
            <div class="rating">Rating: <?php echo $product['rating']; ?>/5</div>
            <form action="shop_products.php" method="post">
                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                <input type="hidden" name="price" value="<?php echo $product['price']; ?>">
                <label for="quantity">Quantity:</label>
                <input type="number" name="quantity" value="1" min="1" max="10">
                <button type="submit" name="add_to_cart" class="add-to-cart-btn">Add to Cart</button>
            </form>
        </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No products found.</p>
    <?php endif; ?>
</div>

<!-- Back to Dashboard Button -->
<a href="customer_dashboard.php">
    <button class="back-to-dashboard-btn">Back to Dashboard</button>
</a>

<script>
    // Voice Search functionality using Web Speech API
    const voiceSearchButton = document.getElementById('voice-search-btn');
    const searchInput = document.getElementById('search');

    const recognition = new (window.SpeechRecognition || window.webkitSpeechRecognition)();
    recognition.lang = 'en-US';

    recognition.onstart = () => {
        console.log('Voice recognition started');
    };

    recognition.onresult = (event) => {
        const transcript = event.results[0][0].transcript;
        searchInput.value = transcript; // Set the value of the search input to the recognized speech
        searchInput.form.submit(); // Submit the form with the voice input
    };

    recognition.onerror = (event) => {
        console.error('Voice recognition error: ', event.error);
    };

    voiceSearchButton.addEventListener('click', () => {
        recognition.start(); // Start the voice recognition
    });
</script>

</body>
</html>
