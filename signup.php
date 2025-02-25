<?php
session_start();

// Database connection details
$host = 'localhost';
$dbname = 'ecommerce';
$username = 'root';
$password = '';

try {
    // Create a new PDO connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $user_name = $_POST['username'];
        $email = $_POST['email'];
        $pass = $_POST['password'];
        $role = $_POST['role']; // Added role selection

        // Check if the email already exists
        $checkEmailQuery = "SELECT * FROM users WHERE email = :email";
        $stmt = $pdo->prepare($checkEmailQuery);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            echo "<script>alert('The email is already registered. Please use a different email or log in.');</script>";
        } else {
            // Hash the password for secure storage
            //$hashed_password = password_hash($pass, PASSWORD_DEFAULT);

            // Insert user data into the database
            $sql = "INSERT INTO users (username, email, password, role) VALUES (:username, :email, :password, :role)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':username', $user_name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $pass);
            $stmt->bindParam(':role', $role);

            if ($stmt->execute()) {
                echo "<script>alert('Registration successful! You can now log in.'); window.location.href='login.php';</script>";
            } else {
                echo "<script>alert('Error occurred while registering. Please try again.');</script>";
            }
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
    <title>Sign Up</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(120deg, #0236ff, #aabbdd);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #333;
        }

        header {
            background-color: #0072ff;
            color: white;
            padding: 15px 0;
            text-align: center;
            border-radius: 8px 8px 0 0;
            font-size: 24px;
            font-weight: 600;
        }

        main {
            width: 100%;
            max-width: 400px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }

        form {
            padding: 30px;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        label {
            margin-bottom: 5px;
            font-weight: 600;
            color: #333;
        }

        input[type="text"], input[type="email"], input[type="password"], select {
            padding: 12px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }

        input[type="text"]:focus, input[type="email"]:focus, input[type="password"]:focus, select:focus {
            border-color: #0072ff;
            outline: none;
        }

        button {
            padding: 12px;
            background-color: #3023aa;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        button:hover {
            background-color: #005bb5;
            transform: translateY(-3px);
        }

        p {
            text-align: center;
            color: #666;
        }

        a {
            color: #0072ff;
            text-decoration: none;
            font-weight: 600;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <main>
        <header>
            Sign Up
        </header>
        <form method="post" action="">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <label for="role">Role:</label>
            <select id="role" name="role" required>
                <option value="customer">Customer</option>
                <option value="admin">Admin</option>
            </select>

            <button type="submit">Sign Up</button>
        </form>
        <p>Already have an account? <a href="login.php">Login</a></p>
    </main>
</body>
</html>

