<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ambil Foto</title>

    <script>
        let videoStream;

        const startCamera = async () => {
            try {
                videoStream = await navigator.mediaDevices.getUserMedia({ video: true });
                document.getElementById("video").srcObject = videoStream;
            } catch (err) {
                alert(`Kamera tidak dapat diakses: ${err.message}`);
            }
        };

        const capturePhoto = () => {
            const canvas = document.getElementById("canvas");
            const context = canvas.getContext("2d");
            const video = document.getElementById("video");

            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            context.drawImage(video, 0, 0, canvas.width, canvas.height);

            const photoData = canvas.toDataURL("image/png");
            localStorage.setItem("capturedPhoto", photoData); // Save photo to localStorage

            stopCamera();
            window.close(); // Close tab after capturing photo
        };

        const stopCamera = () => {
            if (videoStream) {
                videoStream.getTracks().forEach(track => track.stop());
            }
        };

        window.addEventListener("beforeunload", stopCamera);
    </script>

    <style>
        body {
            text-align: center;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f9f9f9;
        }
        video, canvas {
            width: 100%;
            max-width: 400px;
            border-radius: 10px;
            margin: 10px 0;
        }
        button {
            padding: 10px 20px;
            margin: 5px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        .btn-primary {
            background-color: #007bff;
            color: white;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }
        .btn-secondary:hover {
            background-color: #5a6268;
        }
    </style>
</head>
<body onload="startCamera()">
    
    <h3>Ambil Foto</h3>
    <video id="video" autoplay></video>
    <canvas id="canvas" style="display: none;"></canvas>

    <div>
        <button onclick="capturePhoto()" class="btn btn-primary">Ambil Foto</button>
        <button onclick="window.close()" class="btn btn-secondary">Batal</button>
    </div>

</body>
</html>