<?php
session_start();
include_once('config.php');

// Turn on error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$msg = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $enteredOtp = $_POST['otp'];
    $email = $_SESSION['emailid']; 

    // DEBUG: Show what is being compared
    echo "Session Email: " . htmlspecialchars($email) . "<br>";

    // Fetch OTP from DB
    $sql = "SELECT emailOtp FROM tblusers WHERE emailId = :email";
    $query = $dbh->prepare($sql);
    $query->bindParam(':email', $email, PDO::PARAM_STR);
    $query->execute();
    $row = $query->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        echo "Entered OTP: " . htmlspecialchars($enteredOtp) . "<br>";
        echo "Stored OTP: " . htmlspecialchars($row['emailOtp']) . "<br>";
    }

    // Trim both values before comparison
    if ($row && trim($enteredOtp) === trim($row['emailOtp'])) {
        // OTP is correct, update verification status
        $update = "UPDATE tblusers SET isEmailVerify = 1 WHERE emailId = :email";
        $stmt = $dbh->prepare($update);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        $_SESSION['verified'] = true;
        $_SESSION['face_enroll'] = true;
        $_SESSION['user_id'] = getUserIdByEmail($email, $dbh); // Optional
        header("Location: face_enroll.php");
        exit();
    } else {
        $msg = "Please enter correct OTP";
    }
}

function getUserIdByEmail($email, $dbh) {
    $sql = "SELECT id FROM tblusers WHERE emailId = :email";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row ? $row['id'] : null;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Verify OTP</title>
    <style>
        body {
            margin: 0;
            background-color: #000;
            color: #fff;
            font-family: Arial, sans-serif;
        }
        .container {
            max-width: 400px;
            margin: 100px auto;
            background-color: #111;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px #222;
            text-align: center;
        }
        h2 {
            text-align: center;
            color: #fff;
            margin-bottom: 10px;
        }
        input[type="text"] {
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
            padding: 10px 20px;
            background-color: #fff;
            color: #000;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }
        button:hover {
            background-color: #ccc;
        }
        .message {
            color: red;
            margin-top: 15px;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Verify OTP</h2>
    <form method="POST">
        <input type="text" name="otp" placeholder="Enter OTP" required>
        <button type="submit">Verify</button>
    </form>
    <?php if (!empty($msg)): ?>
        <p class="message"><?php echo $msg; ?></p>
    <?php endif; ?>
</div>
</body>
</html>
