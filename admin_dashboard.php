<?php
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        /* Global styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            color: #333;
            overflow: hidden;
        }

        /* Background Video */
        .background-video {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: -1; /* Keep the video in the background */
        }

        /* Container for the main content */
        .container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.8); /* Semi-transparent background */
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            font-size: 2em;
            color: #333;
            text-align: center;
            margin-bottom: 30px;
        }

        /* Admin actions section (links/buttons) */
        .admin-actions {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
        }

        /* Button styles */
        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
            transition: background-color 0.3s ease;
        }

        .button:hover {
            background-color: #45a049;
        }

        .button:active {
            background-color: #388e3c;
        }

        /* Logout Button style */
        .logout {
            background-color: #f44336;
        }

        .logout:hover {
            background-color: #e53935;
        }

        .logout:active {
            background-color: #d32f2f;
        }

        /* Footer styles */
        footer {
            text-align: center;
            margin-top: 40px;
            font-size: 14px;
            color: #777;
        }
    </style>
</head>
<body>
    <!-- Background Video -->
    <video autoplay muted loop class="background-video">
        <source src="vechile.webm" type="video/webm">
        Your browser does not support the video tag.
    </video>

    <div class="container">
        <h1>Welcome to Admin Dashboard</h1>
        
        <!-- Add Links for Admin Features -->
        <div class="admin-actions">
            <a href="admin_manage_products.php" class="button">Manage Products</a>
            <!-- You can add more links here, like manage users, orders, etc. -->
            <a href="admin_view_orders.php" class="button">View Orders</a>
            <a href="admin_manage_users.php" class="button">Manage Users</a>
        </div>

        <!-- Logout Button -->
        <a href="logout.php" class="button logout">Logout</a>
    </div>

    <!-- Footer -->
    <footer>
        &copy; 2024 E-Shop. All Rights Reserved.
    </footer>
</body>
</html>
