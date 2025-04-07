<?php
include("connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $helmet_id = $_POST['helmet_id'];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];
    $speed_val = $_POST['speed_val'];

    $sql = "INSERT INTO helmet_live_data (helmet_id, latitude, longitude, speed_val) 
            VALUES ('$helmet_id', '$latitude', '$longitude', '$speed_val')
            ON DUPLICATE KEY UPDATE 
                latitude = VALUES(latitude),
                longitude = VALUES(longitude),
                speed_val = VALUES(speed_val)";

    if ($conn->query($sql) === TRUE) {
        echo "1"; // Just send a success code
    } else {
        echo "Error updating: " . $conn->error;
    }
}
?>
