<?php
session_start();

// Check if the user is logged in and is a customer
// if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
//    header("Location: login.php");
//    exit;
//}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vehicle Sales Management System</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        /* Global Styles */
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            color: #fff;
            overflow-x: hidden;
            position: relative;
        }

        /* Background video styling */
        .background-video {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            object-fit: cover; /* Ensures the video covers the entire background */
        }

        header {
            background: rgba(0, 0, 0, 0.8);
            padding: 20px 0;
            text-align: center;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        header h1 {
            font-size: 36px;
            color: #ffcc00;
            margin: 0;
        }

        header p {
            font-size: 20px;
            margin: 5px 0 0;
            color: #ddd;
        }

        .dashboard-container {
            max-width: 1200px;
            margin: 60px auto 30px;
            background: rgba(0, 0, 0, 0.75);
            border-radius: 15px;
            padding: 50px 30px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.5);
            text-align: center;
        }

        .dashboard-container h2 {
            font-size: 28px;
            margin-bottom: 30px;
            color: #ffcc00;
        }

        .dashboard-container p {
            font-size: 18px;
            color: #ccc;
            line-height: 1.8;
        }

        .nav {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            margin: 40px 0;
        }

        .nav a {
            color: #fff;
            background: linear-gradient(45deg, #0072ff, #00c6ff);
            padding: 20px 30px;
            font-size: 20px;
            font-weight: 600;
            text-decoration: none;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .nav a:hover {
            transform: scale(1.1);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.5);
        }

        .highlight {
            font-size: 22px;
            color: #ffcc00;
            font-weight: 700;
            margin-top: 30px;
            animation: blink 1.5s infinite;
        }

        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }

        footer {
            text-align: center;
            padding: 120px;
            background: rgba(0, 0, 0, 0.35);
            color: #ccc;
            font-size: 18px;
        }

        footer a {
            color: #ffcc00;
            text-decoration: none;
        }

        footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<!-- Background video -->
<video autoplay muted loop class="background-video">
    <source src="vinod2.webm" type="video/webm">
    Your browser does not support the video tag.
</video>

<header class="nav" >
    <h1>Vehicle Sales Management System</h1>
    <p>Drive your dreams with ease!</p>
    <a href="logout.php">🚪 Logout</a>

</header>

<div class="dashboard-container">
    <h2>Welcome to Customer Dashboard</h2>
  <marquee behavior="" direction="left">  <p>
        Explore the latest vehicles, manage your orders, and customize your preferences.
        Your journey toward the perfect ride starts here.
    </p>
    </marquee>
    <div class="nav">
        <a href="shop_products.php">🚗 Explore Vehicles</a>
        <a href="cart.php">🛒 View Cart</a>
        <a href="view_orders.php">📦Orders & Payments</a>
        <!-- <a href="service_history.php">🛠️ Service History</a> -->
        <!-- <a href="vehicle_financing.php">💳 Vehicle Financing</a> -->
    </div>

    <p class="highlight">🔥 Discover exclusive deals on your next car! 🔥</p>
</div>

<footer>
    <p>&copy; 2024 AutoSales Co. | <a href="privacy_policy.php">Privacy Policy</a> | <a href="what.html">Contact Us</a></p>
</footer>

</body>
</html>
