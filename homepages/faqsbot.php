<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    
    <style>
body {
    font-family: 'Roboto', sans-serif;
    background-color: #f4f4f4;
    margin: 0;
    padding: 0;
    overflow: hidden;
}

#faqs-bot {
    position: fixed;
    bottom: 20px;
    right: 20px;
    cursor: pointer;
}

#faqs-bot img {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    background-color: #fff;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
    padding: 15px;
    box-sizing: border-box;
    transition: box-shadow 0.3s ease;
    animation: scaleIn 0.5s ease-in-out forwards;
    animation-delay: 1s;

}

#faqs-bot img:hover {
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.3);
}

#faqs-container {
    display: none;
    position: fixed;
    bottom: 20px;
    right: 20px;
    width: 400px;
    background-color: #fff;
    border: 1px solid #ccc;
    padding: 25px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    z-index: 999;
    border-radius: 8px;
    animation-fill-mode: forwards;
    animation-duration: 2s;
    scroll-behavior: smooth;
    overflow-y: auto;
    max-height: 80vh; /* adjust this value as needed */
}

#faqs-container.open {
    animation: openContainer 2s ease-in-out;
}

#faqs-container.close {
    animation: closeContainer 2s ease-in-out;
}

.faqs-group h3 {
    color: #333;
    font-size: 1.5em;
    text-align: left; /* Add this line */
}
    .faqs-group p {
        color: #333;
        font-size: 1em;
    }



.faqs-group {
        background-color: #f9f9f9;
        border-radius: 5px;
        padding: 20px;
        margin-bottom: 20px;
        font-family: Arial, sans-serif;
    }

.back-button {
    cursor: pointer;
    color: #3498db;
    text-decoration: underline;
background-color: #86654B;
        color: #fff;
        padding: 10px 15px;
        border-radius: 5px;
        text-decoration: none;
        display: inline-block;
        margin-bottom: 20px;
    }

.back-button:hover {
    background-color: #2980b9;
}

h3 {
    color: #333;
    font-size: 1.2em;
    margin-bottom: 15px;
}

h4 {
    color: #333333;
    font-size: 1em;
    margin-bottom: 10px;
}

ul {
    list-style-type: none;
    padding: 0;
}

li {
    width: 100%;
    margin-bottom: 15px;
    padding: 15px;
    border-radius: 5px;
    transition: background-color 0.3s ease;
    opacity: 0;
    animation: fadeIn 0.5s ease-in-out forwards;
}

li:hover {
    background-color: #e9ecef;
}

@keyframes openContainer {
    0% { transform: translateY(100%) scale(0.5); opacity: 0; }
    50% { transform: translateY(-10%) scale(1.05); opacity: 0.5; }
    100% { transform: translateY(0) scale(1); opacity: 1; }
}

@keyframes closeContainer {
    0% { transform: translateY(0) scale(1); opacity: 1; }
    50% { transform: translateY(-10%) scale(0.95); opacity: 0.5; }
    100% { transform: translateY(100%) scale(0.5); opacity: 0; }
}
@media (max-width: 768px) {
    #faqs-container {
        width: 100%;
        height: 100%;
        bottom: 0;
        right: 0;
        padding: 15px;
    }
}
@keyframes fadeIn {
    0% { opacity: 0; }
    100% { opacity: 1; }
}

@keyframes scaleIn {
    0% { transform: scale(0); }
    100% { transform: scale(1); }
}

#faqs-content {
    text-align: justify;
    color: #333;
}
</style>
</head>
<body>

<div id="faqs-bot">
    <img src="logoyata.svg" alt="FAQs Bot Logo" onclick="toggleFaqsContainer()">
</div>

<div id="faqs-container">
    <div id="faqs-content">
        <!-- Initial content -->
        <div class="faqs-group">
            <div class="back-button" onclick="toggleFaqsContainer()">Back</div>
            <h3>Nase Hotel:</h3>
            <ul>
                <li onclick="showGroup('group1')">Frequently Asked Questions</li>
                <li onclick="showGroup('group2')">Terms and Agreement</li>
                <li onclick="showGroup('group3')">Rules and Regulations</li>
            </ul>
        </div>

