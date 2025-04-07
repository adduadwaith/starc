<?php
include 'connect.php'; // your DB connection

if (isset($_GET['helmet_id'])) {
    $helmet_id = $_GET['helmet_id'];

    $stmt = $conn->prepare("SELECT speed_limit FROM helmet WHERE helmet_id = ?");
    $stmt->bind_param("i", $helmet_id); // "i" = integer
    $stmt->execute();
    $stmt->bind_result($speed_limit);

    if ($stmt->fetch()) {
        echo $speed_limit;
    } else {
        echo "60";
    }

    $stmt->close();
} else {
    echo "60";
}

?>
