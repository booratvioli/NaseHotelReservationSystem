<?php
// room_search.php

// Connect to the database and fetch room information based on room category
session_start();
require_once('C:\xampp\htdocs\NASE HOTEL\db_config.php');

header('Content-Type: application/json'); // Set the content type to JSON

if (isset($_GET['room_category'])) {
    $roomCategory = $_GET['room_category'];

    $availableRooms = [];
    $pendingRooms = [];
    $acceptedRooms = [];

    // Fetch available rooms
    $availableQuery = $conn->prepare("SELECT room_number FROM nasehotel WHERE room_category = ? AND status = 'Available'");
    $availableQuery->bind_param("s", $roomCategory);
    $availableQuery->execute();
    $availableResult = $availableQuery->get_result();

    while ($row = $availableResult->fetch_assoc()) {
        $availableRooms[] = $row['room_number'];
    }

    // Fetch pending rooms
    $pendingQuery = $conn->prepare("SELECT room_number FROM reservations WHERE room_category = ? AND status = 'Pending'");
    $pendingQuery->bind_param("s", $roomCategory);
    $pendingQuery->execute();
    $pendingResult = $pendingQuery->get_result();

    while ($row = $pendingResult->fetch_assoc()) {
        $pendingRooms[] = $row['room_number'];
    }

    // Fetch accepted rooms
    $acceptedQuery = $conn->prepare("SELECT room_number FROM reservations WHERE room_category = ? AND status = 'Accepted'");
    $acceptedQuery->bind_param("s", $roomCategory);
    $acceptedQuery->execute();
    $acceptedResult = $acceptedQuery->get_result();

    while ($row = $acceptedResult->fetch_assoc()) {
        $acceptedRooms[] = $row['room_number'];
    }

    // Close the database connections
    $availableQuery->close();
    $pendingQuery->close();
    $acceptedQuery->close();
    $conn->close();

    // Return the room information as JSON
    $response = [
        'available' => $availableRooms,
        'pending' => $pendingRooms,
        'accepted' => $acceptedRooms
    ];

    echo json_encode($response);
} else {
    // Return an empty JSON if no room category is provided
    echo json_encode([]);
}
?>
