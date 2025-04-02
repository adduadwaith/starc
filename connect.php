<?php
// db_connection.php
$servername = "localhost"; // or your database host
$username = "root"; // your database username
$password = ""; // your database password
$dbname = "if0_38609414_helmet_db"; // your database name
$port=3307;

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname,$port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
