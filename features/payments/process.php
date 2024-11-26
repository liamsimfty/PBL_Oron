<?php
session_start();
include '../connection/connection.php';

if (!isset($_SESSION['payment_products']) || !isset($_SESSION['account_id'])) {
    die("Invalid access");
}

$accountId = $_SESSION['account_id'];
$paymentProducts = $_SESSION['payment_products'];

foreach ($paymentProducts as $product) {
    // Generate unique transaction ID
    $transactionId = rand();

    // Insert transaction, including the price_at_checkout column
    $insertQuery = "INSERT INTO transaction (
        transaction_id, 
        account_id, 
        product_id, 
        payment_status, 
        payment_gateway, 
        price_at_checkout
    ) VALUES (
        :transaction_id, 
        :account_id, 
        :product_id, 
        'pending', 
        'midtrans', 
        :price_at_checkout
    )";

    $insertStmt = oci_parse($conn, $insertQuery);

    // Bind parameters
    oci_bind_by_name($insertStmt, ":transaction_id", $transactionId);
    oci_bind_by_name($insertStmt, ":account_id", $accountId);
    oci_bind_by_name($insertStmt, ":product_id", $product['product_id']);
    oci_bind_by_name($insertStmt, ":price_at_checkout", $product['final_price']); // Use final price from session

    // Execute query
    oci_execute($insertStmt);
    oci_free_statement($insertStmt);
}

header("Location:payment.php?transaction_id=$transaction_id");
?>
