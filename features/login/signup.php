<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
    <link rel="stylesheet" href="../../Styling/signup.css">
    <script>
        function validateForm(event) {
            const password = document.getElementById("password").value;
            const confirmPassword = document.getElementById("confirm_password").value;

            if (password !== confirmPassword) {
                alert("Passwords do not match!");
                event.preventDefault(); // Mencegah pengiriman formulir
                return false;
            }
            return true;
        }
    </script>
    <link rel="stylesheet" href="../../Styling/css/sg.css">
    <style>
        @import url('https://fonts.cdnfonts.com/css/lemonmilk');
    </style>
</head>
<body>

<div class="overlay"></div>
<div class="container">
  <h1>Create Account</h1>
  
  <form method="POST" action="signup.php" onsubmit="return validateForm(event)">
    <div class="form-group">
      <p class="ss">Username</p>
      <input type="text" id="username" name="username" placeholder="Username" required>
    </div>
    
    <div class="form-group">
      <p class="ss">Password</p>
      <input type="password" id="password" name="password" placeholder="Password" required>
    </div>
    
    <div class="form-group">
      <p class="ss">Confirm Password</p>
      <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password" required>
    </div>
    
    <button class="su" type="submit">Sign up</button>
  </form>
</div>

</body>
</html>

<?php
// Connect to Oracle database
include '../connection/connection.php';      

// Function to generate mnemonic phrase
function generateMnemonic() {
    // Baca kata-kata dari seeds.txt
    $filePath = 'seeds.txt'; // Path ke file seeds.txt
    if (!file_exists($filePath)) {
        die("File seeds.txt tidak ditemukan!");
    }

    $wordList = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    
    // Pastikan ada kata-kata di dalam file
    if (empty($wordList)) {
        die("File seeds.txt kosong atau tidak valid!");
    }

    // Generate 12 random words
    $mnemonic = [];
    for ($i = 0; $i < 12; $i++) {
        $randomIndex = random_int(0, count($wordList) - 1);
        $mnemonic[] = $wordList[$randomIndex];
    }

    // Gabungkan kata-kata dengan spasi
    return implode(" ", $mnemonic);
}
    
// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get user input from the form
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // Check if the username already exists
    $checkQuery = "SELECT COUNT(*) AS user_count FROM account WHERE username = :username";
    $checkStid = oci_parse($conn, $checkQuery);
    oci_bind_by_name($checkStid, ":username", $username);
    oci_execute($checkStid);

    $row = oci_fetch_array($checkStid, OCI_ASSOC);
    if ($row['USER_COUNT'] > 0) {
        echo '<script>alert("Username Already Exist")</script>';
    } else {
        // Hash the password for security
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        // Generate mnemonic phrase
        $mnemonic = generateMnemonic();
        $hashedMnemonic = password_hash($mnemonic, PASSWORD_DEFAULT);

        // Prepare SQL statement to insert new user
        $query = "INSERT INTO account (username, password, mnemonic, created_at) VALUES (:username, :password, :mnemonic, SYSDATE)";
        $stid = oci_parse($conn, $query);

        // Bind the parameters
        oci_bind_by_name($stid, ":username", $username);
        oci_bind_by_name($stid, ":password", $hashedPassword);
        oci_bind_by_name($stid, ":mnemonic", $hashedMnemonic);

        // Execute the query
        if (oci_execute($stid)) {
            // Store mnemonic in session for displaying in next page
            session_start();
            $_SESSION['mnemonic'] = $mnemonic;
            
            // Redirect to mnemonic display page
            header("Location: mnemonic.php");
            exit;
        } else {
            $e = oci_error($stid);
            echo "<script>Error creating user: " . htmlentities($e['message'], ENT_QUOTES) . "</script>";
        }
    }

    // Free the statements and close the connection
    oci_free_statement($checkStid);
    if (isset($stid)) {
        oci_free_statement($stid);
    }
    oci_close($conn);
}
?>
