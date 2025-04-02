<?php
include '../connect.php';
session_start();

// Ensure user is logged in
$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    header('Location: ../home/index.php');
    exit;
}

// Fetch rider's unique code
$code_query = "SELECT unique_code FROM user WHERE user_id = ?";
$code_stmt = $conn->prepare($code_query);
$code_stmt->bind_param("i", $user_id);
$code_stmt->execute();
$code_result = $code_stmt->get_result();
$user_data = $code_result->fetch_assoc();
$unique_code = $user_data['unique_code'] ?? 'N/A';
$code_stmt->close();

// Fetch connected helmet data
$helmet_query = "SELECT helmet_id, status, speed_limit FROM helmet WHERE rider_id = ?";
$helmet_stmt = $conn->prepare($helmet_query);
$helmet_stmt->bind_param("i", $user_id);
$helmet_stmt->execute();
$helmet_result = $helmet_stmt->get_result();
$helmet = $helmet_result->fetch_assoc();
$helmet_stmt->close();

// Fetch latest helmet live data
$helmet_live_query = "SELECT speed_val, latitude, longitude FROM helmet_live_data WHERE helmet_id = ? ORDER BY last_updated DESC LIMIT 1";
$helmet_live_stmt = $conn->prepare($helmet_live_query);
$helmet_live_stmt->bind_param("i", $helmet['helmet_id']);
$helmet_live_stmt->execute();
$helmet_live_result = $helmet_live_stmt->get_result();
$helmet_live_data = $helmet_live_result->fetch_assoc();
$helmet_live_stmt->close();

// Handle helmet registration
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['helmet_id'])) {
    $helmet_id = $_POST['helmet_id'];

    // Register helmet for rider
    $query = "INSERT INTO helmet (helmet_id, rider_id, status, speed_limit, created_at) 
              VALUES (?, ?, 'active', 100, NOW())
              ON DUPLICATE KEY UPDATE rider_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iii", $helmet_id, $user_id, $user_id);
    $stmt->execute();
    $stmt->close();

    // Refresh page to reflect changes
    header("Location: rider_dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rider Dashboard</title>
    <link rel="stylesheet" href="style_dash.css">
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

    <nav class="navbar">
        <span class="brand">Smart Helmet</span>
        <ul>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>

    <main>
        <h1>Rider Dashboard</h1>

        <!-- Helmet Connection -->
        <div class="container">
            <h2>Register/Connect Your Helmet</h2>
            <form method="post">
                <input type="text" name="helmet_id" placeholder="Enter Helmet ID" required>
                <button type="submit">Connect</button>
            </form>
        </div>

        <!-- Unique Code Sharing -->
        <div class="container">
            <h2>Your Unique Code</h2>
            <p><strong>Share this code with your relative:</strong></p>
            <input type="text" id="riderCode" value="<?php echo $unique_code; ?>" readonly>
            <button onclick="shareViaWhatsApp()">Share via WhatsApp</button>
            <button onclick="shareViaEmail()">Share via Email</button>
            <button onclick="shareViaSMS()">Share via SMS</button>
        </div>

        <!-- Helmet Live Data -->
        <?php if ($helmet): ?>
        <div class="container">
            <h2>Live Tracking</h2>
            <p><strong>Helmet ID:</strong> <?php echo $helmet['helmet_id']; ?></p>
            <p><strong>Status:</strong> <?php echo ucfirst($helmet['status']); ?></p>
            <p><strong>Speed Limit:</strong> <?php echo $helmet['speed_limit']; ?> km/h</p>

            <!-- Real-time Speed -->
            <h3>Current Speed</h3>
            <p><span id="rider-speed"><?php echo $helmet_live_data['speed_val'] ?? '--'; ?></span> km/h</p>

            <!-- Map -->
            <h3>Rider's Location</h3>
            <div id="map-container" style="height: 300px;"></div>
            <p id="rider-location-text">Lat: <span id="lat"><?php echo $helmet_live_data['latitude'] ?? '--'; ?></span>, Lon: <span id="lon"><?php echo $helmet_live_data['longitude'] ?? '--'; ?></span></p>
        </div>
        <?php endif; ?>

    </main>

<script>
let map = L.map('map-container').setView([<?php echo $helmet_live_data['latitude'] ?? 0; ?>, <?php echo $helmet_live_data['longitude'] ?? 0; ?>], 15);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap contributors'
}).addTo(map);

let marker = L.marker([<?php echo $helmet_live_data['latitude'] ?? 0; ?>, <?php echo $helmet_live_data['longitude'] ?? 0; ?>]).addTo(map);

function updateLiveData() {
    $.ajax({
        url: 'fetch_live_data.php',
        type: 'GET',
        data: { helmet_id: <?php echo $helmet['helmet_id'] ?? 0; ?> },
        success: function(response) {
            let data = JSON.parse(response);
            if (data.speed_val && data.latitude && data.longitude) {
                $('#rider-speed').text(data.speed_val);
                $('#lat').text(data.latitude);
                $('#lon').text(data.longitude);
                
                let newLatLng = new L.LatLng(data.latitude, data.longitude);
                marker.setLatLng(newLatLng);
                map.setView(newLatLng, 15);
            }
        }
    });
}

setInterval(updateLiveData, 5000);

function shareViaWhatsApp() {
    let code = document.getElementById("riderCode").value;
    let message = "Track my ride using this unique code: " + code + " on Smart Helmet Tracker.";
    let url = "https://wa.me/?text=" + encodeURIComponent(message);
    window.open(url, "_blank");
}

function shareViaEmail() {
    let code = document.getElementById("riderCode").value;
    let subject = "Track My Ride - Smart Helmet";
    let body = "Hello,\n\nYou can track my helmet using this unique code: " + code + "\nVisit the tracking portal to enter the code.\n\nStay safe!";
    let url = "mailto:?subject=" + encodeURIComponent(subject) + "&body=" + encodeURIComponent(body);
    window.open(url, "_blank");
}

function shareViaSMS() {
    let code = document.getElementById("riderCode").value;
    let message = "Track my ride using this unique code: " + code + " on Smart Helmet Tracker.";
    let url = "sms:?body=" + encodeURIComponent(message);
    window.open(url, "_blank");
}
</script>

</body>
</html>
