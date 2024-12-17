<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"/>
  <link rel="stylesheet" href="../../Styling/css/homepage.css" />
  <link rel="stylesheet" href="../../Styling/css/swiper-bundle.min.css">
  <title>Profile Dropdown</title>
</head>
<body>

    <?php
    // Start session
    session_start();

    // Check if user is logged in
    $isLoggedIn = isset($_SESSION['username']);
    ?>


<nav class="navbar">
  <div class="navbar-logo">
    <img src="../../Styling/images/oron-logo.png" class="navbar-logo" alt="logo" />
    <h1>ORON</h1>
  </div>
  <div class="navbar-link">
  <ul class="navbar-list">
    <li><a href="../pages/homepage.php">Home</a></li>
    <li><a href="../store/store.php">Store</a></li>
    <li><a href="../pages/blog.php">Blog</a></li>
    <li><a href="../pages/about.php">About</a></li>
    <li><a href="#"><i class="fa-solid fa-magnifying-glass"></i></a></li>
    <li><a href="#"><i class="fa-solid fa-cart-shopping"></i></a></li>
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
          <a href="../pages/profile.php">
            <i class="fa-regular fa-user"></i>
            Edit Profile
          </a>
        </li>
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

<form>
    <label class="slide active" for="clubs"><img src="../../Styling/images/fc25.png" alt="Clubs"><span class="arrow prev"><</span><span class="arrow next">></span></label>
    <label class="slide" for="hearts"><img src="../../Styling/images/blckmyth.png" alt="Hearts"><span class="arrow prev"><</span><span class="arrow next">></span></label>
    <label class="slide" for="spades"><img src="../../Styling/images/Group 68.png" alt="Spades"><span class="arrow prev"><</span><span class="arrow next">></span></label>
    <label class="slide" for="diamonds"><img src="../../Styling/images/Group 69.png" alt="Diamonds"><span class="arrow prev"><</span><span class="arrow next">></span></label>
</form>

<!-- BEST SELLER SECTION -->

<div class="best-seller-container">
  <div class="caption">
    <h1>BEST SELLER</h1>
  </div>
  <div class="best-seller-games-container">
    <div class="best-seller1">
      <img src="../../Styling/images/game1.png">
    </div>
    <div class="best-seller2">
      <img src="../../Styling/images/game1.png">
    </div>
    <div class="best-seller3">
      <img src="../../Styling/images/game1.png">
    </div>
  </div>
</div>

<!-- NEWLY ADDED GAMES SECTION -->

<div class="newly-added-container">
  <h1>New Games</h1>
</div>

<section class="container">
  <div class="card__container swiper">
    <div class="card__content">
        <div class="swiper-wrapper">
          <!-- Game Card -->
          <div class="game-card swiper-slide">
            <a href="gamedespage.php">
                <img src="../../Styling/images/game1.png" alt="GTA">
            </a>
                <h3>Grand Theft Auto</h3>
                <p>IDR 221.600</p>
            </div>
            <div class="game-card swiper-slide">
            <a href="gamedespage.php">
                <img src="../../Styling/images/game3.jpg" alt="Black Myth: Wukong">
            </a>
                <h3>Black Myth: Wukong</h3>
                <p>IDR 221.600</p>
            </div>
            <div class="game-card swiper-slide">
                <img src="../../Styling/images/gamered.jpg" alt="Red Dead Redemption">
                <h3>Red Dead Redemption</h3>
                <p>IDR 221.600</p>
            </div>
            <div class="game-card swiper-slide">
                <img src="../../Styling/images/game1.png" alt="GTA">
                <h3>Grand Theft Auto</h3>
                <p>IDR 221.600</p>
            </div>
            <div class="game-card swiper-slide">
                <img src="../../Styling/images/game3.jpg" alt="Black Myth: Wukong">
                <h3>Black Myth: Wukong</h3>
                <p>IDR 221.600</p>
            </div>
            <div class="game-card swiper-slide">
                <img src="../../Styling/images/gamered.jpg" alt="Red Dead Redemption">
                <h3>Red Dead Redemption</h3>
                <p>IDR 221.600</p>
            </div>
            <div class="game-card swiper-slide">
                <img src="../../Styling/images/gamered.jpg" alt="Red Dead Redemption">
                <h3>Red Dead Redemption</h3>
                <p>IDR 221.600</p>
            </div>
            <div class="game-card swiper-slide">
                <img src="../../Styling/images/gamered.jpg  " alt="Red Dead Redemption">
                <h3>Red Dead Redemption</h3>
                <p>IDR 221.600</p>
            </div>
        </div>
    </div>

    <!-- Navigation buttons -->
    <div class="swiper-button-next">
        <i class="ri-arrow-right-s-line"></i>
    </div>
    
    <div class="swiper-button-prev">
        <i class="ri-arrow-left-s-line"></i>
    </div>

    <!-- Pagination -->
    <div class="swiper-pagination"></div>
  </div>
</section>

<div class="newly-added-container">
  <h1>Top Free Games</h1>
</div>

