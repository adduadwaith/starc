<?php
require "../connect.php";
session_start();

if (!isset($_SESSION["user_id"])) {
    die("Unauthorized access");
}

$user_id = $_SESSION["user_id"]; // Rider's ID

// Fetch relatives connected to this rider
$sql = "SELECT * FROM relatives WHERE rider_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Connected Relatives</title>
    <style>
        table { border-collapse: collapse; width: 80%; margin: 20px auto; }
        th, td { padding: 10px; border: 1px solid #ccc; text-align: center; }
        button { padding: 6px 12px; cursor: pointer; }
    </style>
</head>
<body>
    <h2 style="text-align:center;">Your Connected Relatives</h2>
    <table>
        <tr>
            <th>Relative ID</th>
            <th>Shared Code</th>
            <th>Access</th>
            <th>Action</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) : ?>
            <tr>
                <td><?= $row['relative_id'] ?></td>
                <td><?= $row['shared_code'] ?></td>
                <td><?= $row['access_granted'] ? "Granted" : "Blocked" ?></td>
                <td>
                    <form method="POST" action="toggle_access.php">
                        <input type="hidden" name="relative_id" value="<?= $row['relative_id'] ?>">
                        <input type="hidden" name="access_granted" value="<?= $row['access_granted'] ? 0 : 1 ?>">
                        <button type="submit"><?= $row['access_granted'] ? "Block" : "Unblock" ?></button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
