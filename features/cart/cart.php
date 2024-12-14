<?php
namespace Midtrans;
require_once dirname(__FILE__) . '/../../vendor/midtrans/midtrans-php/Midtrans.php';
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

            // Cek apakah game sudah ada di library
            $checkQuery = "SELECT COUNT(*)  AS GAME_COUNT FROM library WHERE account_id = :account_id AND product_id = :product_id";
            $checkStmt = oci_parse($conn, $checkQuery);
            oci_bind_by_name($checkStmt, ":account_id", $account_id);
            oci_bind_by_name($checkStmt, ":product_id", $productToPay);
            oci_execute($checkStmt);
            $row = oci_fetch_assoc($checkStmt);
            oci_free_statement($checkStmt);
            // Jika current_price = 0, langsung masukkan ke library
            if ($currentPrice == 0) {
            
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
                
                if ($row['GAME_COUNT'] > 0) {
                    echo '<script>alert("You already own this game.");</script>';
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
                    header("Location: ../process/process.php");

                    $deleteQuery = "DELETE FROM cart WHERE account_id = :account_id AND product_id = :product_id";
                    $deleteStmt = oci_parse($conn, $deleteQuery);
                    oci_bind_by_name($deleteStmt, ":account_id", $account_id);
                    oci_bind_by_name($deleteStmt, ":product_id", $productToPay);
                    oci_execute($deleteStmt);
                    oci_free_statement($deleteStmt);
                }

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
            p.discount,
            p.image
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
        'image' => $row['IMAGE'],
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
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Games Collection - ORON</title>
    <link rel="stylesheet" href="../../Styling/css/newheader.css" />
    <link rel="stylesheet" href="../../Styling/css/cart.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        @import url('https://fonts.cdnfonts.com/css/lemonmilk');
    </style> 
    <script src="../../Styling/JS/function.js"></script> 

</head>
<body>
<header>
    <div>
        <nav class="navbar">
            <img src="../../Styling/images/oron-logo.png" class="navbar-logo" alt="logo" />
            <h1>ORON</h1>
            <ul class="navbar-list">
            <li><a href="../pages/homepage.php">Home</a></li>
            <li><a href="../store/store.php">Store</a></li>
            <li><a href="../pages/blog.php">Blog</a></li>
            <li><a href="../pages/about.php">About</a></li>
            <li><a href="#"><i class="fa-solid fa-magnifying-glass"></i></a></li>
            <li><a href="#"><i class="fa-solid fa-cart-shopping"></i></a></li>
            </ul>
        
            <div class="profile-dropdown">
            <div onclick="toggle()" class="profile-dropdown-btn">
                <div class="profile-img">
                <i class="fa-solid fa-circle"></i>
                </div>
                
                <span>
                <?php if ($isLoggedIn): ?>
                    <?php echo htmlspecialchars($_SESSION['username']); ?>
                <?php else: ?>
                    Guest
                <?php endif; ?>
                <i class="fa-solid fa-angle-down"></i>
                </span>
            </div>
        
            <ul class="profile-dropdown-list">
                <?php if ($isLoggedIn): ?>
                <li class="profile-dropdown-list-item">
                    <a href="../pages/profile.php">
                    <i class="fa-regular fa-user"></i>
                    Edit Profile
                    </a>
                </li>
                <li class="profile-dropdown-list-item">
                    <a href="../login/login.php">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                    Log out
                    </a>
                </li>
                <?php else: ?>
                <li class="profile-dropdown-list-item">
                    <a href="../login/login.php">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                    Log in
                    </a>
                </li>
                <?php endif; ?>
            </ul>
            </div>
        </nav>
        </div>
</header>
<div class="cart">
    <h1>Cart</h1>
        <form method="POST" class="cart-container">
            <div class="cart-main">
                <div class="cart-header">
                    <div class="product-col">PRODUCT</div>
                    <div class="price-col">PRICE</div>
                </div>

                <?php foreach ($cart_items as $item): ?>
                <div class="cart-item">
                    <div class="product-col">
                        <input type="checkbox" class="product-checkbox" name="selected_products[]" value="<?= htmlspecialchars($item['product_id']) ?>" onclick="calculateTotal()"="<?= htmlspecialchars($item['final_price'] * 1000) ?>">
                        <?php if (!empty($item['image'])): ?>
                            <img src="../../<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['product_name']) ?>">
                        <?php else: ?>
                            <img src="https://placehold.co/80x80" alt="<?= htmlspecialchars($item['product_name']) ?>">
                        <?php endif; ?>
                        <span><?= htmlspecialchars($item['product_name']) ?></span>
                    </div>
                    <div>
                            <div class="product-price">
                                <div class="price-container">
                                    <div class="discount-item">
                                        <?php if ($item['discount'] > 0): ?>
                                            <span class="discount-badge">-<?= htmlspecialchars($item['discount'] * 100) ?>%</span>
                                            <span class="original-price">IDR <?= number_format($item['current_price'] * 1000, 0) ?></span>
                                        <?php endif; ?>
                                    </div>
                                            <span class="final-price" id="final-price">IDR <?= number_format($item['final_price'] * 1000, 0) ?></span>
                                </div>
                            </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <div class="cart-summary">
                <div class="summary-row">
                    <span>Total</span>
                    <span id="total-price" class="final-price">IDR 0</span>
                </div>
                <button type="submit" name="remove_selected" class="checkout-btn">Remove Selected</button>
                <button type="submit" name="process_payment" class="checkout-btn">Continue to Payment</button>
                <div class="transaction-history">
                    <a href="../history/transaction.php" >Transaction History</a>
                </div>
            </div>
    </form>
</div>

    <?php
        Config::$clientKey = $_ENV['MIDTRANS_CLIENT_KEY'];
        $snap_token = isset($_GET['snap_token']) ? $_GET['snap_token'] : null;
        if ($snap_token) {
        ?>
        <!DOCTYPE html>
        <html>
            <body>
                <!-- Midtrans Snap.js -->
                <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="<?php echo Config::$clientKey; ?>"></script>
                <script type="text/javascript">
                    window.snap.pay('<?php echo $snap_token; ?>');
                </script>
            </body>
        </html>
        <?php
    } else {
        echo 'snap token not found';
    }
    ?>

</body>
</html>
