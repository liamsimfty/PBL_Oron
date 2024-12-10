<?php
session_start();
include '../connection/connection.php';

$account_id = $_SESSION['account_id'];
$paymentProducts = $_SESSION['payment_data'];
try {
    // Deklarasikan variabel untuk menyimpan transaction_date
    $transaction_date = null;
    $token_id = rand();

    // Loop untuk setiap produk
    foreach ($paymentProducts as $product) {
        $transaction_id = rand();
        // Dapatkan waktu transaksi (gunakan fungsi date() untuk waktu saat ini)
        if ($transaction_date === null) {
            $transaction_date = date('Y-m-d H:i:s'); // Format: YYYY-MM-DD HH:MM:SS
        }

        $insertQuery = "INSERT INTO transaction (
                transaction_id, 
                account_id, 
                product_id, 
                token_id,
                price_at_checkout,
                transaction_date
            ) VALUES (
                $transaction_id, 
                :account_id, 
                :product_id, 
                $token_id,
                :price_at_checkout,
                TO_TIMESTAMP(:transaction_date, 'YYYY-MM-DD HH24:MI:SS')
            )";

        // Persiapkan statement
        $insertStmt = oci_parse($conn, $insertQuery);

        // Bind parameter
        oci_bind_by_name($insertStmt, ":account_id", $account_id);
        oci_bind_by_name($insertStmt, ":product_id", $product['product_id']);
        
        oci_bind_by_name($insertStmt, ":price_at_checkout", $product['total_price']);
        oci_bind_by_name($insertStmt, ":transaction_date", $transaction_date);

        $result = oci_execute($insertStmt);

        // Bebaskan statement
        oci_free_statement($insertStmt);
    }

    if ($transaction_date) {
        $_SESSION['transaction_date'] = $transaction_date;
    }

} catch (Exception $e) {
    // Tangani error
    echo "Error: " . $e->getMessage();
}
unset($_SESSION['payment_data']);
header("Location:process2.php?token_id=$token_id");
?>