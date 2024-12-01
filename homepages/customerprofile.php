
<?php
// customerprofile.php - Customer profile and reservations

session_start();
require_once('C:\xampp\htdocs\NASE HOTEL\db_config.php');
include 'C:\xampp\htdocs\NASE HOTEL\homepages\faqsbot.php';

// Check if the user is logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION["user_id"];

// Fetch user details
$user_query = "SELECT * FROM users WHERE user_id = $user_id";
$user_result = $conn->query($user_query);

// Check if the user details are found
if ($user_result->num_rows > 0) {
    $user = $user_result->fetch_assoc();
} else {
    echo "User not found";
    exit();
}

// Fetch reservations for the user
$reservations_query = "SELECT * FROM reservations WHERE user_id = $user_id";
$reservations_result = $conn->query($reservations_query);

$stats_query = "SELECT
    COUNT(*) AS total_reservations,
    SUM(CASE WHEN status = 'Accepted' THEN 1 ELSE 0 END) AS accepted_reservations,
    SUM(CASE WHEN status = 'Rejected' THEN 1 ELSE 0 END) AS rejected_reservations,
    SUM(CASE WHEN status = 'Pending' OR status IS NULL THEN 1 ELSE 0 END) AS pending_reservations,
    SUM(CASE WHEN status = 'Accepted' THEN hours_reserved ELSE 0 END) AS total_hours_accepted,
    SUM(CASE WHEN status = 'Accepted' THEN reservation_cost ELSE 0 END) AS total_cost_accepted,
    
    SUM(CASE WHEN status IN ('Accepted', 'Pending') THEN remaining_payment ELSE 0 END) AS total_remaining_payment
FROM reservations
WHERE user_id = $user_id";




$stats_result = $conn->query($stats_query);

