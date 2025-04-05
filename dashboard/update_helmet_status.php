<?php
header('Content-Type: application/json');
include '../connect.php';
session_start();

// Check if admin is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

$data = json_decode(file_get_contents("php://input"), true);

// Validate helmet_id and status
if (!isset($data['helmet_id']) || !isset($data['status'])) {
    echo json_encode(['error' => 'Invalid request']);
    exit();
}

$helmet_id = intval($data['helmet_id']);
$status = strtolower(trim($data['status']));

// Validate status value
if (!in_array($status, ['active', 'inactive'])) {
    echo json_encode(['error' => 'Invalid status value']);
    exit();
}

// Update helmet status
$update_sql = "UPDATE helmet SET status = ? WHERE helmet_id = ?";
$stmt = $conn->prepare($update_sql);
$stmt->bind_param("si", $status, $helmet_id);

if ($stmt->execute()) {
    // Log admin action
    $admin_id = $_SESSION['user_id'];
    $action_text = ucfirst($status) . "d helmet with ID " . $helmet_id;

    $log_sql = "INSERT INTO admin (admin_id, admin_action) VALUES (?, ?)";
    $log_stmt = $conn->prepare($log_sql);
    $log_stmt->bind_param("is", $admin_id, $action_text);
    $log_stmt->execute();
    $log_stmt->close();

    echo json_encode(['message' => "Helmet successfully {$status}d"]);
} else {
    echo json_encode(['error' => 'Failed to update status']);
}

$stmt->close();
$conn->close();
?>
