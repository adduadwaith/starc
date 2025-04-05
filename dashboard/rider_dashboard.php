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

// Fetch connected helmet
$helmet_query = "SELECT helmet_id, status, speed_limit FROM helmet WHERE rider_id = ?";
$helmet_stmt = $conn->prepare($helmet_query);
$helmet_stmt->bind_param("i", $user_id);
$helmet_stmt->execute();
$helmet_result = $helmet_stmt->get_result();
$helmet = $helmet_result->fetch_assoc();
$helmet_stmt->close();

// Helmet live data (only if helmet is active)
$helmet_live_data = null;
$helmet_is_active = false;
$helmet_is_connected = $helmet !== null;

if ($helmet && $helmet['status'] === 'active') {
    $helmet_is_active = true;
    $helmet_live_query = "SELECT speed_val, latitude, longitude FROM helmet_live_data WHERE helmet_id = ? ORDER BY last_updated DESC LIMIT 1";
    $helmet_live_stmt = $conn->prepare($helmet_live_query);
    $helmet_live_stmt->bind_param("i", $helmet['helmet_id']);
    $helmet_live_stmt->execute();
    $helmet_live_result = $helmet_live_stmt->get_result();
    $helmet_live_data = $helmet_live_result->fetch_assoc();
    $helmet_live_stmt->close();
}

// Handle helmet connection form
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['helmet_id'])) {
   // Helmet connection logic
$connect_msg = '';
$helmet_is_connected = false;
$helmet_is_active = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['helmet_id'])) {
    $helmet_id = trim($_POST['helmet_id']);

    // Check if the helmet exists
    $check_query = "SELECT rider_id, status FROM helmet WHERE helmet_id = ?";
    $check_stmt = $conn->prepare($check_query);
    $check_stmt->bind_param("i", $helmet_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        $helmet_data = $check_result->fetch_assoc();
        $existing_rider_id = $helmet_data['rider_id'];
        $helmet_status = $helmet_data['status'];

        if ($existing_rider_id == $user_id) {
            if ($helmet_status === 'inactive') {
                $connect_msg = "This helmet is deactivated. Please contact support.";
            } else {
                $connect_msg = "You are already connected to this helmet.";
            }
        } else {
            $connect_msg = "This helmet is already connected to another rider.";
        }
    } else {
        // Not connected yet — safe to register
        $query = "INSERT INTO helmet (helmet_id, rider_id, status, speed_limit, created_at) 
                  VALUES (?, ?, 'active', 100, NOW())";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $helmet_id, $user_id);
        $stmt->execute();
        $stmt->close();

        $connect_msg = "Helmet connected successfully.";
       // header("Location: rider_dashboard.php");
       // exit;
       header("Location: " . $_SERVER['PHP_SELF']);
       exit;
    }

    $check_stmt->close();
}

// Fetch connected helmet data again
$helmet_query = "SELECT helmet_id, status, speed_limit FROM helmet WHERE rider_id = ?";
$helmet_stmt = $conn->prepare($helmet_query);
$helmet_stmt->bind_param("i", $user_id);
$helmet_stmt->execute();
$helmet_result = $helmet_stmt->get_result();
$helmet = $helmet_result->fetch_assoc();
$helmet_stmt->close();

$helmet_is_connected = $helmet !== null;
$helmet_is_active = ($helmet && $helmet['status'] === 'active');

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
            <li><a href="relatives.php">Relatives</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>

    <main>
    <h1>Rider Dashboard</h1>

    <!-- Always visible: Helmet Connection -->
    <!-- Helmet Connection -->
<div class="container">
    <h2>Register/Connect Your Helmet</h2>
    <form method="post">
        <input type="text" name="helmet_id" placeholder="Enter Helmet ID" required>
        <button type="submit">Connect</button>
    </form>
    <?php if (!empty($connect_msg)): ?>
        <p style="color: red;"><?php echo $connect_msg; ?></p>
    <?php endif; ?>
</div>


   

    <?php if ($helmet_is_connected && $helmet_is_active): ?>
        <!-- Unique Code Sharing -->
        <div class="container">
            <h2>Your Unique Code</h2>
            <p><strong>Share this code with your relative:</strong></p>
            <input type="text" id="riderCode" value="<?php echo $unique_code; ?>" readonly>
            <button onclick="shareViaWhatsApp()">Share via WhatsApp</button>
            <button onclick="shareViaEmail()">Share via Email</button>
        </div>

        <!-- Speed Limit Section -->
