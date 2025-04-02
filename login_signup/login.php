<?php
session_start();
include '../connect.php'; // Database connection

// Redirect logged-in users to their respective dashboards
if (isset($_SESSION['user_id'])) {
    switch ($_SESSION['role']) {
        case 'admin':
            header("Location: ../dashboard/admin_dashboard.php");
            break;
        case 'rider':
            header("Location: ../dashboard/rider_dashboard.php");
            break;
        case 'relative':
            header("Location: ../dashboard/relative_dashboard.php");
            break;
        default:
            header("Location: ../dashboard/dashboard.php");
            break;
    }
    exit;
}

$success_message = "";
$error_message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['login'])) {
    $email = trim($_POST['login_email']);
    $password = trim($_POST['login_password']);
    
    if (!empty($email) && !empty($password)) {
        $stmt = $conn->prepare("SELECT user_id, password, role FROM user WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $hashed_password, $role);
            $stmt->fetch();
            
            if (password_verify($password, $hashed_password)) {
                $_SESSION['user_id'] = $id;
                $_SESSION['role'] = $role;

                // Redirect based on role
                switch ($role) {
                    case 'admin':
                        header("Location: ../dashboard/admin_dashboard.php");
                        break;
                    case 'rider':
                        header("Location: ../dashboard/rider_dashboard.php");
                        break;
                    case 'relative':
                        header("Location: ../dashboard/relative_dashboard.php");
                        break;
                    default:
                        header("Location: ../dashboard/dashboard.php");
                        break;
                }
                exit(); // Stop further execution
            } else {
                $error_message = "Invalid password!";
            }
        } else {
            $error_message = "No account found with this email!";
        }
        
        $stmt->close();
    } else {
        $error_message = "Email and password cannot be empty!";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Helmet System</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div class="login-container">
        <div class="left-panel">
            <div class="panel-content">
                <h2>SMART HELMET SYSTEM</h2>
                <p>Login or create an account to get started.</p>
                <img src="r1.jpg" alt="Dashboard Preview" style="width: 80%; margin-top: 20px;">
            </div>
        </div>

        <div class="right-panel">
            <form id="login-form" action="login.php" method="post">
                <h2>Login</h2>
                
                <!-- Error Message Display -->
                <?php if (!empty($error_message)): ?>
                    <div class="error-message"><?php echo htmlspecialchars($error_message); ?></div>
                <?php endif; ?>

                <div class="form-group">
                    <label for="login-email">Email</label>
                    <input type="email" id="login-email" name="login_email" placeholder="Enter your email" required>
                </div>
                <div class="form-group">
                    <label for="login-password">Password</label>
                    <input type="password" id="login-password" name="login_password" placeholder="Enter your password" required>
                </div>
                <button type="submit" name="login" class="login-button">Login</button>
                <div class="links">
                    <a href="/forgot-password">Forgot Password?</a>
                    <p>Don't have an account? <a href="register.php">Register here</a></p>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
