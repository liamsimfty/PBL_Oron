<?php
include 'connection.php';   

    $query = "SELECT * FROM account";
    $stid = oci_parse($conn, $query);
    
    if (!oci_execute($stid)) {
        $e = oci_error($stid);
        echo "Error: " . htmlentities($e['message'], ENT_QUOTES);
        exit;
    }
    
    // Tampilkan data dalam bentuk tabel HTML
    echo "<table border='1'>";
    echo "<tr>
            <th>ID</th>
            <th>Username</th>
            <th>Password</th>
            <th>Mnemonic</th>
            <th>Created At</th>
          </tr>";
    
    while (($row = oci_fetch_array($stid, OCI_ASSOC)) != false) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['ACCOUNT_ID']) . "</td>";
        echo "<td>" . htmlspecialchars($row['USERNAME']) . "</td>";
        echo "<td>" . htmlspecialchars($row['PASSWORD']) . "</td>";
        echo "<td>" . htmlspecialchars($row['MNEMONIC']) . "</td>";
        echo "<td>" . htmlspecialchars($row['CREATED_AT']) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // Bebaskan sumber daya
    oci_free_statement($stid);
    oci_close($conn);
?>