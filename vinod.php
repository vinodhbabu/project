<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login with YouTube Shorts Video</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f4f4f4;
            overflow: hidden;
        }

        .video-container {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            overflow: hidden;
        }

        .video-container iframe {
            width: 100%;
            height: 100%;
            pointer-events: none; /* Disables interaction with the video */
        }

        .login-container {
            text-align: center;
            background: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            z-index: 1;
            position: relative;
        }

        .login-btn {
            background-color: #0072ff;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 20px;
        }

        .login-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<div class="video-container">
    <!-- YouTube Shorts Embed with Your Video ID -->
    <iframe 
        src="https://www.youtube.com/embed/LAqLUZrQcpc?autoplay=1&mute=1&loop=1&playlist=LAqLUZrQcpc" 
        frameborder="0" 
        allow="autoplay; loop; encrypted-media" 
        allowfullscreen>
    </iframe>
</div>

<div class="login-container">
    <h2>Login</h2>
    <form action="authenticate.php" method="POST">
        <input type="text" name="username" placeholder="Enter Username" required>
        <input type="password" name="password" placeholder="Enter Password" required>
        <button type="submit" class="login-btn">Login</button>
    </form>
</div>

</body>
</html>
