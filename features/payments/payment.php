<?php
namespace Midtrans;
include '../connection/connection.php';   
session_start();
$username = isset($_SESSION['username']);
$account_id = isset($_SESSION['account_id']);
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
$query = "
SELECT 
    SUM(t.price_at_checkout) AS total_price
FROM 
    transaction t
WHERE 
    t.account_id = 62
";

$statement = oci_parse($conn, $query);
oci_execute($statement);

$row = oci_fetch_assoc($statement);

// Get the gross_amount from the query result
$gross_amount = $row['TOTAL_PRICE'];

// Prepare transaction details
$transaction_details = array(
    'order_id' => rand(),
    'gross_amount' => $gross_amount * 100, // Total price for all transactions
);

$transaction = array(
    'transaction_details' => $transaction_details,
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
