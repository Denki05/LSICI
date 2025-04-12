<!DOCTYPE html>
<html>
<head>
    <title>Scan QR Code</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
</head>
<body>
    <h4>Silakan scan QR Code Anda</h4>
    <div id="preview" style="width: 100%; max-width: 400px; margin: auto;"></div>

    <script>
        const html5QrCode = new Html5Qrcode("preview");

        Html5Qrcode.getCameras().then(devices => {
            if (devices && devices.length) {
                const cameraId = devices[0].id;

                html5QrCode.start(
                    cameraId,
                    {
                        fps: 10,
                        qrbox: 250
                    },
                    qrCodeMessage => {
                        html5QrCode.stop();

                        fetch("{{ route('guest.storeFromQr') }}", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": "{{ csrf_token() }}"
                            },
                            body: JSON.stringify({ slug: qrCodeMessage })
                        })
                        .then(res => res.json())
                        .then(data => {
                            alert(data.message || data.error);

                            if (window.opener) {
                                window.opener.location.reload();
                            }
                            window.close();
                        })
                        .catch(error => {
                            console.error("QR Processing Error:", error);
                            alert("Gagal memproses QR Code.");
                            window.close();
                        });
                    },
                    errorMessage => {
                        console.warn("Scan error:", errorMessage);
                    }
                );
            }
        }).catch(err => {
            console.error("Camera error:", err);
            alert("Tidak dapat mengakses kamera. Pastikan izin diberikan dan perangkat mendukung.");
        });
    </script>
</body>
</html>