<?php
include 'connection.php';   
if (!$conn) {
    $e = oci_error();
    trigger_error(htmlentities($e['message']), ENT_QUOTES, E_USER_ERROR);
} else {
    echo "ORACLE DATABASES CONNECTED SUCCESSFULLY <br>";

    $query = "SELECT * FROM account";
    $stid = oci_parse($conn, $query);

    if (!oci_execute($stid)) {
        $e = oci_error($stid);
        echo "Error in query execution: " . htmlentities($e['message']);
    } else {
        echo "<table border='1'>";
        echo "<tr><th>Account ID</th><th>Username</th><th>Password</th><th>Email</th><th>Created At</th><th>Last Login</th></tr>";
        while (($row = oci_fetch_array($stid, OCI_ASSOC)) != false) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['ACCOUNT_ID']) . "</td>";
            echo "<td>" . htmlspecialchars($row['USERNAME']) . "</td>";
            echo "<td>" . htmlspecialchars($row['PASSWORD']) . "</td>";
            echo "<td>" . htmlspecialchars($row['EMAIL']) . "</td>";
            echo "<td>" . htmlspecialchars($row['CREATED_AT']) . "</td>";
            echo "<td>" . htmlspecialchars($row['LAST_LOGIN']) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }

    oci_free_statement($stid);
    oci_close($conn);
}
?>