<!-- Group 1: Frequently Asked Questions -->
<div class="faqs-group" id="group1" style="display: none;">
    <div class="back-button" onclick="showGroupList()">Back</div>
    <h3>Frequently Asked Questions:</h3>
    <ul style="list-style-type: none;">
        <li style="margin-bottom: 20px;">
            <h4 onclick="toggleVisibility('answer1')" style="cursor: pointer; color: #000000; transition: color 0.3s ease; font-weight: normal;">What time can I check-in and check-out?</h4>
            <p id="answer1" style="display: none; padding: 15px; background-color: #f8f9fa; border-radius: 5px; transition: max-height 0.3s ease, padding 0.3s ease;">Standard check-in time is at 2 PM and check-out at 12 PM. Kindly note that check-in time may be shifted to 3:00 PM during high occupancy periods.</p>
        </li>
        <li style="margin-bottom: 20px;">
            <h4 onclick="toggleVisibility('answer2')" style="cursor: pointer; color: #000000; transition: color 0.3s ease; font-weight: normal;">Can I request for wake up calls?</h4>
            <p id="answer2" style="display: none; padding: 15px; background-color: #f8f9fa; border-radius: 5px; transition: max-height 0.3s ease, padding 0.3s ease;">Make your way out of bed with our timely wake-up call. Please press service key “1” for assistance.</p>
        </li>
        <li style="margin-bottom: 20px;">
            <h4 onclick="toggleVisibility('answer3')" style="cursor: pointer; color: #000000; transition: color 0.3s ease; font-weight: normal;">Can I request housekeeping?</h4>
            <p id="answer3" style="display: none; padding: 15px; background-color: #f8f9fa; border-radius: 5px; transition: max-height 0.3s ease, padding 0.3s ease;">Housekeeping services are available. Shoe shine service is offered to guests upon request. Please press the service key “1” for assistance.</p>
        </li>
        <li style="margin-bottom: 20px;">
            <h4 onclick="toggleVisibility('answer4')" style="cursor: pointer; color: #000000; transition: color 0.3s ease; font-weight: normal;">Do you have wifi?</h4>
            <p id="answer4" style="display: none; padding: 15px; background-color: #f8f9fa; border-radius: 5px; transition: max-height 0.3s ease, padding 0.3s ease;">Stay connected during your stay with 40MB bandwidth internet service provided throughout our guestrooms and public areas. Please log-in with your username and password provided at check-in. User name is your room no. Password is *followed by room no. eg: USER NAME: 2803 PASSWORD: *2803.</p>
        </li>
        <li style="margin-bottom: 20px;">
            <h4 onclick="toggleVisibility('answer5')" style="cursor: pointer; color: #000000; transition: color 0.3s ease; font-weight: normal;">Do you allow early check in and late check-out?</h4>
            <p id="answer5" style="display: none; padding: 15px; background-color: #f8f9fa; border-radius: 5px; transition: max-height 0.3s ease, padding 0.3s ease;">We honor requests for early check-in and late check-out, but availability is subject to confirmation and availability. We extend this privilege to guests who book directly with us.</p>
        </li>
        <li style="margin-bottom: 20px;">
            <h4 onclick="toggleVisibility('answer6')" style="cursor: pointer; color: #000000; transition: color 0.3s ease; font-weight: normal;">Do you allow pet inside the room?</h4>
            <p id="answer6" style="display: none; padding: 15px; background-color: #f8f9fa; border-radius: 5px; transition: max-height 0.3s ease, padding 0.3s ease;">As much as we love pets, unfortunately, we are unable to accommodate them in our rooms or any of our common areas. We can refer you to the local pet house, while you choose to stay with us.</p>
        </li>
        <li style="margin-bottom: 20px;">
            <h4 onclick="toggleVisibility('answer7')" style="cursor: pointer; color: #000000; transition: color 0.3s ease; font-weight: normal;">Do you offer parking slot for hotel guests?</h4>
            <p id="answer7" style="display: none; padding: 15px; background-color: #f8f9fa; border-radius: 5px; transition: max-height 0.3s ease, padding 0.3s ease;">Yes, however parking space is subject to availability. Registered hotel guests are entitled to one (1) complimentary parking slot per room.</p>
        </li>
    </ul>
