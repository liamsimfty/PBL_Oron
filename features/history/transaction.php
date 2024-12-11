<?php
include '../connection/connection.php';
session_start();
if (!isset($_SESSION['account_id'])) {
    echo "<p>You need to log in to view your tranasction history.</p>";
    exit();
}
$isLoggedIn = isset($_SESSION['username']);
$account_id = $_SESSION['account_id'];

// Query untuk mengambil data transaksi
$query = "
    SELECT 
        t.transaction_date,
        LISTAGG(p.name || ' ($' || t.price_at_checkout || ')', CHR(10)) 
            WITHIN GROUP (ORDER BY p.name) AS nama_games,
        MIN(t.payment_status) AS payment_status,
        SUM(t.price_at_checkout) AS total_price
    FROM transaction t
    JOIN products p ON t.product_id = p.product_id
    WHERE t.account_id = :account_id
    AND EXISTS (
        SELECT 1 
        FROM transaction t2 
        WHERE t2.snap_token = t.snap_token
    )
    GROUP BY t.transaction_date
    ORDER BY t.transaction_date DESC
";

// Menyiapkan dan menjalankan query
$stid = oci_parse($conn, $query);
oci_bind_by_name($stid, ":account_id", $account_id);
oci_execute($stid);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Transaksi</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: left;
            vertical-align: top;
        }
        .game-list {
            white-space: pre-line; /* Menghormati karakter newline untuk membuat line break */
        }
    </style>
</head>
<body>
    <h1>Data Transaksi</h1>

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
                    <td><?= htmlspecialchars($row['PAYMENT_STATUS']); ?></td>
                    <td><?= number_format($row['TOTAL_PRICE'], 2); ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <script>
        function payTransaction(transactionDate) {
            alert("Melakukan pembayaran untuk transaksi pada: " + transactionDate);
            // Tambahkan logika pembayaran di sini
        }
    </script>
</body>
</html>

<?php
// Membersihkan sumber daya dan menutup koneksi
oci_free_statement($stid);
oci_close($conn);
?>
