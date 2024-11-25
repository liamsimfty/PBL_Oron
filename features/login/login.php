<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../../Styling/login.css">
</head>
<body>
    <div class="overlay"></div>
    <div class="container">
        <h1>Login Form</h1>
        <form method="POST" action="login.php" class="form-group">
            <label for="username" class="ss">Username:</label><br>
            <input type="text" id="username" name="username" required><br><br>

            <label for="password" class="ss">Password:</label><br>
            <input type="password" id="password" name="password" required><br><br>

            <button type="submit" class="login">Login</button><br>
            <p class="or-login ss">or</p>
            <div class="social-icons">
                <a href="recovery.php" class="link">Forgot Password</a> |
                <a href="signup.php" class="link">Sign Up</a>
            </div>
        </form>
    </div>
</body>
</html>


<?php
// Start session
session_start();

// Connect to Oracle database
include '../connection/connection.php';   

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get user input from the form
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query to check if the username exists in the database
    $query = "SELECT username, password, account_id FROM account WHERE username = :username";
    $stid = oci_parse($conn, $query);

    // Bind the username to the query
    oci_bind_by_name($stid, ":username", $username);

    // Execute the query
    oci_execute($stid);

    // Check if a record is found
    if ($row = oci_fetch_array($stid, OCI_ASSOC)) {
        // Verify the hashed password from the database with the input password
        if (password_verify($password, $row['PASSWORD'])) {
            // Save username in session
            $_SESSION['username'] = $row['USERNAME'];
            $_SESSION['account_id'] = $row['ACCOUNT_ID'];  

            // Redirect to dashboard or homepage
            header("Location: ../../dashboard.php");
            exit(); // Stop further execution
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
