<?php
include '../connect.php';
session_start();

if (!isset($_SESSION['user_id']) || !isset($_POST['rider_code'])) {
    echo "Unauthorized or missing rider code.";
    exit();
}

$relative_id = $_SESSION['user_id'];
$shared_code = trim($_POST['rider_code']);

// Fetch rider_id using shared_code (which is rider's unique_code)
$riderQuery = "SELECT user_id FROM user WHERE unique_code = ?";
$stmt = $conn->prepare($riderQuery);
$stmt->bind_param("s", $shared_code);
$stmt->execute();
$riderResult = $stmt->get_result();

if ($riderResult->num_rows === 0) {
    echo "Rider not found.";
    exit();
}

$riderRow = $riderResult->fetch_assoc();
$rider_id = $riderRow['user_id'];

// Check if already connected
$checkQuery = "SELECT * FROM relatives WHERE rider_id = ? AND relative_id = ?";
$checkStmt = $conn->prepare($checkQuery);
$checkStmt->bind_param("ii", $rider_id, $relative_id);
$checkStmt->execute();
$checkResult = $checkStmt->get_result();

if ($checkResult->num_rows === 0) {
    // Not connected, insert connection
    $insertQuery = "INSERT INTO relatives (rider_id, relative_id, shared_code, access_granted) VALUES (?, ?, ?, 1)";
    $insertStmt = $conn->prepare($insertQuery);
    $insertStmt->bind_param("iis", $rider_id, $relative_id, $shared_code);
    $insertStmt->execute();

    echo "Connected successfully.";
} else {
    echo "Already connected.";
}
?>
