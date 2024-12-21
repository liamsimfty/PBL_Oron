<?php
include '../connection/connection.php';      
session_start();

// Redirect if not logged in
if (!isset($_SESSION['account_id'])) {
    header('Location: ../login/login.php');
    exit();
}

// Get the account_id from session
$accountId = $_SESSION['account_id'];

// Query to get library data for the user
$query = "SELECT 
        l.library_id, 
        p.product_id,
        p.name AS game_name, 
        TO_CHAR(l.purchase_date, 'DD-Mon-YYYY HH24:MI') AS purchase_date 
    FROM 
        library l
    JOIN 
        products p ON l.product_id = p.product_id
    WHERE 
        l.account_id = :account_id
    ORDER BY 
        l.purchase_date DESC";

// Prepare and execute the query
$stid = oci_parse($conn, $query);
oci_bind_by_name($stid, ":account_id", $accountId);
oci_execute($stid);

// Fetch results into an array
$library_items = [];
while (($row = oci_fetch_array($stid, OCI_ASSOC)) != false) {
    $library_items[] = [
        'product_id' => $row['PRODUCT_ID'],
        'game_name' => $row['GAME_NAME'],
        'purchase_date' => $row['PURCHASE_DATE']
    ];
}

// Free resources and close connection
oci_free_statement($stid);
oci_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"/>
    <title>ORON Library</title>
    <link rel="stylesheet" href="../../Styling/css/library.css">
</head>
<body>
    <!-- Navbar Start-->
    <nav class="navbar">
        <div class="navbar-logo">
            <img src="../../Styling/images/oron-logo.png" class="navbar-logo-img" alt="logo"/>
            <h1>ORON</h1>
        </div>
        <div class="navbar-link">
            <ul class="navbar-list">
                <li><a href="#">Home</a></li>
                <li><a href="#">Store</a></li>
                <li><a href="#">Blog</a></li>
                <li><a href="#">About</a></li>
                <li><a href="#"><i class="fa-solid fa-magnifying-glass"></i></a></li>
                <li><a href="#"><i class="fa-solid fa-cart-shopping"></i></a></li>
            </ul>
        </div>
    </nav>    
    <!-- Navbar End -->

    <!-- Library Section Start -->
    <main class="Library">
        <h1>Library</h1>
        <div class="libray-table">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Games</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($library_items)): ?>
                        <?php foreach($library_items as $key => $item): ?>
                            <tr>
                                <td><?= $key + 1 ?></td>
                                <td><?= htmlspecialchars($item['game_name']) ?></td>
                                <td><button>Download</button></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" style="text-align: center;">No games in your library</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
    <!-- Library Section End -->
</body>
</html>