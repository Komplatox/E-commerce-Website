<?php

include('connection.php');

$firstname = isset($_POST['firstname']) ? mysqli_real_escape_string($connect, $_POST['firstname']) : '';
$lastname = isset($_POST['lastname']) ? mysqli_real_escape_string($connect, $_POST['lastname']) : '';
$email = isset($_POST['email']) ? mysqli_real_escape_string($connect, $_POST['email']) : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';
$confirmpassword = isset($_POST['confirmpassword']) ? $_POST['confirmpassword'] : '';

if ($password !== $confirmpassword) {

    $message = "Passwords do not match!";
    $alertClass = "alert-danger"; 

} else {
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (firstname, lastname, email, password) VALUES ('$firstname', '$lastname', '$email', '$passwordHash')";

    if (mysqli_query($connect, $sql)) {
        $message = "Your registration was successful!";
        $alertClass = "alert-success";

        header("Location: login.php?msg=success");
        exit();
    } else {
        $message = "Registration failed! Please try again.";
        $alertClass = "alert-danger";
    }
}

mysqli_close($connect);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
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
    </style>
</head>
<body>
    <div class="container">
        <h1>Create Your User Account</h1>

        <?php if (isset($message)): ?>
            <div class="alert <?php echo $alertClass; ?> alert-dismissible fade show" role="alert">
                <?php echo $message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <form method="POST" action="register.php">
            <input type="text" name="firstname" placeholder="First Name" required><br>
            <input type="text" name="lastname" placeholder="Last Name" required><br>
            <input type="email" name="email" placeholder="Email Address" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <input type="password" name="confirmpassword" placeholder="Confirm Password" required><br>
            <button type="submit">Register</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>