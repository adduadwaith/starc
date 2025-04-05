<?php
require "../connect.php";
session_start();

if (!isset($_SESSION["user_id"])) {
    die("Unauthorized access");
}

if (isset($_POST["relative_id"], $_POST["access_granted"])) {
    $relative_id = $_POST["relative_id"];
    $access_granted = $_POST["access_granted"];

    $update = "UPDATE relatives SET access_granted = ? WHERE relative_id = ?";
    $stmt = $conn->prepare($update);
    $stmt->bind_param("ii", $access_granted, $relative_id);

    if ($stmt->execute()) {
        header("Location: relatives.php");
        exit();
    } else {
        echo "Error updating access: " . $conn->error;
    }

    $stmt->close();
} else {
    echo "Invalid request.";
}

$conn->close();
?>
