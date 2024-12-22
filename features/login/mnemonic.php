<?php
session_start();

// Check if mnemonic exists in session
if (!isset($_SESSION['mnemonic'])) {
    header("Location: login.php");
    exit;
}

$mnemonic = $_SESSION['mnemonic'];
// Clear the mnemonic from session after displaying
unset($_SESSION['mnemonic']);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../Styling/css/mnemonic.css">
    <style>
        @import url('https://fonts.cdnfonts.com/css/lemonmilk');
    </style>
</head>
<body>
<div class="overlay"></div>
    <div class="container">
        <div class="content">
            <h2>Your Recovery Phrase</h2>
            <p>IMPORTANT: Write down these 12 words and keep them in a safe place. You will need them to recover your account if you forget your password.</p>
            <div class="mnemonic">
                <?php echo htmlspecialchars($mnemonic); ?>
            </div>
            <p>Once you have safely stored these words, you can proceed to <a href="login.php">login</a>.</p>
        </div>
    </div>
</body>
</html>