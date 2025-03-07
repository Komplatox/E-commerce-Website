<?php
require 'connection.php';

session_start();
$isLoggedIn = isset($_SESSION['user']);
$userId = null;

if ($isLoggedIn) {
    $username = $_SESSION['user'];
    
    $stmt = $pdo->prepare("SELECT id FROM users WHERE firstname = ?");
    $stmt->execute([$username]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($result) {
        $userId = $result['id'];
    }
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
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #e6e6e6;
            font-family: Arial, sans-serif;
        }

        .container {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            margin: 50px auto;
            max-width: 1200px;
        }

        .cart-table {
            flex: 2;
            background-color: #f7f7f7;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .payment-info {
            flex: 1;
            background-color: #dcdcdc;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .payment-info h3 {
            margin-bottom: 20px;
        }

        .checkout-btn {
            background-color: #000;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }

        .checkout-btn:hover {
            background-color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        th, td {
            padding: 10px;
            text-align: center;
            border-bottom: 1px solid #ccc;
        }

        th {
            background-color: #b3b3b3;
            width: 20%;
        }

        td {
            word-wrap: break-word;
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
        <div class="cart-table">
            <h3>Shopping Cart</h3>
            <table>
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
                    <?php if (count($cartItems) > 0): ?>
                        <?php foreach ($cartItems as $item): ?>
                            <tr class="cart-row" data-id="<?= $item['id']; ?>">
                                <td><?= htmlspecialchars($item['product_name']); ?></td>
                                <td><?= number_format($item['product_price'], 2); ?> $</td>
                                <td>
                                    <button class="quantity-btn" data-action="decrease" data-id="<?= $item['id']; ?>">-</button>
                                    <span class="quantity"><?= $item['quantity']; ?></span>
                                    <button class="quantity-btn" data-action="increase" data-id="<?= $item['id']; ?>">+</button>
                                </td>
                                <td><?= number_format($item['product_price'] * $item['quantity'], 2); ?> $</td>
                                <td>
                                    <button class="remove-btn" data-id="<?= $item['id']; ?>">Remove</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="empty-message">Your cart is empty.</p>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="payment-info">
            <h3>Payment Info.</h3>
            <form>
                <div class="mb-3">
                    <label>Purchase Method</label><br>
                    <input type="radio" name="payment" value="credit" id="credit"> 
                    <label for="credit">Credit Card</label><br>
                    <input type="radio" name="payment" value="paypal" id="paypal"> 
                    <label for="paypal">Paypal</label>
                </div>

                <div class="mb-3">
                    <label for="name">Name and Surname</label>
                    <input type="text" id="name" class="form-control" placeholder="Enter your name">
                </div>
                <div class="mb-3">
                    <label for="card-number">Card Number</label>
                    <input type="text" id="card-number" class="form-control" placeholder="1234 5678 9012 3456">
                </div>
                <div class="mb-3 d-flex gap-2">
                    <div>
                        <label for="exp-date">Date</label>
                        <select id="exp-date" class="form-select">
                            <option>MM</option>
                            <option>01</option>
                            <option>02</option>
                        </select>
                    </div>
                    <div>
                        <label for="exp-year">&nbsp;</label>
                        <select id="exp-year" class="form-select">
                            <option>YYYY</option>
                            <option>2025</option>
                            <option>2026</option>
                        </select>
                    </div>
                    <div>
                        <label for="cvv">CVV</label>
                        <input type="text" id="cvv" class="form-control" placeholder="123">
                    </div>
                </div>
                <button type="submit" class="checkout-btn">Check Out</button>
            </form>
        </div>
    </div>

    <script>
        const buttons = document.querySelectorAll('.quantity-btn');

        buttons.forEach(button => {
            button.addEventListener('click', function() {
                const action = this.getAttribute('data-action');
                const productId = this.getAttribute('data-id');
                const quantitySpan = this.parentElement.querySelector('.quantity');
                let currentQuantity = parseInt(quantitySpan.textContent);

                if (action === 'increase') {
                    currentQuantity++;
                } else if (action === 'decrease' && currentQuantity > 1) {
                    currentQuantity--;
                }

                fetch('update_cart.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        product_id: productId,
                        quantity: currentQuantity
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        quantitySpan.textContent = currentQuantity;
                    } else {
                        alert('An error occurred. Please try again.');
                    }
                });
            });
        });
    </script>
</body>
</html>