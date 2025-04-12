@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">List Guest Book</h2>

    <div class="mb-4">
        <a href="{{ url('/cameraQr') }}" target="_blank" class="btn btn-primary mb-3">📷 Scan QR Code</a>
    </div>

    <table class="table table-striped" id="guestbook">
        <thead>
            <tr>
                <th>#</th>
                <th>Nama</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Company</th>
                <th>Action</th>
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
                    <form action="{{ route('admin.guest.delete', $guest->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script>
   $(document).ready(function() {
        $('#guestbook').DataTable({
           paging: true,
           pageLength: 5,
           lengthMenu: [5, 10, 25, 50, 100],
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