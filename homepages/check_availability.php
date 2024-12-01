<?php
// check_availability.php

require_once('C:\xampp\htdocs\Nase Hotel\db_config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize user input
    $room_category = mysqli_real_escape_string($conn, $_POST["room_category"]);

    // Query to fetch reserved room numbers with Pending or Accepted status
    $query = "SELECT room_number FROM reservations WHERE room_category = ? AND status IN ('Pending', 'Accepted')";
    
    // Use prepared statement to prevent SQL injection
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $room_category);
    
    $stmt->execute();
    
    // Bind the result
    $stmt->bind_result($reserved_room);
    
    // Fetch the reserved room numbers
    $reserved_rooms = [];
    while ($stmt->fetch()) {
        $reserved_rooms[] = $reserved_room;
    }
    
    // Close the prepared statement
    $stmt->close();
    
    // Return the result as JSON
    header('Content-Type: application/json');
    echo json_encode(['reserved_rooms' => $reserved_rooms]);
} else {
    // Invalid request method
    http_response_code(400);
    echo "Invalid request method.";
}
?>;
