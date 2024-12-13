<?php
include '../connection/connection.php';
session_start();

// Get product ID from URL
if (!isset($_SESSION["product_id"])) {
    header("Location: store.php");
    exit();
}

$product_id = $_SESSION["product_id"];
$query = "SELECT * FROM products WHERE product_id = :product_id";
$result = oci_parse($conn, $query);
oci_bind_by_name($result, ":product_id", $product_id);
oci_execute($result);
$product = oci_fetch_assoc($result);

if (!$product) {
    header("Location: store.php");
    exit();
}

$isLoggedIn = isset($_SESSION['username']);
// Handle add to cart action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    if (!$isLoggedIn) {
        // Redirect ke login jika belum login
        header("Location: ../login/login.php");
        exit();
    }

    // Dapatkan account_id dari session
    $accountId = $_SESSION['account_id'];

    // Periksa apakah produk sudah ada di keranjang
    $checkQuery = "SELECT COUNT(*) AS count FROM cart WHERE account_id = :account_id AND product_id = :product_id";
    $checkStmt = oci_parse($conn, $checkQuery);
    oci_bind_by_name($checkStmt, ":account_id", $accountId);
    oci_bind_by_name($checkStmt, ":product_id", $product_id);
    oci_execute($checkStmt);
    $checkResult = oci_fetch_assoc($checkStmt);

    if ($checkResult['COUNT'] > 0) {
        // Produk sudah ada di keranjang
        echo '<script language="javascript">';
        echo 'alert("This product is already in your cart!");';
        echo '</script>';
    } else {
        // Query untuk menambahkan ke tabel cart
        $insertQuery = "INSERT INTO cart (account_id, product_id) VALUES (:account_id, :product_id)";
        $insertStmt = oci_parse($conn, $insertQuery);
        oci_bind_by_name($insertStmt, ":account_id", $accountId);
        oci_bind_by_name($insertStmt, ":product_id", $product_id);

        if (oci_execute($insertStmt)) {
            echo '<script language="javascript">';
            echo 'alert("Product successfully added to your cart!");';
            echo '</script>';
        } else {
            $e = oci_error($insertStmt);
            echo "<p>Error adding product to cart: " . htmlspecialchars($e['message']) . "</p>";
        }

        oci_free_statement($insertStmt);
    }

    oci_free_statement($checkStmt);
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Games Collection - ORON</title>
    <link rel="stylesheet" href="../../Styling/css/games.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        @import url('https://fonts.cdnfonts.com/css/lemonmilk');
    </style>
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
                    <li><a class="nav-link" href="features/profile/profile.php"><i class="fa-solid fa-user"></i><?php echo htmlspecialchars($_SESSION['username']); ?></a></li>
                <?php else: ?>
                    <li><a href="../profile/profile.php"><i class="fa-solid fa-user"></i></a></li>
                <?php endif; ?>
                    <li><a href="../cart/cart.php"><i class="fa-solid fa-cart-shopping"></i></a></li>
            </ul>
        </nav>
    </div>
    </header>

    <main>
        <h2><?php echo htmlspecialchars($product['NAME']); ?></h2>

        <img src="<?php echo htmlspecialchars($product['IMAGE']); ?>" 
             alt="<?php echo htmlspecialchars($product['NAME']); ?>"
             width="400">

        <?php if (!empty($product['VIDEO'])): ?>
            <div>
                <h3>Game Trailer</h3>
                <iframe src="<?php echo htmlspecialchars($product['VIDEO']); ?>" 
                        width="560" 
                        height="315" 
                        frameborder="0"></iframe>
            </div>
        <?php endif; ?>

        <h3>Price:</h3>
        <?php if ($product['DISCOUNT'] > 0): ?>
            <p>
                Original Price: <strike>$<?php echo number_format($product['CURRENT_PRICE'], 2); ?></strike><br>
                Discount: <?php echo number_format($product['DISCOUNT'] * 100); ?>%<br>
                Final Price: $<?php echo number_format($product['CURRENT_PRICE'] * (1 - $product['DISCOUNT']), 2); ?>
            </p>
        <?php else: ?>
            <p>Price: $<?php echo number_format($product['CURRENT_PRICE'], 2); ?></p>
        <?php endif; ?>

        <form method="POST">
            <button type="submit" name="add_to_cart">Add to Cart</button>
        </form>

        <h3>About This Game</h3>
        <p><?php echo nl2br(htmlspecialchars($product['DESCRIPTION'])); ?></p>

        <h3>Game Details</h3>
        <p>
            Developer: <?php echo htmlspecialchars($product['DEVELOPER']); ?><br>
            Publisher: <?php echo htmlspecialchars($product['PUBLISHER']); ?><br>
            Release Date: <?php echo date('F j, Y', strtotime($product['LAUNCHED_AT'])); ?>
        </p>
    </main>

</body>
</html>

<?php
// Clean up
oci_free_statement($result);
oci_close($conn);
?>