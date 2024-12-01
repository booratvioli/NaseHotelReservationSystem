
<?php
    // reservation.php - Reservation page


    session_start();
    require_once('C:\xampp\htdocs\Nase Hotel\db_config.php');
    include 'C:\xampp\htdocs\Nase Hotel\homepages\faqsbot.php';

    require 'vendor/autoload.php';

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    // Check if the user is logged in
    if (!isset($_SESSION["user_id"])) {
        header("Location: login.php");
        exit();
    }

    $user_id = $_SESSION["user_id"];
    $reservation_message = ""; // Initialize reservation message

    // Handle reservation form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Additional fields
        $reservation_date = $_POST["reservation_date"]; // Assuming today's date for the reservation_date
        $reservation_time = $_POST["reservation_time"];

        $room_category = $_POST["room_category"];
        $hours_reserved = $_POST["hours_reserved"];

        // Determine reservation cost based on room category and hours reserved
        $reservation_cost = calculateReservationCost($room_category, $hours_reserved);

        // Get selected room number
        $room_number = $_POST["room_number"];

        $payment_method = $_POST["payment_method"];
        $advance_payment = $_POST["advance_payment"];
        $reference_number = $_POST["reference_number"];
        $emailaddress = $_POST["emailaddress"];

        // Check if the selected room number has a status of "Accepted" or "Pending"
        $check_status_query = $conn->prepare("SELECT status FROM reservations WHERE room_number = ? AND (status = 'Accepted' OR status = 'Pending')");
        $check_status_query->bind_param("i", $room_number);
        $check_status_query->execute();
        $check_status_result = $check_status_query->get_result();

        if ($check_status_result->num_rows > 0) {
            $status_result = $check_status_result->fetch_assoc();
            $reservation_status = $status_result['status'];

            if ($reservation_status == 'Pending') {
                echo '<script type="text/javascript">';
echo 'setTimeout(function () { swal("Reservation Error","This room is currently pending.","error");';
echo '}, 1000);</script>';
            } elseif ($reservation_status == 'Accepted') {
                echo '<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>';
                echo '<script type="text/javascript">';
                echo 'setTimeout(function () { swal("Reservation Error","This room has already been reserved.","error");';
                echo '}, 1000);</script>';
            }

            // Close the check status result set
            $check_status_result->close();
        } else {
            // Sanitize user input
            $reservation_cost = mysqli_real_escape_string($conn, $reservation_cost);
            $advance_payment = mysqli_real_escape_string($conn, $advance_payment);

            // Use prepared statement to prevent SQL injection
            $insert_reservation_query = $conn->prepare("INSERT INTO reservations (user_id, reservation_date, reservation_time, room_category, hours_reserved, room_number, reservation_cost, payment_method, advance_payment, remaining_payment, reference_number, emailaddress, status) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Pending')");
            $insert_reservation_query->bind_param("isssisssisss", $user_id, $reservation_date, $reservation_time, $room_category, $hours_reserved, $room_number, $reservation_cost, $payment_method, $advance_payment, $remaining_payment, $reference_number, $emailaddress);

            // Calculate remaining payment
            $remaining_payment = $reservation_cost - $advance_payment;

            // Set remaining_payment to 0 if it's negative
            $remaining_payment = max($remaining_payment, 0);

            // Set the remaining_payment value
            $insert_reservation_query->bind_param("isssisssisss", $user_id, $reservation_date, $reservation_time, $room_category, $hours_reserved, $room_number, $reservation_cost, $payment_method, $advance_payment, $remaining_payment, $reference_number, $emailaddress);

            if ($insert_reservation_query->execute()) {
                $reservation_id = $conn->insert_id;


                echo '<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>';
                echo '<script type="text/javascript">';
                echo 'setTimeout(function () { ';
                echo 'swal({';
                echo 'title: "Reservation Successful",';
                echo 'text: "Your receipt has been sent and is pending confirmation from the hotel administration. Please await further details.",';
                echo 'icon: "success"';
                echo '}).then(function() {';
                echo 'swal({';
                echo 'title: "What do you want to do next?",';
                echo 'text: "Do you want to reserve again or go to your profile?",';
                echo 'icon: "info",';
                echo 'buttons: {';
                echo 'cancel: "Go to profile",';
                echo 'confirm: "Reserve again"';
                echo '}';
                echo '}).then(function(isConfirm) {';
                echo 'if (isConfirm) {';
                echo 'window.location.href = "reservation.php";'; // replace with your reservation page URL
                echo '} else {';
                echo 'window.location.href = "customerprofile.php";'; // replace with your profile page URL
                echo '}';
                echo '});';
                echo '});';
                echo '}, 1000);</script>';

                // Create a new instance of PHPMailer
                
                $mail = new PHPMailer(true);

                try {
                    // Server settings
                    $mail->SMTPDebug = 0;  // Set to 2 for detailed debugging
                    $mail->isSMTP();
                    $mail->Host       = 'smtp.gmail.com';
                    $mail->SMTPAuth   = true;
                    $mail->Username   = 'itstest14342@gmail.com';  // Your Gmail email address
                    $mail->Password   = 'app password';  // The app password you generated
                    $mail->SMTPSecure = 'tls';
                    $mail->Port       = 587;

                    // Recipients
                    $mail->setFrom('itstest14342@gmail.com', 'Nase Hotel');
                    $mail->addAddress($_POST['emailaddress'], $_POST['emailaddress']);

                // ...

    // Content
    $mail->isHTML(true);
    $mail->Subject = 'Reservation Receipt';

    $mail->Body = '
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Reservation Receipt</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f4f4f4;
                margin: 0;
                padding: 20px;
            }
            .container {
                max-width: 600px;
                margin: 0 auto;
                background-color: #ffffff;
                padding: 20px;
                border-radius: 8px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            }
            h1 {
                color: #333;
                text-align: center;
            }
            p {
                margin: 10px 0;
                color: #555;
            }
            strong {
                color: #333;
            }
            .logo {
                display: block;
                margin: 0 auto;
                width: 200px;
                height: auto;
            }
            .footer {
                text-align: center;
                margin-top: 20px;
                color: #888;
                font-size: 0.8em;
            }
            table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 20px;
            }
            th, td {
                border: 1px solid #ddd;
                padding: 8px;
                text-align: left;
            }
            th {
                background-color: #f2f2f2;
            }
        </style>
    </head>
    <body>
    <div class="container">
        <img class="logo" src="https://i.imgur.com/LrNesCy.png" alt="Your Logo">

        <h1>Reservation Receipt</h1>
        <table>
            <tr><th>Reservation ID</th><td>' . htmlspecialchars($reservation_id) . '</td></tr>
            <tr><th>Room Category</th><td>' . htmlspecialchars($room_category) . '</td></tr>
            <tr><th>Reservation Date</th><td>' . htmlspecialchars($reservation_date) . '</td></tr>
            <tr><th>Reservation Time</th><td>' . htmlspecialchars($reservation_time) . '</td></tr>
            <tr><th>Hours Reserved</th><td>' . htmlspecialchars($hours_reserved) . '</td></tr>
            <tr><th>Room Number</th><td>' . htmlspecialchars($room_number) . '</td></tr>
            <tr><th>Payment Method</th><td>' . htmlspecialchars($payment_method) . '</td></tr>
            <tr><th>Advance Payment</th><td>₱' . htmlspecialchars($advance_payment) . '</td></tr>
            <tr><th>Remaining Payment</th><td>₱' . htmlspecialchars($remaining_payment) . '</td></tr>
            <tr><th>Reference Number</th><td>' . htmlspecialchars($reference_number) . '</td></tr>
            <tr><th>Email Address</th><td>' . htmlspecialchars($emailaddress) . '</td></tr>
        </table>
        <p><strong>Additional Message:</strong> Your receipt has been sent and is pending confirmation from the hotel administration. Please await further details.</p>

        <p class="footer">Thank you for choosing our hotel. We look forward to serving you.</p>
    </div>
</body>
</html>
    ';

    // ...

                    // Send the email
                    $mail->send();
                    
                } catch (Exception $e) {
                    ;
                }
            }

            // Close the prepared statement
            $insert_reservation_query->close();
        }

        // Close the check status query
        $check_status_query->close();
    }

    // Function to calculate reservation cost based on room category and hours reserved
    function calculateReservationCost($room_category, $hours_reserved)
    {
        // Define costs based on room category and hours reserved
        $costs = [
            "Economy" => [1 => 150, 3 => 350, 6 => 550, 12 => 1200, 24 => 1700],
            "Deluxe" => [1 => 250, 3 => 450, 6 => 850, 12 => 1350, 24 => 2550],
            "Premium" => [1 => 350, 3 => 650, 6 => 1250, 12 => 2350, 24 => 4000]
        ];

        // Validate input
        if (array_key_exists($room_category, $costs) && array_key_exists($hours_reserved, $costs[$room_category])) {
            return $costs[$room_category][$hours_reserved];
        } else {
            return 0; // Invalid input, return 0
        }
    }

    // Display room numbers based on room category
    switch ($_POST["room_category"] ?? '') {
        case "Economy":
            $room_numbers = range(1, 15);
            break;
        case "Deluxe":
            $room_numbers = range(16, 25);
            break;
        case "Premium":
            $room_numbers = range(26, 33);
            break;
    }
    ?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="styles.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat">
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <title>Reservation Page</title>
        <!-- Add your styles or include external stylesheets here -->
        <style>
        /* ... (your existing styles) */

        body {
            background-image: url('bg.jpg');
            background-repeat: no-repeat;
            background-size: cover;
            background-attachment: fixed;
            background-position: center;
            font-family: 'Montserrat', sans-serif;
        }

        .popup {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .popup-content {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background-color: #fff;
    padding: 20px;
    max-width: 600px;
    max-height: 100%;
    overflow-y: auto;
}

.popup-content .close {
    font-size: 30px;
    color: red;
    border: 2px solid red;
    border-radius: 50%;
    padding: 5px;
    cursor: pointer;
    display: inline-block;
    text-align: center;
    line-height: 1;
    position: absolute;
    top: 10px;
    right: 10px;
}

        #reservation-notification {
        position: fixed;
        top: 50%;
        left: 60%;
        transform: translate(-50%, -50%);
        width: 300px;
        padding: 10px;
        background-color: #4CAF50;
        color: #fff;
        z-index: 1000;
        display: none;
    }

    /* Style for the notification content */
    .notification-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    /* Style for the close button */
    .close-btn {
        cursor: pointer;
        font-size: 20px;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translate(-50%, -60%);
        }
        to {
            opacity: 1;
            transform: translate(-50%, -50%);
        }
    }

    @keyframes slideOut {
        from {
            opacity: 1;
            transform: translate(-50%, -50%);
        }
        to {
            opacity: 0;
            transform: translate(-50%, -60%);
        }
    }
    .nav-link.active {
    color: white !important;
    background-color: #86654B !important;
}

