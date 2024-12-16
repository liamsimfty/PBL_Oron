<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../../Styling/css/login.css">
</head>
<body>
<div class="overlay"></div>
<div class="container">
<h1>Welcome To Oron</h1>
    
    <form method="POST" action="login.php">
        <div class="form-group">
            <p for="username" class="ss">Username</p>
            <input type="text" id="username" name="username" placeholder="Username" required><br><br>
        </div>
        <div class="form-group">
            <p for="password" class="ss">Password</p>
            <input type="password" id="password" name="password" placeholder="Password" required><br><br>
        </div>
        <div class="form-group">
            <input class="login " type="submit" value="Login">
        </div>
        <div class="form-group">
            <a href="recovery.php"   class="link">Forgot Password</a>
        </div>
        <div class="form-group">
            <a href="signup.php"   class="link">Dont Have Account?</a>
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
            header("Location: ../pages/newheader.php");
            exit(); // Stop further execution
        } else {
            echo '<script>alert("Invalid Password")</script>';
        }
    } else {
        echo '<script>alert("No User Found With That Username")</script>';
    }

    // Free the statement and close the connection
    oci_free_statement($stid);
    oci_close($conn);
}
?>
