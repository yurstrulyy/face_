<?php
session_start();
require 'config.php'; // ensure this defines $dbh (PDO)
header('Content-Type: application/json');
$data = json_decode(file_get_contents("php://input"), true);
if (!isset($data['face_image'])) {
    echo json_encode(["success" => false, "message" => "No image received"]);
    exit;
}

$image_data = str_replace('data:image/jpeg;base64,', '', $data['face_image']);
$image_data = base64_decode($image_data);
$image_name = uniqid() . '.jpg';
file_put_contents("faces/$image_name", $image_data);

$api_key = '5eIL3E2EpiPb67jm3LKuNo0-HjarTclt';
$api_secret = '_QhMmYH4H8uvEwd_yFb3vS_jvfGUffGh';
$image_path = realpath("faces/$image_name");

// Detect face token
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

if (!isset($response['faces'][0]['face_token'])) {
    echo json_encode(["success" => false, "message" => "Face not detected. Try again."]);
    exit;
}

$face_token = $response['faces'][0]['face_token'];

// Compare with all users' face_token
$sql = "SELECT * FROM tblusers WHERE face_token IS NOT NULL";
$stmt = $dbh->prepare($sql);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

$found = false;
foreach ($users as $user) {
    // Compare face tokens
    $compare = curl_init();
    curl_setopt($compare, CURLOPT_URL, 'https://api-us.faceplusplus.com/facepp/v3/compare');
    curl_setopt($compare, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($compare, CURLOPT_POSTFIELDS, [
        'api_key' => $api_key,
        'api_secret' => $api_secret,
        'face_token1' => $face_token,
        'face_token2' => $user['face_token']
    ]);
    $res = json_decode(curl_exec($compare), true);
    curl_close($compare);

    if (isset($res['confidence']) && $res['confidence'] > 80) { // adjust threshold as needed
        $_SESSION['ulogin'] = $user['id'];
        $_SESSION['fname'] = $user['userName']; // or adjust to correct column name

        $found = true;
        break;
    }
}

if ($found) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => "Face not recognized."]);
}
