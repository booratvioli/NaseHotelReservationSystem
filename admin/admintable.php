<?php
session_start();

require_once('C:\xampp\htdocs\NASE HOTEL\db_config.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';



function sendConfirmationEmail($con, $reservation_id, $action) {
    $query = "SELECT * FROM reservations WHERE reservation_id='$reservation_id'";
    $result = mysqli_query($con, $query);

    if ($result) {
        $row = mysqli_fetch_assoc($result);

        if ($row) {
            $clientEmail = $row['emailaddress'];

            $mail = new PHPMailer(true);

            try {
                // Server settings
                $mail->SMTPDebug = 0;  // Set to 2 for detailed debugging
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'nasehotel@gmail.com';  // Your Gmail email address
                $mail->Password   = 'yotb ghwi orqs npzx';  // The app password you generated
                $mail->SMTPSecure = 'tls';
                $mail->Port       = 587;

                $mail->setFrom('nasehotel@gmail.com', 'Nase Hotel');
                $mail->addAddress($clientEmail);

                $mail->isHTML(true);

                if ($action === 'Accepted') {
                    $mail->Subject = 'Reservation Accepted';
                    $mail->Body = "
                    <html>
                    <head>
                        <title>Reservation Accepted - Nase Hotel</title>
                        <style>
                            body {
                                font-family: Arial, sans-serif;
                                color: #333;
                            }
                            .container {
                                width: 80%;
                                margin: auto;
                                padding: 20px;
                                border: 1px solid #ddd;
                                border-radius: 5px;
                                background-color: #f9f9f9;
                            }
                            h2 {
                                color: #444;
                            }
                            p {
                                line-height: 1.6;
                            }
                        </style>
                    </head>
                    <body>
                        <div class='container'>
                            <img src='https://i.imgur.com/LrNesCy.png' alt='Nase Hotel' style='width: 50%; height: auto;' />
                            <h2>Reservation Accepted</h2>
                            <p>Dear User,</p>
                            <p>Your reservation request has been accepted. Here are the details:</p>
                            <p>Reservation Number: " . $row['reservation_id'] . "<br>
                            Reservation Date: " . $row['reservation_date'] . "<br>
                            Room Category: " . $row['room_category'] . "<br>
                            Hours Reserved: " . $row['hours_reserved'] . "</p>
                            <p>Thank you for choosing Nase Hotel!</p>
                            <p>Best regards,<br>Nase Hotel</p>
                        </div>
                    </body>
                    </html>";
                } elseif ($action === 'Rejected') {
                    $mail->Subject = 'Reservation Rejected';
                    $mail->Body = "
                    <html>
                    <head>
                        <title>Reservation Rejected - Nase Hotel</title>
                        <style>
                            body {
                                font-family: Arial, sans-serif;
                                color: #333;
                            }
                            .container {
                                width: 80%;
                                margin: auto;
                                padding: 20px;
                                border: 1px solid #ddd;
                                border-radius: 5px;
                                background-color: #f9f9f9;
                            }
                            h2 {
                                color: #444;
                            }
                            p {
                                line-height: 1.6;
                            }
                        </style>
                    </head>
                    <body>
                        <div class='container'>
                            <img src='https://i.imgur.com/KmbtY1r.png' alt='Nase Hotel' style='width: 50%; height: auto;' />
                            <h2>Reservation Rejected</h2>
                            <p>Dear User,</p>
                            <p>Your reservation request has been rejected. We apologize for any inconvenience caused. Here are the details of your reservation:</p>
                            <p>Reservation Number: " . $row['reservation_id'] . "<br>
                            Reservation Date: " . $row['reservation_date'] . "<br>
                            Room Category: " . $row['room_category'] . "<br>
                            Hours Reserved: " . $row['hours_reserved'] . "</p>
                            <p>Thank you for considering Nase Hotel.</p>
                            <p>Best regards,<br>Nase Hotel</p>
                        </div>
                    </body>
                    </html>";
                }
                
                $mail->send();

                echo "";
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        } else {
            echo "Invalid or empty email address retrieved from the database.";
        }
    } else {
        echo "Error executing database query: " . mysqli_error($con);
    }
}


function updateReservationStatus($con, $reservation_id, $action) {
    $updateQuery = "UPDATE reservations SET status='$action' WHERE reservation_id='$reservation_id'";
    mysqli_query($con, $updateQuery);

    // Send confirmation email for both Accepted and Rejected statuses
    sendConfirmationEmail($con, $reservation_id, $action);
}

$con = mysqli_connect("localhost", "root", "", "nasehotel");

if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && (isset($_POST["accept"]) || isset($_POST["reject"]))) {
    $reservation_id = $_POST["reservation_id"];
    $action = isset($_POST["accept"]) ? "Accepted" : "Rejected";

    updateReservationStatus($con, $reservation_id, $action);
     // SweetAlert trigger for success
     echo "<script>
     Swal.fire({
         icon: 'success',
         title: 'Success',
         text: 'Reservation $action successfully!',
     });
   </script>";
}

$query = "SELECT * FROM reservations";
$result = mysqli_query($con, $query);

if (!$result) {
echo "Error: " . mysqli_error($con);
exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">

    <!-- Include SweetAlert script -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat">
    <title>Admin Table</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Montserrat', sans-serif;
        }

        body {
    font-family: 'Montserrat';
    margin-left: 15%;
    background-image: url('bg.jpg');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    overflow: auto; /* Allow scrolling */
}

:root {
    --overlay-color: black;
    --overlay-opacity: 0.5;
}

body::before {
    content: "";
    position: fixed; /* Changed from absolute to fixed */
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: var(--overlay-color); /* Color of the overlay */
    opacity: var(--overlay-opacity); /* Adjust the opacity as needed */
}


        .container {
            max-width: 90%;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            z-index: 1;
            position: relative;
        }

        

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }

        td button {
            padding: 8px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
        }

        

        td button.accept {
            background-color: #4CAF50;
            color: #fff;
        }

        td button.red {
            background-color: red;
            color: #fff;
        }

        td button:disabled {
            cursor: not-allowed;
            opacity: 0.5;
        }

        td a {
            padding: 8px;
            background-color: #3498db;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
        }

        /* CSS */
        .sidebar {
            width: 250px;
            height: 100vh;
            background: #333;
            color: #fff;
            position: fixed;
            left: 0;
            top: 0;
            transition: all 0.5s ease;
        }

        .sidebar.active {
            width: 60px;
        }

        .sidebar .logo-details {
            height: 60px;
            display: flex;
            align-items: center;
        }

        .sidebar .logo-details i {
            font-size: 28px;
            font-weight: 500;
        }

        

        .sidebar .logo {
            padding: 20px;
            background: #444;
            text-align: center;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar ul li {
            padding: 15px;
            border-bottom: 1px solid #444;
            transition: all 0.5s ease;
        }

        .sidebar ul li a {
            color: #fff;
            text-decoration: none;
        }

        .sidebar ul li:hover {
            background: #444;
        }

        .sidebar ul li .fa-solid {
            margin-right: 10px;
        }
        .menu-item {
            animation: slideIn 0.5s forwards;
            opacity: 0;
        }

        @keyframes slideIn {
            from {
                transform: translateX(-20px);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        .logo img {
            align-self: center;
            width: 60px;
        }

        ul {
            height: 88%;
            position: relative;
            list-style: none;
            padding: 0;
        }

        ul li {
            padding: 1.5rem;
            margin: 20px 0;
            border-radius: 8px;
            transition: all 0.5s ease-in-out;
        }

        ul li a {
            color: white;
            font-size: 14px;
            text-decoration: none;
            gap: 1.5rem;
            display: flex;
            color: black;
            align-items: center;
        }

        ul a span {
            overflow: hidden;
        }

        ul a i {
            font-size: 1.2rem;
            color: black;
        }

        ul li:hover {
            background: lightgray;
        }

        .logout-button {
            position: fixed; /* Fixed/sticky position */
            top: 20px; /* Place the button at the top */
            right: 20px; /* Place the button 20px from the right */
            z-index: 99; /* Make sure it does not overlap other items */
        }

        section {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .mainContent {
            position: relative;
            width: 90%;
            margin-top: 10px;
            padding: 1rem;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }

        .headerWrapper img {
            width: 50px;
            height: 50px;
            border-radius: 50px;
        }

        .headerWrapper {
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            border-radius: 10%;
            padding: 10px 2rem;
        }

/* CSS for larger screens */
@media (min-width: 1200px) {
    .container {
        max-width: 1200px;
    }
}

/* CSS for medium screens */
@media (min-width: 992px) and (max-width: 1199px) {
    .container {
        max-width: 960px;
    }
}

/* CSS for small screens */
@media (min-width: 768px) and (max-width: 991px) {
    .container {
        max-width: 720px;
    }
}

/* CSS for extra small screens */
@media (max-width: 767px) {
    .container {
        max-width: 540px;
    }
}

img[src="logo.png"] {
    filter: invert(1);
}
       
    </style>
<body>
<div class="sidebar">
        <div class="logo">
            <img src="logo.png" alt="Logo">
            <h3>Nase Hotel</h3>
        </div>
        <ul>
            <li>
                <a href="admintable.php" class="menu-item">
                    
                    <span>Reservation</span>
                </a>
            </li>
            <li>
                <a href="room.php" class="menu-item">
                    
                    <span>Room Availability</span>
                </a>
            </li>


            <li>
                <a href="summaryreport.php" class="menu-item">
                    
                    <span>Summary and reports</span>
                </a>
            </li>

            <li>
                <a href="ourteam.php" class="menu-item">
                    
                    <span>Our Team</span>
                </a>
            </li>
            <li class="logout">
                <a href="adminlogin.php" class="menu-item">
                    
                    <span>Logout</span>
                </a>
            </li>
        </ul>
    </div>
    <style>
        .custom-container {
            max-width: 80%; /* Adjust this value to your liking */
            margin: auto;
        }
    </style>


    <script>
        
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('searchInput');
    const reservationRows = document.querySelectorAll('.reservation-row');

    searchInput.addEventListener('input', function () {
        const searchTerm = searchInput.value.toLowerCase();

        reservationRows.forEach(function (row) {
            const rowData = row.innerText.toLowerCase();
            row.style.display = rowData.includes(searchTerm) ? '' : 'none';
        });
    });
});
</script>


    <script src="https://kit.fontawesome.com/fb68db4d3c.js" crossorigin="anonymous"></script>
    <script>
    <?php
    // Assuming $action holds the action type (Accepted or Rejected)
    $message = ($action === 'Accepted') ? 'Reservation Accepted!' : 'Reservation Rejected!';
    ?>

    // SweetAlert trigger with dynamic message
    Swal.fire({
        icon: 'success',
        title: 'Success',
        text: '<?php echo $message; ?>',
    });
</script>

    <section>
        <div class="mainContent" style="width: 90%; margin: auto; margin-left: 20px; margin-right: 20px;">
            <div class="headerWrapper">
                <div class="headerTitle">
                    <span>Admin</span>
                    <h2>Reservation Reports</h2>
                </div>
                <div class="searchBox">
                    <div class="userInfo">
                    <input type="text" id="searchInput" placeholder="Search">
                        <img src="admin.jpg" alt="ADMIN PIC">
                    </div>
                </div>
            </div>
            <div class="filter-buttons">
                <form method="post">
                <button type="submit" name="status" value="All" class="filter-button all">All</button>

                    <button type="submit" name="status" value="Accepted" class="filter-button accepted">Accepted</button>
                    <button type="submit" name="status" value="Pending" class="filter-button pending">Pending</button>

                    <button type="submit" name="status" value="Rejected" class="filter-button rejected">Rejected</button>
                </form>
            </div>

            <style>
            .filter-buttons {
                display: flex;
                justify-content: space-around;
                margin-bottom: 20px;
            }

            .filter-button {
                border: none;
                color: white;
                padding: 10px 20px;
                text-align: center;
                text-decoration: none;
                display: inline-block;
                font-size: 16px;
                margin: 4px 2px;
                cursor: pointer;
                border-radius: 12px;
                transition: background-color 0.3s ease;
            }

            .filter-button:hover {
                opacity: 0.8;
            }

            .accepted {
                background-color: #4CAF50; /* Green */
            }

            .rejected {
                background-color: #f44336; /* Red */
            }

            .pending {
                background-color: #ff9800; /* Orange */
            }

            .all {
                background-color: #008CBA; /* Blue */
            }
            </style>

<!-- Then in your PHP code where you fetch the reservations -->
<?php
$status = $_POST['status'] ?? 'All';

$query = "SELECT * FROM reservations";
if ($status !== 'All') {
    $query .= " WHERE status = '$status'";
}
$query .= " ORDER BY reservation_id ASC"; // Order by reservation_id from oldest to latest

$result = mysqli_query($con, $query);
// Continue with your code to display the reservations
?>
<style>
    .container {
        font-size: 0.6em; /* Adjust this value to change the font size */
    }
    .container table {
        width: 100%; /* This will make the table width equal to its container's width */
        overflow-x: auto; /* This will add a horizontal scrollbar if the content exceeds the width */
        display: block; /* This is necessary for overflow to work */
    }
</style>

    <div class="container">
       
        <table>
            <tr>
                <th>User ID</th>
                <th>Reservation Number</th>
                <th>Reservation Date</th>
                <th>Room Category</th>
                <th>Hours Reserved</th>
                <th>Room Number</th>

                <th>Reservation Cost</th>
                <th>Payment Method</th>
                <th>Advance Payment</th>
                <th>Reference Number</th>
                <th>Remaining Payment</th>
                <th>Status</th>
                <th>Action</th>
            </tr>


        <?php
        while ($row = mysqli_fetch_assoc($result)) {
            ?>
             <tr class="reservation-row">
                <td><?php echo $row['user_id']; ?></td>
                <td><?php echo $row['reservation_id']; ?></td>
                <td><?php echo $row['reservation_date']; ?></td>
                <td><?php echo $row['room_category']; ?></td>
                <td><?php echo $row['hours_reserved']; ?></td>
                <td><?php echo $row['room_number']; ?></td>

                <td><?php echo $row['reservation_cost']; ?></td>
                <td><?php echo $row['payment_method']; ?></td>
                <td><?php echo $row['advance_payment']; ?></td>
                <td><?php echo $row['reference_number']; ?></td>
                <td><?php echo $row['remaining_payment']; ?></td>
                <td><?php echo $row['status']; ?></td>
                <td>
                    <form method="POST" action="">
                        <input type="hidden" name="reservation_id" value="<?php echo $row['reservation_id']; ?>">
                        <button class="accept" name="accept" type="submit" <?php echo isActionAlreadyPerformed($con, $row['reservation_id'], "Accepted") ? "disabled" : ""; ?>>Accept</button>
                        <button class="red" name="reject" type="submit" <?php echo isActionAlreadyPerformed($con, $row['reservation_id'], "Rejected") ? "disabled" : ""; ?>>Reject</button>
                    </form>
                </td>
            </tr>
            <?php
        }

        mysqli_close($con);

        function isActionAlreadyPerformed($con, $reservation_id, $action) {
            if (isset($_SESSION["action_performed"][$reservation_id][$action]) && $_SESSION["action_performed"][$reservation_id][$action]) {
                return true;
            }

            $query = "SELECT status FROM reservations WHERE reservation_id = '$reservation_id'";
            $result = mysqli_query($con, $query);

            if ($result && $row = mysqli_fetch_assoc($result)) {
                return $row['status'] === $action;
            }

            return false;
        }

            ?>
        </table>
    </div>


    
</body>
</html>