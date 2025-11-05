<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Incident Report - Print</title>

    <link rel="icon" type="image/png" href="{{ asset('images/respondeLogo.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        html, body {
            background: white !important;
            color: black !important;
            font-family: 'Segoe UI', Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .container-fluid {
            margin: 0 !important;
            padding: 0 20px !important;
        }

        .card {
            background: white !important;
            border: none !important;
            box-shadow: none !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        hr {
            border-top: 2px solid black;
            margin-top: 1rem;
            margin-bottom: 1rem;
        }

        img {
            page-break-inside: avoid;
        }

        h2, h4 {
            margin: 0;
            padding: 0;
        }

        @page {
            margin: 0.75in;
        }

        @media print {
            body {
                background: white !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .no-print {
                display: none !important;
            }

            .card {
                box-shadow: none !important;
            }
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="card" id="printableArea">
        <div class="d-flex justify-content-between align-items-center mb-3 mt-3">
            <img src="{{ asset('images/mdrrmologo.png') }}" alt="MDRRMO Logo" style="height:80px;">
            <h2 class="text-center fw-bold flex-grow-1">Incident Reporting System</h2>
            <img src="{{ asset('images/respondeLogo.png') }}" alt="System Logo" style="height:80px;">
        </div>
        <hr>

        {{-- I. Incident Details --}}
        <h4 class="fw-bold mt-3">I. Incident Details</h4>
        <br>
        <table class="table table-borderless table-striped">
            <tbody>
                <tr><th>Type of Incident:</th><td>{{ $report->incident_type }}</td></tr>
                <tr><th>Reported By (Citizen):</th><td>{{ $report->user->name ?? '—' }}</td></tr>
                <tr><th>Responder:</th><td>{{ $report->incident->responder->name ?? '—' }}</td></tr>
                <tr><th>Date & Time of Incident:</th><td>{{ $report->date_reported?->format('M d, Y h:i A') }}</td></tr>
                <tr><th>Location:</th><td>{{ $report->location }}</td></tr>
                <tr><th>Number of Victims:</th><td>{{ $counts['victims'] }}</td></tr>
                <tr><th>Number of Deaths:</th><td>{{ $counts['deaths'] }}</td></tr>
                <tr><th>Number of Rescued:</th><td>{{ $counts['rescued'] }}</td></tr>
                <tr><th>Date Resolved:</th><td>{{ $report->incident->date_resolved?->format('M d, Y h:i A') }}</td></tr>
            </tbody>
        </table>

        {{-- II. Narrative --}}
        <h4 class="fw-bold mt-4">II. Narrative</h4>
        <br>
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

        {{-- III. Photos Taken --}}
        <h4 class="fw-bold mt-4">III. Photos Taken</h4>
        <br>
        @php
            // Decode JSON safely (with type checking)
            $landmarks = is_string($report->landmark_photos) ? json_decode($report->landmark_photos, true) : (is_array($report->landmark_photos) ? $report->landmark_photos : []);
            $proofs = is_string($report->proof_photos) ? json_decode($report->proof_photos, true) : (is_array($report->proof_photos) ? $report->proof_photos : []);

            $rawDocs = $report->incident->documentation ?? [];
            if (is_string($rawDocs)) {
                $decoded = json_decode(stripslashes($rawDocs), true);
                $docs = is_array($decoded) ? $decoded : [];
            } elseif (is_array($rawDocs)) {
                $docs = $rawDocs;
            } else {
                $docs = [];
            }
        @endphp

        <div>
            <strong>Landmark Photos:</strong>
            <div class="d-flex flex-wrap gap-2 mt-2">
                @forelse($landmarks as $photo)
                    <img src="{{ asset($photo) }}" class="img-thumbnail" style="width:180px;height:180px;object-fit:cover;">
                @empty
                    <p class="text-muted">No landmark photos available.</p>
                @endforelse
            </div>
        </div>

        <div class="mt-3">
            <strong>Proof Photos:</strong>
            <div class="d-flex flex-wrap gap-2 mt-2">
                @forelse($proofs as $photo)
                    <img src="{{ asset($photo) }}" class="img-thumbnail" style="width:180px;height:180px;object-fit:cover;">
                @empty
                    <p class="text-muted">No proof photos available.</p>
                @endforelse
            </div>
        </div>

        <div class="mt-3">
            <strong>Documentation:</strong>
            <div class="d-flex flex-wrap gap-2 mt-2">
                @forelse($docs as $doc)
                    <img src="{{ asset(str_starts_with($doc, 'storage/') ? $doc : 'storage/' . ltrim($doc, '/')) }}" 
                         class="img-thumbnail" 
                         style="width:180px;height:180px;object-fit:cover;">
                @empty
                    <p class="text-muted">No documentation files available.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<script>
window.onload = function() {
    // Wait for layout to fully render
    setTimeout(async () => {
        const printPromise = new Promise((resolve) => {
            const beforePrint = () => resolve('print');
            const afterPrint = () => resolve('cancel');
            window.addEventListener('beforeprint', beforePrint, { once: true });
            window.addEventListener('afterprint', afterPrint, { once: true });
        });

        window.print();
        const result = await printPromise;
        if (result === 'cancel' || result === 'print') {
            window.close();
        }
    }, 300);
};
</script>

</body>
</html>