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

    <div class="mb-4">
        <a href="{{ url('/cameraQr') }}" target="_blank" class="btn btn-primary mb-3">📷 Scan QR Code</a>
        <a href="{{ route('admin.export_guests') }}" target="_blank" class="btn btn-success mb-3">📤 Export Guests</a>
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
                    <!--<img src="{{ asset($guest->photo) }}" alt="Guest Photo" width="100">-->
                    <img src="{{ asset($guest->photo) }}" alt="Guest Photo" width="100">
                </td>
                <td>
                    <form action="{{ route('admin.guest.delete', $guest->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this guest?');">
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
            ],
            // dom: 'Bfrtip',
            // buttons: [
            //     {
            //         extend: 'excelHtml5',
            //         title: 'GuestBook',
            //         exportOptions: {
            //             columns: ':visible'
            //         },
            //         className: 'btn btn-success'
            //     },
            // ]
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