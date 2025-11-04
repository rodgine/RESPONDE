@extends('layouts.responder')

@section('content')
<div class="content-card">
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
            <h2 class="fw-bold text-dark mb-0">
                <i class="bi bi-journal-check me-2"></i>Assigned Incidents
            </h2>
        </div>

        @if($reports->isEmpty())
            <div class="alert alert-info text-center">
                <i class="bi bi-info-circle me-2"></i>No assigned incidents yet.
            </div>
        @else
            <div class="row g-3" id="reportCards">
                @foreach($reports as $report)
                    @php
                        $landmarkPhotos = is_string($report->landmark_photos) 
                            ? json_decode($report->landmark_photos, true) 
                            : ($report->landmark_photos ?? []);

                        $proofPhotos = is_string($report->proof_photos) 
                            ? json_decode($report->proof_photos, true) 
                            : ($report->proof_photos ?? []);
                    @endphp

                    <div class="col-md-6 col-lg-4 report-card">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-body">
                                <h5 class="card-title text-success fw-bold">
                                    <i class="bi bi-geo-alt-fill me-1"></i>{{ $report->incident_type }}
                                </h5>

                                <p class="text-muted small mb-1">
                                    <i class="bi bi-clock me-1"></i>
                                    {{ $report->date_reported ? $report->date_reported->format('M d, Y h:i A') : '—' }}
                                </p>
                                <p class="mb-2"><i class="bi bi-geo me-1"></i>{{ $report->location }}</p>

                                <div class="d-flex justify-content-between align-items-center mt-2">
                                    <span class="badge 
                                        @if($report->status == 'Pending') bg-warning 
                                        @elseif($report->status == 'In Progress') bg-info 
                                        @else bg-success @endif">
                                        {{ $report->status }}
                                    </span>

                                    <div class="btn-group">
                                        <button class="btn btn-outline-success btn-sm"
                                            onclick='viewReport(
                                                @json($report->incident_type),
                                                @json($report->location),
                                                @json($landmarkPhotos),
                                                @json($proofPhotos)
                                            )'>
                                            <i class="bi bi-eye-fill"></i> View
                                        </button>

                                        @if($report->status !== 'Resolved')
                                        <button class="btn btn-outline-primary btn-sm" onclick="submitReport({{ $report->id }})">
                                            <i class="bi bi-check2-circle"></i> Submit
                                        </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

{{-- SweetAlert --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function viewReport(type, location, landmarks, proofs) {
        let html = `
            <p><b>Location:</b> ${location}</p>
            <div><b>Landmark Photos:</b><br>
                ${landmarks.map(p => 
                    `<img src="/${p}" style='width:70px;height:70px;margin:3px;border-radius:8px;cursor:pointer' onclick='previewImage("/${p}")'>`
                ).join('')}
            </div>
            <hr>
            <div><b>Proof Photos:</b><br>
                ${proofs.map(p => 
                    `<img src="/${p}" style='width:70px;height:70px;margin:3px;border-radius:8px;cursor:pointer' onclick='previewImage("/${p}")'>`
                ).join('')}
            </div>
        `;
        Swal.fire({
            title: type,
            html: html,
            icon: 'info',
            width: 600,
            showCloseButton: true
        });
    }

    function previewImage(src) {
        Swal.fire({
            imageUrl: src,
            imageWidth: 600,
            imageHeight: 400,
            showCloseButton: true
        });
    }

    function submitReport(reportId) {
        Swal.fire({
            title: 'Submit Incident Report',
            html: `
                <div class="d-flex flex-column gap-3">

                    <div>
                        <label for="details" class="form-label fw-semibold">Details</label>
                        <textarea id="details" class="form-control" placeholder="Enter details" rows="4"></textarea>
                    </div>

                    <div>
                        <label for="action_taken" class="form-label fw-semibold">Action Taken</label>
                        <textarea id="action_taken" class="form-control" placeholder="Enter action taken" rows="3"></textarea>
                    </div>

                    <div class="row">
                        <div class="col">
                            <label class="form-label fw-semibold">Victims</label>
                            <input type="number" id="victims_count" class="form-control" min="0" placeholder="0">
                        </div>

                        <div class="col">
                            <label class="form-label fw-semibold">Deaths</label>
                            <input type="number" id="deaths_count" class="form-control" min="0" placeholder="0">
                        </div>

                        <div class="col">
                            <label class="form-label fw-semibold">Rescued</label>
                            <input type="number" id="rescued_count" class="form-control" min="0" placeholder="0">
                        </div>
                    </div>

                    <div>
                        <label class="form-label fw-semibold">Date Resolved</label>
                        <input type="datetime-local" id="date_resolved" class="form-control">
                    </div>

                    <div>
                        <label for="documentation" class="form-label fw-semibold">Documentation</label>
                        <input type="file" id="documentation" class="form-control" multiple accept="image/*">
                        <small class="text-muted">You can upload multiple images.</small>
                    </div>

                    <small class="text-danger mt-2">All fields are required including documentation.</small>
                </div>
            `,
            focusConfirm: false,
            showCancelButton: true,
            confirmButtonText: 'Submit',
            preConfirm: () => {
                const details = document.getElementById('details').value.trim();
                const action_taken = document.getElementById('action_taken').value.trim();
                const victims_count = document.getElementById('victims_count').value;
                const deaths_count = document.getElementById('deaths_count').value;
                const rescued_count = document.getElementById('rescued_count').value;
                const date_resolved = document.getElementById('date_resolved').value;
                const documentation = document.getElementById('documentation').files;

                if (!details || !action_taken || !victims_count || !deaths_count || !rescued_count || !date_resolved || documentation.length === 0) {
                    Swal.showValidationMessage('All fields including documentation are required');
                    return false;
                }

                const promises = Array.from(documentation).map(file => {
                    return new Promise((resolve, reject) => {
                        const reader = new FileReader();
                        reader.onload = e => resolve(e.target.result);
                        reader.onerror = err => reject(err);
                        reader.readAsDataURL(file);
                    });
                });

                return Promise.all(promises).then(documents => {
                    return {
                        details,
                        action_taken,
                        victims_count,
                        deaths_count,
                        rescued_count,
                        date_resolved,
                        documentation: documents
                    };
                });
            }
        }).then(result => {
            if (result.value) {
                Swal.fire({
                    title: 'Submitting...',
                    text: 'Please wait while your report is being processed.',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });

                fetch(`/responder/incidents/${reportId}/submit`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(result.value)
                })
                .then(res => res.json())
                .then(data => {
                    Swal.fire({
                        icon: data.status,
                        title: data.title,
                        text: data.message,
                        confirmButtonText: 'OK'
                    });
                    if (data.status === 'success') {
                        setTimeout(() => location.reload(), 1200);
                    }
                })
                .catch(() => {
                    Swal.fire('Error', 'An unexpected error occurred. Please try again.', 'error');
                });
            }
        });
    }
</script>
@endsection
