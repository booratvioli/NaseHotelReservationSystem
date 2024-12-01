<?php
// Database connection parameters
$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "nasehotel";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $status = $_POST['status'];
    $statuses = explode(', ', $status);

    $placeholders = str_repeat('?,', count($statuses) - 1) . '?';
    $sql = "SELECT * FROM reservations WHERE status IN ($placeholders) ORDER BY reservation_id ASC";
    $stmt = $conn->prepare($sql);
    $stmt->execute($statuses);

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td>{$row['reservation_id']}</td>";
        echo "<td>{$row['user_id']}</td>";
        echo "<td>{$row['reservation_date']}</td>";
        echo "<td>{$row['reservation_time']}</td>";
        echo "<td>{$row['room_category']}</td>";
        echo "<td>{$row['hours_reserved']}</td>";
        echo "<td>{$row['room_number']}</td>";
        echo "<td>{$row['reservation_cost']}</td>";
        echo "<td>{$row['payment_method']}</td>";
        echo "<td>{$row['advance_payment']}</td>";
        echo "<td>{$row['reference_number']}</td>";
        echo "<td>{$row['status']}</td>";
        echo "<td>{$row['emailaddress']}</td>";
        echo "<td>{$row['remaining_payment']}</td>";
        echo "</tr>";
    }
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
$conn = null;
?>