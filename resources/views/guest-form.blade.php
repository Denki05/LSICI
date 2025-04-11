<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Buku Tamu</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- Google Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer></script>
    <script src="{{ asset('js/app.js') }}" defer></script>

    <script>
        // Menutup alert otomatis setelah 5 detik
        setTimeout(function() {
            let alert = document.getElementById("alertMessage");
            if (alert) {
                alert.classList.remove("show");  // Menghilangkan kelas 'show' Bootstrap
                alert.classList.add("fade");     // Menambah efek fade
                setTimeout(() => alert.remove(), 500); // Hapus elemen setelah efek fade
            }
        }, 2000);
    </script>


    <style>
        body {
            background-color: #f8f9fa;
        }

        .guest-form-container {
            width: 100%;
            max-width: 500px;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }

        video, canvas {
            width: 100%;
            border-radius: 5px;
        }

        .hidden {
            display: none;
        }
    </style>
</head>
<body class="d-flex justify-content-center align-items-center min-vh-100">
    
    <div class="guest-form-container">

        <!-- Notifikasi -->
        @if(session('success'))
            <div id="alertMessage" class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <h3 class="text-center mb-4">Isi Buku Tamu</h3>
        <form action="{{ url('/guest-form') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label for="name" class="form-label fw-bold">Nama:</label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" required>
                @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="phone" class="form-label fw-bold">No. HP:</label>
                <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" required>
                @error('phone')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="email" class="form-label fw-bold">Email:</label>
                <input type="email" name="email" class="form-control">
            </div>

            <div class="mb-3">
                <label for="company" class="form-label fw-bold">Company:</label>
                <input type="text" name="company" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Ambil Foto:</label>
                <button type="button" class="btn btn-success btn-sm" onclick="openCameraTab()">Buka Kamera</button>  
                <input type="hidden" name="photo" id="photoInput">
                <img id="photoPreview" class="img-thumbnail mt-2 hidden">
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-primary fw-bold py-2 btn-hover">
                    Submit
                </button>
            </div>
        </form>
    </div>

    <script>
        function openCameraTab() {
            let cameraWindow = window.open("{{ url('/camera') }}", "_blank", "width=600,height=600");

            let checkPhoto = setInterval(() => {
                let photoData = localStorage.getItem("capturedPhoto");
                if (photoData) {
                    document.getElementById("photoInput").value = photoData; // Simpan ke input hidden
                    document.getElementById("photoPreview").src = photoData;
                    document.getElementById("photoPreview").classList.remove("hidden");

                    localStorage.removeItem("capturedPhoto");
                    clearInterval(checkPhoto);
                    cameraWindow.close();
                }
            }, 1000);
        }
    </script>

</body>
</html>