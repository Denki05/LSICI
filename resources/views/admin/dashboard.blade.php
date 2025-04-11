@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">List Guest Book</h2>

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
</script>
@endpush