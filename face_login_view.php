<!DOCTYPE html>
<html>
<head>
    <title>Face Recognition Login</title>
</head>
<body>
    <h2>Login with Face</h2>
    
    <video id="video" autoplay playsinline width="320" height="240"></video>
    <div id="error" style="color:red;"></div>
    <br>
    <button id="login">Login with Face</button>

    <script>
        const video = document.getElementById('video');
        const errorDiv = document.getElementById('error');
        const loginButton = document.getElementById('login');
        const canvas = document.createElement('canvas');

        navigator.mediaDevices.getUserMedia({ video: true })
            .then(stream => {
                video.srcObject = stream;
            })
            .catch(err => {
                errorDiv.textContent = 'Camera access denied or not available.';
                console.error('Camera error:', err);
            });

        loginButton.onclick = () => {
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            canvas.getContext('2d').drawImage(video, 0, 0);
            const dataURL = canvas.toDataURL('image/jpeg');

            fetch('face_login.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ face_image: dataURL })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert('Face recognized! Logging in...');
                    window.location.href = 'dashboard.php';
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                errorDiv.textContent = 'Error logging in: ' + error.message;
                console.error(error);
            });
        };
    </script>
</body>
</html>
