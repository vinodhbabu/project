<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - E-Shop</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="form-container">
        <h1>Enter Your Address and Payment Details</h1>
        <form method="POST" action="pay_success.php">
            <input type="hidden" name="product_name" value="<?php echo htmlspecialchars($_GET['name']); ?>">
            <input type="hidden" name="product_price" value="<?php echo htmlspecialchars($_GET['price']); ?>">
            <input type="hidden" name="product_image" value="<?php echo htmlspecialchars($_GET['image']); ?>">

            <label for="fullname">Full Name:</label>
            <input type="text" id="fullname" name="fullname" required>

            <label for="village">Village:</label>
            <input type="text" id="village" name="village" required>

            <label for="mandel">Mandel:</label>
            <input type="text" id="mandel" name="mandel" required>

            <label for="district">District:</label>
            <input type="text" id="district" name="district" required>

            <label for="state">State:</label>
            <input type="text" id="state" name="state" required>

            <label for="pincode">Pincode:</label>
            <input type="text" id="pincode" name="pincode" required>

            <label for="phone">Phone Number:</label>
            <input type="text" id="phone" name="phone" required>

            <label for="card_number">Card Number:</label>
            <input type="text" id="card_number" name="card_number" required>

            <label for="expiry">Expiry Date (MM/YY):</label>
            <input type="text" id="expiry" name="expiry" required>

            <label for="cvv">CVV:</label>
            <input type="number" id="cvv" name="cvv" required>

            <input type="submit" value="Buy Now">
        </form>
    </div>
</body>
</html>

