
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
    <title>Rider Information - Smart Helmet</title>
    <link rel="stylesheet" href="iot.css">
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
    <main class="iot-page">
        <section class="page-header-wrapper">
            <section class="page-header">
                <div class="header-content">
                    <h1>RIDER INFORMATION</h1>
                    <p>Emergency contact details and personal information.</p>
                </div>
                <img src="img/user1.jpg" alt="Rider Information" class="header-image">
            </section>
        </section>

        <section id="iotData" class="iot-data" style="display: block;">
            <h2>Rider Personal Information</h2>
            <div id="personalInfo">
                <p><strong>Rider Name:</strong> <span id="riderName" contenteditable="false">John Doe</span></p>
                <p><strong>Date of Birth:</strong> <span id="dob" contenteditable="false">01/01/1990</span></p>
                <p><strong>Blood Type:</strong> <span id="bloodType" contenteditable="false">O+</span></p>
                <p><strong>Medical Conditions:</strong> <span id="medicalConditions" contenteditable="false">None</span></p>
                <p><strong>Allergies:</strong> <span id="allergies" contenteditable="false">None</span></p>
            </div>
            <h2>Emergency Contacts</h2>
            <div id="emergencyContacts">
                <p><strong>Contact 1 Name:</strong> <span id="contact1Name" contenteditable="false">Jane Doe</span></p>
                <p><strong>Relationship:</strong> <span id="contact1Relationship" contenteditable="false">Spouse</span></p>
                <p><strong>Contact 1 Phone:</strong> <span id="contact1Phone" contenteditable="false">123-456-7890</span></p>

                <p><strong>Contact 2 Name:</strong> <span id="contact2Name" contenteditable="false">Peter Smith</span></p>
                <p><strong>Relationship:</strong> <span id="contact2Relationship" contenteditable="false">Parent</span></p>
                <p><strong>Contact 2 Phone:</strong> <span id="contact2Phone" contenteditable="false">987-654-3210</span></p>
            </div>
            <button id="editButton">Edit</button>
        </section>
    </main>
    <footer>
        <p>© 2024 Smart Helmet. All Rights Reserved.</p>
    </footer>
    <script src="iot.js"></script>
</body>
</html>