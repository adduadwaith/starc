<?php
session_start();
include '../connect.php'; // Database connection
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Helmet | Contact Us</title>
    <link rel="stylesheet" href="contact.css">
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
    <main class="contact-page">
        <section class="page-header">
            <div class="header-content">
                <h1>CONTACT US</h1>
                <p>Have questions or feedback? Reach out to us. We're here to help!</p>
            </div>
            <img src="img/contact.jpg" alt="Contact Us" class="header-image">
        </section>

        <section class="contact-content-wrapper">
            <div class="contact-content">
                <div class="contact-form">
                    <h2 class="form-title">Send Us a Message</h2>
                    <form action="#" method="post">
                        <div class="form-group">
                            <label for="name">Name:</label>
                            <input type="text" id="name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email:</label>
                            <input type="email" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="message">Message:</label>
                            <textarea id="message" name="message" rows="5" required></textarea>
                        </div>
                        <button type="submit" class="submit-btn">Send Message</button>
                    </form>
                </div>

                <div class="contact-info">
                    <h2 class="info-title">Contact Information</h2>
                    <p><strong>Address:</strong> 123 Tech Street, Innovation City, State, ZIP</p>
                    <p><strong>Phone:</strong> +1 (555) 123-4567</p>
                    <p><strong>Email:</strong> info@smarthelmet.com</p>
                    <p><strong>Support:</strong> support@smarthelmet.com</p>
                </div>
            </div>
        </section>
    </main>
    <footer>
        <p>© 2024 Smart Helmet. All Rights Reserved.</p>
    </footer>
</body>
</html>