<section class="container">
    <div class="card__container swiper">
      <div class="card__content">
          <div class="swiper-wrapper">
              <!-- Game Card -->
    <div class="game-card swiper-slide">
      <a href="gamedespage.php">
          <img src="../../Styling/images/game1.png" alt="GTA">
      </a>
          <h3>Grand Theft Auto</h3>
          <p>IDR 221.600</p>
      </div>
      <div class="game-card swiper-slide">
      <a href="gamedespage.php">
          <img src="../../Styling/images/game3.jpg" alt="Black Myth: Wukong">
      </a>
          <h3>Black Myth: Wukong</h3>
          <p>IDR 221.600</p>
      </div>
      <div class="game-card swiper-slide">
          <img src="../../Styling/images/gamered.jpg" alt="Red Dead Redemption">
          <h3>Red Dead Redemption</h3>
          <p>IDR 221.600</p>
      </div>
      <div class="game-card swiper-slide">
          <img src="../../Styling/images/game1.png" alt="GTA">
          <h3>Grand Theft Auto</h3>
          <p>IDR 221.600</p>
      </div>
      <div class="game-card swiper-slide">
          <img src="../../Styling/images/game3.jpg" alt="Black Myth: Wukong">
          <h3>Black Myth: Wukong</h3>
          <p>IDR 221.600</p>
      </div>
      <div class="game-card swiper-slide">
          <img src="../../Styling/images/gamered.jpg" alt="Red Dead Redemption">
          <h3>Red Dead Redemption</h3>
          <p>IDR 221.600</p>
      </div>
      <div class="game-card swiper-slide">
          <img src="../../Styling/images/gamered.jpg" alt="Red Dead Redemption">
          <h3>Red Dead Redemption</h3>
          <p>IDR 221.600</p>
      </div>
      <div class="game-card swiper-slide">
          <img src="../../Styling/images/gamered.jpg  " alt="Red Dead Redemption">
          <h3>Red Dead Redemption</h3>
          <p>IDR 221.600</p>
      </div>
          </div>
      </div>

      <!-- Navigation buttons -->
      <div class="swiper-button-next">
          <i class="ri-arrow-right-s-line"></i>
      </div>
      
      <div class="swiper-button-prev">
          <i class="ri-arrow-left-s-line"></i>
      </div>

      <!-- Pagination -->
      <div class="swiper-pagination"></div>
    </div>
</section>

<!-- VECTOR -->
<div class="vectors">
  <div class="vector1">
    <img src="../../Styling/images/Vector 7.png">
  </div>
  <div class="vector2">
    <img src="../../Styling/images/Vector 8.png">
  </div>
</div>

<!-- LATEST BLOG SPOT SECTION -->
<div class="newly-added-container">
  <h1>LATEST BLOG SPOT</h1>
</div>

<div class="blog-spot-container">
  <div class="blog-image-and-caption">
    <div class="blog-image">
      <img src="../../Styling/images/image (10).png">
    </div>
    <div class="blog-captions">
      <h1 class="date">20 Oktober 2024</h1>
      <h1 class="headline">Update Terbaru: Game Cyberpunk 2077 Terima Patch Besar 2.0 - Banyak Perbaikan dan Fitur Baru!</h1>
      <p class="description">Setelah lama dinantikan oleh para penggemar, Cyberpunk 2077 baru saja merilis Patch 2.0 yang membawa banyak perubahan besar. CD Projekt Red telah berusaha memperbaiki bug yang masih tersisa sekaligus menambahkan beberapa fitur baru untuk meningkatkan pengalaman bermain........</p>
      <a class="read-more" href="../../features/pages/blog.php">Read More</a>
    </div>
  </div>
  <div class="blog-image-and-caption">
    <div class="blog-image">
      <img src="../../Styling/images/image (11).png">
    </div>
    <div class="blog-captions">
      <h1 class="date">18 Oktober 2024</h1>
      <h1 class="headline">Pengumuman Besar: The Elder Scrolls VI Resmi Diumumkan untuk 2026!</h1>
      <p class="description">Berita besar bagi para penggemar The Elder Scrolls! Bethesda resmi mengumumkan bahwa sekuel yang telah lama dinantikan, The Elder Scrolls VI, akan dirilis pada tahun 2026. Pengumuman ini dibuat dalam acara E3 2024, dan trailer pertama memperlihatkan pemandangan dunia Tamriel yang lebih luas dan detail dibandingkan seri sebelumnya.</p>
      <a class="read-more" href="../../features/pages/blog.php">Read More</a>
    </div>
  </div>
</div>

<!-- FOOTER SECTION -->
<footer class="footer">
  <div class="footer-captions-container">
    <div class="about-us-footer">
      <h1>About Us</h1>
      <p>Oron adalah solusi terbaik untuk membeli dan menjual video game. Dengan dukungan komunitas global, kami memprioritaskan pengalaman pengguna yang aman dan transparan.</p>
    </div>
    <div class="social-media">
      <h1>Our Social Media:</h1>
      <div class="sosmed">
        <a href=""></a>
        <a href=""></a>
        <a href=""></a>
        <a href=""></a>
      </div>
    </div>
  </div>
</footer>



<script src="../../Styling/js/swiper-bundle.min.js"></script>
<script src="../../Styling/js/main.js"></script>
<script src="../../Styling/JS/newheader.js"></script>
<script src="../../Styling/JS/slide.js"></script>
</body>
</html>