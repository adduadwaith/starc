<?php
session_start();
include '../connect.php';

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id || !isset($_POST['speed_limit'])) {
    echo json_encode(['success' => false, 'error' => 'Missing user or speed limit']);
    exit;
}

$speed_limit = (float) $_POST['speed_limit'];

// Step 1: Get latest helmet_id for this rider
$query = "SELECT helmet_id FROM helmet WHERE rider_id = ? ORDER BY created_at DESC LIMIT 1";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();
$stmt->close();

if ($data && $data['helmet_id']) {
    $helmet_id = $data['helmet_id'];

    // Step 2: Update speed_limit in helmet table
    $updateHelmet = "UPDATE helmet SET speed_limit = ? WHERE helmet_id = ?";
    $stmt2 = $conn->prepare($updateHelmet);
    $stmt2->bind_param("di", $speed_limit, $helmet_id);
    $success = $stmt2->execute();
    $stmt2->close();

    echo json_encode(['success' => $success]);
} else {
    echo json_encode(['success' => false, 'error' => 'No helmet found for rider']);
}
?>
