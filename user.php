<?php
session_start();
require_once "config.php";

if (!isset($_SESSION['ulogin'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['ulogin']; // This is the actual user ID
$user = null;

try {
    $stmt = $dbh->prepare("SELECT * FROM tblusers WHERE id = :id");
    $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $user = null;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Credentials</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            margin: 0;
            background-color: #121212;
            color: #ffffff;
            font-family: 'Segoe UI', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .card {
            background-color: #2c2c2c;
            color: #ffffff;
            padding: 40px 50px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.4);
            width: 100%;
            max-width: 500px;
            text-align: center;
        }
        .card h2 {
            margin-bottom: 25px;
            font-size: 26px;
            border-bottom: 1px solid #444;
            padding-bottom: 10px;
        }
        .card p {
            font-size: 18px;
            margin: 15px 0;
        }
        .card p strong {
            display: inline-block;
            width: 80px;
            color: #aaaaaa;
        }
        .no-user {
            color: #888;
            font-style: italic;
        }
        .back-button {
            margin-top: 30px;
            padding: 10px 20px;
            background-color: #ffffff;
            color: #121212;
            border: none;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .back-button:hover {
            background-color: #cccccc;
        }
    </style>
</head>
<body>

<div class="card">
    <h2>User Credentials</h2>
    <?php if ($user): ?>
        <p><strong>Name:</strong> <?= htmlspecialchars($user['userName']); ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($user['emailId']); ?></p>
    <?php else: ?>
        <p class="no-user">User not found or session expired.</p>
    <?php endif; ?>

    <a href="dashboard.php">
        <button class="back-button"><i class="fas fa-arrow-left"></i> Back to Dashboard</button>
    </a>
</div>

</body>
</html>
