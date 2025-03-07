<?php
require 'db_connection.php';

session_start();
header('Content-Type: application/json');

if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];

    $data = json_decode(file_get_contents("php://input"), true);

    if ($data) {
        $productName = $data['productName'];
        $productImage = $data['productImage'];
        $productPrice = floatval(str_replace('$', '', $data['productPrice']));
        $productSize = $data['productSize'];

        try {
            $stmt = $pdo->prepare("SELECT id, quantity FROM cart WHERE user_id = ? AND product_name = ? AND product_size = ?");
            $stmt->execute([$userId, $productName, $productSize]);
            $existingProduct = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($existingProduct) {
                $stmt = $pdo->prepare("UPDATE cart SET quantity = quantity + 1 WHERE id = ?");
                $stmt->execute([$existingProduct['id']]);
            } else {
                $stmt = $pdo->prepare("INSERT INTO cart (user_id, product_name, product_image, product_price, product_size, quantity) 
                                       VALUES (?, ?, ?, ?, ?, 1)");
                $stmt->execute([$userId, $productName, $productImage, $productPrice, $productSize]);
            }

            if (!isset($_SESSION['cart'])) {
                $_SESSION['cart'] = [];
            }

            $productFound = false;
            foreach ($_SESSION['cart'] as &$item) {
                if ($item['name'] === $productName && $item['size'] === $productSize) {
                    $item['quantity'] += 1;
                    $productFound = true;
                    break;
                }
            }

            if (!$productFound) {
                $_SESSION['cart'][] = [
                    'name' => $productName,
                    'image' => $productImage,
                    'price' => $productPrice,
                    'size' => $productSize,
                    'quantity' => 1
                ];
            }

            echo json_encode(['success' => true, 'message' => 'Product successfully added to the cart.']);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
            exit;
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid data sent.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Please log in.']);
}
?>