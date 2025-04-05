<?php
session_start();
include '../connect.php';

$helmet_id = $_GET['helmet_id'] ?? '';
$shared_code = $_GET['shared_code'] ?? '';
$user_role = $_SESSION['role'] ?? '';

if ($helmet_id === '') {
    echo json_encode(["error" => "Invalid request."]);
    exit;
}

// Allow admin to track any rider, skip access check
if ($user_role === 'admin') {
    $query = "SELECT * FROM helmet_live_data WHERE helmet_id = ?";
} else {
    $query = "SELECT h.* FROM helmet_live_data h
              JOIN relatives r ON h.helmet_id = r.rider_id
              WHERE r.shared_code = ? AND r.access_granted = TRUE";
}

$stmt = $conn->prepare($query);
if ($user_role === 'admin') {
    $stmt->bind_param("s", $helmet_id);
} else {
    $stmt->bind_param("s", $shared_code);
}

$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

if ($data) {
    echo json_encode($data);
} else {
    echo json_encode(["error" => "No tracking data found."]);
}

$conn->close();
?>
