@extends('layouts.app')

@section('content')
<div class="container">
    @if(session('import_success') || session('import_failed'))
        <div class="alert alert-info">
            <h5>Hasil Import:</h5>
            @if(session('import_success'))
                <p><strong class="text-success">{{ count(session('import_success')) }} data berhasil diimport.</strong></p>
            @endif
            @if(session('import_failed'))
                <p><strong class="text-danger">{{ count(session('import_failed')) }} data gagal diimport:</strong></p>
                <ul>
                    @foreach(session('import_failed') as $row)
                        <li>
                            <b>{{ $row['name'] ?? 'Tidak diketahui' }}</b>
                            @if(isset($row['error']))
                                <span class="text-danger"> - {{ $row['error'] }}</span>
                            @endif
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    @endif

    <h2 class="mb-4">List Customer Invitations</h2>

    <div class="mb-3">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
            Manage
        </button>
    </div>
    
    <table class="table table-striped" id="invitations">
        <thead>
            <tr>
                <th>#</th>
                <th>Nama</th>
                <th>Attendance</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($customers as $customer)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $customer->name }}</td>
                <td>
                    @if($customer->attendance == 1)
                        <span class="badge bg-success">Yes</span>
                    @else
                        <span class="badge bg-danger">No</span>
                    @endif
                </td>
                <td>
                    @if($customer->is_invitation_generated == 0)
                        <a href="{{ route('admin.generateInvitation', $customer->id) }}" class="btn btn-secondary active" role="button" aria-pressed="true">
                            Generate
                        </a>
                    @else
                        <a href="{{ route('admin.rsvp.page', base64_encode($customer->slug)) }}" class="btn btn-success active" role="button" aria-pressed="true" target="_blank">
                            View
                        </a>
                    @endif
                </td>
            
            @endforeach
        </tbody>
    </table>
</div>

<!-- Modal export & Import -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="exampleModalLabel">Manage Invitations</h5>
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
                        <a href="{{ route('admin.export') }}" class="btn btn-info btn-sm">
                            <i class="fa fa-download me-2"></i>Download Template
                        </a>
                        <hr>
                        <form action="{{ route('admin.import') }}" method="POST" enctype="multipart/form-data">
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
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
   $(document).ready(function() {
        $('#invitations').DataTable({
           paging: true,
           pageLength: 5,
           lengthMenu: [5, 10, 25, 50, 100],
           order: [[0, 'asc']],
           responsive: true,
           columnDefs: [
               { targets: [2], orderable: false } // Nonaktifkan sorting pada kolom "Action"
           ]
       });
   });
</script>
@endpush