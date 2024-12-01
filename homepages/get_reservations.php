<?php
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'DESC'; // Default sort order is DESC

// Database connection
$conn = mysqli_connect("localhost","root","","hotel1");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM reservations ORDER BY reservation_id $sort";
$result = $conn->query($sql);
$reservations = array();
while($row = $result->fetch_assoc()) {
    $reservations[] = $row;
}
echo json_encode($reservations);
$conn->close();
?>

