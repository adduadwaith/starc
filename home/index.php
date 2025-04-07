<?php
session_start();
include '../connect.php'; // Database connection

// Prevent caching so back button won’t restore the session
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Helmet | Accident Alert System</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&display=swap" rel="stylesheet">
    <script type="module" src="https://unpkg.com/@google/model-viewer/dist/model-viewer.min.js"></script>
    <script src="https://apis.google.com/js/platform.js"></script>
</head>
<body>
    <header>
        <img src="img/rider.jpg" alt="Smart Helmet Logo" class="logo">
        <nav>
            <a href="index.php">HOME</a>
            <a href="features.php">FEATURES</a>
            <a href="about.php">ABOUT</a>
            <a href="contact.php">CONTACT</a>
            <a href="../login_signup/login.php">LOGIN</a>
        </nav>
    </header>
    
    <main>
        <div class="rectangle rect1">
            <div class="rect1-title autoShowTitle">
                <div class="rect1-line1">SMART HELMET</div>
                <div class="rect1-line3">WITH</div>
                <div class="rect1-line2">ACCIDENT ALERT SYSTEM</div>
            </div>
        </div>
        <div class="atom-loader">
            <img src="img/smart.jpg" alt="Smart Helmet Image" class="helmet-image">
        </div>
        <div class="rectangle rect2">
            <div class="info-cloud-container">
                <div class="info">
                    <h2>ENHANCING RIDER SAFETY</h2>
                    <p class="typing-animation" id="typing-text"></p>
                </div>
            </div>
        </div>

        <section class="features">
            <section class="quick-features">
                <div class="quick-features-row">
                    <div class="quick-feature">
                        <img src="img/diagn.jpeg" alt="Helmet Diagnostics">
                        <h3>Helmet Diagnostics</h3>
                        <p>Run quick checks on your helmet.</p>
                    </div>
                    <div class="quick-feature">
                        <img src="img/dev.jpeg" alt="Device Management">
                        <h3>Device Management</h3>
                        <p>Manage your helmet settings easily.</p>
                    </div>
                    <div class="quick-feature">
                        <img src="img/exc1.jpg" alt="Exclusive Offers">
                        <h3>Exclusive Offers</h3>
                        <p>Get deals on upgrades and more.</p>
                    </div>
                </div>
                <div class="quick-features-row">
                    <div class="quick-feature">
                        <img src="img/guide.jpg" alt="Guided Support">
                        <h3>Guided Support</h3>
                        <p>Get help from our virtual assistant.</p>
                    </div>
                    <div class="quick-feature">
                        <img src="img/ale.jpg" alt="Personalized Alerts">
                        <h3>Personalized Alerts</h3>
                        <p>Customize your safety notifications.</p>
                    </div>
                    <div class="quick-feature">
                        <img src="img/rid.jpg" alt="Rider Health">
                        <h3>Rider Health</h3>
                        <p>Monitor your helmet's performance.</p>
                    </div>
                </div>
            </section>
            <h2 class="key-features-title">
                <span>K</span><span>E</span><span>Y</span>
                <span>&nbsp;</span>
                <span>F</span><span>E</span><span>A</span><span>T</span><span>U</span><span>R</span><span>E</span><span>S</span>
            </h2>
            <div class="feature-box">
                <div class="feature">
                    <a href="../login_signup/login.php">
                        <img src="img/sp.jpeg" alt="Speed Monitoring" class="feature-image">
                        <h3>Speed Monitoring</h3>
                        <p>Alerts when exceeding speed limits.</p>
                    </a>
                </div>
                <div class="feature">
                    <a href="../login_signup/login.php">
                        <img src="img/lo.jpeg" alt="GPS Tracking" class="feature-image">
                        <h3>GPS Tracking</h3>
                        <p>Real-time location sharing for emergencies.</p>
                    </a>
                </div>
                <div class="feature">
                    <a href="../login_signup/login.php">
                        <img src="img/io.jpeg" alt="IoT Integration" class="feature-image">
                        <h3>IoT Integration</h3>
                        <p>Connects with emergency services for quick assistance.</p>
                    </a>
                </div>
            </div>
        </section>
    </main>

    <footer>
        <p>© 2024 Smart Helmet. All Rights Reserved.</p>
    </footer>
    <script src="script.js"></script>
</body>
</html>


