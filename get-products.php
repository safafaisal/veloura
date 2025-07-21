<?php
// Always set the correct content type
header('Content-Type: application/json');

// Start session if needed (optional, only if you're doing any session-based filtering)
// if (session_status() === PHP_SESSION_NONE) {
//     session_start();
// }

// Connect to DB
include("db/db.php");

// ✅ Optional filter by category using GET parameter like ?category=men or ?category=women
$category = isset($_GET['category']) ? $_GET['category'] : null;

// Build SQL query safely
if ($category === 'men' || $category === 'women') {
    $stmt = $conn->prepare("SELECT id, name, price, image, category FROM products WHERE category = ?");
    $stmt->bind_param("s", $category);
} else {
    $stmt = $conn->prepare("SELECT id, name, price, image, category FROM products");
}

$stmt->execute();
$result = $stmt->get_result();

// Fetch all products
$products = [];
while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}

// Output as JSON
echo json_encode($products, JSON_PRETTY_PRINT);

// Cleanup
$stmt->close();
$conn->close();
?>
