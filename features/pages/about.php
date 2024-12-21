<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../../Styling/css/about.css" />
    <title>Profile Dropdown</title>
</head>
<body>

<!-- DATABASE CONNECTION -->
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
    <li><a href="../library/library.php">Library</a></li>
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

<!-- MAIN IMAGE OF ABOUT PAGE -->
<div class="main-image">
    <img src="../../Styling/images/about.png">
</div>

<!-- VECTOR BELOW MAIN IMAGE -->
<div class="vector">
    <div class="vector1">
        <img src="../../Styling/images/Vector 7.png">
    </div>
    <div class="between-vectors">
        <img src="../../Styling/images/oron-logo.png">
        <h1>ORON</h1>
    </div>
    <div class="vector2">
        <img src="../../Styling/images/Vector 8.png">
    </div>
</div>

<!-- ABOUT US SECTION -->
<div class="about-us-container">
    <div class="first-grid">
      <div class="very-first-grid">
        <div class="dice-container">
          <i class="fa-solid fa-dice fa-1x"></i>
          <p>About Us</p>
        </div>
        <h1 class="h1-1">Your Forever</h1>
        <h1 class="h1-2">Gaming Hub</h1>
        <p>A home for building, playing, and preserving your handpicked game collection, 
          Oron is a digital platform that puts gamers at the center, respecting the true ownership of your games. 
          Dive into the ultimate destination for playing, discussing, and crafting unforgettable gaming experiences.</p>
      </div>
      <div class="very-second-grid">
        <div class="captions">
          <div class="checklist-container"><i class="fa-solid fa-check"></i></div>
          <p>Trusted by Gamers Worldwide</p>
        </div>
        <div class="captions">
          <div class="checklist-container"><i class="fa-solid fa-check"></i></div>
          <p>Massive Game Library</p>
        </div>
        <div class="captions">
          <div class="checklist-container"><i class="fa-solid fa-check"></i></div>
          <p>Simple and Secure Transactions</p>
        </div>
        <div class="captions">
          <div class="checklist-container"><i class="fa-solid fa-check"></i></div>
          <p>Award-Winning Game Selection</p>
        </div>
        <div class="captions">
          <div class="checklist-container"><i class="fa-solid fa-check"></i></div>
          <p>Top Gaming Destination</p>
        </div>
        <div class="captions">
          <div class="checklist-container"><i class="fa-solid fa-check"></i></div>
          <p>Cutting-Edge Game Engine</p>
        </div>
        <div class="captions">
          <div class="checklist-container"><i class="fa-solid fa-check"></i></div>
          <p>High Security Standards</p>
        </div>
        <div class="captions">
          <div class="checklist-container"><i class="fa-solid fa-check"></i></div>
          <p>Constantly Updated Game Types</p>
        </div>
      </div>
    </div>
    <div class="second-grid">
        <img src="../../Styling/images/vr.png">
    </div>
</div>

<!-- FEATURE SECTION -->

<!-- Feature Caption Header -->
<div class="features-caps">
  <h1>Features</h1>
  <p>Dive into the ultimate destination for playing, discussing, and crafting unforgettable gaming experiences.</p>
</div>

