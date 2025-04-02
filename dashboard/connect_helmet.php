<?php
session_start();
include "../connect.php"; // Database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $helmet_id = $_POST['helmet_id'];
    $rider_id = $_SESSION['rider_id']; // Assuming rider_id is stored in session after login

    if (!empty($helmet_id)) {
        // Check if the helmet ID exists in the database
        $checkHelmet = $conn->prepare("SELECT * FROM helmet WHERE helmet_id = ?");
        $checkHelmet->bind_param("s", $helmet_id);
        $checkHelmet->execute();
        $result = $checkHelmet->get_result();

        if ($result->num_rows > 0) {
            // Update the rider's helmet_id
            $updateHelmet = $conn->prepare("UPDATE user SET helmet_id = ? WHERE id = ?");
            $updateHelmet->bind_param("si", $helmet_id, $rider_id);
            if ($updateHelmet->execute()) {
                echo "Helmet Connected Successfully!";
            } else {
                echo "Error connecting helmet!";
            }
        } else {
            echo "Invalid Helmet ID!";
        }
    } else {
        echo "Please enter a Helmet ID!";
    }
}
?>
