<?php
session_start();
include '../connection/connection.php';

// Check if the necessary session variables are set
if (!isset($_SESSION['payment_products']) || !isset($_SESSION['account_id'])) {
    die("Invalid access");
}

// Retrieve account ID and payment products from the session
$accountId = $_SESSION['product_id'];
$price_at_checkout = $_SESSION['total_price'];

echo "Favorite animal is " . $_SESSION["totalPrice"] . ".";


// Output the final price
echo $totalPrice;

?>