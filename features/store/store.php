<?php
include '../connection/connection.php';
// Start session
session_start();
$isLoggedIn = isset($_SESSION['username']);

// Handle search query
$searchQuery = '';
if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET["search"])) {
    $searchQuery = trim($_GET["search"]);
}

// Handle product selection
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["product_id"])) {
    $_SESSION["product_id"] = $_POST["product_id"];
    header("Location: productpage.php");
    exit();
}

$limit = 4;
$pn = 1;  
if (isset($_GET["page"])) {  
    $pn  = $_GET["page"];  
    $start_from = ($pn - 1) * $limit;   
    $queryPagination = "
    SELECT * FROM products
    ORDER BY name  
    OFFSET :start_row ROWS
    FETCH NEXT :limit ROWS ONLY";
    $result = oci_parse($conn, $queryPagination);
    oci_bind_by_name($result, ':start_row', $start_from);
    oci_bind_by_name($result, ':limit', $limit);
}  
else {  
    // Base query
    $query = "SELECT product_id, name, current_price, discount, image FROM products ORDER BY name FETCH FIRST 4 ROWS ONLY";
    $result = oci_parse($conn, $query);
}   


// Append search condition if searchQuery is not empty
$querySearch = "SELECT product_id, name, current_price, discount, image FROM products";
if (!empty($searchQuery)) {
    $querySearch .= " WHERE LOWER(name) LIKE '%' || :search || '%'";
    $result = oci_parse($conn, $querySearch);
    oci_bind_by_name($result, ':search', $searchQuery);
}


if (!oci_execute($result)) {
    $e = oci_error($result);
    echo "Error: " . htmlentities($e['message'], ENT_QUOTES);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Games Collection | ORON</title>
    <link rel="stylesheet" href="../../Styling/css/newheader.css" />
    <link rel="stylesheet" href="../../Styling/css/store.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        @import url('https://fonts.cdnfonts.com/css/lemonmilk');
    </style>
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

    <!-- Games Collection -->
    <section class="games-collection" id="products">
        <h2>
            <span class="highlight1">GAMES</span> <span class="highlight2">COLLECTION</span>
        </h2>

    <!-- Search Form -->
        <div class="search-bar">
            <form method="get" action="store.php#products">
                <input type="text" name="search" value="<?php echo htmlspecialchars($searchQuery); ?>" placeholder="Search for games..." />
                <button type="submit"><a href="#"><i class="fa-solid fa-magnifying-glass"></i></a></button>
            </form>
        </div>

        <div class="games-grid">
            <?php
            if ($result && oci_fetch_all($result, $rows, 0, -1, OCI_FETCHSTATEMENT_BY_ROW) > 0) {
                foreach ($rows as $row) {
                    $product_id = htmlspecialchars($row["PRODUCT_ID"]);
                    ?>
                    <a href="productpage.php?product_id=<?php echo $product_id; ?>" class="game-card">
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
        <?php
            $count_query = "SELECT COUNT(*) as total FROM products";
            $resultcount = oci_parse($conn, $count_query);
            oci_execute($resultcount);
            $total_row = oci_fetch_assoc($resultcount);
            $total_records = $total_row['TOTAL'];
            $total_pages = ceil($total_records / $limit);

            echo "<div class='pagination'>";
            if ($pn > 1) {
                echo "<a class='pagination-link' href='?page=" . ($pn - 1) . "#products'>Previous</a> ";
            }

            for ($i = 1; $i <= $total_pages; $i++) {
                if ($i == $pn) {
                    echo "<span class='pagination-current'>$i</span> "; // Current page
                } else {
                    echo "<a class='pagination-link' href='?page=$i#products'>$i</a> ";
                }
            }

            if ($pn < $total_pages) {
                echo "<a class='pagination-link' href='?page=" . ($pn + 1) . "#products'>Next</a>";
            }
            echo "</div>";
        ?>
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
<script src="../../Styling/JS/function.js"></script> 
</body>
</html>
