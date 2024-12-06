<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../Styling/css/about.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Blog - ORON</title>
</head>
<body>
    
    <?php
    // Start session
    session_start();

    // Check if user is logged in
    $isLoggedIn = isset($_SESSION['username']);
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
<!-- Main Banner -->
<section class="main-banner">
    <div class="section-image1">
    <img src="../../Styling/images/about1.png" alt="FC25 Banner">
    </div>
</section>

</body>
</html>