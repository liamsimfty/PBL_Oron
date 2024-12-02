<?php
$isLoggedIn = isset($_SESSION['username']);
session_start(); // Mulai sesi
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Login</title>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light">
            <div class="container">
                <a class="navbar-brand" href="#">ORON</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="../store/store.php">Store</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../library/library.php">Library</a>
                        </li>
                        <li class="nav-item">
                            <?php if ($isLoggedIn): ?>
                                <a class="nav-link" href="../profile/profile.php"><?php echo htmlspecialchars($_SESSION['username']); ?></a>
                            <?php else: ?>
                                <a class="nav-link" href="../login/login.php">Profile</a>
                            <?php endif; ?>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../cart/cart.php">Cart</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

    <form method="POST" action="profile.php">    
        <input type="submit" name="logout" value="Log Out">
    </form>
    <?php
    // Jika tombol logout ditekan
    if (isset($_POST['logout'])) {
        session_unset(); // Hapus semua data sesi
        session_destroy(); // Akhiri sesi
        header("Location: ../../dashboard.php"); // Redirect ke halaman login
        exit();
    }
    ?>
</body>
</html>



