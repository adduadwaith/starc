<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include '../connect.php'; // Database connection

$success_message = "";
$error_message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = $_POST['role'];

    if ($password !== $confirm_password) {
        $error_message = "Passwords do not match!";
    } else {
        $stmt = $conn->prepare("SELECT user_id FROM user WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error_message = "Email already exists!";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $unique_code = ($role === "rider") ? substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"), 0, 8) : NULL;
            
            $stmt = $conn->prepare("INSERT INTO user (name, email, password, role, unique_code) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $name, $email, $hashed_password, $role, $unique_code);
            
            if ($stmt->execute()) {
                $success_message = "Registration successful! <a href='login.php'>Login here</a>";
            } else {
                $error_message = "Registration failed!";
            }
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Smart Helmet System</title>
    <link rel="stylesheet" href="reg.css">
</head>
<body>
    <div class="login-container">
        <div class="left-panel">
            <div class="panel-content">
                <h2>SMART HELMET SYSTEM</h2>
                <p>Create your account to get started.</p>
                <img src="r1.jpg" alt="Dashboard Preview" style="width: 80%; margin-top: 20px;">
            </div>
        </div>
        
        <div class="right-panel">
            <form id="register-form" action="register.php" method="post">
                <h2>Create Your Account</h2>
                
                <?php if ($success_message): ?>
                    <p class="success-message"><?php echo $success_message; ?></p>
                <?php endif; ?>
                
                <?php if ($error_message): ?>
                    <p class="error-message"><?php echo $error_message; ?></p>
                <?php endif; ?>
                
                <div class="form-group">
                    <label for="register-name">Your Name</label>
                    <input type="text" id="register-name" name="name" placeholder="Enter your full name" required>
                </div>
                
                <div class="form-group">
                    <label for="register-email">Email</label>
                    <input type="email" id="register-email" name="email" placeholder="Enter your email" required>
                </div>
                
                <div class="form-group">
                    <label for="register-password">Password</label>
                    <input type="password" id="register-password" name="password" placeholder="Create a password" required>
                </div>
                
                <div class="form-group">
                    <label for="register-confirm-password">Confirm Password</label>
                    <input type="password" id="register-confirm-password" name="confirm_password" placeholder="Confirm your password" required>
                </div>
                
                <div class="form-group role-selection">
                    <label>Register as:</label>
                    <div class="radio-group">
                        <input type="radio" id="role-rider" name="role" value="rider" checked>
                        <label for="role-rider" class="radio-label">Rider</label>

                        <input type="radio" id="role-relative" name="role" value="relative">
                        <label for="role-relative" class="radio-label">Relative</label>
                    </div>
                </div>
                
                <div class="form-group terms-group">
                    <input type="checkbox" id="terms" name="terms" required>
                    <label for="terms" class="checkbox-label">I agree to the <a href="/terms" target="_blank">Terms & Conditions</a></label>
                </div>
                
                <button type="submit" class="login-button">Register</button>
                <div class="links">
                    <p>Already have an account? <a href="login.php">Sign In</a></p>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
