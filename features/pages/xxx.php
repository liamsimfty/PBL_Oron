<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage</title> 
    <link rel="stylesheet" href="../../Styling/css/cott.css">
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
                <li><i class="fa-solid fa-user"></i></a>
                    <ul class="dropdown">
                      <li>
                        <a></a>
                      </li>
                      <li>
                        <a></a>
                      </li>
                      <li>
                        <a></a>
                      </li>
                      <li>
                        <a></a>
                      </li>
                    </ul>
                </li>
            </ul>
        </nav>
    </div>
</header>


<section class="container">
  <div class="slide-wrapper">
    <div class="slider">
      <img id="slide1" src="../../Styling/images/blackmyth.png" alt=""/>
      <img id="slide2" src="../../Styling/images/bg.png" alt=""/>
      <img id="slide3" src="../../Styling/images/BG_Oron.png" alt=""/>
    </div>
    <div class="slider-nav">
      <a href="#slide1"></a>
      <a href="#slide2"></a>
      <a href="#slide3"></a>
    </div>
  </div>
</section>
</body>
</html>