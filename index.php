<?php 
session_start();
include_once('config.php');
require 'mail.php'; // ✅ Include PHPMailer OTP sender

// Code for Signup
if (isset($_POST['submit'])) {
    // Getting Post values
    $name = $_POST['username'];	
    $email = $_POST['email'];	
    $loginpass = md5($_POST['password']); // Password is encrypted using MD5
    $otp = mt_rand(100000, 999999);	

    // Check if email already exists
    $ret = "SELECT id FROM tblusers WHERE emailId = :uemail";
    $queryt = $dbh->prepare($ret);
    $queryt->bindParam(':uemail', $email, PDO::PARAM_STR);
    $queryt->execute();

    if ($queryt->rowCount() == 0) {
        // Insert user if not exists
        $emailverifiy = 0;
        $sql = "INSERT INTO tblusers(userName, emailId, userPassword, emailOtp, isEmailVerify)
                VALUES(:fname, :emaill, :hashedpass, :otp, :isactive)";
        $query = $dbh->prepare($sql);
        $query->bindParam(':fname', $name, PDO::PARAM_STR);
        $query->bindParam(':emaill', $email, PDO::PARAM_STR);
        $query->bindParam(':hashedpass', $loginpass, PDO::PARAM_STR);
        $query->bindParam(':otp', $otp, PDO::PARAM_STR);
        $query->bindParam(':isactive', $emailverifiy, PDO::PARAM_STR);
        $query->execute();
        $lastInsertId = $dbh->lastInsertId();

        if ($lastInsertId) {
            $_SESSION['emailid'] = $email;

            // ✅ Send the OTP via email
            if (sendOTP($email, $otp)) {
                echo "<script>window.location.href='verify-otp.php'</script>";
            } else {
                echo "<script>alert('OTP could not be sent. Please check your email setup.');</script>";
            }
        } else {
            echo "<script>alert('Something went wrong. Please try again.');</script>";	
        }
    } else {
        echo "<script>alert('Email ID already associated with another account.');</script>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        * {
            box-sizing: border-box;
        }
        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #111;
            color: #eee;
        }
        .container {
            max-width: 400px;
            margin: 80px auto;
            background: #1c1c1c;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(255,255,255,0.03);
        }
        h2 {
            text-align: center;
            color: #fff;
            margin-bottom: 10px;
        }
        p {
            text-align: center;
            color: #bbb;
            margin-bottom: 30px;
            font-size: 14px;
        }
        label {
            display: block;
            margin-bottom: 6px;
            font-size: 13px;
            color: #ddd;
        }
        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 18px;
            border: 1px solid #444;
            border-radius: 5px;
            background: #222;
            color: #fff;
            font-size: 14px;
        }
        input:focus {
            border-color: #777;
            outline: none;
        }
        button {
            width: 100%;
            padding: 12px;
            background: #fff;
            color: #111;
            border: none;
            font-size: 16px;
            font-weight: bold;
            border-radius: 6px;
            cursor: pointer;
            transition: background 0.3s, color 0.3s;
        }
        button:hover {
            background: #ddd;
            color: #000;
        }
        .bottom-text {
            text-align: center;
            margin-top: 15px;
            font-size: 14px;
            color: #ccc;
        }
        .bottom-text a {
            color: #fff;
            text-decoration: underline;
        }
        .bottom-text a:hover {
            color: #ccc;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Sign Up</h2>
    <p>Fill out this form to create your account</p>
    <form method="post">
        <label for="username">Full Name</label>
        <input type="text" id="username" name="username" required>

        <label for="email">Email Address</label>
        <input type="email" id="email" name="email" required>

        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>

        <button type="submit" name="submit">Sign Up</button>
    </form>
    <div class="bottom-text">
        Already have an account? <a href="login.php">Login here</a><br>
        <a href="resend-otp.php">Resend OTP</a>
    </div>
</div>

</body>
</html>

