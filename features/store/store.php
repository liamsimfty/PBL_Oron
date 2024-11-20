<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">    
    <style>
    .product-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        padding: 20px;
        width: 100%;
        box-sizing: border-box;
    }

    .product-box {
        border: 1px solid #ddd;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: space-between;
        text-align: center;
        padding: 15px;
    }
</style>

</head>
<body>
    <?php
    include '../connection/connection.php';
    // Start session
    session_start();
    $isLoggedIn = isset($_SESSION['username']);

    $query = "SELECT name, current_price, discount FROM products";
    $result = oci_parse($conn, $query);
    
    if (!oci_execute($result)) {
        $e = oci_error($result);
        echo "Error: " . htmlentities($e['message'], ENT_QUOTES);
        exit;
    }

    ?>
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand" href="#">ORON</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="store.php">Store</a>
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
    <input type="text" placeholder="Search.." style="display: block; margin: 0 auto; text-align: center;">

    <div class="container">
        <div class="row">
            <?php
                if ($result && oci_fetch_all($result, $rows, 0, -1, OCI_FETCHSTATEMENT_BY_ROW) > 0) {
                    echo '<div class="product-container">';
                    foreach ($rows as $row) {
                        echo '<div class="product-box">';
                        echo '<h3>' . htmlspecialchars($row["NAME"]) . '</h3>';
                        if ($row["DISCOUNT"] > 0) {
                            $originalPrice = $row["CURRENT_PRICE"];
                            $discountedPrice = $row["CURRENT_PRICE"] - ($row["CURRENT_PRICE"] * ($row["DISCOUNT"] / 100));
                            echo '<p><strike>$' . number_format($originalPrice, 2) . '</strike> -' . $row["DISCOUNT"] . '% = $' . number_format($discountedPrice, 2) . '</p>';
                        } else {
                            echo '<p>$' . number_format($row["CURRENT_PRICE"], 2) . '</p>';
                        }
                        echo '</div>';
                    }
                    echo '</div>';
                } else {
                    echo "No products found.";
                }
            ?>
        </div>
    </div>
</body>
</html>