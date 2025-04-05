<?php
header('Content-Type: application/json');
include '../connect.php';
session_start(); // Required to get logged-in user data

// Check helmet ID (or code)
if (!isset($_GET['helmet_id']) || empty($_GET['helmet_id'])) {
    echo json_encode(["error" => "Helmet ID is required"]);
    exit();
}

$helmet_id = $_GET['helmet_id'];

// Get user info from session
if (!isset($_SESSION["user_id"]) || !isset($_SESSION["role"])) {
    echo json_encode(["error" => "Unauthorized"]);
    exit();
}

$user_id = $_SESSION["user_id"];
$role = $_SESSION["role"];

// 👉 If user is a relative, check access permissions
if ($role === 'relative') {
    $sql_check = "SELECT r.access_granted 
                  FROM relatives r 
                  JOIN helmet h ON r.rider_id = h.rider_id 
                  WHERE r.relative_id = ? AND h.helmet_id = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("ii", $user_id, $helmet_id);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($row = $result_check->fetch_assoc()) {
        if ((int)$row['access_granted'] === 0) {
            echo json_encode(["error" => "Access to this rider is blocked."]);
            exit();
        }
    } else {
        echo json_encode(["error" => "No access to this rider."]);
        exit();
    }
}

// ✅ Access is allowed (either relative with access or admin)

// Fetch live rider data
$sql = "SELECT u.user_id, u.name, u.email, 
               h.helmet_id, h.status, 
               hl.speed_val AS speed, hl.latitude, hl.longitude, hl.last_updated
        FROM helmet h
        JOIN user u ON h.rider_id = u.user_id
        JOIN helmet_live_data hl ON h.helmet_id = hl.helmet_id
        WHERE h.helmet_id = ? 
        ORDER BY hl.last_updated DESC LIMIT 1";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $helmet_id);
$stmt->execute();
$result = $stmt->get_result();

if ($data = $result->fetch_assoc()) {
    echo json_encode($data);
} else {
    echo json_encode(["error" => "No live data found."]);
}

$stmt->close();
$conn->close();
?>
