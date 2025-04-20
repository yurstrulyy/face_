<?php
session_start();
require 'config.php'; // For DB

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['face_image'])) {
    $image_data = $_POST['face_image'];
    $image_data = str_replace('data:image/jpeg;base64,', '', $image_data);
    $image_data = base64_decode($image_data);
    $image_name = uniqid() . '.jpg';
    file_put_contents("faces/$image_name", $image_data);

    $api_key = '5eIL3E2EpiPb67jm3LKuNo0-HjarTclt';
    $api_secret = '_QhMmYH4H8uvEwd_yFb3vS_jvfGUffGh';
    $image_path = realpath("faces/$image_name");

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://api-us.faceplusplus.com/facepp/v3/detect');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, [
        'api_key' => $api_key,
        'api_secret' => $api_secret,
        'image_file' => new CURLFile($image_path)
    ]);

    $response = json_decode(curl_exec($ch), true);
    curl_close($ch);

    if (isset($response['faces'][0]['face_token'])) {
        $face_token = $response['faces'][0]['face_token'];
        $user_id = $_SESSION['user_id'];

        $stmt = $dbh->prepare("UPDATE tblusers SET face_token = :face_token WHERE id = :id");
        $stmt->bindParam(':face_token', $face_token);
        $stmt->bindParam(':id', $user_id);
        $stmt->execute();


        header("Location: login.php?registered=success");
        exit();
        
    } else {
        echo "Face registration failed.";
    }
}
?>