</div>
        <!-- Group 2: Terms and Agreement -->
        <div class="faqs-group" id="group2" style="display: none;">
            <div class="back-button" onclick="showGroupList()">Back</div>
            <h3>Reservation and Payment:</h3>
            <p>Reservation Confirmation: All reservations are subject to availability and confirmation by Nase Hotel.</p>
            <p>Cancellation Policy: Guests are required to verify their reservations within 24 hours. Failure to do so will result in automatic cancellation of the reservation, and the downpayment will not be refunded.</p>
            <p>Payment: Payment must be made in full at the time of booking, unless otherwise specified. Accepted forms of payment include [list accepted payment methods].</p>
            <p>Reservation: If all rooms are fully booked, guests will have to wait. This will be reflected in real-time in the room options, where only available rooms will be displayed. Guests will be updated via email if rooms become available.</p>
            <h3>Check-in and Check-out:</h3>
            <p>Check-in: Check-in time is [insert time], and guests are required to present a valid government-issued photo ID and a credit card for incidentals.</p>
            <p>Check-out: Check-out time is [insert time]. Late check-out requests are subject to availability and may incur additional charges.</p>
            <h3>Guest Responsibilities:</h3>
            <p>Behavior: Guests are expected to conduct themselves in a respectful and responsible manner, refraining from any disruptive or illegal activities on hotel premises.</p>
            <p>Damages: Guests will be held responsible for any damages caused to the hotel property, including but not limited to rooms, furnishings, and amenities.</p>
            <h3>Amenities and Services:</h3>
            <p>Services: Nase Hotel reserves the right to alter or discontinue any services or amenities without prior notice.</p>
            <p>Use of Facilities: Guests are expected to use hotel facilities and amenities in a safe and responsible manner.</p>
            <h3>Liability and Security:</h3>
            <p>Security: The hotel is not responsible for the loss or theft of personal belongings. Guests are advised to use the provided safety deposit boxes.</p>
            <p>Liability: Nase Hotel is not liable for any injuries, accidents, or damages that may occur on hotel premises, including those related to the use of hotel facilities.</p>
            <h3>Miscellaneous:</h3>
            <p>Force Majeure: Nase Hotel is not liable for any failure or delay in performing its obligations due to circumstances beyond its control, including but not limited to natural disasters, strikes, and government actions.</p>
            <p>Privacy: The hotel respects guest privacy and handles personal information in accordance with applicable data protection laws.</p>
            <p>By making a reservation with Nase Hotel, guests acknowledge and agree to abide by these terms and conditions.</p>
        </div>

       <!-- Group 3: Rules and Regulations -->
<div class="faqs-group" id="group3" style="display: none;">
    <div class="back-button" onclick="showGroupList()">Back</div>
    <h3>Rules and Regulations:</h3>
    <p>Smoking is strictly prohibited.</p>
    <p>If upon check-in the guest does not specify the hours duration of his or her stay, they will be charged per hour.</p>
    <p>Only 2 guests are allowed to stay in a room. Children older than 7 years old or the third guest must pay 500 Pesos for an extra bed. If the third guest does not wish to pay for an extra bed, a fee of 250 Pesos will be charged and breakfast will not be provided.</p>
    <p>Guests will be required to provide personal information on the registration form, including their name, age, phone number, email address, and two legitimate IDs through the hotel's web system. Following account confirmation, a verification code will be given to the customer's phone number and email address.</p>
    <p>Guests are required to verify their reservations within 24 hours. Failure to do so will result in automatic cancellation of the reservation, and the downpayment will not be refunded.</p>
    <p>Inviting strangers into the guest rooms to use the facilities and/or amenities is prohibited. For security reasons, guests must register at reception before entering the room.</p>
    <p>No guest is allowed to give up his or her room to be used by third parties even if the room has been fully paid off.</p>
    <p>Gambling or any other behavior that is against public morals in the hallways or guest rooms is prohibited.</p>
    <p>Bringing illegal items and exotic plants is prohibited.</p>
    <p>Ordering and delivering of food from outside the hotel is prohibited.</p>
    <p>Pet owners are responsible for their animals.</p>
    <p>Any damage should be reported to our reception immediately after it becomes apparent.</p>
    <p>In case of loss or damage of the key card, 1,000 pesos will be charged to the room bill.</p>
    <p>Removing items from guest rooms or moving them to other places in the hotel is prohibited. Violators are subject to a fine of up to 5,000 pesos.</p>
    <p>Extension of your stay without reservation is subject to room availability.</p>
    <p>Hotel Guests are required to wear proper swimwear.</p>
</div>

    </div>
</div>

<script>
    function toggleFaqsContainer() {
        var faqsContainer = document.getElementById('faqs-container');
        faqsContainer.style.display = (faqsContainer.style.display === 'block') ? 'none' : 'block';
    }

    function showGroup(group) {
        var groups = document.querySelectorAll('.faqs-group');
        for (var i = 0; i < groups.length; i++) {
            groups[i].style.display = 'none';
        }

        var selectedGroup = document.getElementById(group);
        if (selectedGroup) {
            selectedGroup.style.display = 'block';
        }
    }

    function showGroupList() {
        var groups = document.querySelectorAll('.faqs-group');
        for (var i = 0; i < groups.length; i++) {
            groups[i].style.display = 'none';
        }

        var faqsGroup = document.querySelector('.faqs-group');
        if (faqsGroup) {
            faqsGroup.style.display = 'block';
        }
    }
    function toggleVisibility(id) {
    var element = document.getElementById(id);
    if (element.style.display === "none") {
        element.style.display = "block";
    } else {
        element.style.display = "none";
    }
}
</script>

</body>
</html>
