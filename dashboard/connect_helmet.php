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
            // Clear previous helmet connection (if any)
            $clearPrevious = $conn->prepare("UPDATE helmet SET rider_id = NULL WHERE rider_id = ?");
            $clearPrevious->bind_param("i", $rider_id);
            $clearPrevious->execute();

            // Connect new helmet to rider
            $connectHelmet = $conn->prepare("UPDATE helmet SET rider_id = ? WHERE helmet_id = ?");
            $connectHelmet->bind_param("is", $rider_id, $helmet_id);

            if ($connectHelmet->execute()) {
                echo "Helmet connected successfully!";
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
