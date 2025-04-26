<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scan QR Code</title>
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <style>
        body {
            font-family: sans-serif;
            margin: 0;
            padding: 2rem;
            background-color: #f9fafb;
            text-align: center;
        }

        #preview {
            margin: 2rem auto;
            max-width: 400px;
            width: 100%;
        }

        .loader {
            margin-top: 1rem;
            display: none;
        }
    </style>
</head>
<body>
    <h2>Silakan Scan QR Code Anda</h2>
    <div id="preview"></div>
    <div class="loader" id="loader">Memproses...</div>

    <script>
        document.addEventListener("DOMContentLoaded", async () => {
            const previewElementId = "preview";
            const loader = document.getElementById("loader");
            const html5QrCode = new Html5Qrcode(previewElementId);

            try {
                const devices = await Html5Qrcode.getCameras();

                if (!devices.length) {
                    alert("Tidak ada kamera yang ditemukan.");
                    return;
                }

                // Cari kamera belakang (jika ada)
                const backCamera = devices.find(device =>
                    device.label.toLowerCase().includes("back") ||
                    device.label.toLowerCase().includes("rear") ||
                    device.label.toLowerCase().includes("environment")
                );

                const cameraId = backCamera ? backCamera.id : devices[0].id;

                await html5QrCode.start(
                    cameraId,
                    {
                        fps: 10,
                        qrbox: { width: 250, height: 250 }
                    },
                    async (qrCodeMessage) => {
                        try {
                            loader.style.display = "block";
                            await html5QrCode.stop();

                            const response = await fetch("{{ route('guest.storeFromQr') }}", {
                                method: "POST",
                                headers: {
                                    "Content-Type": "application/json",
                                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                                },
                                body: JSON.stringify({ slug: qrCodeMessage })
                            });

                            const data = await response.json();
                            
                            if (window.opener) {
                                window.opener.postMessage({
                                    type: "qrScanResult",
                                    status: data.success ? "success" : "error",
                                    message: data.success ? "Data berhasil dimuat dari QR Code." : (data.message || "Data tidak ditemukan."),
                                    payload: data
                                }, "*");
                            }
                            window.close();
                        } catch (error) {
                           if (window.opener) {
                                window.opener.postMessage({
                                    type: "qrScanResult",
                                    status: "error",
                                    message: "Terjadi kesalahan saat memproses QR Code."
                                }, "*");
                            }
                            window.close();
                        }
                    },
                    (errorMessage) => {
                        console.warn("Scan error:", errorMessage);
                    }
                );
            } catch (err) {
                console.error("Camera access error:", err);
                alert("Gagal mengakses kamera. Pastikan izin telah diberikan.");
            }
        });
    </script>
</body>
</html>