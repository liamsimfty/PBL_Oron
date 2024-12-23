<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Login</title>
    <link rel="stylesheet" href="../../Styling/css/login.css">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <style>
        @import url('https://fonts.cdnfonts.com/css/lemonmilk');
    </style>
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
        <!-- Add the reCAPTCHA widget -->
        <div class="g-recaptcha" data-sitekey="6LcvhqMqAAAAANImBr1uvAxdaosUJXvKJLWd3puT"></div>
    </div>
    <div class="form-group">
        <input class="login" type="submit" value="Login">
    </div>
    <div class="form-group">
        <a href="recovery.php" class="link">Forgot Password</a>
    </div>
    <div class="form-group">
        <a href="signup.php" class="link">Don't Have an Account?</a>
    </div>
</form>
</div>
</body>
</html>


<?php
/// Start session
session_start();

// Include Composer's autoloader
require '../vendor/autoload.php';

// Connect to Oracle database
include '../connection/connection.php';   

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get user input from the form
    $username = $_POST['username'];
    $password = $_POST['password'];
    $recaptchaResponse = $_POST['g-recaptcha-response'];

    // Verify reCAPTCHA response
    $secretKey = '6LcvhqMqAAAAALLJlOCx5J6yy_RwN3vPrEZjz51B'; // Replace with your Secret Key
    $recaptcha = new \ReCaptcha\ReCaptcha($secretKey);
    $response = $recaptcha->verify($recaptchaResponse, $_SERVER['REMOTE_ADDR']);

    if ($response->isSuccess()) {
        // Proceed with username/password validation
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
                header("Location: ../pages/homepage.php");
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
    } else {
        // reCAPTCHA failed
        echo '<script>alert("reCAPTCHA verification failed. Please try again.")</script>';
    }
}
?>
