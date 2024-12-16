<?php
// This is just for very basic implementation reference, in production, you should validate the incoming requests and implement your backend more securely.
// Please refer to this docs for sample HTTP notifications:
// https://docs.midtrans.com/en/after-payment/http-notification?id=sample-of-different-payment-channels

namespace Midtrans;
include '../connection/connection.php';
require_once dirname(__FILE__) . '/../../vendor/midtrans/midtrans-php/Midtrans.php';
Config::$isProduction = false;
Config::$serverKey = $_ENV['MIDTRANS_SERVER_KEY'];
session_start();
$account_id = $_SESSION['account_id'];

// non-relevant function only used for demo/example purpose
printExampleWarningMessage();

try {
    $notif = new Notification();
}
catch (\Exception $e) {
    exit($e->getMessage());
}

$notif = $notif->getResponse();
$transaction = $notif->transaction_status;
$type = $notif->payment_type;
$order_id = $notif->order_id;
$fraud = $notif->fraud_status;

if ($transaction == 'capture') {
    // For credit card transaction, we need to check whether transaction is challenge by FDS or not
    if ($type == 'credit_card') {
        if ($fraud == 'challenge') {
            // TODO set payment status in merchant's database to 'Challenge by FDS'
            // TODO merchant should decide whether this transaction is authorized or not in MAP
            echo "Transaction order_id: " . $order_id ." is challenged by FDS";
        } else {
            // TODO set payment status in merchant's database to 'Success'
            echo "Transaction order_id: " . $order_id ." successfully captured using " . $type;
        }
    }
} else if ($transaction == 'settlement') {
    // Update payment status in the transaction table
    $updateQuery = "UPDATE transaction SET payment_status = 'Paid' WHERE token_id = :order_id";
    $updateStmt = oci_parse($conn, $updateQuery);
    oci_bind_by_name($updateStmt, ':order_id', $order_id);
    $result = oci_execute($updateStmt);

    // Check if the payment status update was successful
    if ($result) {
        // Insert into the library table
        $insertQuery = "
        INSERT INTO library (product_id, account_id, purchase_date)
        SELECT product_id, account_id, CURRENT_TIMESTAMP 
        FROM transaction 
        WHERE token_id = :order_id";

        $insertStmt = oci_parse($conn, $insertQuery);
        oci_bind_by_name($insertStmt, ':order_id', $order_id);
        $insertResult = oci_execute($insertStmt);

        if ($insertResult) {
            echo "Transaction successfully settled and product added to the library.";
        } else {
            echo "Failed to insert product into the library.";
        }

        oci_free_statement($insertStmt);
    } else {
        echo "Failed to update transaction status.";
    }

    oci_free_statement($updateStmt);
}  else if ($transaction == 'deny') {
    $updateQuery = "UPDATE transaction SET payment_status = 'deny' WHERE token_id = :order_id";
    $updateStmt = oci_parse($conn, $updateQuery);
    oci_bind_by_name($updateStmt, ':order_id', $order_id);
    $result = oci_execute($updateStmt);
} else if ($transaction == 'expire') {
    $updateQuery = "UPDATE transaction SET payment_status = 'expire' WHERE token_id = :order_id";
    $updateStmt = oci_parse($conn, $updateQuery);
    oci_bind_by_name($updateStmt, ':order_id', $order_id);
    $result = oci_execute($updateStmt);
} else if ($transaction == 'cancel') {  
    $updateQuery = "UPDATE transaction SET payment_status = 'cancel' WHERE token_id = :order_id";
    $updateStmt = oci_parse($conn, $updateQuery);
    oci_bind_by_name($updateStmt, ':order_id', $order_id);
    $result = oci_execute($updateStmt);
}

function printExampleWarningMessage() {
    if ($_SERVER['REQUEST_METHOD'] != 'POST') {
        echo 'Notification-handler are not meant to be opened via browser / GET HTTP method. It is used to handle Midtrans HTTP POST notification / webhook.';
    }
    if (strpos(Config::$serverKey, 'your ') != false ) {
        echo "<code>";
        echo "<h4>Please set your server key from sandbox</h4>";
        echo "In file: " . __FILE__;
        echo "<br>";
        echo "<br>";
        echo htmlspecialchars('Config::$serverKey = \'<your server key>\';');
        die();
    }   
}
