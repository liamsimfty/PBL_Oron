<?php
include 'connection.php';   

    $query = "SELECT * FROM products";
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
            <th>Name</th>
            <th>Description</th>
            <th>launched_at</th>
            <th>Price</th>
            <th>discount</th>
            <th>image</th>
            <th>video</th>
            <th>developer</th>
            <th>publisher</th>
          </tr>";
    
    while (($row = oci_fetch_array($stid, OCI_ASSOC)) != false) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['PRODUCT_ID']) . "</td>";
        echo "<td>" . htmlspecialchars($row['NAME']) . "</td>";
        echo "<td>" . htmlspecialchars($row['DESCRIPTION']) . "</td>";
        echo "<td>" . htmlspecialchars($row['LAUNCHED_AT']) . "</td>";
        echo "<td>" . htmlspecialchars($row['CURRENT_PRICE']) . "</td>";
        echo "<td>" . htmlspecialchars($row['DISCOUNT']) . "</td>";
        echo "<td>" . htmlspecialchars($row['IMAGE']) . "</td>";
        echo "<td>" . htmlspecialchars($row['VIDEO']) . "</td>";
        echo "<td>" . htmlspecialchars($row['DEVELOPER']) . "</td>";
        echo "<td>" . htmlspecialchars($row['PUBLISHER']) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // Bebaskan sumber daya
    oci_free_statement($stid);
    oci_close($conn);
?>