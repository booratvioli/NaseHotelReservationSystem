<?php
session_start();


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["code"])) {
        $code = $_POST["code"];
        if ($code != $_SESSION["code"]) {
         
        } else {
            header("Location: reset_password.php");
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Verify Code</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <style>
        body {
            background-image: url('bg.jpg');
            background-size: cover;
            background-position: center;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .container {
            background-color: rgba(255, 255, 255, 0.8);
            padding: 30px;
            border-radius: 10px;
            max-width: 400px;
            width: 90%;
        }
        .logo {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 30px;
        }
        .logo img {
            width: 50%;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .btn {
            background-color: #3498db;
            color: white;
            padding: 15px;
            border: none;
            border-radius: 5px;
            width: 100%;
            font-size: 18px;
        }
        .links {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }
    </style>
   <script>
    function resendCode() {
        $.ajax({
            url: 'resend_code.php',
            type: 'post',
            success: function(response) {
                swal("Success!", response, "success");
            },
            error: function(jqXHR, textStatus, errorThrown) {
                swal("Error!", "Failed to resend code!", "error");
            }
        });
    }
</script>
</head>
<body>
    <div class="container">
        <div class="logo">
        <img src="logo no bg.png" alt="Nase Hotel" style="width:350px; height:350px;">
        </div>
        <form action="verify_code.php" method="post">
            <div class="form-group">
                <label for="code">Enter Verification Code:</label>
                <input type="text" id="code" name="code" class="form-control" required>
            </div>
            <div class="form-group">
                <input type="submit" value="Verify Code" class="btn">
            </div>
        </form>
        <div class="links">
            <a href="#" onclick="resendCode(); return false;">Resend Code</a>
            <a href="forgot_password.php">Back</a>

        </div>
    </div>

    <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $code = $_POST["code"];
            if ($code == $_SESSION["code"]) {
                // Code is correct
                echo '<script>swal("Good job!", "Verification successful!", "success");</script>';
            } else {
                // Code is incorrect
                echo '<script>swal("Oops!", "Verification failed!", "error");</script>';
            }
        }
    ?>
</body>
</html>