<?php
session_start();
include '../connection/connection.php';

$account_id = $_SESSION['account_id'];
$paymentProducts = $_SESSION['payment_data'];
try {
    // Loop untuk setiap produk
    foreach ($paymentProducts as $product) {
        // Query untuk menyimpan transaksi
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
            :payment_status, 
            :payment_gateway, 
            :price_at_checkout
        )";

        // Persiapkan statement
        $insertStmt = oci_parse($conn, $insertQuery);

        // Bind parameter
        oci_bind_by_name($insertStmt, ":transaction_id", $product['transaction_id']);
        oci_bind_by_name($insertStmt, ":account_id", $account_id);
        oci_bind_by_name($insertStmt, ":product_id", $product['product_id']);
        
        // Default values sesuai definisi tabel
        $payment_status = 'pending';
        $payment_gateway = 'midtrans';
        oci_bind_by_name($insertStmt, ":payment_status", $payment_status);
        oci_bind_by_name($insertStmt, ":payment_gateway", $payment_gateway);
        oci_bind_by_name($insertStmt, ":price_at_checkout", $product['total_price']);

        // Eksekusi query
        $result = oci_execute($insertStmt);

        if ($result) {
            // Transaksi berhasil disimpan untuk produk ini
            echo "Transaksi berhasil dibuat untuk produk: " . $product['product_id'] . "<br>";
        } else {
            // Gagal menyimpan transaksi
            $error = oci_error($insertStmt);
            echo "Gagal membuat transaksi untuk produk: " . $product['product_id'] . " - " . $error['message'] . "<br>";
        }

        // Bebaskan statement
        oci_free_statement($insertStmt);
    }

} catch (Exception $e) {
    // Tangani error
    echo "Error: " . $e->getMessage();
}
unset($_SESSION['payment_data']);
header("Location:payment.php");
?>