<?php session_start();
include_once('config.php');

//Code for Resend
if(isset($_POST['resend'])){
//Getting Post values
$email=$_POST['email'];	
//Generating 6 Digit Random OTP
$otp= mt_rand(100000, 999999);	
// Query for validation of  email-id
$ret="SELECT id,isEmailVerify FROM  tblusers where (emailId=:uemail)";
$queryt = $dbh -> prepare($ret);
$queryt->bindParam(':uemail',$email,PDO::PARAM_STR);
$queryt -> execute();
$results = $queryt -> fetchAll(PDO::FETCH_OBJ);
if($queryt -> rowCount() > 0)
{
foreach ($results as $result) {
$verifystatus=$result->isEmailVerify;}	

//if email already verified
if($verifystatus=='1'){
echo "<script>alert('Email already verified. No need to verify again.');</script>";
} else{
$_SESSION['emailid']=$email;
$_SESSION['otp']=$otp;

$sql="update tblusers set emailOtp=:otp where emailId=:emailid";
$query = $dbh->prepare($sql);
// Binding Post Values
$query->bindParam(':emailid',$email,PDO::PARAM_STR);
$query->bindParam(':otp',$otp,PDO::PARAM_STR);
$query->execute();	
//Code for Sending Email
$subject = "OTP Verification";
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type: text/html; charset=iso-8859-1" . "\r\n";
$headers .= "From: User Signup <yourname@yourdomain.com>" . "\r\n";

$ms = "<html><body>";
$ms .= "<div style='padding:10px; font-size:16px;'>";
$ms .= "Dear User,<br><br>";
$ms .= "Your OTP for account verification is <strong>$otp</strong>.<br><br>";
$ms .= "Thank you!";
$ms .= "</div></body></html>";

mail($email, $subject, $ms, $headers);
 
echo "<script>window.location.href='verify-otp.php'</script>";
}}else {
echo "<script>alert('Email id not registered yet');</script>";
}
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Resend OTP</title>
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
            margin-bottom: 20px;
        }
        p {
            color: #ccc;
            margin-bottom: 30px;
        }
        a {
            display: inline-block;
            padding: 10px 20px;
            background-color: #fff;
            color: #000;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            transition: background-color 0.3s;
        }
        a:hover {
            background-color: #ccc;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>OTP Resent</h2>
    <p>An OTP has been resent to your registered email address.</p>
    <a href="verify-otp.php">Go to OTP Verification</a>
</div>

</body>
</html>