<!-- Feature Captions -->
<div class="features-captions-container">
  <div class="captions1">
    <div class="logo-container"><i class="fa-solid fa-users fa-3x"></i></div>
    <h1>Your Space for All Things Gaming</h1>
    <p>Join discussions, discover creative content, and stay updated with news—all from the Oron community and official creators. 
      Game Hubs bring gamers together to rate, share, and enjoy everything gaming.</p>
  </div>
  <div class="captions2">
    <div class="logo-container"><i class="fa-solid fa-heart fa-3x"></i></div>
    <h1>Supporting Game Creators</h1>
    <p>With Oron, every game you purchase is authentic, 
      ensuring your support goes directly to the rightful owners and creators behind each title.</p>
  </div>
  <div class="captions3">
    <div class="logo-container"><i class="fa-solid fa-chart-simple fa-3x"></i></div>
    <h1>Handpicked Games, Just for You</h1>
    <p>From blockbuster AAA titles to standout indie gems and timeless classics, 
      every game on Oron is here because it's been carefully chosen with you in mind.</p>
  </div>
  <div class="captions4">
    <div class="logo-container"><i class="fa-solid fa-wallet fa-3x"></i></div>
    <h1>Purchases, Your Way</h1>
    <p>Oron's storefront offers over 100 payment methods in 35+ currencies, 
      giving you the freedom to pay however suits you best.</p>
  </div>
  <div class="captions5">
    <div class="logo-container"><i class="fa-solid fa-comments fa-3x"></i></div>
    <h1>Connect Like Never Before</h1>
    <p>Stay close to your crew with an enhanced friends list, text, and voice chat features. 
      Oron Chat makes it easy and fun to organize, connect, and jump into games with friends anytime.</p>
  </div>
  <div class="captions6">
    <div class="logo-container"><i class="fa-solid fa-download fa-3x"></i></div>
    <h1>True Ownership with Oron</h1>
    <p>We believe in your right to fully own the games you buy. With DRM-free gaming, 
      you'll never be locked out or forced to prove ownership—your games are yours to keep, no strings attached.</p>
  </div>
</div>

<!-- ACHIEVEMENT CONTAINER -->
<div class="achievement">
  <div class="achievement-container">
    <div class="achievement1">
      <i class="fa-solid fa-thumbs-up fa-3x"></i>
      <h1>299+</h1>
      <h2>Top Games</h2>
    </div>
    <div class="achievement2">
      <i class="fa-solid fa-gamepad fa-3x"></i>
      <h1>489+</h1>
      <h2>Game Titles</h2>
    </div>
    <div class="achievement3">
      <i class="fa-solid fa-user-plus fa-3x"></i>
      <h1>900+</h1>
      <h2>Active Players</h2>
    </div>
    <div class="achievement4">
      <i class="fa-solid fa-medal fa-3x"></i>
      <h1>99%</h1>
      <h2>Quality Gaming Experience</h2>
    </div>
  </div>
</div>

<!-- FEEDBACK SECTION -->
<div class="feedback-header-caption">
  <h1>Feedback</h1>
</div>

<div class="feedback-container">
  <div class="feedback">
    <i class="fa-solid fa-quote-right fa-4x"></i>
    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Iusto commodi exercitationem dolor alias quae! Provident minus a odit? Assumenda, sunt. Suscipit rem impedit in cupiditate sequi aspernatur hic beatae ab.</p>
  </div>
  <div class="feedback">
    <i class="fa-solid fa-quote-right fa-4x"></i>
    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Iusto commodi exercitationem dolor alias quae! Provident minus a odit? Assumenda, sunt. Suscipit rem impedit in cupiditate sequi aspernatur hic beatae ab.</p>
  </div>
  <div class="feedback">
    <i class="fa-solid fa-quote-right fa-4x"></i>
    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Iusto commodi exercitationem dolor alias quae! Provident minus a odit? Assumenda, sunt. Suscipit rem impedit in cupiditate sequi aspernatur hic beatae ab.</p>
  </div>
  <div class="feedback">
    <i class="fa-solid fa-quote-right fa-4x"></i>
    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Iusto commodi exercitationem dolor alias quae! Provident minus a odit? Assumenda, sunt. Suscipit rem impedit in cupiditate sequi aspernatur hic beatae ab.</p>
  </div>
  <div class="feedback">
    <i class="fa-solid fa-quote-right fa-4x"></i>
    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Iusto commodi exercitationem dolor alias quae! Provident minus a odit? Assumenda, sunt. Suscipit rem impedit in cupiditate sequi aspernatur hic beatae ab.</p>
  </div>
  <div class="feedback">
    <i class="fa-solid fa-quote-right fa-4x"></i>
    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Iusto commodi exercitationem dolor alias quae! Provident minus a odit? Assumenda, sunt. Suscipit rem impedit in cupiditate sequi aspernatur hic beatae ab.</p>
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

<script src="../../Styling/JS/about.js"></script>
</body>
</html>