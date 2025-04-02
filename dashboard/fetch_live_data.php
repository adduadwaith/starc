<?php
include '../connect.php'; // Ensure database connection

if (!isset($_GET['helmet_id'])) {
    echo json_encode(["error" => "Helmet ID required"]);
    exit;
}

$helmet_id = $_GET['helmet_id'];

// Fetch latest speed and location
$query = "SELECT speed_val, latitude, longitude FROM helmet_live_data WHERE helmet_id = ? ORDER BY last_updated DESC LIMIT 1";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $helmet_id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

if ($data) {
    echo json_encode([
        "speed" => $data['speed_val'],
        "latitude" => $data['latitude'],
        "longitude" => $data['longitude']
    ]);
} else {
    echo json_encode(["error" => "No live data found"]);
}

$stmt->close();
$conn->close();
?>
