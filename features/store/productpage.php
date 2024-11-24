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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title><?php echo htmlspecialchars($product['NAME']); ?> - ORON Store</title>
</head>
<body>
    <header>
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand" href="#">ORON</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="features/store/store.php">Store</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Library</a>
                    </li>
                    <li class="nav-item">
                        <?php if ($isLoggedIn): ?>
                            <a class="nav-link" href="features/profile/profile.php"><?php echo htmlspecialchars($_SESSION['username']); ?></a>
                        <?php else: ?>
                            <a class="nav-link" href="features/login/login.php">Profile</a>
                        <?php endif; ?>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="features/cart/cart.php">Cart</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
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