<?php
include '../connection/connection.php';
session_start();
if (!isset($_SESSION['account_id'])) {
    echo "<p>You need to log in to view your cart.</p>";
    exit();
}
$isLoggedIn = isset($_SESSION['username']);
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

                $deleteQuery = "DELETE FROM cart WHERE account_id = :account_id AND product_id = :product_id";
                $deleteStmt = oci_parse($conn, $deleteQuery);
                oci_bind_by_name($deleteStmt, ":account_id", $account_id);
                oci_bind_by_name($deleteStmt, ":product_id", $productToPay);
                oci_execute($deleteStmt);
                oci_free_statement($deleteStmt);
            }
        }
    } else {
        echo '<script>alert("No products selected for payment.");</script>';
    }
}

$cart_items = [];
$totalPrice = 0;

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

// Simpan data keranjang ke array
while (($row = oci_fetch_array($stid, OCI_ASSOC)) != false) {
    $finalPrice = $row['CURRENT_PRICE'] - ($row['CURRENT_PRICE'] * ($row['DISCOUNT']));
    $totalPrice += $finalPrice;

    $cart_items[] = [
        'cart_id' => $row['CART_ID'],
        'product_id' => $row['PRODUCT_ID'],
        'product_name' => $row['PRODUCT_NAME'],
        'current_price' => $row['CURRENT_PRICE'],
        'discount' => $row['DISCOUNT'],
        'final_price' => $finalPrice
    ];
}

oci_free_statement($stid);
oci_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../../Styling/css/cart.css">
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
</head>
<body>
<header>
    <div class="header-container">
        <div class="brand-logo">
            <img src="../../Styling/images/oron-logo.png" alt="Logo ORON">
            <h1>ORON</h1>
        </div>
        <nav>
            <ul>
                <li><a href="../pages/homepage.php">Home</a></li>
                <li><a href="gamesdespage.php">Games</a></li>
                <li><a href="../pages/blog.php">Blog</a></li>
                <li><a href="../pages/about.php">About</a></li>
                <li><a href="#"><i class="fa-solid fa-magnifying-glass"></i></a></li>
                <?php if ($isLoggedIn): ?>
                    <li> <a class="nav-link" href="features/profile/profile.php"><i class="fa-solid fa-user"></i><?php echo htmlspecialchars($_SESSION['username']); ?></a></li>
                <?php else: ?>
                    <li><a href="../profile/profile.php"><i class="fa-solid fa-user"></i></a></li>
                <?php endif; ?>
                    <li><a href="../cart/cart.php"><i class="fa-solid fa-cart-shopping"></i></a></li>
            </ul>
        </nav>
    </div>
</header>
    <h2>Your Cart</h2>

    <form method="POST">
        <table border="1" cellpadding="5">
            <tr>
                <th>Select</th>
                <th>Product Name</th>
                <th>Current Price</th>
                <th>Discount</th>
                <th>Final Price</th>
            </tr>
            <?php foreach($cart_items as $item): ?>
            <tr>
                <td>
                    <input type="checkbox" name="selected_products[]" 
                           value="<?= htmlspecialchars($item['product_id']) ?>">
                </td>
                <td><?= htmlspecialchars($item['product_name']) ?></td>
                <td>$<?= number_format($item['current_price'], 2) ?></td>
                <td><?= htmlspecialchars($item['discount'] * 100) ?>%</td>
                <td>$<?= number_format($item['final_price'], 2) ?></td>
            </tr>
            <?php endforeach; ?>
        </table>

        <div>
            <strong>Total Price: $<span id="total-price">0.00</span></strong>
        </div>

        <button type="submit" name="remove_selected">Remove Selected</button>
        <button type="submit" name="process_payment">Process Payment</button>
        <button type="button" onclick="calculateTotal()">Calculate Total</button>
        <a href="../history/transaction.php" class="button">See Transaction History</a>    
    </form>
</body>
</html>
