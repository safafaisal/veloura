<?php
session_start();
include 'navbar.php';
include 'db/db.php';
include 'includes/activity_logger.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['product_id'])) {
    $productId = $_POST['product_id'];

    // Get product from database
    $sql = "SELECT * FROM products WHERE id = $productId";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $product = $result->fetch_assoc();

        // Cart logic
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        if (isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId]['quantity']++;
        } else {
            $_SESSION['cart'][$productId] = [
                'name' => $product['name'],
                'price' => $product['price'],
                'image' => $product['image'],
                'quantity' => 1
            ];
        }
        
        // Log add to cart activity
        logAddToCart($product['name'], $_SESSION['cart'][$productId]['quantity']);
    }
}

header("Location: cart.php");
exit();
?>
