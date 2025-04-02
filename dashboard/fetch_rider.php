<?php
header('Content-Type: application/json');
include '../connect.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_GET['helmet_id']) || empty($_GET['helmet_id'])) {
    echo json_encode(["error" => "Helmet ID is required"]);
    exit();
}

$helmet_id = $_GET['helmet_id'];

// SQL Query to JOIN helmet, helmet_live_data, and user
$sql = "SELECT u.user_id, u.name, u.email, 
               h.helmet_id, h.status, 
               hl.speed_val AS speed, hl.latitude, hl.longitude, hl.last_updated
        FROM helmet h
        JOIN user u ON h.rider_id = u.user_id
        JOIN helmet_live_data hl ON h.helmet_id = hl.helmet_id
        WHERE h.helmet_id = ? 
        ORDER BY hl.last_updated DESC LIMIT 1";  // Get the latest data

$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode(["error" => "SQL Error: " . $conn->error]);
    exit();
}

$stmt->bind_param("i", $helmet_id);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    echo json_encode($row);
} else {
    echo json_encode(["error" => "No data found for Helmet ID $helmet_id"]);
}

$stmt->close();
$conn->close();
?>
