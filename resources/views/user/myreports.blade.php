@extends('layouts.user')

@section('content')
<div class="content-card">
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
            <h2 class="fw-bold text-dark">
                <i class="bi bi-clipboard2-data-fill me-2"></i>My Incident Reports
            </h2>
            <div>
                <select id="statusFilter" class="form-select form-select-sm">
                    <option value="all">All</option>
                    <option value="Pending">Pending</option>
                    <option value="In Progress">In Progress</option>
                    <option value="Resolved">Resolved</option>
                </select>
            </div>
        </div>

        @if($reports->isEmpty())
            <div class="alert alert-info text-center">
                <i class="bi bi-info-circle me-2"></i>No reports found yet.
            </div>
        @else
            <div class="row g-3" id="reportCards">
                @foreach($reports as $report)
                    @php
                        // Handle JSON or array format
                        $landmarkPhotos = is_string($report->landmark_photos)
                            ? json_decode($report->landmark_photos, true) ?? []
                            : ($report->landmark_photos ?? []);

                        $proofPhotos = is_string($report->proof_photos)
                            ? json_decode($report->proof_photos, true) ?? []
                            : ($report->proof_photos ?? []);
                    @endphp

                    <div class="col-md-6 col-lg-4 report-card" data-status="{{ $report->status }}">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-body">
                                <h5 class="card-title text-success fw-bold">
                                    <i class="bi bi-geo-alt-fill me-1"></i>{{ $report->incident_type }}
                                </h5>

                                <p class="text-muted small mb-1">
                                    <i class="bi bi-clock me-1"></i>
                                    {{ $report->date_reported ? \Carbon\Carbon::parse($report->date_reported)->format('M d, Y h:i A') : '—' }}
                                </p>
                                <p class="mb-2"><i class="bi bi-geo me-1"></i>{{ $report->location }}</p>

                                {{-- Landmark Photos --}}
                                @if(count($landmarkPhotos))
                                    <div class="mb-2">
                                        <strong>Landmark Photos:</strong>
                                        <div class="d-flex flex-wrap gap-2 mt-1">
                                            @foreach($landmarkPhotos as $photo)
                                                <img src="{{ asset($photo) }}"
                                                    class="img-thumbnail preview-img"
                                                    style="width: 60px; height: 60px; object-fit: cover; cursor:pointer"
                                                    onclick="previewImages(@json(array_map('asset', $landmarkPhotos)), 'Landmark Photos')">
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                {{-- Proof Photos --}}
                                @if(count($proofPhotos))
                                    <div class="mb-2">
                                        <strong>Proof Photos:</strong>
                                        <div class="d-flex flex-wrap gap-2 mt-1">
                                            @foreach($proofPhotos as $photo)
                                                <img src="{{ asset($photo) }}"
                                                    class="img-thumbnail preview-img"
                                                    style="width: 60px; height: 60px; object-fit: cover; cursor:pointer"
                                                    onclick="previewImages(@json(array_map('asset', $proofPhotos)), 'Proof Photos')">
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                <div class="d-flex justify-content-between align-items-center mt-2">
                                    <span class="badge 
                                        @if($report->status == 'Pending') bg-warning 
                                        @elseif($report->status == 'In Progress') bg-info 
                                        @else bg-success @endif">
                                        {{ $report->status }}
                                    </span>

                                    <button class="btn btn-outline-success btn-sm"
                                        onclick='viewReport(
                                            @json($report->reference_code),
                                            @json($report->incident_type),
                                            @json($report->location),
                                            @json($report->status),
                                            @json(array_map("asset", $landmarkPhotos)),
                                            @json(array_map("asset", $proofPhotos))
                                        )'>
                                        <i class="bi bi-eye-fill"></i> View
                                    </button>
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
    function viewReport(ref, type, location, status, landmarkPhotos = [], proofPhotos = []) {
        const renderPhotos = (photos, label) => {
            if (!photos || photos.length === 0) return '';
            return `
                <div class="mb-2 text-start">
                    <strong>${label}:</strong>
                    <div class="d-flex flex-wrap gap-2 mt-1 justify-content-start">
                        ${photos.map(photo => `
                            <img src="${photo}" 
                                class="swal-thumb"
                                style="width:60px;height:60px;object-fit:cover;cursor:pointer;border-radius:6px"
                                onclick="previewSingleImage('${photo}', '${label}')">
                        `).join('')}
                    </div>
                </div>
            `;
        };

        Swal.fire({
            title: `${type} Incident`,
            html: `
                <div class="text-start">
                    <p><b>Reference Code:</b> ${ref}</p>
                    <p><b>Location:</b> ${location}</p>
                    <p><b>Status:</b> ${status}</p>
                    ${renderPhotos(landmarkPhotos, 'Landmark Photos')}
                    ${renderPhotos(proofPhotos, 'Proof Photos')}
                </div>
            `,
            width: 600,
            confirmButtonText: 'Close',
            showCloseButton: true,
            customClass: { htmlContainer: 'text-start' }
        });
    }

    // Carousel-style preview for multiple images
    function previewImages(images, title) {
        let currentIndex = 0;
        const showImage = (index) => {
            Swal.fire({
                title: `${title} (${index + 1}/${images.length})`,
                imageUrl: images[index],
                imageWidth: 500,
                imageHeight: 400,
                showCancelButton: true,
                confirmButtonText: 'Next →',
                cancelButtonText: '← Prev',
                showCloseButton: true,
                didOpen: () => {
                    const prevButton = Swal.getCancelButton();
                    if (prevButton) prevButton.disabled = (index === 0);
                }
            }).then((result) => {
                if (result.isConfirmed && index < images.length - 1) {
                    showImage(index + 1);
                } else if (result.dismiss === Swal.DismissReason.cancel && index > 0) {
                    showImage(index - 1);
                }
            });
        };
        showImage(currentIndex);
    }

    // Single image enlargement
    function previewSingleImage(imageUrl, title = 'Image Preview') {
        Swal.fire({
            title: title,
            imageUrl: imageUrl,
            imageWidth: 700,
            imageHeight: 500,
            showCloseButton: true,
            confirmButtonText: 'Close'
        });
    }

    // Filter by status
    document.getElementById('statusFilter').addEventListener('change', function() {
        const selected = this.value;
        document.querySelectorAll('.report-card').forEach(card => {
            const status = card.dataset.status;
            card.style.display = (selected === 'all' || status === selected) ? '' : 'none';
        });
    });
</script>
@endsection