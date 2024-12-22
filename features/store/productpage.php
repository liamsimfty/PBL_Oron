<?php
include '../connection/connection.php';
session_start();

if (isset($_GET['product_id'])) {
    $product_id = htmlspecialchars($_GET['product_id']);
    $_SESSION['product_id'] = $product_id;
} else {
    header("Location: store.php");
    exit();
}

// Query untuk mengambil data produk
$query = "SELECT * FROM products WHERE product_id = :product_id";
$result = oci_parse($conn, $query);
oci_bind_by_name($result, ":product_id", $product_id);
oci_execute($result);
$product = oci_fetch_assoc($result);

// Query untuk mendapatkan media (gambar/video) dari product_media
$mediaQuery = "SELECT media_url, media_type FROM product_media WHERE product_id = :product_id";
$mediaStmt = oci_parse($conn, $mediaQuery);
oci_bind_by_name($mediaStmt, ":product_id", $product_id);
oci_execute($mediaStmt);

$media = [];
while ($row = oci_fetch_assoc($mediaStmt)) {
    $media[] = $row;
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
            echo "<script>Error adding product to cart: " . htmlspecialchars($e['message']) . "</script>";
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
    <title><?php echo htmlspecialchars($product['NAME']); ?> - ORON</title>
    <link rel="stylesheet" href="../../Styling/css/newheader.css">
    <link rel="stylesheet" href="../../Styling/css/product.css">
    <link rel="stylesheet" href="../../Styling/css/footer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
<nav class="navbar">
  <div class="navbar-logo">
    <img src="../../Styling/images/oron-logo.png" class="navbar-logo" alt="logo" />
    <h1>ORON</h1>
  </div>
  <div class="navbar-link">
  <ul class="navbar-list">
    <li><a href="../pages/homepage.php">Home</a></li>
    <li><a href="../store/store.php">Store</a></li>
    <li><a href="../library/library.php">library</a></li>
    <li><a href="../pages/about.php">About</a></li>
    <li><a href="../cart/cart.php"><i class="fa-solid fa-cart-shopping"></i></a></li>
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
  </div>
</nav>
<body>
    <main>
        <h2><?php echo htmlspecialchars($product['NAME']); ?></h2>
        
        <div class="content-wrapper">
            <!-- Slideshow Container -->
            <div class="slideshow-container">
            <?php if (empty($media)): ?>
                <p>No media available to display.</p>
            <?php else: ?>
                <?php foreach ($media as $index => $item): ?>
                    <div class="mySlides fade" style="display: <?php echo $index === 0 ? 'block' : 'none'; ?>;">
                        <?php if ($item['MEDIA_TYPE'] === 'image'): ?>
                            <img src="<?php echo htmlspecialchars($item['MEDIA_URL']); ?>" alt="Product Image">
                        <?php elseif ($item['MEDIA_TYPE'] === 'video'): ?>
                            <video controls>
                                <source src="<?php echo htmlspecialchars($item['MEDIA_URL']); ?>" type="video/webm">
                                Your browser does not support the video tag.
                            </video>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
                
                <div class="dots-container">
                    <?php foreach ($media as $index => $item): ?>
                        <span class="dot" onclick="currentSlide(<?php echo $index + 1; ?>)"></span>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Product Container -->
            <div class="product-container">
                <div class="product-info">
                    <h3>Price:</h3>
                    <div class="price-container">
                        <?php if ($product['DISCOUNT'] > 0): ?>
                            <p class="price">
                                Original Price: <strike>$<?php echo number_format($product['CURRENT_PRICE'], 2); ?></strike><br>
                                Discount: <span class="discount"><?php echo number_format($product['DISCOUNT'] * 100); ?>%</span><br>
                                Final Price: <span class="final-price">$<?php echo number_format($product['CURRENT_PRICE'] * (1 - $product['DISCOUNT']), 2); ?></span>
                            </p>
                        <?php else: ?>
                            <p class="price">Price: <span class="final-price">$<?php echo number_format($product['CURRENT_PRICE'], 2); ?></span></p>
                        <?php endif; ?>
    
                    </div>
                    <form method="POST" class="add-to-cart-form">
                        <button type="submit" name="add_to_cart">Add to Cart</button>
                    </form>

                    <h3>About This Game</h3>
                    <p><?php echo nl2br(htmlspecialchars($product['DESCRIPTION'])); ?></p>

                    <h3>Game Details</h3>
                    <div class="game-details">
                        <p>
                            <strong>Developer:</strong> <?php echo htmlspecialchars($product['DEVELOPER']); ?><br>
                            <strong>Publisher:</strong> <?php echo htmlspecialchars($product['PUBLISHER']); ?><br>
                            <strong>Release Date:</strong> <?php echo date('F j, Y', strtotime($product['LAUNCHED_AT'])); ?><br>
                            <strong>File SIze:</strong> <?php echo htmlspecialchars($product['FILE_SIZE']); ?><strong> GB</strong>
                            
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer class="footer">
    <img src="../../Styling/images/ftbg.png" alt="Oron Logo" class="footer-bg">
    <div class="footer-container">
        <div class="about">
            <h4>About Us</h4>            
                <p>Oron adalah solusi terbaik untuk membeli dan menjual video game.<br>
                Dengan dukungan komunitas global, kami memprioritaskan<br>pengalaman pengguna yang aman dan transparan.</p>
            </div>
        <div class="footer-links">
            <ul>
                <li><a href="#">Home</a></li>
                <li><a href="#">Games</a></li>
                <li><a href="#">Blog</a></li>
                <li><a href="#">About</a></li>
            </ul>
        </div>
    </div>

    <script>
        function currentSlide(n) {
            const slides = document.getElementsByClassName("mySlides");
            const dots = document.getElementsByClassName("dot");
            
            for (let i = 0; i < slides.length; i++) {
                slides[i].style.display = "none";
                dots[i].classList.remove("active");
            }
            
            slides[n - 1].style.display = "block";
            dots[n - 1].classList.add("active");
        }
    </script>
</body>
</html>
<script src="../../Styling/JS/function.js"></script> 
</html>

<?php
// Clean up
oci_free_statement($result);
oci_free_statement($mediaStmt);
oci_close($conn);
?>
