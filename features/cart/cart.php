<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php
    // Start session
    session_start();

    // Check if user is logged in
    $isLoggedIn = isset($_SESSION['username']);
    ?>
    
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand" href="#">ORON</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="../store/store.php">Store</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Library</a>
                    </li>
                    <li class="nav-item">
                        <?php if ($isLoggedIn): ?>
                            <a class="nav-link" href="../profile/profile.php"><?php echo htmlspecialchars($_SESSION['username']); ?></a>
                        <?php else: ?>
                            <a class="nav-link" href="../login/login.php">Profile</a>
                        <?php endif; ?>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../cart/cart.php">Cart</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</body>
</html>

<?php
include '../connection/connection.php';

if (!isset($_SESSION['account_id'])) {
    echo "<p>You need to log in to view your cart.</p>";
    exit();
}

$accountId = $_SESSION['account_id'];

// Handle remove product
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_selected'])) {
    if (isset($_POST['selected_products'])) {
        foreach ($_POST['selected_products'] as $productToRemove) {
            $deleteQuery = "DELETE FROM cart WHERE account_id = :account_id AND product_id = :product_id";
            $deleteproduct = oci_parse($conn, $deleteQuery);
            oci_bind_by_name($deleteproduct, ":account_id", $accountId);
            oci_bind_by_name($deleteproduct, ":product_id", $productToRemove);
            oci_execute($deleteproduct);
            oci_free_statement($deleteproduct);
        }
    }
    echo '<script>alert("Selected products removed successfully.");</script>';
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
oci_bind_by_name($stid, ":account_id", $accountId);
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
