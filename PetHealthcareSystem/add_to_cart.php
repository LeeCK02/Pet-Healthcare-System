<?php
session_start();
include 'config/config.php'; // database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $product_id = $data['product_id'];
    $name = $data['name'];
    $price = $data['price'];
    $image = $data['image'];
    $user_id = $data['user_id'];

    // Check if the item is already in the cart
    $query = "SELECT * FROM cart WHERE product_id = ? AND user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $product_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Item already in cart
        echo json_encode(['success' => false]);
    } else {
        // Insert into cart
        $insertQuery = "INSERT INTO cart (product_id, name, price, quantity, image, user_id) VALUES (?, ?, ?, 1, ?, ?)";
        $insertStmt = $conn->prepare($insertQuery);
        $insertStmt->bind_param("isdsi", $product_id, $name, $price, $image, $user_id);
        $insertStmt->execute();
        echo json_encode(['success' => true]);
    }
    $stmt->close();
    $conn->close();
}
?>
