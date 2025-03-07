<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Mail Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }

        .container {
            text-align: center;
            padding: 50px;
        }

        form input {
            padding: 10px;
            width: 300px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        form button {
            padding: 10px 20px;
            background-color: #000;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        form button:hover {
            background-color: #444;
        }

        .alert-content {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            border: 1px solid #444;
            border-radius: 5px;
            background-color: #ffeb3b;
            color: #000;
            font-weight: bold;
        }

        .close-alert {
            background: transparent;
            border: none;
            font-size: 20px;
            margin-left: 10px;
            cursor: pointer;
            font-weight: bold;
        }

        .php-alert {
            text-align: center;
            margin: 20px auto;
            padding: 10px 20px;
            color: #4caf50;
            border: 1px solid #4caf50;
            display: inline-block;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <img src="images/nike_logo.png" alt="">
        <h1>Enter Your Email to Join Us</h1>
        <form method="POST" action="send_email.php" enctype="multipart/form-data">
            <input type="email" name="email" placeholder="Your E-mail Adress" required>
            <button type="submit">Send</button>
        </form>
        <div id="alert-container"></div>
    </div>
</body>
</html>