.section {
        border: 1px solid #ddd;
        padding: 20px;
        margin-bottom: 20px;
        border-radius: 5px;
        background-color: #f9f9f9;
        height: 100%;
    }

    .section h3 {
        color: #86654B;
    }

    .btn {
        display: inline-block;
        font-weight: 400;
        color: #FFFFFF;
        text-align: center;
        vertical-align: middle;
        cursor: pointer;
        background-color: #86654B;
        border: 1px solid transparent;
        padding: .375rem .75rem;
        font-size: 1rem;
        line-height: 1.5;
        border-radius: .25rem;
        transition: color .15s ease-in-out, background-color .15s ease-in-out, border-color .15s ease-in-out, box-shadow .15s ease-in-out;
    }

    .btn:hover {
        color: #fff;
        background-color: #6d5138;
        border-color: #6d5138;
    }

    button {
        background-color: #4CAF50; /* Green */
        border: none;
        color: white;
        padding: 15px 32px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 16px;
        margin: 4px 2px;
        cursor: pointer;
        transition-duration: 0.4s;
    }

    button:hover {
        background-color: #45a049;
    }
    .container {
        max-width: 80%;
        max-height: 100vh; /* Adjust as needed */
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
            .container label,
            .container  input,
            .container select {
                border: none;
                padding: 7px;
                display: block;
                width: 100%;
                margin-bottom: 5px;
                border-bottom: 1px solid grey;
                background-color: #f9f9f9;
            }
            
            .container label{
                border: none;
            }
            .label{
                display: flex;
                align-items: center;
            }
            .label input{
                margin-top: -2%;
                width: 5%;
                margin-left: 2%;
                height: 25px;
            }

            .payment{
                border: none;
            }
            .message{
                margin-top: 5px;
                color: green;
                font-weight: bolder;
            }
</style>    


</head>
<body>
    <form action="reservation.php" method="post" onsubmit="return validateReservation()">
        <div class="container">
            <div class="card">
                <div class="card-header text-center">
                    <h2 style="font-size: 40px;"><b>MAKE A RESERVATION</b></h2>
                </div>

                <div class="card-body">
                    <div class="row">
                        <!-- Reservation Details -->
                        <div class="col-md-6">
                            <div class="section">
                            
                            
                            <h3><b>ROOM DETAILS</b></h3>
                                <p class="room-cat"><label for="room_category"><b>Room Category:</b></label>
                                    <select id="room_category" name="room_category" required onchange="updateRoomNumber()">
                                        <option value="Economy">Economy</option>
                                        <option value="Deluxe">Deluxe</option>
                                        <option value="Premium">Premium</option>
                                    </select>
                                    
                                    <button type="button" class="btn" onclick="showRoomCategoryInfo()">Room Category Informations</button>
                                    
                                    
                                    <br>
                                    <label for="reservation_date"><b>Reservation Date:</b></label>
                                    <input type="date" id="reservation_date" name="reservation_date" required><br>


                                    <label for="reservation_time"><b>Reservation Time:</b></label>
                                    <input type="time" id="reservation_time" name="reservation_time" required><br>

                                   
                                  
                                    
                                    <label for="hours_reserved"><b>Hours Reserved:</b></label>
                                    <select id="hours_reserved" name="hours_reserved" required>
                                        <option value="1">1 hour</option>
                                        <option value="3">3 hours</option>
                                        <option value="6">6 hours</option>
                                        <option value="12">12 hours</option>
                                        <option value="24">24 hours</option>
                                    </select><br>
                                    
                                    <label for="room_number"><b>Room Number:</b></label>
                                    <select id="room_number" name="room_number" required>
                                        <?php
                                        foreach ($room_numbers as $room) {
                                            echo "<option value='$room'>$room</option>";
                                        }
                                        ?>
                                    </select>
                                    
                                    <button type="button" class="btn" id="room_search_button">Room Search</button>
                            
                                    <h3><b>PERSONAL DETAILS</b></h3>
                                <p><label for="emailaddress"><b>Email Address:</b></label>
                                    <input type="text" id="emailaddress" name="emailaddress" required><br>
                            
              
                                </div>
                        </div>
                        

                        <!-- Payment and Personal Details -->
                        <div class="col-md-6">
                            <div class="section">
                            <h3><b>PAYMENT DETAILS</b></h3>
                                <p><label for="payment_method">Payment Method:</label>
                                    <select class="payment" id="payment_method" name="payment_method" required>
                                        <option value="Gcash">Gcash</option>

                                    </select><br>
                            
                                    <span><b>Send your Payment in this Number:</b></span>
                            <br>
                            <span>0961 214 8397 - Jazz Nase</span>
                            <br>
                            
                                    <label for="advance_payment"><b>Advance Payment:</b></label>
                                    <input type="text" id="advance_payment" name="advance_payment" readonly>
                                    
                                    <button type="button" class="btn" onclick="togglePayment()" id="payment_button">Full Advance Payment</button>                                 
                            
                                    <br>
                                    <label for="reservation_cost"><b>Reservation Cost:</b></label>
                                    <input type="text" id="reservation_cost" name="reservation_cost" value="" readonly><br>
                                        
                                    <label for="remaining_payment"><b>Remaining Payment:</b></label>
                                <input type="text" id="remaining_payment" name="remaining_payment" readonly><br>
                            
                                    <label for="reference_number"><b>Reference Number:</b></label>
                                    <input type="text" id="reference_number" name="reference_number" required><br></p>
                           
                                    <div class="label">
                                    <p class="agree">I agree to the terms</p>
                            <input class = "checkbox" type="checkbox" id="terms_agreement" name="terms_agreement" required>
                            </div>
                            
                        <a href="#" id="showTerms">Terms and Conditions</a><br>
                        <button type="submit" class="btn">Submit Reservation</button>        
                        <p class="message"><?php echo $reservation_message; ?></p>
                        </div>
                        </div>
                        </div>

                        <a href="customerprofile.php" class="btn" style="margin-top: 10px;">Back to Customer Profile</a>
                </div>
            </div>
        </div>
    </form>
</body>
    <!-- Display feedback to the user -->


    <div id="reservation-notification" class="hidden">
    <div class="notification-content">
        <span id="notification-message"></span>
        <span class="close-btn" onclick="closeNotification()">×</span>
    </div>
</div>  

    <div id="roomSearchModal" class="popup">
    <div class="popup-content">
        <span class="close" onclick="closeRoomSearchModal()">&times;</span>
        <div id="roomSearchResult"></div>
    </div>
</div>



    <div id="receiptPopup" class="popup">
    <div class="popup-content">
        <span class="close" onclick="closeReceiptPopup()">&times;</span>
        <div id="receiptContent">
            <h2>Receipt</h2>
            <p><strong>Room Category:</strong> <span id="receiptRoomCategory"></span></p>
            <p><strong>Reservation Date:</strong> <span id="receiptReservationDate"></span></p>
            <p><strong>Reservation Time:</strong> <span id="receiptReservationTime"></span></p>
            <p><strong>Hours Reserved:</strong> <span id="receiptHoursReserved"></span></p>
            <p><strong>Room Number:</strong> <span id="receiptRoomNumber"></span></p>
            <p><strong>Payment Method:</strong> <span id="receiptPaymentMethod"></span></p>
            <p><strong>Advance Payment:</strong> ₱' . htmlspecialchars($advance_payment) . '</p>
            <p><strong>Remaining Payment:</strong> ₱' . htmlspecialchars($remaining_payment) . '</p>
            <p><strong>Reference Number:</strong> <span id="receiptReferenceNumber"></span></p>
            <p><strong>Email Address:</strong> <span id="receiptEmailAddress"></span></p>
        </div>
    </div>
</div>
<div id="receiptPopup" class="popup">
    <div class="popup-content">
        <span class="close" onclick="closeReceiptPopup()">&times;</span>
        <div id="receiptContent"></div>
    </div>
</div>

<!-- Add this element in your HTML body -->
<div id="room_search_result"></div>

    <div id="roomInfoModal" class="popup">
        <div class="popup-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <div id="roomInfoContent"></div>
        </div>
    </div>


    <div id="termsPopup" class="popup">
        <div class="popup-content">
            <span class="close" onclick="closePopup()">&times;</span>
            <h1><b>TERMS AND CONDITIONS</b></h1>
                        
            <ul class="terms">
                <li><b>Reservation and Payment:</b></li>
                <li><b>Reservation Confirmation:</b> All reservations are subject to availability and confirmation by Nase Hotel.</li>
                <li><b>Cancellation Policy:</b> Guests are required to verify their reservations within 24 hours. Failure to do so will result in automatic cancellation of the reservation, and the downpayment will not be refunded.</li>
                <li><b>Payment:</b> Payment must be made in full at the time of booking, unless otherwise specified. Accepted forms of payment include [list accepted payment methods].</li>
                <li><b>Reservation:</b> If all rooms are fully booked, guests will have to wait. This will be reflected in real-time in the room options, where only available rooms will be displayed. Guests will be updated via email if rooms become available.</li>
                <li><b>Check-in and Check-out:</b></li>
                <li><b>Check-in:</b> Check-in time is [insert time], and guests are required to present a valid government-issued photo ID and a credit card for incidentals.</li>
                <li><b>Check-out:</b> Check-out time is [insert time]. Late check-out requests are subject to availability and may incur additional charges.</li>
                <li><b>Guest Responsibilities:</b></li>
                <li><b>Behavior:</b> Guests are expected to conduct themselves in a respectful and responsible manner, refraining from any disruptive or illegal activities on hotel premises.</li>
                <li><b>Damages:</b> Guests will be held responsible for any damages caused to the hotel property, including but not limited to rooms, furnishings, and amenities.</li>
                <li><b>Amenities and Services:</b></li>
                <li><b>Services:</b> Nase Hotel reserves the right to alter or discontinue any services or amenities without prior notice.</li>
                <li><b>Use of Facilities:</b> Guests are expected to use hotel facilities and amenities in a safe and responsible manner.</li>
                <li><b>Liability and Security:</b></li>
                <li><b>Security:</b> The hotel is not responsible for the loss or theft of personal belongings. Guests are advised to use the provided safety deposit boxes.</li>
                <li><b>Liability:</b> Nase Hotel is not liable for any injuries, accidents, or damages that may occur on hotel premises, including those related to the use of hotel facilities.</li>
                <li><b>Miscellaneous:</b></li>
                <li><b>Force Majeure:</b> Nase Hotel is not liable for any failure or delay in performing its obligations due to circumstances beyond its control, including but not limited to natural disasters, strikes, and government actions.</li>
                <li><b>Privacy:</b> The hotel respects guest privacy and handles personal information in accordance with applicable data protection laws.</li>
                <li>By making a reservation with Nase Hotel, guests acknowledge and agree to abide by these terms and conditions.</li>
            </ul>
        </div>
    </div>
    <script>

    
document.querySelector('form').addEventListener('submit', function(event) {
    var email = document.getElementById('emailaddress').value;
    var emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;

    if (!emailRegex.test(email)) {
        event.preventDefault();
        swal("Error", "Please enter a valid email address", "error");
    }
});

document.getElementById('emailaddress').addEventListener('blur', function() {
    var email = this.value;
    var re = /^[\w-]+(\.[\w-]+)*@([\w-]+\.)+[a-zA-Z]{2,7}$/;
    if (!re.test(email) && email.length > 0) {
        swal("Error", "Please enter a valid email address", "error");
    }
});



// Function to show the room search result modal
function showRoomSearchModal() {
    var roomSearchModal = document.getElementById("roomSearchModal");
    roomSearchModal.style.display = "block";

    // Attach event listener to close the modal when clicking the "x" button
    var closeButton = roomSearchModal.querySelector(".close");
    closeButton.addEventListener("click", function () {
        roomSearchModal.style.display = "none";
    });
}

document.getElementById("room_search_button").addEventListener("click", function () {
    // Get selected room category
    var roomCategory = document.getElementById("room_category").value;

    // Make an AJAX request to search for available rooms
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            // Parse the JSON response
            var result = JSON.parse(xhr.responseText);

            // Display the result or handle errors
            if (result.hasOwnProperty("rooms")) {
                var rooms = result.rooms;
                var availableRooms = rooms.filter(room => room.status !== "Rejected");
                var rejectedRooms = rooms.filter(room => room.status === "Rejected");

                // Add rejected rooms to available rooms
                availableRooms = availableRooms.concat(rejectedRooms);

                // Build the result message with room category
                var resultMessage = "<h2>Room Search Result for " + roomCategory + " Rooms</h2>";

                if (availableRooms.length > 0) {
                    resultMessage += "<p><strong>Available Rooms:</strong> " + availableRooms.map(room => room.room_number).join(", ") + "</p>";
                } else {
                    resultMessage += "<p>All rooms are currently occupied or reserved.</p>";
                }

                // Update the roomSearchResult element with the result message
                document.getElementById("roomSearchResult").innerHTML = resultMessage;

                // Show the room search result modal
                showRoomSearchModal();
            } else {
                // Handle error case, e.g., invalid room category
            
            }
        }
    };

    // Send the AJAX request
    xhr.open("GET", "search_rooms.php?room_category=" + encodeURIComponent(roomCategory), true);
    xhr.send();
});
// ... (Your existing code)


