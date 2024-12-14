<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"/>
    <link rel="stylesheet" href="../../Styling/css/newheader.css" />
    <title>Profile Dropdown</title>
  </head>
  <body>

    <?php
    // Start session
    session_start();

    // Check if user is logged in
    $isLoggedIn = isset($_SESSION['username']);
    ?>


<div class="navbar">
  <nav class="navbar">
    <img src="../../Styling/images/oron-logo.png" class="navbar-logo" alt="logo" />
    <h1>ORON</h1>
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
  </nav>
</div>

    <script src="../../Styling/JS/newheader.js"></script>
  </body>
</html>