<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LS | ICI 2025</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            flex-direction: column;
        }

        .qr-container {
            width: 100%;
            max-width: 320px;
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            position: relative;
            top: -20px;
        }

        h3 {
            font-size: 1.4rem;
            font-weight: bold;
            margin-bottom: 15px;
        }

        #qrcode {
            display: inline-block;
            width: 100%;
            max-width: 250px;
            height: auto;
        }

        /* Notifikasi Alert */
        .alert-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1050;
        }

        /* Responsif untuk layar kecil */
        @media (max-width: 576px) {
            .qr-container {
                max-width: 90%;
                padding: 20px;
                top: -10px;
            }

            h3 {
                font-size: 1.2rem;
            }

            #qrcode {
                max-width: 200px;
            }
        }
    </style>
</head>
<body>

    <!-- Notifikasi -->
    <div class="alert-container">
        @if(session('success'))
            <div id="alertMessage" class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    </div>

    <div class="qr-container">
        <h3>Scan QR Code untuk mengisi Buku Tamu</h3>
        <div id="qrcode"></div>
    </div>

    <script>
        // Generate QR Code
        var qr = new QRCode(document.getElementById("qrcode"), {
            text: "{{ url('/guest-form') }}",
            width: 250,
            height: 250
        });
    </script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>