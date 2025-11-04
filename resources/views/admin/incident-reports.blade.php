@extends('layouts.admin')

@section('content')
<div class="content-card">
    <div class="container py-4">

        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
            <div>
                <h2 class="fw-bold text-dark mb-0">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>Incident Reports
                </h2>
                <small class="text-muted">Monitor and manage reported incidents from citizens.</small>
            </div>
        </div>

        <div class="mb-4">
            <form id="filterForm" method="GET" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label for="status" class="form-label fw-semibold text-muted">Filter by Status</label>
                    <select class="form-select" name="status" id="status">
                        <option disabled selected>Select status</option>
                        <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="In Progress" {{ request('status') == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="Resolved" {{ request('status') == 'Resolved' ? 'selected' : '' }}>Resolved</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label for="date" class="form-label fw-semibold text-muted">Filter by Date</label>
                    <input type="date" name="date" id="date" value="{{ request('date') }}" class="form-control">
                </div>

                <div class="col-md-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary mt-2 w-50">
                        <i class="bi bi-funnel me-1"></i> Apply
                    </button>
                    <a href="{{ route('admin.incidents') }}" class="btn btn-secondary mt-2 w-50">
                        <i class="bi bi-arrow-clockwise me-1"></i> Reset
                    </a>
                </div>
            </form>
        </div>

        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table align-middle table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Type</th>
                                <th>Reported By</th>
                                <th>Location</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($reports as $report)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td class="fw-semibold">{{ $report->incident_type }}</td>
                                    <td>{{ $report->user->name ?? '—' }}</td>
                                    <td>{{ $report->location }}</td>
                                    <td>
                                        <span class="badge 
                                            @if($report->status == 'Pending') bg-warning 
                                            @elseif($report->status == 'In Progress') bg-info 
                                            @else bg-success @endif
                                            px-3 py-2 rounded-pill">
                                            {{ $report->status }}
                                        </span>
                                    </td>
                                    <td>{{ $report->created_at->format('M d, Y h:i A') }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-outline-success me-1"
                                                onclick='viewReport(@json($report))'
                                                title="View Details">
                                                <i class="bi bi-eye-fill"></i>
                                            </button>

                                            <button class="btn btn-sm btn-outline-primary me-1"
                                                onclick="assignResponder({{ $report->id }})"
                                                title="Assign Responder">
                                                <i class="bi bi-person-check-fill"></i>
                                            </button>

                                            <button class="btn btn-sm btn-outline-warning"
                                                onclick="updateStatus({{ $report->id }}, '{{ $report->status }}')"
                                                title="Update Status">
                                                <i class="bi bi-arrow-repeat"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">
                                        <i class="bi bi-info-circle me-1"></i>No incident reports found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function viewReport(report) {
    const type = report.incident_type || 'Unknown';
    const location = report.location || 'N/A';
    const status = report.status || 'N/A';

    let landmarks = [];
    let proofs = [];

    try {
        landmarks = Array.isArray(report.landmark_photos)
            ? report.landmark_photos
            : JSON.parse(report.landmark_photos || '[]');
    } catch {
        landmarks = [];
    }

    try {
        proofs = Array.isArray(report.proof_photos)
            ? report.proof_photos
            : JSON.parse(report.proof_photos || '[]');
    } catch {
        proofs = [];
    }

    const fixPath = (path) => {
        if (!path) return '';
        return path.startsWith('storage/') ? `/${path}` : `/storage/${path}`;
    };

    const landmarkSection = landmarks.length > 0
        ? landmarks.map(p => 
            `<img src="${fixPath(p)}"
                class="img-thumbnail shadow-sm"
                style='width:100px;height:100px;margin:5px;border-radius:10px;cursor:pointer'
                onclick='previewImage("${fixPath(p)}")'>`
        ).join('')
        : '<p class="text-muted fst-italic">No landmark photos provided.</p>';

    const proofSection = proofs.length > 0
        ? proofs.map(p => 
            `<img src="${fixPath(p)}"
                class="img-thumbnail shadow-sm"
                style='width:100px;height:100px;margin:5px;border-radius:10px;cursor:pointer'
                onclick='previewImage("${fixPath(p)}")'>`
        ).join('')
        : '<p class="text-muted fst-italic">No proof photos provided.</p>';

    const html = `
        <div class="text-start">
            <h5 class="fw-bold text-primary mb-2">${type}</h5>
            <p><i class="bi bi-geo-alt-fill text-danger me-1"></i><b>Location:</b> ${location}</p>
            <p><i class="bi bi-clock-fill text-secondary me-1"></i><b>Status:</b> 
                <span class="badge 
                    ${status === 'Pending' ? 'bg-warning' : 
                    status === 'In Progress' ? 'bg-info' : 
                    'bg-success'} px-3 py-2 rounded-pill">
                    ${status}
                </span>
            </p>
            <hr>
            <div class="mb-3">
                <h6 class="fw-semibold"><i class="bi bi-image-fill text-primary me-1"></i>Landmark Photos</h6>
                <div class="d-flex flex-wrap">${landmarkSection}</div>
            </div>
            <div>
                <h6 class="fw-semibold"><i class="bi bi-camera-fill text-success me-1"></i>Proof Photos</h6>
                <div class="d-flex flex-wrap">${proofSection}</div>
            </div>
        </div>
    `;

    Swal.fire({
        title: 'Incident Report Details',
        html: html,
        width: 650,
        showCloseButton: true,
        showConfirmButton: false,
        scrollbarPadding: false,
        customClass: { popup: 'rounded-4 shadow-lg' }
    });
}

function previewImage(src) {
    Swal.fire({
        imageUrl: src,
        imageWidth: 600,
        imageHeight: 400,
        showCloseButton: true,
        showConfirmButton: false
    });
}

function assignResponder(id) {
    Swal.fire({
        title: 'Assign Responder',
        input: 'select',
        inputOptions: {
            @foreach($responders as $r)
                {{ $r->id }}: '{{ $r->name }} ({{ $r->email }})',
            @endforeach
        },
        inputPlaceholder: 'Select responder',
        showCancelButton: true,
        confirmButtonText: 'Assign',
        confirmButtonColor: '#198754',
        preConfirm: (responder_id) => {
            Swal.showLoading(); // 🔹 Preloader while request is running
            return fetch(`/admin/incidents/${id}/assign`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ responder_id })
            })
            .then(res => {
                if (!res.ok) throw new Error(res.statusText);
                return res.json();
            })
            .catch(err => {
                Swal.showValidationMessage(`Request failed: ${err}`);
            });
        }
    }).then(result => {
        if (result.value) {
            Swal.fire({
                icon: result.value.status,
                title: result.value.title,
                text: result.value.message,
                confirmButtonColor: '#198754'
            });
            if (result.value.status === 'success') setTimeout(() => location.reload(), 1200);
        }
    });
}

function updateStatus(id, currentStatus) {
    Swal.fire({
        title: 'Update Status',
        input: 'select',
        inputOptions: {
            'Pending': 'Pending',
            'In Progress': 'In Progress',
            'Resolved': 'Resolved'
        },
        inputValue: currentStatus,
        showCancelButton: true,
        confirmButtonText: 'Update',
        confirmButtonColor: '#198754',
        preConfirm: (status) => {
            Swal.showLoading(); // 🔹 Preloader while request is running
            return fetch(`/admin/incidents/${id}/update-status`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ status })
            })
            .then(res => {
                if (!res.ok) throw new Error(res.statusText);
                return res.json();
            })
            .catch(err => {
                Swal.showValidationMessage(`Request failed: ${err}`);
            });
        }
    }).then(result => {
        if (result.value) {
            Swal.fire({
                icon: result.value.status,
                title: result.value.title,
                text: result.value.message,
                confirmButtonColor: '#198754'
            });
            if (result.value.status === 'success') setTimeout(() => location.reload(), 1200);
        }
    });
}
</script>
@endsection