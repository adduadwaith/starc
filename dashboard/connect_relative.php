<?php
include '../connect.php';
session_start();

if (!isset($_SESSION['user_id']) || !isset($_POST['rider_code'])) {
    header("Location: rider_dashboard.php?error=unauthorized");
    exit();
}

$relative_id = $_SESSION['user_id'];
$shared_code = trim($_POST['rider_code']);

$riderQuery = "SELECT user_id FROM user WHERE unique_code = ?";
$stmt = $conn->prepare($riderQuery);
$stmt->bind_param("s", $shared_code);
$stmt->execute();
$riderResult = $stmt->get_result();

if ($riderResult->num_rows === 0) {
    header("Location: rider_dashboard.php?error=notfound");
    exit();
}

$riderRow = $riderResult->fetch_assoc();
$rider_id = $riderRow['user_id'];

$checkQuery = "SELECT * FROM relatives WHERE rider_id = ? AND relative_id = ?";
$checkStmt = $conn->prepare($checkQuery);
$checkStmt->bind_param("ii", $rider_id, $relative_id);
$checkStmt->execute();
$checkResult = $checkStmt->get_result();

if ($checkResult->num_rows === 0) {
    $insertQuery = "INSERT INTO relatives (rider_id, relative_id, shared_code, access_granted) VALUES (?, ?, ?, 1)";
    $insertStmt = $conn->prepare($insertQuery);
    $insertStmt->bind_param("iis", $rider_id, $relative_id, $shared_code);
    $insertStmt->execute();
}

// ✅ Redirect to avoid resubmission
header("Location: rider_dashboard.php?status=connected");
exit();
?>
