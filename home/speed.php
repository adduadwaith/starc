<?php
session_start();
include '../connect.php'; // Database connection

// Check if user is logged in
$isLoggedIn = isset($_SESSION['user_id']);
$userName = "";

// If logged in, retrieve the user's name
if ($isLoggedIn) {
    $stmt = $conn->prepare("SELECT name FROM user WHERE user_id = ?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $stmt->bind_result($userName);
    $stmt->fetch();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Helmet | Speed Monitoring</title>
    <link rel="stylesheet" href="speed.css">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <img src="img/rider.jpg" alt="Smart Helmet Logo" class="logo">
        <nav>
            <a href="index.php">HOME</a>
            <a href="features.php">FEATURES</a>
            <a href="about.php">ABOUT</a>
            <a href="contact.php">CONTACT</a>

            <!-- Dynamic Login/Logout Button -->
            <?php if ($isLoggedIn): ?>
                <a href="dashboard.php"><?php echo htmlspecialchars($userName); ?></a> <!-- Display user name -->
                <a href="logout.php">LOGOUT</a> <!-- Logout option -->
            <?php else: ?>
                <a href="login.php">LOGIN</a>
            <?php endif; ?>
        </nav>
    </header>
    <main class="speed-page">
        <section class="page-header-wrapper">
            <section class="page-header">
                <div class="header-content">
                    <h1>SPEED MONITORING</h1>
                    <p>Real-time speed data of the Smart Helmet.</p>
                </div>
                <img src="img/speedo.jpeg" alt="Speed Monitoring" class="header-image">
            </section>
        </section>

        <section id="speedData" class="speed-data" style="display: block;">
            <h2>Real-Time Speed Data</h2>
            <div id="speedDisplay">
                <p>Current Speed: <span id="currentSpeed">0</span> km/h</p>

            </div>
            <canvas id="speedometerCanvas" width="300" height="200"></canvas>
        </section>
    </main>
    <footer>
        <p>© 2024 Smart Helmet. All Rights Reserved.</p>
    </footer>
    <script src="speed.js"></script>
</body>
</html>