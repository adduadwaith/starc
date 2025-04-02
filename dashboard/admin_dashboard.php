<?php
session_start();
include '../connect.php';

// Ensure admin is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
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
    <title>Admin Dashboard - Smart Helmet</title>
    <link rel="stylesheet" href="style_dash.css">
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script> 
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

    <nav class="navbar">
        <span class="brand">Smart Helmet</span>
        <ul>
            <li><a href="admin_dashboard.php" class="active">Home</a></li>
            <li><a href="admin_view_users.php">View Users</a></li>
            <li><a href="admin_alerts.php">Alerts</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>

    <main>
        <h1>Admin Dashboard</h1>

        <div class="container">
            <h2>Search Rider by Helmet ID</h2>
            <div class="form-group">
                <input type="text" id="search_helmet_id" placeholder="Enter Helmet ID">
                <button onclick="fetchRiderDetails()">Search</button>
            </div>
            <div id="rider-details"></div>
        </div>

        <div class="container">
            <h2>Live Tracking</h2>
            <div id="map" style="height: 400px;"></div>
        </div>
    </main>

    <script>
        let map, marker;

        function fetchRiderDetails() {
    const helmetId = document.getElementById("search_helmet_id").value;
    if (!helmetId) {
        alert("Please enter a Helmet ID.");
        return;
    }

    $.ajax({
        url: "fetch_rider.php",
        type: "GET",
        data: { helmet_id: helmetId },
        dataType: "json",
        success: function(response) {
            if (response.error) {
                document.getElementById("rider-details").innerHTML = `<p style='color:red;'>${response.error}</p>`;
                return;
            }

            let detailsHtml = `
                <p><strong>User ID:</strong> ${response.user_id}</p>
                <p><strong>Name:</strong> ${response.name}</p>
                <p><strong>Email:</strong> ${response.email}</p>
                <p><strong>Helmet Status:</strong> ${response.status}</p>
                <p><strong>Last Speed:</strong> ${response.speed} km/h</p>
                <p><strong>Last Updated:</strong> ${response.last_updated}</p>
            `;
            document.getElementById("rider-details").innerHTML = detailsHtml;

            updateMap(response.latitude, response.longitude);
        },
        error: function() {
            document.getElementById("rider-details").innerHTML = "<p style='color:red;'>Error fetching data.</p>";
        }
    });
}


        function updateMap(lat, lon) {
            if (!lat || !lon) {
                alert("Location not available.");
                return;
            }

            if (!map) {
                map = L.map('map').setView([lat, lon], 14);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
                marker = L.marker([lat, lon]).addTo(map);
            } else {
                map.setView([lat, lon], 14);
                marker.setLatLng([lat, lon]);
            }
        }
    </script>

</body>
</html>
