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
        $entered_otp = $_POST['otp'];

        if (isset($_SESSION['otp']) && $entered_otp == $_SESSION['otp']) {
            $user_data = $_SESSION['signup_data'];

            // Insert user data into the database
            $sql = "INSERT INTO users (username, email, password, role) VALUES (:username, :email, :password, :role)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':username', $user_data['username']);
            $stmt->bindParam(':email', $user_data['email']);
            $stmt->bindParam(':password', $user_data['password']);
            $stmt->bindParam(':role', $user_data['role']);

            if ($stmt->execute()) {
                unset($_SESSION['otp'], $_SESSION['signup_data']);
                echo "<script>alert('Registration successful! You can now log in.'); window.location.href='login.php';</script>";
            } else {
                echo "<script>alert('Error occurred while registering. Please try again.');</script>";
            }
        } else {
            echo "<script>alert('Invalid OTP. Please try again.');</script>";
        }
    }
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>
