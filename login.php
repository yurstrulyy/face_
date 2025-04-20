<?php
session_start();
include_once('config.php');

// Code for login
if (isset($_POST['login'])) {
    $uname = $_POST['email'];
    $password = md5($_POST['password']);

    $sql = "SELECT id, isEmailVerify, userName FROM tblusers WHERE emailId = :uname AND userPassword = :password";
    $query = $dbh->prepare($sql);
    $query->bindParam(':uname', $uname, PDO::PARAM_STR);
    $query->bindParam(':password', $password, PDO::PARAM_STR);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);

    if ($query->rowCount() > 0) {
        foreach ($results as $result) {
            $emailstatus = $result->isEmailVerify;
            $fname = $result->userName;
            $uid = $result->id;
        }
        if ($emailstatus == 1) {
            $_SESSION['ulogin'] = $uid;
            $_SESSION['fname'] = $fname;
            echo "<script type='text/javascript'> document.location = 'welcome.php'; </script>";
        } else {
            echo "<script>alert('Email not verified. Please verify using the OTP sent to your email.');</script>";
        }
    } else {
        echo "<script>alert('Invalid Details');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        * { box-sizing: border-box; }
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
        h2, h3 { text-align: center; color: #fff; margin-bottom: 20px; }
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
        input:focus { border-color: #777; outline: none; }
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
        button:hover { background: #ddd; color: #000; }
        .text-center {
            text-align: center;
            margin-top: 15px;
            font-size: 14px;
            color: #ccc;
        }
        a { color: #fff; text-decoration: underline; }
        a:hover { color: #ccc; }
        #video {
            margin-top: 15px;
            width: 100%;
            border-radius: 8px;
            border: 2px solid #444;
            display: none;
        }
        #statusMsg {
            text-align: center;
            margin-top: 10px;
            color: #ccc;
        }
    </style>
</head>
<body>
<?php if (isset($_GET['registered']) && $_GET['registered'] === 'success'): ?>
    <p style="color: green; text-align: center;">Face successfully registered! Please log in.</p>
<?php endif; ?>

<div class="container">
    <h2>Login</h2>
    <form method="post">
        <input type="text" name="username" placeholder="Full Name" required>
        <input type="email" name="email" placeholder="Email Address" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" name="login">Login</button>
    </form>
    <div class="text-center">
        Don’t have an account? <a href="index.php">Sign Up</a>
    </div>
</div>

<!-- FACE LOGIN UI -->
<h3>OR</h3>
<div class="container">
    <button onclick="startFaceLogin()">Login with Face</button>
    <video id="video" autoplay></video>
    <canvas id="canvas" style="display:none;"></canvas>
    
    <p id="statusMsg"></p>
</div>

<script>
async function startFaceLogin() {
    const video = document.getElementById('video');
    const canvas = document.getElementById('canvas');
    const statusMsg = document.getElementById('statusMsg');
    video.style.display = 'block';
    statusMsg.textContent = 'Accessing camera...';

    try {
        const stream = await navigator.mediaDevices.getUserMedia({ video: true });
        video.srcObject = stream;
        statusMsg.textContent = 'Capturing face in 3 seconds...';

        setTimeout(() => {
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            const ctx = canvas.getContext('2d');
            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
            const image_data = canvas.toDataURL('image/jpeg');

            statusMsg.textContent = 'Verifying face...';

            fetch('face_login.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ face_image: image_data })
            })
            .then(res => res.json())
            .then(data => {
    if (data.success) {
        statusMsg.textContent = 'Login successful! Redirecting...';
        setTimeout(() => {
            window.location.href = 'welcome.php';
        }, 1500);
    } else {
        alert(data.message || 'Face not recognized.');
    }
})

            .catch(err => {
                console.error(err);
                statusMsg.textContent = 'Something went wrong.';
            });

            stream.getTracks().forEach(track => track.stop());
            video.style.display = 'none';
        }, 3000);

    } catch (error) {
        console.error(error);
        statusMsg.textContent = 'Camera access denied or not available.';
    }
}
</script>

</body>
</html>