function searchRooms() {
    var roomCategory = document.getElementById("room_category").value;

    // Fetch room availability based on room category and status
    fetch(`search_rooms.php?room_category=${roomCategory}`)
        .then(response => response.json())
        .then(data => {
            // Check if data.available is an array before using join
            var availableMessage = Array.isArray(data.available) ? "Room/s Available: " + data.available.join(", ") : "No available rooms";

            // Check if data.pending is an array before using join
            var pendingMessage = Array.isArray(data.pending) ? "\nRoom/s Pending: " + data.pending.join(", ") : "";

            // Check if data.accepted is an array before using join
            var acceptedMessage = Array.isArray(data.accepted) ? "\nRoom/s Accepted: " + data.accepted.join(", ") : "";

            // Display the result in a popup message
            var message = availableMessage + pendingMessage + acceptedMessage;
            alert(message);
        })
        .catch(error => {
            console.error('Error:', error);
        });
}
// ... (Your existing code)

document.getElementById("room_search_button").addEventListener("click", function () {
    // Get selected room category
    var roomCategory = document.getElementById("room_category").value;

    // Make an AJAX request to search for available rooms
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            // Parse the JSON response
            var result = JSON.parse(xhr.responseText);

            // Display the result or handle errors
            if (result.hasOwnProperty("available")) {
                var availableRooms = result.available;
                var pendingRooms = result.pending || [];
                var acceptedRooms = result.accepted || [];

                // Build the result message with room category
                var resultMessage = "<h2>Room Search Result for " + roomCategory + " Rooms</h2>";

                if (availableRooms.length > 0) {
                    resultMessage += "<p><strong>Available Rooms:</strong> " + availableRooms.join(", ") + "</p>";
                } else {
                    resultMessage += "<p>All rooms are currently occupied or reserved.</p>";
                }

                if (pendingRooms.length > 0) {
                    resultMessage += "<p><strong>Pending Rooms:</strong> " + pendingRooms.join(", ") + "</p>";
                } else {
                    resultMessage += "<p>No rooms are pending.</p>";
                }

                if (acceptedRooms.length > 0) {
                    resultMessage += "<p><strong>Accepted Rooms:</strong> " + acceptedRooms.join(", ") + "</p>";
                } else {
                    resultMessage += "<p>No rooms are accepted.</p>";
                }

                // Update the roomSearchResult element with the result message
                document.getElementById("roomSearchResult").innerHTML = resultMessage;

                // Show the room search result modal
                showRoomSearchModal();
            } else {
                // Handle error case, e.g., invalid room category
    
            }
        }
    };

    // Send the AJAX request
    xhr.open("GET", "search_rooms.php?room_category=" + encodeURIComponent(roomCategory), true);
    xhr.send();
});

