<?php
header('Content-Type: application/json');
include '../connect.php';

if (!isset($_GET['shared_code'])) {
    echo json_encode(["error" => "Missing shared_code."]);
    exit();
}

$shared_code = $_GET['shared_code'];

// Step 1: Get rider_id using shared code
$riderQuery = "SELECT user_id FROM user WHERE unique_code = ?";
$stmt = $conn->prepare($riderQuery);
$stmt->bind_param("s", $shared_code);
$stmt->execute();
$riderResult = $stmt->get_result();

if ($riderResult->num_rows === 0) {
    echo json_encode(["error" => "Rider not found."]);
    exit();
}

$riderRow = $riderResult->fetch_assoc();
$rider_id = $riderRow['user_id'];

// Step 2: Check access_granted in relatives table
$accessQuery = "SELECT access_granted FROM relatives WHERE rider_id = ? AND shared_code = ?";
$accessStmt = $conn->prepare($accessQuery);
$accessStmt->bind_param("is", $rider_id, $shared_code);
$accessStmt->execute();
$accessResult = $accessStmt->get_result();

if ($accessResult->num_rows === 0) {
    echo json_encode(["error" => "Relative connection not found."]);
    exit();
}

$accessRow = $accessResult->fetch_assoc();
if (!$accessRow['access_granted']) {
    echo json_encode(["access_granted" => false]);
    exit();
}

// Step 3: Fetch latest helmet_live_data with helmet status
$dataQuery = "
    SELECT hld.speed_val, hld.latitude, hld.longitude, helmet.status AS helmet_status
    FROM helmet_live_data hld
    INNER JOIN helmet ON hld.helmet_id = helmet.helmet_id
    WHERE helmet.rider_id = ?
    ORDER BY hld.last_updated DESC
    LIMIT 1
";
$dataStmt = $conn->prepare($dataQuery);
$dataStmt->bind_param("i", $rider_id);
$dataStmt->execute();
$dataResult = $dataStmt->get_result();

if ($dataResult->num_rows === 0) {
    echo json_encode(["error" => "No live data found."]);
    exit();
}

$data = $dataResult->fetch_assoc();

echo json_encode([
    "access_granted" => true,
    "helmet_status" => $data['helmet_status'],
    "speed_val" => $data['speed_val'],
    "latitude" => $data['latitude'],
    "longitude" => $data['longitude']
]);
?>
