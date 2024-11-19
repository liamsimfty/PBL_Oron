<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>


<form method="POST" action="profile.php">    
    <input type="submit" name="logout" value="Log Out">
</form>


</body>
</html>

<?php
session_start(); // Mulai sesi

// Jika tombol logout ditekan
if (isset($_POST['logout'])) {
    session_unset(); // Hapus semua data sesi
    session_destroy(); // Akhiri sesi
    header("Location: ../../dashboard.php"); // Redirect ke halaman login
    exit();
}

echo "We are waiting for you to enter";
?>
