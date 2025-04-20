<!DOCTYPE html>
<html>
<head>
    <title>Face Enrollment</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            background: #f9f9f9;
            padding: 40px;
        }
        #video {
            width: 100%;
            max-width: 400px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.3);
        }
        #capture, #submit {
            margin-top: 20px;
            margin-right: 10px;
            padding: 10px 20px;
            font-size: 16px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }
        #submit[disabled] {
            background-color: #aaa;
            cursor: not-allowed;
        }
        #error {
            color: red;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<h2>Face Enrollment</h2>
<p>Align your face and click "Capture" first, then "Submit"</p>

<video id="video" autoplay playsinline></video>
<div id="error"></div>
<br>
<button id="capture">Capture</button>
<button id="submit" disabled>Submit</button>

<script>
    const video = document.getElementById('video');
    const canvas = document.createElement('canvas');
    const errorDiv = document.getElementById('error');
    const captureButton = document.getElementById('capture');
    const submitButton = document.getElementById('submit');
    let imageBlob = null;

    console.log("Initializing camera...");

    navigator.mediaDevices.getUserMedia({ video: true })
        .then(stream => {
            console.log("Camera stream acquired.");
            video.srcObject = stream;
            video.onloadedmetadata = () => {
                video.play();
            };
        })
        .catch(err => {
            console.error("Camera error:", err);
            errorDiv.textContent = 'Camera not supported or permission denied: ' + err.message;
        });

    captureButton.onclick = () => {
        if (!video.videoWidth || !video.videoHeight) {
            alert("Camera not ready. Try again.");
            return;
        }

        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        canvas.getContext('2d').drawImage(video, 0, 0);
        canvas.toBlob(blob => {
            imageBlob = blob;
            submitButton.disabled = false;
            alert('Face captured. Now click Submit.');
        }, 'image/jpeg');
    };

    submitButton.onclick = () => {
        if (!imageBlob) return;

        const formData = new FormData();
        formData.append('face', imageBlob, 'face.jpg');

        fetch('save_face.php', {
            method: 'POST',
            body: formData
        }).then(response => response.text())
          .then(result => {
              alert(result);
              window.location.href = 'dashboard.php';
          }).catch(error => {
              errorDiv.textContent = 'Error submitting face: ' + error.message;
          });
    };
</script>


</body>
</html>
