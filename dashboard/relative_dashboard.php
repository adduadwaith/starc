
<?php
include '../connect.php';
session_start();

// If session is not active, redirect to home
if (!isset($_SESSION['user_id'])) {
    header("Location: ../home/index.php");
    exit();
}

// Prevent caching
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relative Dashboard - Smart Helmet Tracking</title>
    <link rel="stylesheet" href="style_dash.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
</head>
<body>

    <nav class="navbar">
        <span class="brand">Smart Helmet</span>
        <ul>
            <li><a href="emergency_contact.php">Emergency</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>

    <main>
        <h1>Track Rider</h1>

        <div class="container">
            <h2>Enter Rider Code</h2>
            <div class="form-group">
                <label for="helmet_id">Helmet ID:</label>
                <input type="text" id="helmet_id" name="helmet_id" placeholder="Enter unique helmet ID">
                <button onclick="startTracking()">Track</button>
            </div>
        </div>

        <div id="tracking-data" style="display: none;">
            <div id="connection-alert-area">
                <div class="alert alert-warning">
                    <span class="alert-icon">⚠️</span> Rider's Helmet appears disconnected!
                </div>
            </div>

            <div class="container">
                <h2>Live Tracking Data</h2>
                <div class="realtime-display">
                    <div>
                        <h3>Rider's Speed</h3>
                        <p><span id="rider-speed">--</span> km/h</p>
                    </div>
                    <div>
                        <h3>Rider's Location</h3>
                        <div id="map" style="height: 300px;"></div>
                        <p id="rider-location-text">Lat: --, Lon: --</p>
                    </div>
                </div>
            </div>
        </div>

    </main>

    <script>
        let map;
        let marker;
        let helmetID = "";

        function startTracking() {
            helmetID = document.getElementById("helmet_id").value.trim();
            if (helmetID === "") {
                alert("Please enter a valid Helmet ID.");
                return;
            }

            document.getElementById("tracking-data").style.display = "block";
            initializeMap();
            fetchLiveData(); // Fetch data immediately
            setInterval(fetchLiveData, 5000); // Update every 5 seconds
        }

        function initializeMap() {
            map = L.map("map").setView([0, 0], 15);
            L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
                attribution: "&copy; OpenStreetMap contributors"
            }).addTo(map);
            marker = L.marker([0, 0]).addTo(map);
        }

        function fetchLiveData() {
            fetch("fetch_live_data.php?helmet_id=" + helmetID)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        document.getElementById("rider-speed").textContent = "--";
                        document.getElementById("rider-location-text").textContent = "Lat: --, Lon: --";
                        return;
                    }

                    document.getElementById("rider-speed").textContent = data.speed + " km/h";
                    document.getElementById("rider-location-text").textContent = "Lat: " + data.latitude + ", Lon: " + data.longitude;

                    let newLatLng = new L.LatLng(data.latitude, data.longitude);
                    marker.setLatLng(newLatLng);
                    map.setView(newLatLng, 15);
                })
                .catch(error => console.error("Error fetching data:", error));
        }
    </script>
</body>
</html>
