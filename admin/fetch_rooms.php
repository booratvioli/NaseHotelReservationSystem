<?php
$status = $_POST['status'];

// Connect to the database
$con = mysqli_connect("localhost", "root", "", "nasehotel");

// Define the query to fetch all rooms
$query = "SELECT * FROM reservations ORDER BY reservation_id ASC";

$result = mysqli_query($con, $query);

// Create an array to store the room numbers and their statuses
$rooms = array();

while($row = mysqli_fetch_array($result)) {
    $rooms[$row['room_number']] = $row['status'];
}

// Create a list of room numbers up to 33
$room_numbers = range(1, 33);

// Loop through the list of room numbers
foreach($room_numbers as $room_number) {
    // If the room number is not in the array of rooms or its status is not 'Accepted' or 'Pending', mark it as 'Available'
    if (!array_key_exists($room_number, $rooms) || ($rooms[$room_number] != 'Accepted' && $rooms[$room_number] != 'Pending')) {
        $rooms[$room_number] = 'Available';
    }

    // If a specific status is requested, skip the rooms that don't match
    if ($status !== 'All' && $rooms[$room_number] !== $status) {
        continue;
    }

    echo "<tr>";
    echo "<td>" . $room_number . "</td>";

    // Determine the room category based on the room number
    if ($room_number <= 15) {
        echo "<td>Economy</td>";
    } elseif ($room_number <= 25) {
        echo "<td>Deluxe</td>";
    } else {
        echo "<td>Premium</td>";
    }

    if ($rooms[$room_number] == 'Accepted') {
        echo "<td class='accepted'>Accepted</td>";
    } elseif ($rooms[$room_number] == 'Pending') {
        echo "<td class='pending'>Pending</td>";
    } elseif ($rooms[$room_number] == 'Available') {
        echo "<td class='available'>Available</td>";
    }

    echo "</tr>";
}

// Close the database connection
mysqli_close($con);
?>