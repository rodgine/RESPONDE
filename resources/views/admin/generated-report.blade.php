@extends('layouts.admin')

@section('content')
<div class="container py-5">
    <div class="card shadow-sm rounded-4 p-4" id="printableArea">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <img src="{{ asset('images/mdrrmologo.png') }}" alt="MDRRMO Logo" style="height:80px;">
            <h2 class="text-center fw-bold">Incident Reporting System</h2>
            <img src="{{ asset('images/respondeLogo.png') }}" alt="System Logo" style="height:80px;">
        </div>
        <hr>
        {{-- Incident Details --}}
        <h4 class="fw-bold mt-4">I. Incident Details</h4>
        <table class="table table-borderless table-striped">
            <tbody>
                <tr>
                    <th>Type of Incident:</th>
                    <td>{{ $report->incident_type }}</td>
                </tr>
                <tr>
                    <th>Reported By (Citizen):</th>
                    <td>{{ $report->user->name ?? '—' }}</td>
                </tr>
                <tr>
                    <th>Responder:</th>
                    <td>{{ $report->incident->responder->name ?? '—' }}</td>
                </tr>
                <tr>
                    <th>Date Reported:</th>
                    <td>{{ $report->date_reported?->format('M d, Y h:i A') }}</td>
                </tr>
                <tr>
                    <th>Location:</th>
                    <td>{{ $report->location }}</td>
                </tr>
                <tr>
                    <th>Number of Victims:</th>
                    <td>{{ $counts['victims'] }}</td>
                </tr>
                <tr>
                    <th>Number of Deaths:</th>
                    <td>{{ $counts['deaths'] }}</td>
                </tr>
                <tr>
                    <th>Number of Rescued:</th>
                    <td>{{ $counts['rescued'] }}</td>
                </tr>
                <tr>
                    <th>Date Resolved:</th>
                    <td>{{ $report->incident->date_resolved?->format('M d, Y h:i A') }}</td>
                </tr>
            </tbody>
        </table>

        {{-- Narrative --}}
        <h4 class="fw-bold mt-4">II. Narrative</h4>
        <p><strong>Details:</strong> {{ $report->incident->details ?? '—' }}</p>
        <p><strong>Action Taken:</strong></p>
        @php
            $actionItems = preg_split('/[\-\.]\s*/', $report->incident->action_taken ?? '', -1, PREG_SPLIT_NO_EMPTY);
        @endphp
        @if(count($actionItems))
            <ul>
                @foreach($actionItems as $item)
                    <li>{{ trim($item) }}</li>
                @endforeach
            </ul>
        @else
            <p>—</p>
        @endif

        {{-- Photos --}}
        <h4 class="fw-bold mt-4">III. Photos Taken</h4>
        @php
            $landmarks = json_decode($report->landmark_photos ?? '[]', true);
            $proofs = json_decode($report->proof_photos ?? '[]', true);
            $docs = $report->incident->documentation ?? [];
        @endphp
        <div><strong>Landmark Photos:</strong>
            <div class="d-flex flex-wrap gap-2 mt-2">
                @foreach($landmarks as $photo)
                    <img src="{{ asset($photo) }}" class="img-thumbnail" style="width:180px;height:180px;object-fit:cover;cursor:pointer;" onclick="previewImage('{{ asset($photo) }}')">
                @endforeach
            </div>
        </div>
        <div class="mt-3"><strong>Proof Photos:</strong>
            <div class="d-flex flex-wrap gap-2 mt-2">
                @foreach($proofs as $photo)
                    <img src="{{ asset($photo) }}" class="img-thumbnail" style="width:180px;height:180px;object-fit:cover;cursor:pointer;" onclick="previewImage('{{ asset($photo) }}')">
                @endforeach
            </div>
        </div>
        <div class="mt-3"><strong>Documentation:</strong>
            <div class="d-flex flex-wrap gap-2 mt-2">
                @foreach($docs as $doc)
                    <img src="{{ asset('storage/' . $doc) }}" class="img-thumbnail" style="width:180px;height:180px;object-fit:cover;cursor:pointer;" onclick="previewImage('{{ asset('storage/' . $doc) }}')">
                @endforeach
            </div>
        </div>
    </div>

    {{-- Buttons --}}
    <div class="text-end mt-4 no-print">
        <small class="text-white d-block mb-2">
            Tip: In the print dialog, disable “Headers and Footers” for a cleaner report.
        </small>
        {{-- Buttons --}}
        <div class="text-end mt-4 no-print">
            <button class="btn btn-primary me-2" onclick="openPrintView()">Print</button>
            <a href="{{ route('admin.completed.incidents') }}" class="btn btn-secondary">Back</a>
        </div>

        <script>
            function openPrintView() {
                // Redirect to the print view route for this report
                window.open("{{ route('admin.print.report', $report->id) }}", "_blank");
            }
        </script>
    </div>
</div>

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

// Print only the card content
function printCard() {
    const printContents = document.getElementById('printableArea').innerHTML;
    const originalContents = document.body.innerHTML;

    document.body.innerHTML = printContents;
    window.print();
    document.body.innerHTML = originalContents;
    window.location.reload();
}
</script>

{{-- Clean print styling --}}
<style>
@media print {
    body {
        background: white !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
        margin: 0;
        padding: 0;
    }

    .card {
        background: white !important;
        box-shadow: none !important;
        border: none !important;
        color: black !important;
    }

    .no-print {
        display: none !important;
    }

    img {
        page-break-inside: avoid;
    }

    @page {
        margin: 1in;
    }
}
</style>
@endsection