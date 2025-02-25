<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $otp = $_POST['otp'];

    // Check if the entered OTP matches the stored OTP
    if (isset($_SESSION['otp']) && $otp == $_SESSION['otp']) { 
       
        echo json_encode(["status" => "success", "message" => "OTP verified successfully."]);
         //header("Location: customer_dashboard.php");
    } else {
        echo json_encode(["status" => "error", "message" => "Invalid OTP. Please try again."]);
    }
}
?>
