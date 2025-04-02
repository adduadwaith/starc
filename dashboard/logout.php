<?php
session_start();
session_unset();  // Clear session variables
session_destroy(); // Destroy session

// Remove session cookie
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 3600, '/');
}

// Redirect to home page
header("Location: ../home/index.php");
exit();
?>
