<?php
include '../connection/connection.php';
session_start();
if (!isset($_SESSION['account_id'])) {
    echo "<p>You need to log in to view your tranasction history.</p>";
    header('Location:../login/login.php');
}
$isLoggedIn = isset($_SESSION['username']);
$account_id = $_SESSION['account_id'];

// Query untuk mengambil data transaksi
$query = "
SELECT 
    TRUNC(t.transaction_date) AS transaction_date,
    LISTAGG(p.name || ' ($' || TO_CHAR(t.price_at_checkout, 'FM999999.99') || ')', CHR(10)) 
        WITHIN GROUP (ORDER BY p.name) AS nama_games,
    MIN(t.payment_status) AS payment_status,
    SUM(t.price_at_checkout) AS total_price
FROM transaction t
JOIN products p ON t.product_id = p.product_id
WHERE t.account_id = :account_id
GROUP BY TRUNC(t.transaction_date)
ORDER BY TRUNC(t.transaction_date) DESC NULLS LAST
";

// Menyiapkan dan menjalankan query
$stid = oci_parse($conn, $query);
oci_bind_by_name($stid, ":account_id", $account_id);

if (!oci_execute($stid)) {
    $e = oci_error($stid); // Tangkap informasi error
    echo "<p>Error: " . htmlspecialchars($e['message']) . "</p>";
    exit; // Hentikan eksekusi untuk debugging
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"/>
    <link rel="stylesheet" href="../../Styling/css/newheader.css" />
    <link rel="stylesheet" href="../../Styling/css/transaction.css">
    <link rel="stylesheet" href="../../Styling/css/footer.css">
    <title>Data Transaksi</title>
    <style>
        @import url('https://fonts.cdnfonts.com/css/lemonmilk');
    </style>
</head>
<body>
<header>
    <div>
    <nav class="navbar">
            <img src="../../Styling/images/oron-logo.png" class="navbar-logo" alt="logo" />
            <h1>ORON</h1>
            <ul class="navbar-list">
            <li><a href="../pages/homepage.php">Home</a></li>
            <li><a href="../store/store.php">Store</a></li>
            <li><a href="../library/library.php">Library</a></li>
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
        </nav>
        </div>
</header>
    <h1>Data Transaksi</h1>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Games</th>
                    <th>Transaction Date</th>
                    <th>Status Pembayaran</th>
                    <th>Total Harga</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $no = 1;
                while ($row = oci_fetch_assoc($stid)): ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td class="game-list"><?= htmlspecialchars($row['NAMA_GAMES']); ?></td>
                        <td><?= $row['TRANSACTION_DATE']; ?></td>
                        <td>
                            <span class="status <?= strtolower($row['PAYMENT_STATUS']) === 'paid' ? 'status-paid' : 'status-pending' ?>">
                                <?= htmlspecialchars($row['PAYMENT_STATUS']); ?>
                            </span>
                        </td>
                        <td><?= number_format($row['TOTAL_PRICE'], 2); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
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
