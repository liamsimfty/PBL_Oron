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

// Handle add to cart action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    session_start();
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    
    // Add product to cart session
    $_SESSION['cart'][] = [
        'product_id' => $product['PRODUCT_ID'],
        'name' => $product['NAME'],
        'price' => $product['CURRENT_PRICE'] * (1 - $product['DISCOUNT']),
        'quantity' => 1
    ];
    
    // Redirect to cart page
    header("Location: cart.php");
    exit();
}

// Start session for login status
$isLoggedIn = isset($_SESSION['username']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['NAME']); ?> - ORON Store</title>
</head>
<body>
    <header>
        <h1>ORON Store</h1>
        <nav>
            <a href="store.php">Store</a> |
            <a href="#">Library</a> |
            <a href="login.php">Profile</a> |
            <a href="cart.php">Cart</a>
        </nav>
        <hr>
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

    <hr>
    <footer>
        <p>&copy; 2024 ORON Store</p>
    </footer>
</body>
</html>

<?php
// Clean up
oci_free_statement($result);
oci_close($conn);
?>