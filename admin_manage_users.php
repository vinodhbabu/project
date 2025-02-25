<?php
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

// Database connection
$host = 'localhost';
$dbname = 'ecommerce';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit();
}

// Fetch customers (excluding admins)
$sql = "SELECT * FROM users WHERE role = 'customer'";
$stmt = $pdo->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users - Admin</title>
    <style>
        /* Global styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            background: url('bgadm.png') no-repeat center center fixed;
            background-size: cover;
        }

        /* Container for the main content */
        .container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
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

        /* Table styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #343a40;
            color: #fff;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

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

        .logout {
            background-color: #f44336;
        }

        .logout:hover {
            background-color: #e53935;
        }

        .logout:active {
            background-color: #d32f2f;
        }

        footer {
            text-align: center;
            margin-top: 40px;
            font-size: 14px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Manage Users - Customers</h1>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Created At</th>
                     
                </tr>
            </thead>
            <tbody>
                <?php
                if ($stmt->rowCount() > 0) {
                    // Output each row
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<tr>
                                <td>" . htmlspecialchars($row['id']) . "</td>
                                <td>" . htmlspecialchars($row['username']) . "</td>
                                <td>" . htmlspecialchars($row['email']) . "</td>
                                <td>" . htmlspecialchars($row['created_at']) . "</td>
                               
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No customers found</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <a href="admin_dashboard.php" class="button">Back to Dashboard</a>
        <a href="logout.php" class="button logout">Logout</a>
    </div>

    <!-- Footer -->
    <footer>
        &copy; 2024 Vehicle Sales Management System. All Rights Reserved.
    </footer>
</body>
</html>

<?php
// Close the database connection
$pdo = null;
?>

