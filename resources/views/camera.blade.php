<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ambil Foto</title>

    <script>
        let videoStream;

        function startCamera() {
            navigator.mediaDevices.getUserMedia({ video: true })
                .then((stream) => {
                    videoStream = stream;
                    document.getElementById("video").srcObject = stream;
                })
                .catch((err) => {
                    alert("Kamera tidak dapat diakses: " + err);
                });
        }

        function capturePhoto() {
            let canvas = document.getElementById("canvas");
            let context = canvas.getContext("2d");
            let video = document.getElementById("video");

            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            context.drawImage(video, 0, 0, canvas.width, canvas.height);

            let photoData = canvas.toDataURL("image/png");
            localStorage.setItem("capturedPhoto", photoData); // Simpan foto ke localStorage
            
            stopCamera();
            window.close(); // Tutup tab setelah ambil foto
        }

        function stopCamera() {
            if (videoStream) {
                videoStream.getTracks().forEach(track => track.stop());
            }
        }
    </script>

    <style>
        body { text-align: center; }
        video, canvas { width: 100%; max-width: 400px; border-radius: 10px; }
    </style>
</head>
<body onload="startCamera()">
    
    <h3>Ambil Foto</h3>
    <video id="video" autoplay></video>
    <canvas id="canvas" class="hidden"></canvas>

    <div>
        <button onclick="capturePhoto()" class="btn btn-primary mt-2">Ambil Foto</button>
        <button onclick="window.close()" class="btn btn-secondary mt-2">Batal</button>
    </div>

</body>
</html>