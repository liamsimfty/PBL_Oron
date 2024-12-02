<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Library</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php
    include '../connection/connection.php';      
    session_start();

    // Check if user is logged in
    $isLoggedIn = isset($_SESSION['username']);
    ?>
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
    <div>
        <table>
            <thead>
                <tr>
                    <th>Game Name</th>
                    <th>Purchase Date</th>
                </tr>
            </thead>
            <tbody>
                <?php


                if (!isset($_SESSION['account_id'])) {
                    echo "<tr><td colspan='3'>Please log in to view your library.</td></tr>";
                    exit();
                }

                // Get the account_id from session
                $accountId = $_SESSION['account_id'];

                // Query to get library data for the user
                $query = "SELECT 
                        l.library_id, 
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

                // Fetch and display results
                while (($row = oci_fetch_array($stid, OCI_ASSOC)) != false) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['GAME_NAME']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['PURCHASE_DATE']) . "</td>";
                    echo "</tr>";
                }

                // If no games are found
                // Free resources and close connection
                oci_free_statement($stid);
                oci_close($conn);
                ?>
            </tbody>
        </table>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
