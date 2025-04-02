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
    <title>GPS Tracking - Smart Helmet</title>
    <link rel="stylesheet" href="gps.css">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&display=swap" rel="stylesheet">
    <script defer src="gps.js"></script>
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
    <main class="gps-page">
        <section class="page-header-wrapper">
            <section class="page-header">
                <div class="header-content">
                    <h1>GPS TRACKING</h1>
                    <p>Real-time GPS location of the Smart Helmet.</p>
                </div>
                <img src="img/loca.jpeg" alt="GPS Tracking" class="header-image">
            </section>
        </section>
        <section class="feature-item-container">
            <div class="feature-item">
                <div class="feature-details">
                    <h3>Real-Time Location</h3>
                    <p>Latitude: <span id="latitude">-</span></p>
                    <p>Longitude: <span id="longitude">-</span></p>

                    <div id="map"></div>
                </div>
            </div>
        </section>
    </main>
    <footer>
        <p>© 2024 Smart Helmet. All Rights Reserved.</p>
    </footer>
</body>
</html>