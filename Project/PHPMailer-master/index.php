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
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nike Product Page</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="icon" type="image/png" href="images/nike_logo.png">
</head>
<body>
    <nav>
        <div class="logo">
            <img href="index.html" src="images/Logo.png" alt="">
        </div>
        <ul class="nav-links">
            <li><a href="#">Men</a></li>
            <li><a href="#">Women</a></li>
            <li><a href="#">Kids</a></li>
            <li><a href="#">Accessories</a></li>
        </ul>
        <div class="icons">
            <?php if ($isLoggedIn): ?>
                <a href="profile.php"> 
                    <?php echo $_SESSION['user']; ?>
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
    <main>
        <div id="alert-container" class="alert-container"></div>
        <div class="product-container">
            <h1 class="background-title">Nike Free Metcon 6</h1>
            <div class="product-image">
                <img id="shoeImage" src="images/Nike_black.png" alt="Nike Shoes">
            </div>
            <div class="product-details" data-product-name="Nike Free Metcon 6">
                <h1>Nike Free Metcon 6</h1>
                <p class="price">125$</p>
                <p class="description">
                    It is made of 94% leather and 6% synthetic material, which ensures durability and comfort.
                    The foam midsole with iconic padding offers a comfortable structure from the first moment.
                </p>
                <div class="colors">
                    <div class="color" style="background-color: #000000;" data-image="images/Nike_black.png"></div>
                    <div class="color" style="background-color: #F56565;" data-image="images/Nike_pink.png"></div>
                    <div class="color" style="background-color: #ED8936;" data-image="images/Nike_orange.png"></div>
                </div>
                
                <div class="actions">
                    <select>
                        <option value="8.5">US 8.5</option>
                        <option value="9">US 9</option>
                        <option value="10">US 10</option>
                    </select>
                    <button onclick="addToCart(this)" class="add-to-basket">Add To Basket</button>
                </div>
                
            </div>
        </div>
    </main>
    
    <section id="products">
        <div class="product" 
             data-product-img="images/Nike_black.png" 
             data-product-name="Nike_black" 
             data-product-price="15000" 
             data-product-link="1.html">
            <img src="images/Nike_black.png" alt="Nike Black">
        </div>
        <div class="product" 
             data-product-img="images/Nike_orange.png" 
             data-product-name="Nike_orange" 
             data-product-price="16000" 
             data-product-link="1.html">
            <img src="images/Nike_orange.png" alt="Nike Orange">
        </div>
        <div class="product" 
             data-product-img="images/Nike_pink.png" 
             data-product-name="Nike_pink" 
             data-product-price="17000" 
             data-product-link="1.html">
            <img src="images/Nike_pink.png" alt="Nike Pink">
        </div>
    </section>
    
</body>

<script>
    const colorBoxes = document.querySelectorAll('.color');
    const shoeImage = document.getElementById('shoeImage');

    colorBoxes.forEach(colorBox => {
        colorBox.addEventListener('click', () => {
            const newImage = colorBox.getAttribute('data-image');
            shoeImage.src = newImage;
        });
    });

    const products = document.querySelectorAll('.product');

    products.forEach(product => {
        product.addEventListener('click', () => {
            const productLink = product.getAttribute('data-product-link');
            if (productLink) {
                window.location.href = productLink;
            }
        });
    });

    function addToCart(button) {
        const productDetails = button.closest('.product-details');
        const productName = productDetails.getAttribute('data-product-name');
        const productImageSrc = document.getElementById('shoeImage').src;
        const productPrice = productDetails.querySelector('.price').textContent.trim(); 

        const selectedSize = productDetails.querySelector('select').value;

        fetch('add_to_cart.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                productName: productName,
                productImage: productImageSrc,
                productPrice: productPrice,
                productSize: selectedSize
            })
        })

        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const alertContainer = document.getElementById('alert-container');
                const alertMessage = `
                    <div class="alert-content">
                        <img src="${productImageSrc}" alt="${productName}" class="alert-img">
                        <div class="alert-text">
                            <strong>${productName}</strong> has been added to the cart.
                        </div>
                        <button class="close-alert" onclick="closeAlert()">×</button>
                    </div>
                `;
                
                alertContainer.innerHTML = alertMessage;
                alertContainer.classList.add('show');

                setTimeout(() => {
                    if (alertContainer.innerHTML !== '') {
                        alertContainer.classList.add('hide');
                        setTimeout(() => {
                            alertContainer.classList.remove('show', 'hide');
                            alertContainer.innerHTML = '';
                        }, 500);
                    }
                }, 3000);
            } else {
                alert('An error occurred while adding the product to the cart!');
            }
        })
        .catch(error => console.error('Error:', error));
    }

    function closeAlert() {
        const alertContainer = document.getElementById('alert-container');
        alertContainer.classList.add('hide');
        setTimeout(() => {
            alertContainer.classList.remove('show', 'hide');
            alertContainer.innerHTML = '';
        }, 500);
    }
</script>
</html>