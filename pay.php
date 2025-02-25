<?php
// Database connection details
$host = 'localhost';
$dbname = 'ecommerce_db';
$username = 'root';  // Your MySQL username
$password = '';  // Your MySQL password

// Get address ID from the query string
$address_id = $_GET['address_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Create a connection
        $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Collect address details
        $name = $_POST['name'];
        $village = $_POST['village'];
        $mandel = $_POST['mandel'];
        $district = $_POST['district'];
        $state = $_POST['state'];
        $pincode = $_POST['pincode'];
        $phone = $_POST['phone'];  // New field
 
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// You can retrieve address details using $address_id if needed for displaying purposes
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment - E-Shop</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }

        .form-container {
            max-width: 500px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #333;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 10px;
            font-size: 16px;
        }

        input[type="text"], input[type="number"] {
            padding: 10px;
            font-size: 16px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        input[type="submit"] {
            background-color: #007bff;
            color: white;
            padding: 10px;
            font-size: 18px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h1>Enter Payment Details</h1>
        <form action="pay_success.php" method="post">
        <!-- <form action="pay.php" method="post"> -->

            <input type="hidden" name="address_id" value="<?php echo $address_id; ?>">

            <label for="card-number">Card Number:</label>
            <input type="text" id="card-number" name="card_number" required>

            <label for="expiry">Expiry Date:</label>
            <input type="text" id="expiry" name="expiry" placeholder="MM/YY" required>

            <label for="cvv">CVV:</label>
            <input type="number" id="cvv" name="cvv" required>

            <input type="submit" value="Pay Now">
        </form>
    </div>
</body>
</html>
