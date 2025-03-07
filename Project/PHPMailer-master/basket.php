<?php
require 'connection.php';
session_start();
$isLoggedIn = isset($_SESSION['user']);

if (!isset($_SESSION['user_id'])) {
    echo "Please Login!";
    exit;
}

$userId = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT id, product_name, product_price, quantity FROM cart WHERE user_id = ?");
$stmt->execute([$userId]);
$cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Basket</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/png" href="images/nike_logo.png">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }

        .container {
            max-width: 900px;
            margin: 50px auto;
            background: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            padding: 20px;
        }

        h1 {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
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

        .checkout {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 5px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        .checkout:hover {
            background-color: #45a049;
        }

    </style>
</head>
<body>
<nav>
        <div class="logo">
            <a href="index.php">
                <img src="images/Logo.png" alt="Logo">
            </a>
        </div>
        <ul class="nav-links">
            <li><a href="#">Men</a></li>
            <li><a href="#">Women</a></li>
            <li><a href="#">Kids</a></li>
            <li><a href="#">Accessories</a></li>
        </ul>
        <div class="icons">
            <?php if ($isLoggedIn): ?>
                <a href="profile.php" class="profile-link">
                    <?= htmlspecialchars($_SESSION['user']); ?>
                    <i class="bi bi-person"></i>
                </a>

            <?php else: ?>
                <a href="login.php">
                    <i class="bi bi-person"></i> 
                </a>
            <?php endif; ?>

            <i class="bi bi-search"></i>
            <a href="cart.php">
                <i class="bi bi-bag"></i>
            </a>
        </div>
    </nav>
    <div class="container">
        <h1>Basket</h1>

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

            <div class="total-container">
                Total: <?= number_format($total, 2); ?> $
                <button class="checkout" onclick="window.location.href='purchase.php';">Checkout</button>
            </div>
        <?php else: ?>
            <p class="empty-message">There are no products in your cart.</p>
        <?php endif; ?>
    </div>

    <script>
        document.addEventListener("click", function (e) {
            if (e.target.classList.contains("remove-btn")) {
                const productId = e.target.dataset.id;

                fetch("cart_delete.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify({ productId: productId }),
                })
                    .then((response) => response.json())
                    .then((data) => {
                        if (data.success) {
                            const row = e.target.closest(".cart-row");
                            if (row) {
                                row.remove();
                            }

                            const totalContainer = document.querySelector(".total-container");
                            if (totalContainer) {
                                totalContainer.textContent = `Total: ${data.newTotal.toFixed(2)} $`;
                            }
                        } else {
                            alert(data.message);
                        }
                    })
                    .catch((error) => {
                        console.error("Error:", error);
                    });
            }
        });
    </script>
</body>
</html>