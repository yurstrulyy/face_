<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';
require 'PHPMailer/Exception.php';

function sendOTP($email, $otp) {
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'kyradrevor@gmail.com';      
        $mail->Password   = 'dhqaosgypamgyads';         
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        $mail->setFrom('kyradrevor@gmail.com', 'Kycom');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'OTP Verification';
        $mail->Body    = "Dear user,<br><br>Your OTP for account verification is <strong>$otp</strong>.<br><br>Thanks!";
        $mail->AltBody = "Your OTP is $otp";

        if ($mail->send()) {
            return true;
        } else {
            echo "❌ Failed to send: " . $mail->ErrorInfo;
            return false;
        }

    } catch (Exception $e) {
        echo "❌ PHPMailer Error: " . $mail->ErrorInfo;
        return false;
    }
}
?>
