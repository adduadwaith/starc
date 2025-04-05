<?php
session_start();
include '../connect.php';

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id || !isset($_POST['speed_limit'])) {
    echo json_encode(['success' => false]);
    exit;
}

$speed_limit = (int) $_POST['speed_limit'];

// Get rider's helmet ID
$query = "SELECT helmet_id FROM helmet WHERE rider_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();
$stmt->close();

if ($data && $data['helmet_id']) {
    $helmet_id = $data['helmet_id'];

    $update = "UPDATE helmet SET speed_limit = ? WHERE helmet_id = ?";
    $stmt2 = $conn->prepare($update);
    $stmt2->bind_param("ii", $speed_limit, $helmet_id);
    $success = $stmt2->execute();
    $stmt2->close();

    echo json_encode(['success' => $success]);
} else {
    echo json_encode(['success' => false]);
}
?>
