<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ambil Foto</title>

  <script>
    let videoStream;
    let videoTrack;
    let zoomCapability;
    let videoDevices = [];
    let currentCameraIndex = 0;

    const startCamera = async (deviceId = null) => {
      try {
        if (videoStream) {
          videoStream.getTracks().forEach(track => track.stop());
        }

        const constraints = {
          video: deviceId
            ? { deviceId: { exact: deviceId } }
            : { facingMode: "environment" }
        };

        videoStream = await navigator.mediaDevices.getUserMedia(constraints);
        const video = document.getElementById("video");
        video.srcObject = videoStream;

        videoTrack = videoStream.getVideoTracks()[0];
        const capabilities = videoTrack.getCapabilities();

        const zoomControl = document.getElementById("zoomControl");

        if (capabilities.zoom) {
          zoomCapability = capabilities.zoom;
          zoomControl.style.display = "block";
        } else {
          zoomCapability = null;
          zoomControl.style.display = "none";
        }

      } catch (err) {
        alert(`Kamera tidak dapat diakses: ${err.message}`);
      }
    };

    const applyZoomPreset = (zoomLevel) => {
      if (!videoTrack || !zoomCapability) return;

      const clampedZoom = Math.max(zoomCapability.min, Math.min(zoomLevel, zoomCapability.max));
      videoTrack.applyConstraints({
        advanced: [{ zoom: clampedZoom }]
      });
    };

    const listAndStartCameras = async () => {
      const devices = await navigator.mediaDevices.enumerateDevices();
      videoDevices = devices.filter(device => device.kind === "videoinput");

      if (videoDevices.length > 0) {
        currentCameraIndex = 0;
        await startCamera(videoDevices[currentCameraIndex].deviceId);
      }
    };

    const switchCamera = async () => {
      if (videoDevices.length <= 1) return;

      currentCameraIndex = (currentCameraIndex + 1) % videoDevices.length;
      const nextDeviceId = videoDevices[currentCameraIndex].deviceId;
      await startCamera(nextDeviceId);
    };

    const capturePhoto = () => {
      const canvas = document.getElementById("canvas");
      const context = canvas.getContext("2d");
      const video = document.getElementById("video");

      canvas.width = video.videoWidth;
      canvas.height = video.videoHeight;
      context.drawImage(video, 0, 0, canvas.width, canvas.height);

      const photoData = canvas.toDataURL("image/png");

      if (window.opener) {
        window.opener.postMessage({
          type: "photoTaken",
          imageData: photoData
        }, "*");
      }

      stopCamera();
      window.close();
    };

    const stopCamera = () => {
      if (videoStream) {
        videoStream.getTracks().forEach(track => track.stop());
      }
    };

    window.addEventListener("beforeunload", stopCamera);
    window.addEventListener("DOMContentLoaded", listAndStartCameras);
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
    .icon-button {
      background-color: transparent;
      border: none;
      cursor: pointer;
      font-size: 24px;
      margin-bottom: 10px;
    }
  </style>
</head>
<body>

  <h3>Ambil Foto</h3>

  <button onclick="switchCamera()" class="icon-button" title="Ganti Kamera">
    🔄
  </button>

  <video id="video" autoplay playsinline></video>
  <canvas id="canvas" style="display: none;"></canvas>

  <div id="zoomControl" style="display: none; margin-top: 10px;">
    <button onclick="applyZoomPreset(1)" class="btn btn-secondary">1x</button>
    <button onclick="applyZoomPreset(2)" class="btn btn-secondary">2x</button>
    <button onclick="applyZoomPreset(3)" class="btn btn-secondary">3x</button>
  </div>

  <div>
    <button onclick="capturePhoto()" class="btn btn-primary">Ambil Foto</button>
    <button onclick="window.close()" class="btn btn-secondary">Batal</button>
  </div>

</body>
</html>