
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Games Collection - ORON</title>
    <link rel="stylesheet" href="../../Styling/css/store.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        @import url('https://fonts.cdnfonts.com/css/lemonmilk');
    </style>
                
</head>
<body>
    <?php
            include '../connection/connection.php';
            // Start session
            session_start();
            $isLoggedIn = isset($_SESSION['username']);

            // Tangani pengiriman produk
            if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["product_id"])) {
                $_SESSION["product_id"] = $_POST["product_id"];
                header("Location: productpage.php");
                exit();
            }

            $query = "SELECT product_id, name, current_price, discount, image FROM products"; // Tambahkan product_id
            $result = oci_parse($conn, $query);

            if (!oci_execute($result)) {
                $e = oci_error($result);
                echo "Error: " . htmlentities($e['message'], ENT_QUOTES);
                exit;
            }
    ?>

    <!-- Header -->
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

<!-- Main Banner -->
<section class="main-banner">
    <div class="section-image1">
    <img src="../../Styling/images/bg.png" alt="FC25 Banner">
    <div class="banner">
        <img src="../../Styling/images/logofc25.png" alt="EA Sports FC25 Logo" class="hero-logo">
        <p>The legendary FIFA series has been produced by EA <br>SPORTS for over 20 years, and is now the largest sports <br>video game franchise on the planet..</p>
        <button>Play Now</button>
    </div>
    </div>
</section>


<div class="vector-7"></div>
<div class="vector-8"></div>


    <!-- Games Collection -->
    <section class="games-collection">
        <h2>
            <span class="highlight1">GAMES</span> <span class="highlight2">COLLECTION</span>
        </h2>

        <div class="filter-buttons">
            <button class="active">ALL</button>
            <button>Open World</button>
            <button>RPG</button>
        </div>

        <div class="games-grid">
            <?php
            if ($result && oci_fetch_all($result, $rows, 0, -1, OCI_FETCHSTATEMENT_BY_ROW) > 0) {
                foreach ($rows as $row) {
                    $productId = htmlspecialchars($row["PRODUCT_ID"]);
                    ?>
                    <a href="productpage.php?product_id=<?php echo $productId; ?>" class="game-card">
                        <div class="game-card-inner">
                            <?php if (!empty($row["IMAGE"])): ?>
                                <div class="image-container">
                                    <img src="../../<?php echo htmlspecialchars($row["IMAGE"]); ?>" 
                                        alt="<?php echo htmlspecialchars($row["NAME"]); ?>">
                                </div>
                            <?php endif; ?>
                            
                            <div class="content-overlay">
                                <h3><?php echo htmlspecialchars($row["NAME"]); ?></h3>
                                
                                <div class="product-price">
                                    <?php if ($row["DISCOUNT"] > 0): 
                                        $originalPrice = $row["CURRENT_PRICE"];
                                        $discountedPrice = $originalPrice - ($originalPrice * $row["DISCOUNT"]);
                                    ?>
                                        <div class="price-container">
                                            <div class="discount-info">
                                                <span class="discount-badge">
                                                    -<?php echo ($row["DISCOUNT"] * 100); ?>%
                                                </span>
                                                <span class="original-price">
                                                    IDR <?php echo number_format($originalPrice * 1000, 0); ?>
                                                </span>
                                            </div>
                                            <div class="final-price">
                                                IDR <?php echo number_format($discountedPrice * 1000, 0); ?>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <div class="final-price">
                                            IDR <?php echo number_format($row["CURRENT_PRICE"] * 1000, 0); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </a>
                <?php
                }
            } else {
                echo '<div class="no-products">No products found.</div>';
            }
            ?>
        </div>
    </section>

    <!-- Footer -->
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
</footer>
</body>
</html>
