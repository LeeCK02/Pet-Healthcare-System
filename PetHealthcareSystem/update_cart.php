<?php
session_start();
include 'config/config.php'; // Database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the posted data
    $data = json_decode(file_get_contents('php://input'), true);
    $cart_id = $data['cart_id'];
    $quantity = $data['quantity'];

    // Validate input
    if (!empty($cart_id) && is_numeric($quantity) && $quantity > 0) {
        // Update the quantity in the cart
        $query = "UPDATE cart SET quantity = '$quantity' WHERE cart_id = '$cart_id' AND user_id = '{$_SESSION['user_id']}'";

        if (mysqli_query($conn, $query)) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Database error: ' . mysqli_error($conn)]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid input']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}

mysqli_close($conn);
?>
