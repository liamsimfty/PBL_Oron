<?php
namespace Midtrans;
session_start();
include '../connection/connection.php';

$account_id = $_SESSION['account_id'];
$username = $_SESSION['username'];
//$paymentProducts = $_SESSION['payment_products'];
// This is just for very basic implementation reference, in production, you should validate the incoming requests and implement your backend more securely.
// Please refer to this docs for snap popup:
// https://docs.midtrans.com/en/snap/integration-guide?id=integration-steps-overview


require_once dirname(__FILE__) . '/../../vendor/midtrans/midtrans-php/Midtrans.php';
// Set Your server key
// can find in Merchant Portal -> Settings -> Access keys
Config::$serverKey = 'SB-Mid-server-Uq1HNq-vNEmFST7AKmQf5ofo';
Config::$clientKey = 'SB-Mid-client-FdXCfowRdFheZ1z6';

// non-relevant function only used for demo/example purpose
printExampleWarningMessage();

// Uncomment for production environment
// Config::$isProduction = true;
Config::$isSanitized = Config::$is3ds = true;

// Initialize the arrays to store the transaction and item details
$transaction_details = array();
$item_details = array();

isset($_GET['transaction_id']);
$transaction_id = $_GET['transaction_id'];

$query = "
    SELECT 
        t.transaction_id, 
        t.product_id, 
        t.price_at_checkout AS final_price, 
        p.name AS name
    FROM transaction t
    JOIN products p ON t.product_id = p.product_id
    WHERE t.account_id = :account_id AND t.payment_status = 'pending'
";
$stid = oci_parse($conn, $query);
oci_bind_by_name($stid, ":account_id", $accountId);
oci_execute($stid);

// Initialize the arrays to store the transaction and item details
$transaction_details = array();
$item_details = array();

isset($_GET['transaction_id']);
$transaction_id = $_GET['transaction_id'];

while (($row = oci_fetch_assoc($stid)) != false) {
    $transaction_id = $row['TRANSACTION_ID'];
    $finalprice = $row['FINAL_PRICE'];
    $product_id = $row['PRODUCT_ID'];
    $name = $row['NAME'];

    // Populate $transaction_details
    $transaction_details = array(
        'order_id' => $transaction_id,
        'gross_amount' => $finalprice
    );

    // Populate $item_details
    $item_details[] = array(
        'id' => $product_id,
        'price' => $finalprice * 100, // Convert to cents if needed
        'quantity' => 1, // Assuming 1 item per product in the transaction
        'name' => $name,
    );
}
// Optional
$customer_details = array(
    'name'    => $username,
    'account_id' => $account_id
);

// Fill transaction details
$transaction = array(
    'transaction_details' => $transaction_details,
    'customer_details' => $customer_details,
    'item_details' => $item_details,
);

$snap_token = '';
try {
    $snap_token = Snap::getSnapToken($transaction);
}
catch (\Exception $e) {
    echo $e->getMessage();
}
echo "snapToken = ".$snap_token;

function printExampleWarningMessage() {
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

?>

<!DOCTYPE html>
<html>
    <body>
        <button id="pay-button">Pay!</button>
        <!-- TODO: Remove ".sandbox" from script src URL for production environment. Also input your client key in "data-client-key" -->
        <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="<?php echo Config::$clientKey;?>"></script>
        <script type="text/javascript">
            document.getElementById('pay-button').onclick = function(){
                // SnapToken acquired from previous step
                snap.pay('<?php echo $snap_token?>');
            };
        </script>
    </body>
</html>
