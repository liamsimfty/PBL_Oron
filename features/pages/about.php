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
                <?php if ($isLoggedIn): ?>
                    <li class="profile-container"><a class="nav-link" href="features/profile/profile.php"><i class="fa-solid fa-user"></i><?php echo htmlspecialchars($_SESSION['username']); ?></a>
                        <ul class="profile">
                          <li>
                            <a>Profile</a>
                          </li>
                          <li>
                            <a>Log Out</a>
                          </li>
                          <li>
                            <a>Setting</a>
                          </li>
                          <li>
                            <a>Support</a>
                          </li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li><a href="../profile/profile.php"><i class="fa-solid fa-user"></i></a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
</header>
<!-- Main Banner -->
<section class="main-banner">
    <div class="section-image1">
    <img src="../../Styling/images/about1.png" alt="About Us">
    </div>
</section>


<div class="additional-image2">
    <img src="../../Styling/images/Vector 7.png" alt="Additional Image 2">
</div>
<div class="additional-image3">
    <img src="../../Styling/images/Vector 8.png" alt="Additional Image 3">
</div>

<div class="title-oron">
    <div class="title">
        <img src="../../Styling/images/oron-logo.png" alt="Logo ORON">
        <h1>ORON</h1>
    </div>
</div>

<div class="about-us">
    <div class="img-container">
        <img src="../../Styling/images/vr.png" alt="VR Analog">
    </div>
</div>

<!-- FEATURES -->

<div class="features">
    <h1>Features</h1>
    <p>Dive into ultimate destination for playing, discussing, and crafting unforgettable gaming experience</p>
</div>

<div class="features-caps">
    <div class="caps1-3">
        <div class="caps-1">
            <div class="caps-logo-container">
                <div class="fa">
                    <i class="fa-solid fa-user-group fa-2x"></i>
                </div>
            </div>
            <h5 class="captions">Your Space For All Things Gaming</h5>
            <p class="descriptions">Join discussions, discover creative content, and stay updated with news - 
                all from the Oron Community and official creators. Game Hubs bring gamers together to rate,
                share, and enjoy everything gaming.
            </p>
        </div>
        <div class="caps-2">
            <div class="caps-logo-container">
                <div class="fa">
                    <i class="fa-solid fa-heart fa-2x"></i>
                </div>
            </div>
            <h5 class="captions">Supporting Game Creators</h5>
            <p class="descriptions">With Oron, every game you purchase is authentic, ensuring your support goes 
                directly to the rightful owners and creators behind each title.</p>
        </div>
        <div class="caps-3">
            <div class="caps-logo-container">
                <div class="fa">
                    <i class="fa-solid fa-chart-simple fa-2x"></i>
                </div>
            </div>
            <h5 class="captions">Handpicked Games, Just For You</h5>
            <p class="descriptions">From blockbuster AAA titles to standout indie gems and timeless classics, 
                every game on Oron is here because it’s been carefully chosen with you in mind.</p>
        </div>
    </div>
    <div class="caps4-6">
        <div class="caps-4">
            <div class="caps-logo-container">
                <div class="fa">
                    <i class="fa-solid fa-wallet fa-2x"></i>
                </div>
            </div>
            <h5 class="captions">Purchase Your Way</h5>
            <p class="descriptions">Oron’s storefront offers over 100 payment methods in 35+ currencies, 
                giving you the freedom to pay however suits you best.</p>
        </div>
        <div class="caps-5">
            <div class="caps-logo-container">
                <div class="fa">
                    <i class="fa-solid fa-comment fa-2x"></i>
                </div>
            </div>
            <h5 class="captions">Connect Like Never Before</h5>
            <p class="descriptions">Stay close to your crew with an enhanced friends list, text, and voice chat features. 
                Oron Chat makes it easy and fun to organize, connect, and jump into games with friends anytime.</p>
        </div>
        <div class="caps-6">
            <div class="caps-logo-container">
                <div class="fa">
                    <i class="fa-solid fa-envelope fa-2x"></i>
                </div>
            </div>
            <h5 class="captions">True Ownership With Oron</h5>
            <p class="descriptions">We believe in your right to fully own the games you buy. With DRM-free gaming, 
                you’ll never be locked out or forced to prove ownership—your games are yours to keep, no strings attached.</p>
        </div>
    </div>
</div>

<!-- ACHIEVEMENT -->

<div class="achievement">
    <div class="achievement-container">
        <div class="ach">
            <i class="fa-regular fa-thumbs-up fa-2x"></i>
            <h1>299+</h1>
            <h3>Top Games</h3>
        </div>
        <div class="ach">
            <i class="fa-solid fa-gamepad fa-2x"></i>
            <h1>489+</h1>
            <h3>Games Title</h3>
        </div>
        <div class="ach">
            <i class="fa-solid fa-user-plus  fa-2x"></i>
            <h1>900+</h1>
            <h3>Active Players</h3>
        </div>
        <div class="ach">
            <i class="fa-solid fa-medal fa-2x"></i>
            <h1>99%</h1>
            <h3>Quality Gaming Experience</h3>
        </div>
    </div>
</div>

<!-- FEEDBACK -->

<div class="feedback-title">
    <h1>Feedback</h1>
</div>


<!-- FOOTER -->

<footer class="footer">
    <img src="../../Styling/images/footerbg.png" alt="Footer">
</footer>


</body>
</html>