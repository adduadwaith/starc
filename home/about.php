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
    <title>Smart Helmet | About Us</title>
    <link rel="stylesheet" href="about.css">
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
    <main class="about-page">
        <section class="page-header-wrapper">
            <section class="page-header">
                <div class="header-content">
                    <h1>ABOUT SMART HELMET</h1>
                    <p>Discover the innovation, passion, and expertise driving our advanced rider safety solutions.</p>
                </div>
                <img src="img/team.jpg" alt="Our Team" class="header-image">
            </section>
        </section>
        <section class="about-content">
            <div class="about-section">
                <h2>Our Vision</h2>
                <p>To lead the evolution of rider safety through intelligent technology, fostering a world where every journey is secure and enriched.</p>
            </div>

            <div class="about-section">
                <h2>Our Mission</h2>
                <p>We are dedicated to engineering smart helmets that redefine safety standards, empowering riders with real-time insights and seamless connectivity.</p>
            </div>

            <div class="about-section">
                <h2>The Smart Helmet Story</h2>
                <p>Born from a blend of technological ingenuity and a deep-seated passion for riding, Smart Helmet was conceived to bridge the gap between advanced technology and rider safety. Our journey began with a vision to transform traditional helmets into intelligent companions, capable of enhancing awareness and protection on the road.</p>
            </div>

            <div class="about-section">
                <h2>Innovation at Our Core</h2>
                <p>Our team, a synergy of seasoned engineers, visionary designers, and avid riders, is the driving force behind our innovation. We relentlessly pursue technological advancements, ensuring that each Smart Helmet is a testament to our commitment to excellence and safety.</p>
            </div>

            <div class="about-section">
                <h2>Unwavering Commitment to Quality</h2>
                <p>Quality is the cornerstone of our philosophy. We adhere to rigorous testing and meticulous craftsmanship to deliver products that not only meet but exceed industry standards. Our dedication extends beyond product creation to encompass comprehensive customer support, ensuring every rider's experience is exceptional.</p>
            </div>

            <div class="about-section">
                <h2>Building a Safer Riding Community</h2>
                <p>At Smart Helmet, we believe in more than just creating products; we're building a community. We actively engage with riders, incorporating feedback and insights to continuously improve and innovate. Our goal is to foster a safer, more connected riding environment for enthusiasts worldwide.</p>
            </div>
        </section>
    </main>
    <footer>
        <p>© 2024 Smart Helmet. All Rights Reserved.</p>
    </footer>
</body>
</html>