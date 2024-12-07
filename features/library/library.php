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
    <title>My Library</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>My Library</h2>
            <div>
                <button id="downloadSelected" class="btn btn-primary me-2">Download Selected</button>
                <button id="downloadAll" class="btn btn-secondary">Download All</button>
            </div>
        </div>

        <form id="libraryForm">
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