<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
</head>
<body>

<h2>Signup Form</h2>

<form method="POST" action="signup.php">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>
    
    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>
    
    <input type="submit" value="Signup">
</form>

</body>
</html>

<?php
// Connect to Oracle database
include "connection.php";

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get user input from the form
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // Hash the password for security
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Prepare SQL statement to insert new user
    $query = "INSERT INTO account (username, password, created_at) VALUES (:username, :password, SYSDATE)";
    $stid = oci_parse($conn, $query);

    // Bind the parameters
    oci_bind_by_name($stid, ":username", $username);
    oci_bind_by_name($stid, ":password", $hashedPassword);

    // Execute the query
    if (oci_execute($stid)) {
        echo "<p>User successfully created!</p>";
    } else {
        $e = oci_error($stid);
        echo "<p>Error creating user: " . htmlentities($e['message'], ENT_QUOTES) . "</p>";
    }

    // Free the statement and close the connection
    oci_free_statement($stid);
    oci_close($conn);
    header("Location: login.php"); 
    exit;
}
?>
