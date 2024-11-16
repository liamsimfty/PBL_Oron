<?php
session_start();
include "connection.php";

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
            $error = "Invalid username or recovery phrase!";
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
                    $error = "Error resetting password.";
                }
                oci_free_statement($stid);
            } else {
                $error = "Passwords do not match!";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Account Recovery</title>
</head>
<body>
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
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="mnemonic">Enter your 12-word Recovery Phrase (in correct order):</label>
                    <textarea id="mnemonic" name="mnemonic" required 
                              placeholder="Enter your 12 words separated by spaces"></textarea>
                </div>
                <button type="submit" class="button">Verify Recovery Phrase</button>
            </form>
        <?php endif; ?>

        <?php if (isset($showResetForm)): ?>
            <!-- Step 2: Password Reset Form -->
            <form method="POST" action="">
                <input type="hidden" name="action" value="reset_password">
                <div class="form-group">
                    <label for="new_password">New Password:</label>
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