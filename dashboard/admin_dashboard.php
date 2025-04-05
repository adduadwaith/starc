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
    const helmetID = document.getElementById("search_helmet_id").value;

    fetch("fetch_rider.php?helmet_id=" + helmetID)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                document.getElementById("rider-details").innerHTML = `<p style="color:red;">${data.error}</p>`;
            } else {
                // Determine button text and action based on status
                const isActive = data.status.toLowerCase() === "active";
                const buttonText = isActive ? "Deactivate this helmet" : "Activate this helmet";
                const actionFunc = isActive ? `deactivateHelmet(${data.helmet_id})` : `activateHelmet(${data.helmet_id})`;

                document.getElementById("rider-details").innerHTML = `
                    <p><strong>Name:</strong> ${data.name}</p>
                    <p><strong>Email:</strong> ${data.email}</p>
                    <p><strong>Status:</strong> ${data.status}</p>
                    <p><strong>Speed:</strong> ${data.speed} km/h</p>
                    <p><strong>Location:</strong> ${data.latitude}, ${data.longitude}</p>
                    <p><strong>Last Updated:</strong> ${data.last_updated}</p>
                    <button onclick="${actionFunc}">${buttonText}</button>
                `;
                updateMap(data.latitude, data.longitude);


            }
        })
        .catch(error => {
            console.error("Error fetching rider:", error);
            document.getElementById("rider-details").innerHTML = `<p style="color:red;">Error fetching data</p>`;
        });
}



function updateMap(lat, lon) {
    if (!map) {
        map = L.map('map').setView([lat, lon], 14);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);
        marker = L.marker([lat, lon]).addTo(map);
    } else {
        marker.setLatLng([lat, lon]);
        map.setView([lat, lon], 14, { animate: true });
    }
}

// Auto-update every 5 seconds
setInterval(fetchRiderDetails, 5000);

function deactivateHelmet(helmetID) {
    fetch("update_helmet_status.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ helmet_id: helmetID, status: "inactive" })
    })
    .then(res => res.json())
    .then(response => {
       
        fetchRiderDetails(); // Refresh UI
    })
    .catch(err => {
        console.error("Deactivation error:", err);
      
    });
}

function activateHelmet(helmetID) {
    fetch("update_helmet_status.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ helmet_id: helmetID, status: "active" })
    })
    .then(res => res.json())
    .then(response => {
       
        fetchRiderDetails(); // Refresh UI
    })
    .catch(err => {
        console.error("Activation error:", err);
      
    });
}


</script>

</body>
</html>