// Check if the statistics are found
if ($stats_result->num_rows > 0) {
    $stats = $stats_result->fetch_assoc();
} else {
    // Set default values if no statistics are found
    $stats = [
        'total_reservations' => 0,
        'accepted_reservations' => 0,
        'rejected_reservations' => 0,
        'pending_reservations' => 0,
        'total_hours_accepted' => 0,
        'total_cost_accepted' => 0,
        'gcash_count' => 0,
        'paymaya_count' => 0,
        'visa_count' => 0,
    ];
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Profile</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

   <!-- Add your styles or include external stylesheets here -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>



    <style>
        *{
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}
body{
    padding: 0 10% 0 10%;
    font-family: 'Montserrat';
    background-image: url('bg.jpg');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
}
header{
    display: flex;
    align-items: center;
    justify-content: space-between;
    width: 100%;
    height: 100px;
    top: 0;
    border-bottom: 2px solid grey;
    background-color: white;
    padding-left: 30px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
}

header img{
    width: 60px;
}
header .title{
    display: flex;
    align-items: center;
}

header .title h1{
    color: black;
    text-transform:uppercase;
    margin-left: 10px;
    font-size: 2rem;
}

header .navLinks ul {
    display: flex;
    justify-content: center;
    align-items: center;
    list-style: none; /* Remove default list styles */
}

header .navLinks ul li {
    display: flex; /* Make list items flex containers */
    justify-content: center; /* Center horizontally */
    align-items: center; /* Center vertically */
    margin: 0 20px; /* Add spacing between list items if desired */
}

header .navLinks ul li a {
    color: black;
    text-decoration: none; /* Remove underline from links */
    font-size: 1.5rem;
    font-weight: 400;
}

        h2{
            color: #333;
            font-size: 2rem;
            margin-bottom: 20px;

        }
        h3{
            color: #333;
            text-align: center;
            font-size: 3rem;
            margin-bottom: 40px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            background-color: #f0f0f0;
        }

        th {
            background-color: #f2f2f2;
        }

        button {
            padding: 10px;
            margin-top: 10px;
            background-color: #86654B;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-family: "Montserrat";
            font-size: 16px;
        }
        .container {
            position: relative;
            margin: 0 auto;
            margin-top: 4%;
            padding: 80px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            height: 90%;
        }
        .logout {
            position: relative;
            float: right;
        }

        .cancel-btn {
            background-color: #e74c3c;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
        }

        .eye {
    width: 30px;
    height: 15px;
    position: relative;
    border-radius: 50%;
    overflow: hidden;
    background: radial-gradient(circle at center, #444 10%, transparent 40%);
}

.open {
    background: radial-gradient(circle at center, #444 10%, transparent 40%);
}

.closed {
    background: #fff;
}

.eyeball {
    width: 10px;
    height: 10px;
    background: radial-gradient(circle at center, #000 10%, #444 40%);
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    border-radius: 50%;
}

.closed .eyeball {
    background: #fff;
}

.container {
        max-height: 700px; /* Adjust as needed */
        overflow-y: auto;
    }

    /* For Chrome, Safari and Opera */
    .container::-webkit-scrollbar {
        width: 12px;
    }

    .container::-webkit-scrollbar-track {
        -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0);
        border-radius: 10px;
    }

    .container::-webkit-scrollbar-thumb {
        border-radius: 10px;
        -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0);
    }

    /* For Internet Explorer and Edge */
    .container {
        -ms-overflow-style: none;
    }

    .container::-ms-scrollbar {
        display: none;
    }

    </style>
</head>
<body>
<header>
        <div class="title">
            <img src="logo.png" alt="logo" class="logo" />
            <h1>Nase</h1>
        </div>

        <nav class="navLinks">
            <ul>
                <li><a href="/homepages/Home.php">Home</a></li>
                <li><a href="/homepages/About.php">About</a></li>
                <li><a href="/homepages/pricing-table.php">Rooms</a></li>
                <li><a href="/homepages/ContactUs.php">Contacts</a></li>
                <li><a href="login.php"><i class="fa-regular fa-user"></i></a></li>
            </ul>
        </nav>
    </header>
    
    <div class="container">
        <h3>YOUR RESERVATIONS</h3>
        <div style="text-align: left; margin-bottom: 20px;">
            <h2>Welcome, <?php echo $user["username"]; ?>!</h2>
            <p><i>Your User Id: <?php echo $user["user_id"]; ?></i></p>
        </div>


            
<!-- Add a button for toggling details -->
<button id="toggleButton" onclick="toggleDetails()">Minimize Details</button>

 <!-- Add a status button to display user information -->
 <button id="statusButton">Show Your Status</button>

 <div id="userStatus" style="display: none;">
    <h2>Your Statistics</h2>
    <p>Total Reservations: <?php echo $stats['total_reservations']; ?></p>
    <p>Accepted Reservations: <?php echo $stats['accepted_reservations']; ?></p>
    <p>Rejected Reservations: <?php echo $stats['rejected_reservations']; ?></p>
    <p>Pending Reservations: <?php echo $stats['pending_reservations']; ?></p>
    <p>Total Hours (Accepted): <?php echo $stats['total_hours_accepted']; ?></p>
    <p>Total Cost (Accepted): <?php echo $stats['total_cost_accepted']; ?></p>
    <p>Total Remaining Payment: <?php echo $stats['total_remaining_payment']; ?></p>
    
</div>





            <!-- Add a button to update the profile and navigate to updateprofile.php -->
            <a href="updateprofile.php"><button>Update Your Profile</button></a>

            <!-- Add a button to navigate to reservation.php -->
            <a href="reservation.php"><button>Make a Reservation</button></a>

            <!-- Add a button to logout and redirect to login.php -->
            <a class="logout" href="login.php"><button>Logout</button></a>

            <!-- Add a search input field -->
            <label for="search">Search:</label>
            <input type="text" id="search" onkeyup="searchReservations()" placeholder="Enter a keyword...">


            <?php
            // Check if reservations are found
            if ($reservations_result->num_rows > 0) {
                echo "<table>";
                echo "<tr>
                        <th>Reservation ID</th>
                        <th>Reservation Date</th>
                        <th>Reservation Time</th>
                        <th>Room Category</th>
                        <th>Hours Reserved</th>
                        <th>Room Number</th>
                        <th>Reservation Cost</th>
                        <th>Remaining Payment</th>
                        <th>Payment Method</th>
                        <th>Advance Payment</th>
                        <th>Reference Number</th>
                        <th>Status</th>
                        <th>Action</th> <!-- Add a new column for action -->
                      </tr>";

                while ($reservation = $reservations_result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $reservation["reservation_id"] . "</td>";
                    echo "<td>" . $reservation["reservation_date"] . "</td>";
                    echo "<td>" . date("h:i A", strtotime($reservation["reservation_time"])) . "</td>";
                    echo "<td>" . $reservation["room_category"] . "</td>";
                    echo "<td>" . $reservation["hours_reserved"] . "</td>";
                    echo "<td>" . $reservation["room_number"] . "</td>";
                    echo "<td>" . $reservation["reservation_cost"] . "</td>";
                    echo "<td>" . $reservation["remaining_payment"] . "</td>";
                    echo "<td>" . $reservation["payment_method"] . "</td>";
                    echo "<td>" . $reservation["advance_payment"] . "</td>";
                    echo "<td>" . $reservation["reference_number"] . "</td>";
                    echo "<td>" . ($reservation["status"] == 'Accepted' || $reservation["status"] == 'Rejected' ? $reservation["status"] : 'Pending') . "</td>";
                    echo "<td>";

                    // Display the "Cancel" button for pending reservations or where status is null
                    if ($reservation["status"] == 'Pending' || $reservation["status"] === null) {
                        echo '<button class="cancel-btn" onclick="cancelReservation(' . $reservation["reservation_id"] . ')">Cancel</button>';
                    }

                    echo "</td>";
                    echo "</tr>";
                }

                echo "</table>";
            } else {
                echo "No reservations found.";
            }

            ?>
        </div>
    </div>

    <script>
    $(document).ready(function () {
        // Use SweetAlert2 for confirmation
        $("#statusButton").click(function () {
            Swal.fire({
                title: 'Your Statistics',
                html: `<p>Total Reservations: ${<?php echo $stats['total_reservations']; ?>}</p>
                       <p>Accepted Reservations: ${<?php echo $stats['accepted_reservations']; ?>}</p>
                       <p>Rejected Reservations: ${<?php echo $stats['rejected_reservations']; ?>}</p>
                       <p>Pending Reservations: ${<?php echo $stats['pending_reservations']; ?>}</p>
                       <p>Total Hours (Accepted): ${<?php echo $stats['total_hours_accepted']; ?>}</p>
                       <p>Total Cost (Accepted): ${<?php echo $stats['total_cost_accepted']; ?>}</p>
                       <p>Total Remaining Payment: ${<?php echo $stats['total_remaining_payment']; ?>}</p>`,
                showCloseButton: true,
                showConfirmButton: false,
            });
        });
    });
        // Include your existing JavaScript code here

        // Add the showUserStatus function here
        function showUserStatus() {
            var statusDiv = document.getElementById("userStatus");
            if (statusDiv.style.display === "none") {
                statusDiv.style.display = "block";
            } else {
                statusDiv.style.display = "none";
            }
        }


        function cancelReservation(reservationId) {
    // Use SweetAlert for confirmation
    Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, cancel it!"
    }).then((result) => {
        if (result.isConfirmed) {
            // User confirmed, proceed with cancellation
            // Use SweetAlert for password input
            Swal.fire({
                title: 'Enter your password to cancel the reservation:',
                html: '<div style="position: relative;">' +
                    '<input type="password" id="password" class="swal2-input" placeholder="Enter your password">' +
                    '<i class="fas fa-eye" id="togglePasswordVisibility" style="position: absolute; cursor: pointer; top: 10px; right: 10px;"></i>' +
                    '</div>',
                focusConfirm: false,
                preConfirm: () => {
                    return document.getElementById('password').value
                },
                onOpen: () => {
                    document.getElementById('togglePasswordVisibility').addEventListener('click', function () {
                        var passwordInput = document.getElementById('password');
                        var eyeIcon = document.getElementById('togglePasswordVisibility');
                        if (passwordInput.type === "password") {
                            passwordInput.type = "text";
                            eyeIcon.classList.remove('fa-eye');
                            eyeIcon.classList.add('fa-eye-slash');
                        } else {
                            passwordInput.type = "password";
                            eyeIcon.classList.remove('fa-eye-slash');
                            eyeIcon.classList.add('fa-eye');
                        }
                    });
                }
            }).then((result) => {
                if (result.value) {
                    var password = result.value;
                    console.log("Entered password:", password);

                    // Send an AJAX request to the server for password verification
                    var xhr = new XMLHttpRequest();
                    xhr.onreadystatechange = function () {
                        if (this.readyState == 4) {
                            // Log the response from the server
                            console.log("Server response:", this.responseText);

                            if (this.status == 200) {
                                // Display success message using SweetAlert
                                Swal.fire({
                                    title: 'Cancellation Successful!',
                                    text: 'Your reservation has been successfully canceled.',
                                    icon: 'success'
                                }).then(() => {
                                    // Reload the page to update the reservation status
                                    location.reload();
                                });
                            } else {
                                // Display error message using SweetAlert
                                Swal.fire({
                                    title: 'Cancellation Failed!',
                                    text: 'An error occurred while canceling the reservation. Please try again later.',
                                    icon: 'error'
                                });
                            }
                        }
                    };
                    xhr.open("POST", "cancel_reservation.php", true);
                    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                    xhr.send("reservation_id=" + reservationId + "&password=" + password);
                }
            });
        }
    });
}
  // Add the searchReservations function here
  function searchReservations() {
            var input, filter, table, tr, td, i, j, txtValue;
            input = document.getElementById("search");
            filter = input.value.toUpperCase();
            table = document.querySelector("table");
            tr = table.getElementsByTagName("tr");

            // Loop through all table rows
            for (i = 1; i < tr.length; i++) {
                var rowVisible = false;
                td = tr[i].getElementsByTagName("td");

                // Loop through all columns in the current row
                for (j = 0; j < td.length; j++) {
                    if (td[j]) {
                        txtValue = td[j].textContent || td[j].innerText;

                        // Check if the search term is found in the current column
                        if (txtValue.toUpperCase().indexOf(filter) > -1) {
                            rowVisible = true;
                            break; // Break the inner loop if a match is found in any column
                        }
                    }
                }

                // Show or hide the row based on whether a match was found
                if (rowVisible) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }
        var originalTable; // Store the original table
    var minimized = false; // Track the state of details

    function toggleDetails() {
        var button = document.getElementById("toggleButton");
        if (!minimized) {
            minimizeDetails();
            button.innerHTML = "Maximize Details";
        } else {
            maximizeDetails();
            button.innerHTML = "Minimize Details";
        }
        minimized = !minimized;
    }

   // Declare originalTable variable
var originalTable;

function minimizeDetails() {
    var table = document.querySelector("table");

    // Store the original table if not already stored
    if (!originalTable) {
        originalTable = table.innerHTML;
    }

    // Set a new header with only specific columns
    var newHeader = `
        <th>Reservation ID</th>
        <th>Reservation Date</th>
        <th>Reservation Time</th>
        <th>Room Category</th>
        <th>Hours Reserved</th>
        <th>Room Number</th>
        <th>Remaining Payment</th>
        <th>Status</th>
        <th>Action</th>
    `;

    // Set a new body with only specific columns
    var newBody = "";
    var rows = table.querySelectorAll("tr");
    
    rows.forEach(function (row) {
        var cells = row.querySelectorAll("td");
        newBody += "<tr>";
        cells.forEach(function (cell, i) {
            if ([0, 1, 2, 3, 4, 5, 7, 11, 12].includes(i)) {
                newBody += "<td>" + cell.innerHTML + "</td>";
            }
        });
        newBody += "</tr>";
    });

    table.innerHTML = "<tr>" + newHeader + "</tr>" + newBody;
}


    function maximizeDetails() {
        var table = document.querySelector("table");

        // Restore the original table
        if (originalTable) {
            table.innerHTML = originalTable;
        }
    }
    </script>

    <footer style="position: fixed; bottom: 3%; left: 50%; transform: translateX(-50%); width: 60%; text-align: center; color: black; font-family: 'Montserrat';">
<hr><br>
    &copy; 2023 Nase Hotel. All Rights Reserved.
</footer>
</body>
<script src="https://kit.fontawesome.com/fb68db4d3c.js" crossorigin="anonymous"></script>

</html>

<?php
// Close the database connection
$conn->close();
?>
