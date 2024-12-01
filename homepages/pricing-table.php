<?php
include 'faqsbot.php';
?>

<!DOCTYPE html>
<html lang="aa" class="inside_page inside_page_header_design s_layout15 isFreePackage">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="utf-8">

    <!-- Mobile Browser Address Bar Color -->
    <meta name="theme-color" content="#000000">
    <!-- Regular Meta Info -->
    <title class="s123-js-pjax">Pricing Table - Nase Hotel</title>
    <meta name="description" content="Pricing Table - Nase Hotel" class="s123-js-pjax">
    <meta name="keywords" content="" class="s123-js-pjax">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat">

    <!-- Add your other meta tags here -->

    <style>
        *{
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Montserrat';
}
body{
    padding: 0 10% 0 10%;
}
header{
    display: flex;
    align-items: center;
    justify-content: space-between;
    width: 100%;
    height: 100px;
    top: 0;
    border-bottom: 2px solid grey;
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

header .navLinks ul .wow {
    display: flex; /* Make list items flex containers */
    justify-content: center; /* Center horizontally */
    align-items: center; /* Center vertically */
    margin: 0 20px; /* Add spacing between list items if desired */
}

header .navLinks ul .wow a {
    color: black;
    text-decoration: none; /* Remove underline from links */
    font-size: 1.5rem;
    font-weight: 400;
}

        main {
            padding: 25px 1%;
            margin-top: 3%;
        }

        .roomContainer {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .roomContainer .top {
            width: 87%;
            text-align: center;
            padding: 0 20%;
            border-bottom: 2px solid grey;
            margin-bottom: 30px;
        }

        .roomContainer .top h1 {
            text-transform: uppercase;
            margin-bottom: 20px;
        }

        .roomContainer .top p {
            line-height: 20px;
        }

        .roomContainer .rooms {
            display: flex;
            justify-content: space-evenly;
            width: 100%;
            gap: 20px;
            flex-wrap: wrap;
            text-align: center;
        }

        .container {
            background-color: #fff;
            overflow: hidden;
            width: 350px;
            margin-bottom: 30px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }

        .wrapper {
            padding: 20px;
        }

        .banner-image3 img {
            width: 310px;
            height: 220px;
        }

        h1, p {
            margin: 0;
            color: #333;
        }

        .button-wrapper {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
        }

        .btn {
            padding: 12px 24px;
            font-size: 0.8rem;
            letter-spacing: 2px;
            cursor: pointer;
            font-family: 'Montserrat';
            font-weight: 700;
            width: 150px;
        }

        .btn:hover {
            transform: scale(1.05);
            transition: 0.3s;
            filter: drop-shadow(0 10px 5px rgba(0, 0, 0, 0.125));
        }

        .outline {
            background: #D1C8C1;
            color: #4B301B;
        }

        .outline:hover {
            background-color: #D1C8C1;
            transition: 0.3s;
        }

        .fill {
            background: #D1C8C1;
            color: #4B301B;
        }

        .fill:hover {
            background-color: #D1C8C1;
            transition: 0.3s;
        }

    </style>
    <!-- Add your other stylesheets here -->
    <link rel="stylesheet" href="https://kit.fontawesome.com/fb68db4d3c.js" crossorigin="anonymous">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat">
</head>
<body>

<header>
        <div class="title">
            <img src="logo.png" alt="logo" class="logo" />
            <h1>Nase</h1>
        </div>

        <nav class="navLinks">
            <ul>
                <li class="wow"><a href="Home.php">Home</a></li>
                <li class="wow"><a href="About.php">About</a></li>
                <li class="wow"><a href="pricing-table.php">Rooms</a></li>
                <li class="wow"><a href="ContactUs.php">Contacts</a></li>
                <li class="wow"><a href="login.php"><i class="fa-regular fa-user"></i></a></li>
            </ul>
        </nav>
    </header>

<main>
    <section class="roomContainer">
        <div class="top">
            <h1 style="font-size: 40px;"><b>Rooms</b></h1>
            <p style="text-align: justify; text-align-last: center; width: 75%; margin: auto; font-size: 14px;">Welcome to our elegant and comfortable hotel rooms - your home away from home. Each room is well-appointed with modern amenities, a cozy bed, and a private bathroom. Whether you're traveling for business or leisure, our rooms provide everything you need for a relaxing and enjoyable stay.</p>
            <br>
        </div>

        <div class="rooms">
            <div class="container">
                <div class="wrapper">
                    <div class="banner-image3">
                        <img src="economy.png" alt="Economy Image">
                    </div>
                    <h1>ECONOMY</h1>
                    <p>up to PHP 1,700</p>
                </div>
                <div class="button-wrapper">
                    <a href="pricing-table-details-economy.php">
                        <button class="btn outline">DETAILS</button>
                    </a>
                    <form action="login.php" method="GET">
                        <button class="btn fill" type="submit" role="button" data-module="64437820ffd53" data-unique-page="64437820f0862" data-tranw="" aria-label="Reserve Now">RESERVE</button>
                    </form>
                </div>
            </div>

            <div class="container">
                <div class="wrapper">
                    <div class="banner-image3">
                        <img src="deluxe.png" alt="Deluxe Image">
                    </div>
                    <h1>DELUXE</h1>
                    <p>up to PHP 2,250</p>
                </div>
                <div class="button-wrapper">
                    <a href="pricing-table-details-deluxe.php">
                        <button class="btn outline">DETAILS</button>
                    </a>
                    <form action="login.php" method="GET">
                        <button class="btn fill" type="submit" role="button" data-module="64437820ffd53" data-unique-page="64437820f0862" data-tranw="" aria-label="Reserve Now">RESERVE</button>
                    </form>
                </div>
            </div>

            <div class="container">
                <div class="wrapper">
                    <div class="banner-image3">
                        <img src="premium.png" alt="Premium Image">
                    </div>
                    <h1>PREMIUM</h1>
                    <p>up to PHP 4,000</p>
                </div>
                <div class="button-wrapper">
                    <a href="pricing-table-details-premium.php">
                        <button class="btn outline">DETAILS</button>
                    </a>
                    <form action="login.php" method="GET">
                        <button class="btn fill" type="submit" role="button" data-module="64437820ffd53" data-unique-page="64437820f0862" data-tranw="" aria-label="Reserve Now">RESERVE</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
</main>
<footer style="position: fixed; bottom: 3%; left: 50%; transform: translateX(-50%); width: 50%; text-align: center; color: black; font-family: 'Montserrat';">
<hr><br>
    &copy; 2024 Nase Hotel. All Rights Reserved.
</footer>

<script src="https://kit.fontawesome.com/fb68db4d3c.js" crossorigin="anonymous"></script>
</body>
</html>