// ... (Your existing code)





function toggleReceipt() {
    var receiptPopup = document.getElementById("receiptPopup");
    var receiptContent = document.getElementById("receiptContent");
    var receiptRoomCategory = document.getElementById("receiptRoomCategory");
    var receiptReservationTime = document.getElementById("receiptReservationTime");
    var receiptHoursReserved = document.getElementById("receiptHoursReserved");
    var receiptRoomNumber = document.getElementById("receiptRoomNumber");
    var receiptPaymentMethod = document.getElementById("receiptPaymentMethod");
    var receiptAdvancePayment = document.getElementById("receiptAdvancePayment");
    var receiptRemainingPayment = document.getElementById("receiptRemainingPayment");
    var receiptReferenceNumber = document.getElementById("receiptReferenceNumber");
    var receiptEmailAddress = document.getElementById("receiptEmailAddress");

    // Set the receipt information
    receiptRoomCategory.textContent = document.getElementById("room_category").value;
    receiptReservationTime.textContent = document.getElementById("reservation_time").value;
    receiptHoursReserved.textContent = document.getElementById("hours_reserved").value;
    receiptRoomNumber.textContent = document.getElementById("room_number").value;
    receiptPaymentMethod.textContent = document.getElementById("payment_method").value;
    receiptAdvancePayment.textContent = document.getElementById("advance_payment").value;
    receiptRemainingPayment.textContent = document.getElementById("remaining_payment").value;
    receiptReferenceNumber.textContent = document.getElementById("reference_number").value;
    receiptEmailAddress.textContent = document.getElementById("emailaddress").value;

    // Toggle between showing and hiding the receipt popup
    if (receiptPopup.style.display === "none" || receiptPopup.style.display === "") {
        receiptPopup.style.display = "block";
    } else {
        receiptPopup.style.display = "none";
    }
}

