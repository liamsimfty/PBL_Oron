<?php
session_start();
include '../connection/connection.php';   

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'] ?? '';
    
    // Step 1: Verify mnemonic
    if ($action === 'verify_mnemonic') {
        $username = $_POST['username'];
        $mnemonic = $_POST['mnemonic'];

        $query = "SELECT account_id, mnemonic FROM account WHERE username = :username";
        $stid = oci_parse($conn, $query);
        oci_bind_by_name($stid, ":username", $username);
        oci_execute($stid);

        $row = oci_fetch_array($stid, OCI_ASSOC);
        
        if ($row && password_verify($mnemonic, $row['MNEMONIC'])) {
            $_SESSION['recovery_account_id'] = $row['ACCOUNT_ID'];
            // Show password reset form
            $showResetForm = true;
        } else {
            echo '<script>alert("Invalid Recovery Or User Password")</script>';
        }
        oci_free_statement($stid);
    }
    
    // Step 2: Reset Password
    if ($action === 'reset_password') {
        if (isset($_SESSION['recovery_account_id'])) {
            $newPassword = $_POST['new_password'];
            $confirmPassword = $_POST['confirm_password'];
            
            if ($newPassword === $confirmPassword) {
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                
                $query = "UPDATE account SET password = :password WHERE account_id = :account_id";
                $stid = oci_parse($conn, $query);
                oci_bind_by_name($stid, ":password", $hashedPassword);
                oci_bind_by_name($stid, ":account_id", $_SESSION['recovery_account_id']);
                
                if (oci_execute($stid)) {
                    $success = "Password successfully reset! You can now login with your new password.";
                    // Clear the recovery session
                    unset($_SESSION['recovery_account_id']);
                } else {
                    echo '<script>alert("Error Resetting Password")</script>';
                }
                oci_free_statement($stid);
            } else {
                echo '<script>alert("Password Do Not Match")</script>';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        @import url('https://fonts.cdnfonts.com/css/lemonmilk');
    </style>
    <title>Account Recovery</title>
    <link rel="stylesheet" href="../../Styling/css/recovery.css">
</head>
<body>
<div class="overlay"></div>
    <div class="container">
        <h2>Account Recovery</h2>
        
        <?php if (isset($error)): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <?php if (isset($success)): ?>
            <div class="success">
                <?php echo htmlspecialchars($success); ?>
                <br>
                <a href="login.php">Go to Login</a>
            </div>
        <?php endif; ?>

        <?php if (!isset($showResetForm) && !isset($success)): ?>
            <!-- Step 1: Mnemonic Verification Form -->
            <form method="POST" action="">
                <input type="hidden" name="action" value="verify_mnemonic">
                <div class="form-group">
                    <p for="username" class="ss">Username</p>
                    <input type="text" id="username" name="username" required><br><br>
                </div>
                <div class="form-group">
                    <p for="mnemonic" class="ss">Enter your 12-word Recovery Phrase (in correct order):</p>
                    <textarea id="mnemonic" name="mnemonic" required 
                              placeholder="Enter your 12 words separated by spaces"></textarea><br><br>
                </div>
                <button type="submit" class="button">Verify Recovery Phrase</button>
            </form>
        <?php endif; ?>

        <?php if (isset($showResetForm)): ?>
            <!-- Step 2: Password Reset Form -->
            <form method="POST" action="">
                <input type="hidden" name="action" value="reset_password">
                <div class="form-group">
                    <p for="new_password" class="ss">New Password</p>
                    <input type="password" id="new_password" name="new_password" required>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirm New Password:</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>
                <button type="submit" class="button">Reset Password</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>