<div class="container">
    <h2>Set Speed Limit</h2>
    <div class="form-group">
        <label for="speed_limit">Enter Speed Limit (km/h):</label>
        <input type="number" id="speed_limit" min="1" placeholder="Set Speed Limit">
        <button onclick="updateSpeedLimit()">Save Limit</button>
    </div>
    <p id="speed-limit-msg" style="color: green;"></p>
</div>


        <!-- Helmet Live Data -->
        <div class="container">
            <h2>Live Tracking</h2>
            <p><strong>Helmet ID:</strong> <?php echo $helmet['helmet_id']; ?></p>
            <p><strong>Status:</strong> <?php echo ucfirst($helmet['status']); ?></p>
            <p><strong>Speed Limit:</strong> <span id="live-speed-limit"><?php echo $helmet['speed_limit']; ?> km/h</span></p>

            <!-- Real-time Speed -->
    <h3>Current Speed</h3>
    <p><span id="rider-speed"><?php echo $helmet_live_data['speed_val'] ?? '--'; ?></span> km/h</p>

    <!-- Map -->
    <h3>Rider's Location</h3>
    <div id="map-container" style="height: 300px;"></div>
    <p id="rider-location-text">
    Lat: <span id="lat"><?php echo $helmet_live_data['latitude'] ?? '--'; ?></span>, 
    Lon: <span id="lon"><?php echo $helmet_live_data['longitude'] ?? '--'; ?></span>
    </p>

            
    </div>
    <?php endif; ?>
</main>


    <script>
    let map;
    let marker;
    let trackingInterval;

    const helmetID = "<?php echo $helmet['helmet_id'] ?? ''; ?>";

    // Initial coordinates from PHP (or fallback to 0,0)
    const initialLat = <?php echo $helmet_live_data['latitude'] ?? 0; ?>;
    const initialLon = <?php echo $helmet_live_data['longitude'] ?? 0; ?>;
   
    // Initialize map
    map = L.map('map-container').setView([initialLat, initialLon], 15);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    marker = L.marker([initialLat, initialLon]).addTo(map);

    // Fetch data and update UI/map
    function updateLiveData() {
    if (!helmetID) return;

    fetch("fetch_rider_rel.php?helmet_id=" + helmetID)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                document.getElementById("rider-speed").textContent = "--";
                document.getElementById("lat").textContent = "--";
                document.getElementById("lon").textContent = "--";
                return;
            }

            // ✅ Only update the inner values, no " km/h" here!
            document.getElementById("rider-speed").textContent = data.speed_val;
            document.getElementById("lat").textContent = data.latitude;
            document.getElementById("lon").textContent = data.longitude;

            let newLatLng = new L.LatLng(data.latitude, data.longitude);
            marker.setLatLng(newLatLng);
            map.setView(newLatLng, 15);
        })
        .catch(error => {
            console.error("Error fetching data:", error);
        });
}



function updateSpeedLimit() {
    const speedLimit = document.getElementById('speed_limit').value;

    if (speedLimit === '') {
        document.getElementById('speed-limit-msg').textContent = 'Please enter a value';
        return;
    }

    fetch('update_speed_limit.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'speed_limit=' + encodeURIComponent(speedLimit),
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('speed-limit-msg').textContent = 'Speed limit updated to ' + speedLimit + ' km/h';

            // Also update the Live Tracking section speed limit
            const liveLimitDisplay = document.querySelector('#live-speed-limit');
            if (liveLimitDisplay) {
                liveLimitDisplay.textContent = speedLimit + ' km/h';
            }

        } else {
            document.getElementById('speed-limit-msg').textContent = 'Failed to update';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('speed-limit-msg').textContent = 'Error occurred';
    });
}

    // WhatsApp sharing
    function shareViaWhatsApp() {
        let code = document.getElementById("riderCode").value;
        let message = "Track my ride using this unique code: " + code + " on Smart Helmet Tracker.";
        let url = "https://wa.me/?text=" + encodeURIComponent(message);
        window.open(url, "_blank");
    }

    // Email sharing
    function shareViaEmail() {
        let code = document.getElementById("riderCode").value;
        let subject = "Track My Ride - Smart Helmet";
        let body = "Hello,\n\nYou can track my helmet using this unique code: " + code + "\nVisit the tracking portal to enter the code.\n\nStay safe!";
        let url = "mailto:?subject=" + encodeURIComponent(subject) + "&body=" + encodeURIComponent(body);
        window.open(url, "_blank");
    }


document.getElementById("riderForm").addEventListener("submit", function(event) {
    var helmetId = document.getElementById("helmet_id").value.trim();
    var speedLimit = document.getElementById("speed_limit").value.trim();

    if (helmetId === "" || speedLimit === "") {
        event.preventDefault(); // Stop form submission
        alert("Please fill in all fields.");
    }
});



</script>
</body>
</html>
