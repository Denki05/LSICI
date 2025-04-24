@extends('layouts.app')
<style>
        .searchBox {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 5px;
        }

        .searchContainer .btn {
            margin-right: 5px;
        }

        .filtersContainer {
            background-color: #ffffff;
            padding: 15px;
            border: 1px solid #dee2e6;
            border-radius: 5px;
        }

        .filtersContainer.d-none {
            display: none;
        }
    </style>
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

    <!-- Notifikasi import excel -->
    @if(session('import_success') || session('import_failed'))
        <div class="alert">
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
        <a class="btn btn-success" href="{{ route('admin.create') }}" role="button">Create</a>

        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
            Manage
        </button>
    </div>

    <!-- Search Form -->
    <form method="GET" action="{{ route('admin.rsvp') }}">
        <div class="searchBox p-3 mb-4 border rounded bg-light">

            <div class="searchContainer">
                <div class="input-group mb-3">
                    <input type="text" name="search" class="form-control" placeholder="Search" value="{{ request('search') }}">
                    <button class="btn btn-outline-primary" type="submit">
                        <i class="fas fa-search"></i> Search
                    </button>

                    <a href="{{ route('admin.rsvp') }}" class="btn btn-outline-danger" onclick="localStorage.removeItem('showAdvancedSearch')">
                        <i class="fas fa-times"></i> Reset
                    </a>
                    
                    <button class="btn btn-outline-secondary" type="button" onclick="toggleFilters()">
                        <i class="fas fa-sliders-h"></i> Advanced Search
                    </button>
                </div>
            </div>

            <!-- Advanced Filters -->
            <div class="filtersContainer mt-3 d-none" id="FiltersBox">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label"><i class="fas fa-folder"></i> Customer:</label>
                        <select class="form-select" name="customer_id">
                            <option value="">-- All Customers --</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}" {{ request('customer_id') == $customer->id ? 'selected' : '' }}>
                                    {{ $customer->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label"><i class="far fa-folder"></i> Officer:</label>
                        <select class="form-select" name="officer">
                            <option value="">-- All Officers --</option>
                            @foreach($officers as $officer)
                                <option value="{{ $officer->officer }}" {{ request('officer') == $officer->officer ? 'selected' : '' }}>
                                    {{ $officer->officer }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label"><i class="fas fa-file"></i> Attendance:</label>
                        <select class="form-select" name="attendance">
                            <option value="">-- All --</option>
                            <option value="1" {{ request('attendance') == '1' ? 'selected' : '' }}>YES</option>
                            <option value="2" {{ request('attendance') == '2' ? 'selected' : '' }}>NO</option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label"><i class="fas fa-file"></i> Status of Invitation:</label>
                        <select class="form-select" name="invitation_status">
                            <option value="">-- All --</option>
                            <option value="1" {{ request('invitation_status') == '1' ? 'selected' : '' }}>Generate</option>
                            <option value="0" {{ request('invitation_status') == '0' ? 'selected' : '' }}>Not Generate</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <table class="table table-striped" id="invitations">
        <thead>
            <tr>
                <th>#</th>
                <th>Nama</th>
                <th>Officer</th>
                <th>Attendance</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($customers as $customer)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $customer->name }}</td>
                <td>{{ $customer->officer }}</td>
                <td>
                    @if($customer->attendance_label == 'Attended')
                        <span class="badge bg-success">Attended</span>
                    @elseif($customer->attendance_label == 'Not Attended')
                        <span class="badge bg-danger">Not Attended</span>
                    @else
                        <span class="badge bg-secondary">Pending</span>
                    @endif
                </td>
                <td>
                    @if($customer->is_invitation_generated == 0)
                        <a href="{{ route('admin.generateInvitation', $customer->id) }}" class="btn btn-secondary active" role="button" aria-pressed="true">
                            Generate
                        </a>
                    @else
                        <button class="btn btn-info active" onclick="copyToClipboard('{{ route('admin.rsvp.page', base64_encode($customer->slug)) }}')" role="button" aria-pressed="true">
                            Copy Link
                        </button>

                        <a href="{{ route('admin.rsvp.page', base64_encode($customer->slug)) }}" class="btn btn-success active" role="button" aria-pressed="true" target="_blank">
                            View
                        </a>
                    @endif

                    <form action="{{ route('admin.rsvp.delete', $customer->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this invitation?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger active" role="button" aria-pressed="true">
                            Delete
                        </button>
                    </form>
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
           pageLength: 10,
           lengthMenu: [10, 25, 50, 100],
           order: [[0, 'asc']],
           responsive: true,
           searching: false, // Disable search functionality
           columnDefs: [
               { targets: [2], orderable: false }
           ]
       });
    });

    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(() => {
            alert('Link copied to clipboard!');
        }).catch(err => {
            console.error('Failed to copy text: ', err);
        });
    }

    function toggleFilters() {
        const filtersBox = document.getElementById('FiltersBox');
        const isHidden = filtersBox.classList.contains('d-none');

        if (isHidden) {
            filtersBox.classList.remove('d-none');
            localStorage.setItem('showAdvancedSearch', 'true');
        } else {
            filtersBox.classList.add('d-none');
            localStorage.removeItem('showAdvancedSearch');
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        const filtersBox = document.getElementById('FiltersBox');
        const showAdvanced = localStorage.getItem('showAdvancedSearch');

        if (showAdvanced === 'true') {
            filtersBox.classList.remove('d-none');
        }
    });
</script>
@endpush