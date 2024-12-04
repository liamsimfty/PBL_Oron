<?php
include '../connection/connection.php';      
session_start();

// Check if user is logged in
$isLoggedIn = isset($_SESSION['username']);

// Check if user is logged in and has account_id
if (!isset($_SESSION['account_id'])) {
    echo "<tr><td colspan='3'>Please log in to view your library.</td></tr>";
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
    <title>My Library</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
                            <a class="nav-link" href="../profile/profile.php"><?= htmlspecialchars($_SESSION['username']) ?></a>
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
    
    <div class="container mt-4">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>
                        <input type="checkbox" id="select-all">
                    </th>
                    <th>Game Name</th>
                    <th>Purchase Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($library_items as $item): ?>
                <tr>
                    <td>
                        <input type="checkbox" name="selected_products[]" 
                               value="<?= htmlspecialchars($item['product_id']) ?>">
                    </td>
                    <td><?= htmlspecialchars($item['game_name']) ?></td>
                    <td><?= htmlspecialchars($item['purchase_date']) ?></td>
                </tr>
                <?php endforeach; ?>
                
                <?php if (empty($library_items)): ?>
                <tr>
                    <td colspan="3" class="text-center">No games in your library</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>