@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Notifikasi sukses -->
    @if (session('success'))
        <div id="flash-success" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
        <script>
            setTimeout(() => {
                const alert = document.getElementById('flash-success');
                if (alert) {
                    alert.remove();
                }
            }, 3000); // hilang setelah 3 detik
        </script>
    @endif

    <h2 class="mb-4">List Guest Book</h2>

    <div class="mb-4 d-flex gap-2">
        <a href="{{ route('admin.export_guests') }}" target="_blank" class="btn btn-success mb-3">
            📤 Export Guests
        </a>
        <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#exampleModal">
            🛠️ Manage
        </button>
    </div>

    <table class="table table-striped" id="guestbook">
        <thead>
            <tr>
                <th>#</th>
                <th>Nama</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Company</th>
                <th>Foto</th>
            </tr>
        </thead>
        <tbody>
            @foreach($guests as $guest)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $guest->name }}</td>
                <td>{{ $guest->phone }}</td>
                <td>{{ $guest->email }}</td>
                <td>{{ $guest->company }}</td>
                <td>
                    @if($guest->is_invitation != 1 && $guest->photo)
                        <a href="{{ asset($guest->photo) }}" target="_blank">
                            <img src="{{ asset($guest->photo) }}" alt="Guest Photo" width="100">
                        </a>

                        <!-- <img src="{{ url('guestbook/public/' . $guest->photo) }}" alt="Guest Photo" width="100"> -->
                    @else
                        <form action="{{ route('admin.guest.upload_photo', $guest->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="file" name="photo" accept="image/*" onchange="this.form.submit()">
                        </form>
                    @endif
                </td>
                
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- export & import -->
 <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="exampleModalLabel">Manage GuestBook</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md">
                        <h6 class="font-weight-bold">Import</h6>
                        <p>
                            Import your data using the template provided below.<br>
                            <span class="text-danger"><b>Do not</b></span> modify or remove the header (first row).<br>
                            Only fill in the allowed columns; additional columns will be ignored.
                        </p>
                        @if(isset($import_custom_message))
                        <div class="alert alert-info">
                            <strong>Note:</strong><br>
                            {!! $import_custom_message !!}
                        </div>
                        @endif
                        <a href="{{ route('admin.guest.export') }}" class="btn btn-info btn-sm">
                            <i class="fa fa-download me-2"></i>Download Template
                        </a>
                        <hr>
                        <form action="{{ route('admin.guest.import') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="import_file" class="form-label">Choose File</label>
                                <input type="file" class="form-control" id="import_file" name="import_file" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Import</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script>
   $(document).ready(function() {
        $('#guestbook').DataTable({
           paging: true,
           pageLength: 10,
           lengthMenu: [20, 50, 100],
           order: [[0, 'asc']],
           responsive: true,
           columnDefs: [
               { targets: [4], orderable: false } // Nonaktifkan sorting pada kolom "Action"
           ]
        });
   });

   function startQrScanner() {
            const html5QrCode = new Html5Qrcode("preview");
            document.getElementById("preview").style.display = "block";

            html5QrCode.start(
                { facingMode: "environment" },
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
                        location.reload();
                    })
                    .catch(error => {
                        console.error("QR Processing Error:", error);
                        alert("Failed to process QR Code.");
                    });
                },
                errorMessage => {
                    // Anda bisa log atau abaikan
                }
            );
        }
</script>
@endpush