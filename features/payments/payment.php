<?php
namespace Midtrans;
include '../connection/connection.php';   
session_start();
$username = $_SESSION['username'];
$account_id = $_SESSION['account_id'];

require_once dirname(__FILE__) . '/../../vendor/midtrans/midtrans-php/Midtrans.php';

// Set Your server key
Config::$serverKey = 'SB-Mid-server-Uq1HNq-vNEmFST7AKmQf5ofo';
Config::$clientKey = 'SB-Mid-client-FdXCfowRdFheZ1z6';

// Uncomment for production environment
// Config::$isProduction = true;
Config::$isSanitized = Config::$is3ds = true;
$order_id = $_GET['transaction_id'];
// Query to fetch total price
$query = "
SELECT 
    SUM(t.price_at_checkout) AS total_price
FROM 
    transaction t
WHERE 
    t.account_id = :account_id 
";

$statement = oci_parse($conn, $query);

// Correctly bind `account_id` to the variable
oci_bind_by_name($statement, ":account_id", $account_id);
oci_execute($statement);

// Fetch result
$row = oci_fetch_assoc($statement);
$gross_amount = $row['TOTAL_PRICE'] * 100;

// Ensure `gross_amount` is valid
if ($gross_amount <= 0) {
    die('Error: Gross amount must be greater than 0.');
}

// Prepare transaction details
$transaction_details = array(
    'order_id' => $order_id,
    'gross_amount' => $gross_amount
);

$query_items = "
SELECT 
    p.product_id AS product_id, 
    p.name AS name, 
    t.price_at_checkout AS price_at_checkout
FROM 
    products p
JOIN 
    transaction t ON p.product_id = t.product_id
WHERE 
    t.account_id = :account_id
";

$statement_items = oci_parse($conn, $query_items);
oci_bind_by_name($statement_items, ":account_id", $account_id);
oci_execute($statement_items);

$item_details = [];
while ($item_row = oci_fetch_assoc($statement_items)) {
    $item_details[] = array(
        'id' => $item_row['PRODUCT_ID'],
        'price' => $item_row['PRICE_AT_CHECKOUT'] * 100,
        'quantity' => 1,
        'name' => $item_row['NAME'],
    );
}

// Prepare customer details
$customer_details = array(
    'first_name' => $username
);

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