function closeReceiptPopup() {
    var receiptPopup = document.getElementById("receiptPopup");
    receiptPopup.style.display = "none";
}




    function showRoomCategoryInfo() {
            var roomCategory = document.getElementById("room_category").value;
            var roomInfo = "";

            switch (roomCategory) {
                case "Economy":
                    roomInfo = "<h1>Economy Room Information</h1><p>Single Bed</p><p>Free WIFI</p><p>Comfort Room</p><p>Basic Furniture</p>";
                    break;
                case "Deluxe":
                    roomInfo = "<h1>Deluxe Room Information</h1><p>Double Bed</p><p>Air Conditioned</p><p>Comfort Room</p><p>Mini-Fridge</p><p>House Keeping Services</p>";
                    break;
                case "Premium":
                    roomInfo = "<h1>Premium Room Information</h1><p>2 Queen-size Bed</p><p>En-suite Bathroom</p><p>Entertainment System</p><p>Living Room</p><p>Mini-Fridge Service</p><p>24/7 Room Service</p>";
                    break;
                default:
                    roomInfo = "<p>No information available for this room category.</p>";
            }

            showModal(roomInfo);
        }

        function showModal(content) {
            var modal = document.getElementById("roomInfoModal");
            var modalContent = document.getElementById("roomInfoContent");

            modalContent.innerHTML = content;
            modal.style.display = "block";

            window.onclick = function (event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            };
        }
        // Show terms and conditions popup
        document.getElementById("showTerms").addEventListener("click", function () {
            document.getElementById("termsPopup").style.display = "block";
        });

        // Close terms and conditions popup
        function closePopup() {
            document.getElementById("termsPopup").style.display = "none";
        }
        // Update room number options based on selected room category
        function updateRoomNumber() {
            var roomCategory = document.getElementById("room_category").value;
            var roomNumberSelect = document.getElementById("room_number");
            

            roomNumberSelect.innerHTML = "";

            var startNumber, endNumber;

            switch (roomCategory) {
                case "Economy":
                    startNumber = 1;
                    endNumber = 15;
                    break;

                case "Deluxe":
                    startNumber = 16;
                    endNumber = 25;
                    break;

                case "Premium":
                    startNumber = 26;
                    endNumber = 33;
                    break;
            }

            for (var i = startNumber; i <= endNumber; i++) {
                var option = document.createElement("option");
                option.value = i;
                option.text = i;
                roomNumberSelect.appendChild(option);
            }
        }

        

    // Validate reservation before submission
    function validateReservation() {
            var reservationStatus = document.getElementById("reservation_status").value;
            var roomNumber = document.getElementById("room_number").value;
            var termsAgreement = document.getElementById("terms_agreement").checked;

            // Check if the room is already reserved
            if (reservationStatus === 'Pending') {
                alert("This room is currently pending for other users.");
                return false;
            } else if (reservationStatus === 'Accepted') {
                alert("This room has already been reserved.");
                return false;
            }

            // Check if the terms and conditions checkbox is checked
            if (!termsAgreement) {
                alert("Please agree to the terms and conditions.");
                return false;
            }

            // Add any additional validation logic here
            // For now, it returns true to allow form submission
            return true;
        }

        var isFullPayment = false; // Move the declaration outside of any function

        function togglePayment() {
        var reservationCost = document.getElementById("reservation_cost").value;
        var advancePayment = document.getElementById("advance_payment");
        var remainingPayment = document.getElementById("remaining_payment");
        var paymentButton = document.getElementById("payment_button"); // Corrected ID

        // Toggle between full payment and 30% payment
        if (isFullPayment) {
            // If currently in full payment state, switch to 30% payment
            advancePayment.value = reservationCost * 0.3;
            remainingPayment.value = reservationCost * 0.7;
            paymentButton.innerText = "Change into Full Advance Payment";
        } else {
            // If currently in 30% payment state, switch to full payment
            advancePayment.value = reservationCost;
            remainingPayment.value = 0;
            paymentButton.innerText = "Change into 30% Advance Payment";
        }

        // Update the state
        isFullPayment = !isFullPayment;
    }



        // Calculate reservation cost and advance payment based on selected room category and hours reserved
        function calculateCost() {
        var roomCategory = document.getElementById("room_category").value;
        var hoursReserved = document.getElementById("hours_reserved").value;

        var reservationCost = document.getElementById("reservation_cost");
        var advancePayment = document.getElementById("advance_payment");
        var remainingPayment = document.getElementById("remaining_payment"); // Add this line

        var cost;

        switch (roomCategory) {
            case "Economy":
                switch (hoursReserved) {
                    case "1":
                        cost = 150;
                        break;
                    case "3":
                        cost = 350;
                        break;
                    case "6":
                        cost = 550;
                        break;
                    case "12":
                        cost = 1200;
                        break;
                    case "24":
                        cost = 1700;
                        break;
                }
                break;

            case "Deluxe":
                switch (hoursReserved) {
                    case "1":
                        cost = 250;
                        break;
                    case "3":
                        cost = 450;
                        break;
                    case "6":
                        cost = 850;
                        break;
                    case "12":
                        cost = 1350;
                        break;
                    case "24":
                        cost = 2550;
                        break;
                }
                break;

            case "Premium":
                switch (hoursReserved) {
                    case "1":
                        cost = 350;
                        break;
                    case "3":
                        cost = 650;
                        break;
                    case "6":
                        cost = 1250;
                        break;
                    case "12":
                        cost = 2350;
                        break;
                    case "24":
                        cost = 4000;
                        break;
                }
                break;
        }

        reservationCost.value = cost;
        advancePayment.value = cost * 0.3;

        // Calculate remaining payment and update the remaining_payment input
        var remaining = cost - advancePayment.value;
        remainingPayment.value = remaining.toFixed(2); // Ensure it is displayed as a decimal value with two decimal places
    }

        // Update reference number message based on selected payment method
        function updateReferenceMessage() {
            var paymentMethod = document.getElementById("payment_method").value;
            var referenceNumber = document.getElementById("reference_number");

            var message;

            switch (paymentMethod) {
                case "Gcash":
                    message = "Enter your Gcash reference number.";
                    brea
            }

            referenceNumber.placeholder = message;
        }

        function calculateFullAdvance() {
            var reservationCost = document.getElementById("reservation_cost").value;
            var advancePayment = document.getElementById("advance_payment");
            var remainingPayment = document.getElementById("remaining_payment");

            // Toggle between full payment and 30% payment
            if (isFullPayment) {
                // If currently in full payment state, switch to 30% payment
                advancePayment.value = reservationCost * 0.3;
                remainingPayment.value = reservationCost * 0.7;
            } else {
                // If currently in 30% payment state, switch to full payment
                advancePayment.value = reservationCost;
                remainingPayment.value = 0;
            }

            // Update the state
            isFullPayment = !isFullPayment;
        }

        function closeModal() {
            var modal = document.getElementById("roomInfoModal");
            modal.style.display = "none";
        }

        // Attach event listeners
        document.getElementById("room_category").addEventListener("change", updateRoomNumber);
        document.getElementById("hours_reserved").addEventListener("change", calculateCost);
        document.getElementById("payment_method").addEventListener("change", updateReferenceMessage);

        // Initial calculations
        updateRoomNumber();
        calculateCost();
        updateReferenceMessage();
    </script>
   
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.bundle.min.js"></script>
</body>
    </html>
