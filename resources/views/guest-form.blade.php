<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Buku Tamu</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!--<link href="{{ asset('css/app.css') }}" rel="stylesheet">-->

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer></script>
    <!--<script src="{{ asset('js/app.js') }}" defer></script>-->

    <!-- QR Code Scanner Library -->
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js" defer></script>

    <style>
        body {
            background-color: #f0f2f5;
            font-family: 'Nunito', sans-serif;
        }
        .guest-form-container {
            width: 100%;
            max-width: 800px;
            background: #ffffff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
        .form-label {
            font-weight: 600;
            color: #495057;
        }
        .btn-hover {
            transition: all 0.3s ease;
        }
        .btn-hover:hover {
            background-color: #0056b3;
            color: #ffffff;
        }
        #photoPreview {
            max-height: 200px;
            object-fit: cover;
        }
        .hidden {
            display: none;
        }
    </style>
</head>
<body class="d-flex justify-content-center align-items-center min-vh-100">
    
    <div class="guest-form-container">
        @if (session('success'))
            <div id="alertMessage" class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <h2 class="text-center mb-4">Form Buku Tamu</h2>
        <form action="{{ url('/guest-form') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="name" class="form-label">Nama</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="phone" class="form-label">No HP</label>
                    <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}" required>
                    @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="company" class="form-label">Company</label>
                    <input type="text" class="form-control @error('company') is-invalid @enderror" id="company" name="company" value="{{ old('company') }}">
                    @error('company')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                
                <div class="col-12 mb-3">
                    <img id="photoPreview" class="img-thumbnail mt-2 hidden">
                </div>

                <div class="col-12 mb-3">
                    <label class="form-label">Ambil Foto:</label><br>
                    <button type="button" class="btn btn-success btn-sm" onclick="openCameraTab()">Buka Kamera</button>
                    <input type="hidden" name="photo" id="photoInput">
                </div>
            </div>

            <div class="d-flex justify-content-between">
                <button type="button" class="btn btn-info btn-hover" onclick="openQrScanner()">📷 Scan QR Code</button>
                <button type="submit" class="btn btn-primary btn-hover">Kirim</button>
            </div>
        </form>

        <div id="preview" class="mt-4" style="display: none; width: 100%; height: 300px;"></div>
    </div>

    <script>
        function openQrScanner() {
            window.open("{{ url('/cameraQr') }}", "_blank", "width=600,height=600");
        }
        
        function openCameraTab() {
            window.open("{{ url('/camera') }}", "_blank", "width=600,height=600");
        }
    
        // Auto-close alert after 2 seconds
        setTimeout(function() {
            let alert = document.getElementById("alertMessage");
            if (alert) {
                alert.classList.add("fade");
                setTimeout(() => alert.remove(), 500);
            }
        }, 2000);
        
        window.addEventListener("message", function(event) {
            const data = event.data;
        
            if (data && data.type === "qrScanResult") {
                const alertType = data.status === "success" ? "alert-success" : "alert-danger";
                const alertMessage = `
                    <div class="alert ${alertType} alert-dismissible fade show mt-3" role="alert">
                        ${data.message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `;
        
                document.querySelector(".guest-form-container").insertAdjacentHTML("afterbegin", alertMessage);
        
                // Auto-close
                setTimeout(() => {
                    const alertEl = document.querySelector(".alert");
                    if (alertEl) alertEl.remove();
                }, 3000);
        
                // Isi nama jika sukses dan ada data name
                if (data.status === "success" && data.payload?.name) {
                    document.getElementById("name").value = data.payload.name;
                }
            }
            
            // PHOTO TAKEN RESULT
    if (data && data.type === "photoTaken" && data.imageData) {
        const photoPreview = document.getElementById("photoPreview");
        const photoInput = document.getElementById("photoInput");

        photoPreview.src = data.imageData;
        photoPreview.classList.remove("hidden");
        photoInput.value = data.imageData;
    }
        });
    </script>

</body>
</html>