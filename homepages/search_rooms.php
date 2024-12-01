<?php
require_once('C:\xampp\htdocs\Nase Hotel\db_config.php');

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (isset($_GET["room_category"])) {
        $roomCategory = $_GET["room_category"];

        // Define room number ranges based on room category
        switch ($roomCategory) {
            case "Economy":
                $startNumber = 1;
                $endNumber = 15;
                break;
            case "Deluxe":
                $startNumber = 16;
                $endNumber = 26;
                break;
            case "Premium":
                $startNumber = 27;
                $endNumber = 33;
                break;
            default:
                // If an invalid room category is provided, return an empty result
                echo json_encode([
                    "available" => [],
                    "pending" => [],
                    "accepted" => [],
                    "other" => []
                ]);
                exit();
        }

        // Fetch room numbers based on room category and status
        $searchRoomsQuery = $conn->prepare("SELECT room_number, status FROM reservations WHERE room_category = ?");
        $searchRoomsQuery->bind_param("s", $roomCategory);
        $searchRoomsQuery->execute();
        $searchRoomsResult = $searchRoomsQuery->get_result();

        $availableRooms = range($startNumber, $endNumber);
        $pendingRooms = [];
        $acceptedRooms = [];
        $otherRooms = [];

        while ($row = $searchRoomsResult->fetch_assoc()) {
            $roomNumber = $row['room_number'];
            $status = $row['status'];
        
            if ($status === 'Pending') {
                $pendingRooms[] = $roomNumber;
            } elseif ($status === 'Accepted') {
                $acceptedRooms[] = $roomNumber;
            } else {
                $otherRooms[] = $roomNumber;
            }
        
            // Remove the room from available rooms if it has a status other than null or Rejected
            if ($status !== null && $status !== 'Rejected') {
                $index = array_search($roomNumber, $availableRooms);
                if ($index !== false) {
                    unset($availableRooms[$index]);
                }
            }
        }

        // Reindex the array to fix the issue with unset
        $availableRooms = array_values($availableRooms);

        // Return the result as JSON
        echo json_encode([
            "available" => $availableRooms,
            "pending" => $pendingRooms,
            "accepted" => $acceptedRooms,
            "other" => $otherRooms
        ]);

        // Close the result set
        $searchRoomsResult->close();
    }
}
?>;