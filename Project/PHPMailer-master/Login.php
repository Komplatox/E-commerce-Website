<?php
session_start();

if (isset($_SESSION['user'])) {
    header("Location: profile.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include('connection.php');
    
    $email = mysqli_real_escape_string($connect, $_POST['email']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($connect, $sql);
    
    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);

        if (password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user['firstname']; 
            $_SESSION['user_id'] = $user['id']; 

            header("Location: profile.php");
            exit();
        } else {
            $error = "Incorrect password.";
        }
    } else {
        $error = "Email address not found.";
    }
    mysqli_close($connect);
}

$message = '';
$alertClass = '';

if (isset($_GET['msg']) && $_GET['msg'] === 'success') {
    $message = "Your registration was successful. You can log in.";
    $alertClass = "alert-success";
} elseif (isset($_GET['msg']) && $_GET['msg'] === 'failed') {
    $message = "Login failed! Please check your information.";
    $alertClass = "alert-danger";
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
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
            width: 400px;
            margin: auto;
        }
        form input {
            padding: 10px;
            width: 100%;
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
    </style>
</head>
<body>

    <div class="container">
        <h1>Login</h1>
        <?php if ($message): ?>
            <div class="alert <?php echo $alertClass; ?> alert-dismissible fade show" role="alert">
                <?php echo $message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <form method="POST" action="login.php"> 
            <input type="email" name="email" placeholder="Email address" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <button type="submit">Login</button>
        </form>
        
        <br>
        <p>Don't have an account? <a href="confirmation.php">Sign up</a></p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>