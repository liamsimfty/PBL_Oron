<?php
include '../connection/connection.php';
session_start();
if (!isset($_SESSION['account_id'])) {
    echo "<p>You need to log in to view your cart.</p>";
    exit();
}

$account_id = $_SESSION['account_id'];

// Handle remove product
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_selected'])) {
    if (isset($_POST['selected_products'])) {
        foreach ($_POST['selected_products'] as $productToRemove) {
            $deleteQuery = "DELETE FROM cart WHERE account_id = :account_id AND product_id = :product_id";
            $deleteproduct = oci_parse($conn, $deleteQuery);
            oci_bind_by_name($deleteproduct, ":account_id", $account_id);
            oci_bind_by_name($deleteproduct, ":product_id", $productToRemove);
            oci_execute($deleteproduct);
            oci_free_statement($deleteproduct);
        }
    }
    echo '<script>alert("Selected products removed successfully.");</script>';
}

// Handle payment
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['process_payment'])) {
    if (isset($_POST['selected_products'])) {
        foreach ($_POST['selected_products'] as $productToPay) {
            // Query untuk mendapatkan harga current_price
            $priceQuery = "SELECT current_price, product_id, discount name FROM products WHERE product_id = :product_id";
            $priceStmt = oci_parse($conn, $priceQuery);
            oci_bind_by_name($priceStmt, ":product_id", $productToPay);
            oci_execute($priceStmt);
            $priceRow = oci_fetch_assoc($priceStmt);
            $currentPrice = $priceRow['CURRENT_PRICE'];
            oci_free_statement($priceStmt);

            // Jika current_price = 0, langsung masukkan ke library
            if ($currentPrice == 0) {
                // Cek apakah game sudah ada di library
                $checkQuery = "SELECT COUNT(*)  AS GAME_COUNT FROM library WHERE account_id = :account_id AND product_id = :product_id";
                $checkStmt = oci_parse($conn, $checkQuery);
                oci_bind_by_name($checkStmt, ":account_id", $account_id);
                oci_bind_by_name($checkStmt, ":product_id", $productToPay);
                oci_execute($checkStmt);
                $row = oci_fetch_assoc($checkStmt);
                oci_free_statement($checkStmt);
            
                if ($row['GAME_COUNT'] > 0) {
                    echo '<script>alert("You already own this game.");</script>';
                } else {
                    // Jika belum ada, tambahkan ke library
                    $insertQuery = "INSERT INTO library (library_id, product_id, account_id, purchase_date) 
                                    VALUES (library_seq.NEXTVAL, :product_id, :account_id, SYSTIMESTAMP)";
                    $insertStmt = oci_parse($conn, $insertQuery);
                    oci_bind_by_name($insertStmt, ":product_id", $productToPay);
                    oci_bind_by_name($insertStmt, ":account_id", $account_id);
                    oci_execute($insertStmt);
                    oci_free_statement($insertStmt);
            
                    // Hapus dari keranjang
                    $deleteQuery = "DELETE FROM cart WHERE account_id = :account_id AND product_id = :product_id";
                    $deleteStmt = oci_parse($conn, $deleteQuery);
                    oci_bind_by_name($deleteStmt, ":account_id", $account_id);
                    oci_bind_by_name($deleteStmt, ":product_id", $productToPay);
                    oci_execute($deleteStmt);
                    oci_free_statement($deleteStmt);
            
                    echo '<script>alert("Free product added to your library.");</script>';
                }
            } else {
                $priceQuery = "SELECT current_price, product_id, discount FROM products WHERE product_id = :product_id";
                $priceStmt = oci_parse($conn, $priceQuery);
                oci_bind_by_name($priceStmt, ":product_id", $productToPay);
                oci_execute($priceStmt);
                $priceRow = oci_fetch_assoc($priceStmt);
                $currentPrice = $priceRow['CURRENT_PRICE'];
                $discount = $priceRow['DISCOUNT'];
                oci_free_statement($priceStmt);
                
                // Hitung total harga dengan diskon (dalam dollar)
                $totalPrice = $currentPrice * (1 - $discount);
                
                // Tambahkan produk ke array payment_data
                $_SESSION['payment_data'][] = [
                    'product_id' => $productToPay,
                    'total_price' => round($totalPrice, 2), // Bulatkan menjadi 2 desimal
                    'transaction_id' => rand()
                ];
                header("Location: ../payments/process.php");
            }
        }
    } else {
        echo '<script>alert("No products selected for payment.");</script>';
    }
}

// Query untuk mendapatkan data keranjang
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
            c.account_id = :account_id";

$stid = oci_parse($conn, $query);
oci_bind_by_name($stid, ":account_id", $account_id);
oci_execute($stid);

echo '<h2>Your Cart</h2>';

echo '<form method="POST">';
echo '<table border="1" cellpadding="10">';
echo '<tr>
        <th>Select</th>
        <th>Product Name</th>
        <th>Current Price</th>
        <th>Discount</th>
        <th>Final Price</th>
      </tr>';

$totalPrice = 0;
while (($row = oci_fetch_array($stid, OCI_ASSOC)) != false) {
    $finalPrice = $row['CURRENT_PRICE'] - ($row['CURRENT_PRICE'] * ($row['DISCOUNT']));
    $totalPrice += $finalPrice;

    echo '<tr>';
    echo '<td><input type="checkbox" name="selected_products[]" value="' . htmlspecialchars($row['PRODUCT_ID']) . '"></td>';
    echo '<td>' . htmlspecialchars($row['PRODUCT_NAME']) . '</td>';
    echo '<td>$' . number_format($row['CURRENT_PRICE'], 2) . '</td>';
    echo '<td>' . htmlspecialchars($row['DISCOUNT'] * 100) . '%</td>';
    echo '<td>$' . number_format($finalPrice, 2) . '</td>';
    echo '</tr>';
}
echo '</table>';

// Tombol untuk menghapus produk yang dipilih
echo '<button type="submit" name="remove_selected">Remove Selected</button>';

// Tombol untuk memproses pembayaran
echo '<button type="submit" name="process_payment">Process Payment</button>';

// Tombol untuk menghitung total harga berdasarkan produk yang dipilih
echo '<button type="button" onclick="calculateTotal()">Calculate Total</button>';
echo '</form>';

oci_free_statement($stid);
oci_close($conn);
?>
<!-- Tambahkan Total Harga -->
<h3>Total Price: $<span id="total-price">0.00</span></h3>

<script>
function calculateTotal() {
    let checkboxes = document.querySelectorAll('input[name="selected_products[]"]:checked');
    let rows = document.querySelectorAll('table tr');
    let total = 0;

    checkboxes.forEach(checkbox => {
        let row = checkbox.closest('tr');
        let finalPrice = parseFloat(row.cells[4].innerText.replace('$', ''));
        total += finalPrice;
    });

    document.getElementById('total-price').innerText = total.toFixed(2);
}
</script>
