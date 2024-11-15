<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>

<h2>Login Form</h2>

<form method="POST" action="login.php">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>
    
    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>
    
    <input type="submit" value="Login">
    <a href="recovery.php"   class="button">Forgot Password</a>
</form>

</body>
</html>

<?php
// Connect to Oracle database
include 'connection.php';
// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get user input from the form
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query to check if the username exists in the database
    $query = "SELECT username, password FROM account WHERE username = :username";
    $stid = oci_parse($conn, $query);

    // Bind the username to the query
    oci_bind_by_name($stid, ":username", $username);

    // Execute the query
    oci_execute($stid);

    // Check if a record is found
    if ($row = oci_fetch_array($stid, OCI_ASSOC)) {
        // Verify the hashed password from the database with the input password
        if (password_verify($password, $row['PASSWORD'])) {
            echo "<p>Login successful!</p>";
            // header("Location: dashboard.php"); // Uncomment if you have a dashboard
        } else {
            echo "<p>Invalid password. Please try again.</p>";
        }
    } else {
        echo "<p>No user found with that username.</p>";
    }

    // Free the statement and close the connection
    oci_free_statement($stid);
    oci_close($conn);

}

?>

