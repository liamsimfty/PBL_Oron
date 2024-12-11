
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Games Collection - ORON</title>
    <link rel="stylesheet" href="../../Styling/css/games.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
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


<div class="additional-image2">
        <img src="../../Styling/images/Vector 7.png" alt="Additional Image2">
</div>
    
<div class="additional-image3">
    <img src="../../Styling/images/Vector 8.png" alt="Additional Image8">
</div>


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
            <!-- Game Card -->
            <div class="game-card">
            <a href="gamedespage.php">
                <img src="../../Styling/images/game1.png" alt="GTA">
            </a>
                <h3>Grand Theft Auto</h3>
                <p>IDR 221.600</p>
            </div>
            <div class="game-card">
            <a href="gamedespage.php">
                <img src="../../Styling/images/game3.jpg" alt="Black Myth: Wukong">
</a>
                <h3>Black Myth: Wukong</h3>
                <p>IDR 221.600</p>
            </div>
            <div class="game-card">
                <img src="../../Styling/images/gamered.jpg" alt="Red Dead Redemption">
                <h3>Red Dead Redemption</h3>
                <p>IDR 221.600</p>
            </div>
            <div class="game-card">
                <img src="../../Styling/images/game1.png" alt="GTA">
                <h3>Grand Theft Auto</h3>
                <p>IDR 221.600</p>
            </div>
            <div class="game-card">
                <img src="../../Styling/images/game3.jpg" alt="Black Myth: Wukong">
                <h3>Black Myth: Wukong</h3>
                <p>IDR 221.600</p>
            </div>
            <div class="game-card">
                <img src="../../Styling/images/gamered.jpg" alt="Red Dead Redemption">
                <h3>Red Dead Redemption</h3>
                <p>IDR 221.600</p>
            </div>
            <div class="game-card">
                <img src="../../Styling/images/gamered.jpg" alt="Red Dead Redemption">
                <h3>Red Dead Redemption</h3>
                <p>IDR 221.600</p>
            </div>
            <div class="game-card">
                <img src="../../Styling/images/gamered.jpg  " alt="Red Dead Redemption">
                <h3>Red Dead Redemption</h3>
                <p>IDR 221.600</p>
            </div>
            <!-- Tambahkan lebih banyak game card sesuai kebutuhan -->
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
