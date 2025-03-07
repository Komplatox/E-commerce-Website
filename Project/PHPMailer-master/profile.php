<?php
session_start();
require 'connection.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}
$isLoggedIn = isset($_SESSION['user']);

if ($isLoggedIn) {
    $username = $_SESSION['user'];
    
    $stmt = $pdo->prepare("SELECT id FROM users WHERE firstname = ?");
    $stmt->execute([$username]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($result) {
        $userId = $result['id'];
    }
    }


$user = $_SESSION['user'];

$stmt = $pdo->prepare("SELECT id, product_name, product_price, quantity FROM cart WHERE user_id = ?");
$stmt->execute([$userId]);
$cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Page</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<style>
    body {
        background-color: #e6e6e6;
        font-family: Arial, sans-serif;
    }

    .container-wrapper {
        display: flex;
        gap: 20px;
        padding: 20px;
    }

    .profile-container {
        flex: 1;
        max-width: 350px;
        background-color: #fff;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .profile-avatar-wrapper {
    position: relative;
    width: 100px;
    height: 100px;
    margin: 0 auto;
    cursor: pointer;
}

.profile-avatar {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #ccc;
}

.upload-hover {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    color: white;
    font-size: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    opacity: 0;
    transition: opacity 0.3s ease;
    z-index: 1;
}

.profile-avatar-wrapper:hover .upload-hover {
    opacity: 1;
}

    h2 {
        text-align: center;
        font-size: 20px;
        margin: 10px 0;
    }

    .edit-icon {
        font-size: 14px;
        cursor: pointer;
    }

    form label {
        display: block;
        margin-top: 10px;
        font-weight: bold;
    }

    form input, form textarea {
        width: 100%;
        padding: 10px;
        margin-top: 5px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    .buttons {
        display: flex;
        justify-content: space-between;
        margin-top: 15px;
    }

    .btn-save {
        background-color: #4CAF50;
        color: white;
        border: none;
        padding: 10px 15px;
        border-radius: 5px;
        cursor: pointer;
    }

    .btn-logout {
        background-color: #FF0000;
        color: white;
        border: none;
        padding: 10px 15px;
        border-radius: 5px;
        text-decoration: none;
    }

    .content-container {
        flex: 3;
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .stats {
        display: flex;
        justify-content: space-between;
        gap: 5px;
        padding: 10px;
    }

    .stat-box {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        background-color: #fff;
        border-radius: 10px;
        padding: 20px;
        text-align: center;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        cursor: pointer;
        transition: transform 0.2s ease, background-color 0.2s ease;
    }

    .stat-box:hover {
        background-color: #f0f0f0;
        transform: translateY(-5px);
    }

    .stat-box h3 {
        font-size: 32px;
        margin: 0;
        color: #333;
    }

    .stat-box p {
        font-size: 16px;
        color: #777;
        margin: 10px 0 0;
    }

    .main-content {
        flex: 1;
        background-color: #fff;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .cart-table {
        width: 100%;
        border-collapse: collapse;
    }

    .cart-table th, .cart-table td {
        text-align: left;
        padding: 15px;
    }

    .cart-table th {
        background-color: #f4f4f4;
        color: #555;
    }

    .cart-table tr {
        border-bottom: 1px solid #ddd;
    }

    .cart-table tr:last-child {
        border-bottom: none;
    }

    .remove-btn {
        background: #ff5c5c;
        color: #fff;
        border: none;
        padding: 8px 12px;
        border-radius: 4px;
        cursor: pointer;
        transition: background 0.3s ease;
    }

    .remove-btn:hover {
        background: #e60000;
    }

    .total-container {
        margin-top: 20px;
        text-align: right;
        font-size: 18px;
        font-weight: bold;
    }

    .empty-message {
        text-align: center;
        font-size: 18px;
        color: #777;
        margin-top: 50px;
    }

</style>
<body>
<nav>
        <div class="logo">
            <a href="index.php">
                <img href="index.html" src="images/Logo.png" alt="">
            </a>
        </div>
        <ul class="nav-links">
            <li><a href="#">Men</a></li>
            <li><a href="#">Women</a></li>
            <li><a href="#">Child</a></li>
            <li><a href="#">Accessory</a></li>
        </ul>
        <div class="icons">
            <?php if ($isLoggedIn): ?>
                <a href="profile.php"> 
                    Welcome, <?php echo $_SESSION['user']; ?><i class="bi bi-person"></i> 
                </a>
            <?php else: ?>
                <a href="login.php">
                    <i class="bi bi-person"></i> 
                </a>
            <?php endif; ?>

            <i class="bi bi-search"></i>
            <a href="basket.php">
                <i class="bi bi-bag"></i>
            </a>
        </div>
    </nav>

    <div class="container-wrapper">
    <div class="profile-container">
        <div class="profile-card">
        <div class="profile-avatar-wrapper">
            <img src="images/Logo.png" alt="Avatar" class="profile-avatar">
            <div class="upload-hover" id="hoverUpload">Upload Photo</div>
            <input type="file" id="fileInput" accept="image/*" style="display: none;">
        </div>

            <h2><strong><?php echo htmlspecialchars($user); ?></strong> <span class="edit-icon">✎</span></h2>
            <form>
                <label for="email">Email</label>
                <input type="email" id="email" placeholder="Email adresinizi girin">
                <label for="address">Address</label>
                <textarea id="address" rows="4" placeholder="Adresinizi girin"></textarea>
                <div class="buttons">
                    <button type="submit" class="btn-save">Save</button>
                    <a href="logout.php" class="btn-logout">Logout</a>
                </div>
            </form>
        </div>
    </div>

    <div class="content-container">
        <div class="stats">
        <div class="stat-box" id="orders">
            <h3>0</h3>
            <p>My Orders</p>
        </div>
        <div class="stat-box" id="purchased">
            <h3>0</h3>
            <p>Purchased</p>
        </div>
        <div class="stat-box" id="cancelled">
            <h3>0</h3>
            <p>Cancelled</p>
        </div>
        </div>

        <div class="main-content">
        <?php if (count($cartItems) > 0): ?>
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $total = 0;
                    foreach ($cartItems as $item): 
                        $subtotal = $item['product_price'] * $item['quantity'];
                        $total += $subtotal;
                    ?>
                        <tr class="cart-row">
                            <td><?= htmlspecialchars($item['product_name']); ?></td>
                            <td><?= number_format($item['product_price'], 2); ?> $</td>
                            <td><?= $item['quantity']; ?></td>
                            <td><?= number_format($subtotal, 2); ?> $</td>
                            <td>
                                <button class="remove-btn" data-id="<?= $item['id']; ?>">Delete</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

        <?php else: ?>
            <p class="empty-message">Your cart is empty.</p>
        <?php endif; ?>
        
    </div>
        </div>
        
    </div>
    
</div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('orders').addEventListener('click', function () {
            alert("Siparişlerim görüntüleniyor.");
        });

        document.getElementById('purchased').addEventListener('click', function () {
            alert("Satın alınan ürünler görüntüleniyor.");
        });

        document.getElementById('cancelled').addEventListener('click', function () {
            alert("İptal edilen siparişlere bakıyorsunuz.");
        });

        document.getElementById('hoverUpload').addEventListener('click', function () {
            document.getElementById('fileInput').click();
        });

        document.getElementById('fileInput').addEventListener('change', function (event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    document.querySelector('.profile-avatar').src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });


</script>

</body>
</html>
