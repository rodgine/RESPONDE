@extends('layouts.responder')

@section('content')
<div class="container py-5">
    {{-- Card --}}
    <div class="card shadow-sm rounded-4 p-4">
        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <img src="{{ asset('images/mdrrmologo.png') }}" alt="MDRRMO Logo" style="height:80px;">
            <h2 class="text-center fw-bold">Incident Reporting System</h2>
            <img src="{{ asset('images/respondeLogo.png') }}" alt="System Logo" style="height:80px;">
        </div>

        <hr>

        {{-- I. Details of the Incident --}}
        <h4 class="fw-bold mt-4">I. Details of the Incident</h4>
        <table class="table table-borderless table-striped">
            <tbody>
                <tr>
                    <th>Type of Incident:</th>
                    <td>{{ $report->incident_type }}</td>
                </tr>
                <tr>
                    <th>Reported By:</th>
                    <td>{{ $report->user->name ?? '—' }}</td>
                </tr>
                <tr>
                    <th>Date & Time:</th>
                    <td>{{ $report->date_reported?->format('M d, Y h:i A') }}</td>
                </tr>
                <tr>
                    <th>Location:</th>
                    <td>{{ Str::limit($report->location, 100) }}</td>
                </tr>
            </tbody>
        </table>

        {{-- II. Narrative of the Incident --}}
        <h4 class="fw-bold mt-4">II. Narrative of the Incident</h4>
        <p><strong>Details:</strong> {{ $report->incident->details ?? '—' }}</p>
        <p><strong>Action Taken:</strong></p>
        @php
            $actionTaken = $report->incident->action_taken ?? '';
            $actionItems = preg_split('/[\-\.]\s*/', $actionTaken, -1, PREG_SPLIT_NO_EMPTY);
        @endphp
        @if(count($actionItems))
            <ul class="ms-3">
                @foreach($actionItems as $item)
                    <li>{{ trim($item) }}</li>
                @endforeach
            </ul>
        @else
            <p>—</p>
        @endif

        {{-- III. Photos Taken --}}
        <h4 class="fw-bold mt-4">III. Photos Taken</h4>

        @php
            $landmarks = is_string($report->landmark_photos) ? json_decode($report->landmark_photos, true) : ($report->landmark_photos ?? []);
            $proofs = is_string($report->proof_photos) ? json_decode($report->proof_photos, true) : ($report->proof_photos ?? []);
            $docs = $report->incident ? ($report->incident->documentation ?? []) : [];
        @endphp

        <div class="mb-3">
            <strong>Landmark Photos:</strong>
            <div class="d-flex flex-wrap gap-2 mt-2">
                @foreach($landmarks as $photo)
                    <img src="{{ asset($photo) }}" class="img-thumbnail" style="width:180px;height:180px;object-fit:cover;cursor:pointer;" onclick="previewImage('{{ asset($photo) }}')">
                @endforeach
            </div>
        </div>

        <div class="mb-3">
            <strong>Proof Photos:</strong>
            <div class="d-flex flex-wrap gap-2 mt-2">
                @foreach($proofs as $photo)
                    <img src="{{ asset($photo) }}" class="img-thumbnail" style="width:180px;height:180px;object-fit:cover;cursor:pointer;" onclick="previewImage('{{ asset($photo) }}')">
                @endforeach
            </div>
        </div>

        <div class="mb-3">
            <strong>Documentation:</strong>
            <div class="d-flex flex-wrap gap-2 mt-2">
                @if($docs)
                    @foreach($docs as $doc)
                        <img src="{{ asset('storage/' . $doc) }}" 
                            class="img-thumbnail" 
                            style="width:180px;height:180px;object-fit:cover;cursor:pointer;" 
                            onclick="previewImage('{{ asset('storage/' . $doc) }}')">
                    @endforeach
                @else
                    <p class="text-muted">No documentation uploaded.</p>
                @endif
            </div>
        </div>

        <div class="text-end mt-4">
            <a href="{{ route('responder.completed') }}" class="btn btn-secondary">Back</a>
        </div>
    </div>
</div>

{{-- SweetAlert for image preview --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function previewImage(src) {
    Swal.fire({
        imageUrl: src,
        imageAlt: 'Incident Photo',
        imageWidth: 600,
        imageHeight: 400,
        showCloseButton: true
    });
}
</script>
@endsection