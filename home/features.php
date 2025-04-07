<?php
session_start();
include '../connect.php'; // Database connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Helmet | Features</title>
    <link rel="stylesheet" href="features.css">
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
            <a href="../login_signup/login.php">LOGIN</a>
        </nav>
    </header>
    <main class="features-page">
        <section class="page-header-wrapper">
            <section class="page-header">
                <div class="header-content">
                    <h1>EXPLORE ADVANCED RIDING FEATURES</h1>
                    <p>Unlock the potential of your ride with our smart helmet's innovative safety and connectivity features.</p>
                </div>
                <img src="img/feat.jpg" alt="Smart Helmet Feature" class="header-image">
            </section>
        </section>
        <section class="feature-item-container">
            <div class="feature-item">
                <img src="img/speedo.jpeg" alt="Adaptive Speed Intelligence" class="feature-img">
                <div class="feature-details">
                    <h3>Adaptive Speed Intelligence</h3>
                    <p>Real-time speed monitoring with dynamic alerts for optimal safety.</p>
                    <button class="see-more-btn" data-target="speed-details">Learn More</button>
                    <div id="speed-details" class="feature-dropdown">
                        <p>Our Adaptive Speed Intelligence uses advanced sensors to track your speed and provide alerts when approaching or exceeding safe limits. This feature dynamically adjusts to road conditions and speed zones, ensuring you're always informed and in control.</p>
                    </div>
                </div>
            </div>

            <div class="feature-item">
                <img src="img/loca.jpeg" alt="Precision GPS Tracking" class="feature-img">
                <div class="feature-details">
                    <h3>Precision GPS Tracking</h3>
                    <p>Enhanced location accuracy for seamless navigation and emergency response.</p>
                    <button class="see-more-btn" data-target="gps-details">Learn More</button>
                    <div id="gps-details" class="feature-dropdown">
                        <p>Our Precision GPS Tracking offers unparalleled accuracy, allowing for real-time location sharing and navigation. In emergencies, this feature ensures that you can be located quickly and accurately, providing crucial assistance when it matters most.</p>
                    </div>
                </div>
            </div>

            <div class="feature-item">
                <img src="img/ioot.jpeg" alt="Smart Emergency Integration" class="feature-img">
                <div class="feature-details">
                    <h3>Smart Emergency Integration</h3>
                    <p>Automated alerts to emergency services for immediate assistance in critical situations.</p>
                    <button class="see-more-btn" data-target="iot-details">Learn More</button>
                    <div id="iot-details" class="feature-dropdown">
                        <p>Our Smart Emergency Integration system automatically detects severe impacts and sends alerts to emergency services, along with your precise location. This feature ensures that you receive immediate assistance in critical situations, significantly reducing response times.</p>
                    </div>
                </div>
            </div>

            <div class="feature-item">
                <img src="img/dia.jpeg" alt="Integrated Helmet Diagnostics" class="feature-img">
                <div class="feature-details">
                    <h3>Integrated Helmet Diagnostics</h3>
                    <p>Comprehensive system health checks for optimal performance and safety.</p>
                    <button class="see-more-btn" data-target="dia-details">Learn More</button>
                    <div id="dia-details" class="feature-dropdown">
                        <p>Our Integrated Helmet Diagnostics feature allows you to perform thorough system checks on your helmet’s sensors and electronics. This ensures that your helmet is always functioning at its best, providing accurate data and reliable performance.</p>
                    </div>
                </div>
            </div>

            <div class="feature-item">
                <img src="img/alert.jpeg" alt="Customizable Rider Alerts" class="feature-img">
                <div class="feature-details">
                    <h3>Customizable Rider Alerts</h3>
                    <p>Tailored notifications for personalized safety and convenience.</p>
                    <button class="see-more-btn" data-target="ale-details">Learn More</button>
                    <div id="ale-details" class="feature-dropdown">
                        <p>Our Customizable Rider Alerts allow you to personalize your safety notifications. Set thresholds for speed, impact, and other parameters to receive alerts that match your specific needs and preferences.</p>
                    </div>
                </div>
            </div>

            <div class="feature-item">
                <img src="img/shield.jpeg" alt="Advanced Rider Health Monitoring" class="feature-img">
                <div class="feature-details">
                    <h3>Advanced Rider Health Monitoring</h3>
                    <p>Continuous monitoring of helmet performance and rider metrics for ultimate safety.</p>
                    <button class="see-more-btn" data-target="rid-details">Learn More</button>
                    <div id="rid-details" class="feature-dropdown">
                        <p>Our Advanced Rider Health Monitoring system provides continuous updates on your helmet's performance and key rider metrics. Track battery life, sensor data, and connection status to ensure your helmet is always in optimal condition.</p>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <footer>
        <p>© 2024 Smart Helmet. All Rights Reserved.</p>
    </footer>
    <script src="features.js"></script>
</body>
</html>