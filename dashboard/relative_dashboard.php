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
    <link rel="stylesheet" href="emergency.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
</head>
<body>
    <nav class="navbar">
        <span class="brand">Smart Helmet</span>
        <ul>
            <li><a href="#" onclick="shareEmergency()">Emergency</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>

    <main>
    <h1>Track Rider</h1>

    <div class="container">
        <h2>Enter Rider Code</h2>
        <div class="form-group">
            <label for="rider_code">RIDER UNIQUE CODE:</label>
            <input type="text" id="rider_code" name="rider_code" placeholder="Enter unique rider code">
            <button onclick="startTracking()">Track</button>
        </div>

        <!-- Message Display Area -->
        <div id="tracking-message" style="margin: 10px 0; color: red; font-weight: bold;"></div>
    </div>

    <div id="tracking-data" style="display: none;">
        <div class="container">
            <h2>Live Tracking Data</h2>
            <div class="realtime-display">
                <div>
                    <h3>Rider's Speed</h3>
                    <p><span id="rider-speed">--</span> km/h</p>
                </div>
                <div>
                    <h3>Rider's Location</h3>
                    <div id="map-container" style="height: 300px;"></div>
                    <p id="rider-location-text">Lat: <span id="lat">--</span>, Lon: <span id="lon">--</span></p>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
let map;
let marker;
let trackingInterval;

function startTracking() {
    riderCode = document.getElementById("rider_code").value.trim();
    const messageBox = document.getElementById("tracking-message");
    const mapSection = document.getElementById("tracking-data");

    // Always hide live data section initially
    mapSection.style.display = "none";
    messageBox.textContent = "";

    if (riderCode === "") {
        messageBox.textContent = "Please enter a valid Rider Code.";
        return;
    }

    // Save connection in DB
    fetch("connect_relative.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: "rider_code=" + encodeURIComponent(riderCode)
    })
    .then(response => response.text())
    .then(data => console.log("Connection response:", data));

    fetchLiveData(riderCode); // First fetch
}


function fetchLiveData(sharedCode) {
    const messageBox = document.getElementById("tracking-message");
    const mapSection = document.getElementById("tracking-data");

    // Always hide live section before fetch
    mapSection.style.display = "none";

    fetch("fetch_rider_rel.php?shared_code=" + encodeURIComponent(sharedCode))
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                messageBox.textContent = data.error;
                return;
            }

            if (data.access_granted === false) {
                messageBox.textContent = "Access denied. You have been blocked by the rider.";
                return;
            }

            if (data.helmet_status !== "active") {
                messageBox.textContent = "Helmet is inactive. Rider cannot be tracked at this time.";
                return;
            }

            // Valid case: show live data section
            mapSection.style.display = "block";
            messageBox.textContent = "";

            // Update speed and location
            document.getElementById("rider-speed").textContent = data.speed_val;
            document.getElementById("lat").textContent = data.latitude;
            document.getElementById("lon").textContent = data.longitude;

            const newLatLng = new L.LatLng(data.latitude, data.longitude);
            if (!map) {
                map = L.map('map-container').setView(newLatLng, 15);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; OpenStreetMap contributors'
                }).addTo(map);
                marker = L.marker(newLatLng).addTo(map);
            } else {
                marker.setLatLng(newLatLng);
                map.setView(newLatLng, 15);
            }
        })
        .catch(error => {
            console.error("Fetch error:", error);
            messageBox.textContent = "Something went wrong while fetching rider data.";
        });
}


    function shareEmergency() {
        const locationText = document.getElementById("rider-location-text").textContent;
        if (locationText.includes("--")) {
            alert("Rider's location is not available yet.");
            return;
        }

        const [lat, lon] = locationText.match(/[-\d.]+/g);

        const emergencyPopup = document.createElement("div");
        emergencyPopup.className = "popup-container";
        emergencyPopup.innerHTML = `
            <div class="popup-content">
                <h2 class="popup-title">🚨 Emergency Alert 🚨</h2>
                <p class="popup-text">
                    Rider's last known location:<br>
                    <a href="https://www.google.com/maps?q=${lat},${lon}" target="_blank">📍 View on Google Maps</a>
                </p>
                <div class="popup-buttons">
                    <button onclick="sendWhatsApp('${lat}', '${lon}')" class="popup-btn whatsapp">WhatsApp</button>
                    <button onclick="sendSMS('${lat}', '${lon}')" class="popup-btn sms">SMS</button>
                </div>
                <button onclick="this.parentElement.parentElement.remove()" class="popup-btn close">Close</button>
            </div>
        `;

        document.body.appendChild(emergencyPopup);
    }

    function sendWhatsApp(lat, lon) {
        const message = `🚨 Emergency Alert! 🚨\n\nRider's last location:\n📍 https://www.google.com/maps?q=${lat},${lon}`;
        window.open(`https://wa.me/?text=${encodeURIComponent(message)}`, "_blank");
    }

    function sendSMS(lat, lon) {
        const message = `🚨 Emergency Alert! 🚨 Rider's last location: https://www.google.com/maps?q=${lat},${lon}`;
        window.open(`sms:?body=${encodeURIComponent(message)}`, "_blank");
    }
    </script>
</body>
</html>
