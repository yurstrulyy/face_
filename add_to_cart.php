<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $productName = $_POST['product_name'] ?? '';
    $productDesc = $_POST['product_desc'] ?? '';

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    $_SESSION['cart'][] = [
        'name' => $productName,
        'desc' => $productDesc
    ];

    echo json_encode(["success" => true, "message" => "Added to cart"]);
}
?>
