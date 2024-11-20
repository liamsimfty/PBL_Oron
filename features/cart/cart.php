<?php
include '../connection/connection.php';   

session_start();
$isLoggedIn = isset($_SESSION['account_id']);
$account_id = $_SESSION['account_id'];

// Query to fetch cart details
$query = "SELECT 
          c.cart_id,
          p.product_id,
          p.name AS product_name,
          p.current_price,
          p.discount
      FROM 
          cart c
      JOIN 
          products p ON c.product_id = p.product_id
      WHERE 
          c.account_id = :account_id";  // Removed the semicolon from the query

$stid = oci_parse($conn, $query);
oci_bind_by_name($stid, ":account_id", $account_id);

if (!oci_execute($stid)) {
    $e = oci_error($stid);
    echo "Error: " . htmlentities($e['message'], ENT_QUOTES);
    exit;
}

// Display cart data as an HTML table
echo "<h2>Your Cart</h2>";
echo "<table border='1'>";
echo "<tr>
        <th>Product Name</th>
        <th>Current Price</th>
        <th>Discount</th>
      </tr>";

while (($row = oci_fetch_array($stid, OCI_ASSOC)) != false) {
    echo "<tr>";
    echo "<td>" . htmlspecialchars($row['PRODUCT_NAME']) . "</td>";
    echo "<td>" . htmlspecialchars($row['CURRENT_PRICE']) . "</td>";
    echo "<td>" . htmlspecialchars($row['DISCOUNT']) . "</td>";
    echo "</tr>";
}
echo "</table>";

oci_free_statement($stid);
oci_close($conn);
?>