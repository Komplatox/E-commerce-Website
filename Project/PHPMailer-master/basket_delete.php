<?php
require 'connection.php';

session_start();
header('Content-Type: application/json');

if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];

    $data = json_decode(file_get_contents("php://input"), true);

    if ($data) {
        $productId = $data['productId'];

        try {
            $stmt = $pdo->prepare("DELETE FROM cart WHERE id = ? AND user_id = ?");
            $stmt->execute([$productId, $userId]);

            if ($stmt->rowCount() > 0) {
                echo json_encode(['success' => true, 'message' => 'Product successfully removed.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Product not found.']);
            }
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid data sent.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Please log in.']);
}
?>