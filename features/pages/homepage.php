<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../Styling/css/homepagge.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
    <?php
    // Start session
    session_start();

    // Check if user is logged in
    $isLoggedIn = isset($_SESSION['username']);
    ?>
    
<!-- Navbar -->
<header>
    <div class="header-container">
        <div class="brand-logo">
            <img src="../../Styling/images/oron-logo.png" alt="Logo ORON">
            <h1>ORON</h1>
        </div>
        <nav>
            <ul>
                <li><a class="nav-link" href="../../features/pages/homepage.php">HOME</a></li>
                <li><a class="nav-link" href="../../features/store/store.php">STORE</a></li>
                <li><a class="nav-link" href="../../features/pages/blog.php">BLOG</a></li>
                <li><a class="nav-link" href="../../features/pages/about.php">ABOUT</a></li>
                <li><a class="nav-link" href="../../features/store/store.php"><i class="fa-solid fa-magnifying-glass"></i></a></li>
                <?php if ($isLoggedIn): ?>
                    <li> <a class="nav-link" href="features/profile/profile.php"><i class="fa-solid fa-user"></i><?php echo htmlspecialchars($_SESSION['username']); ?></a></li>
                <?php else: ?>
                    <li><a class="nav-link" href="features/login/login.php"><i class="fa-solid fa-user"></i></a></li>
                <?php endif; ?>
                    <li> <a class="nav-link" href="../../features/cart/cart.php"><i class="fa-solid fa-cart-shopping"></i></a></li>
            </ul>
        </nav>
    </div>
</header>

<!-- Main Banner -->
<section class="main-banner">
    <img src="../../Styling/images/bg.png" alt="FC25 Banner">
    <div class="banner">
        <img src="../../Styling/images/logofc25.png" alt="EA Sports FC25 Logo" class="hero-logo">
        <p>The legendary FIFA series has been produced by EA <br>SPORTS for over 20 years, and is now the largest sports <br>video game franchise on the planet..</p>
        <button>Play Now</button>
        <button><i class="fa-solid fa-plus"></i></button>
    </div>
</section>

<div class="additional-image1">
        <img src="../../Styling/images/Group37.png" alt="Additional Image1">
</div>


</body>
</html>