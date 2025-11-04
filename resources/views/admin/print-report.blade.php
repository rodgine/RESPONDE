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

        {{-- Incident Details --}}
        <h4 class="fw-bold mt-3">I. Incident Details</h4>
        <br>
        <table class="table table-borderless table-striped">
            <tbody>
                <tr><th>Type of Incident:</th><td>{{ $report->incident_type }}</td></tr>
                <tr><th>Reported By (Citizen):</th><td>{{ $report->user->name ?? '—' }}</td></tr>
                <tr><th>Responder:</th><td>{{ $report->incident->responder->name ?? '—' }}</td></tr>
                <tr><th>Date & Time of Incident:</th><td>{{ $report->date_reported?->format('M d, Y h:i A') }}</td></tr>
                <tr><th>Location:</th><td>{{ $report->location }}</td></tr>

                {{-- New Section for Incident Counts --}}
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
                <tr><th>Date Resolved:</th><td>{{ $report->incident->date_resolved?->format('M d, Y h:i A') }}</td></tr>
            </tbody>
        </table>

        {{-- Narrative --}}
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

        {{-- Photos --}}
        <h4 class="fw-bold mt-4">III. Photos Taken</h4>
        <br>
        <div><strong>Landmark Photos:</strong>
            <div class="d-flex flex-wrap gap-2 mt-2">
                @foreach($report->landmark_photos as $photo)
                    <img src="{{ asset($photo) }}" class="img-thumbnail" style="width:180px;height:180px;object-fit:cover;">
                @endforeach
            </div>
        </div>

        <div class="mt-3"><strong>Proof Photos:</strong>
            <div class="d-flex flex-wrap gap-2 mt-2">
                @foreach($report->proof_photos as $photo)
                    <img src="{{ asset($photo) }}" class="img-thumbnail" style="width:180px;height:180px;object-fit:cover;">
                @endforeach
            </div>
        </div>

        <div class="mt-3"><strong>Documentation:</strong>
            <div class="d-flex flex-wrap gap-2 mt-2">
                @foreach($report->incident->documentation ?? [] as $doc)
                    <img src="{{ asset('storage/' . $doc) }}" class="img-thumbnail" style="width:180px;height:180px;object-fit:cover;">
                @endforeach
            </div>
        </div>
    </div>
</div>

<script>
window.onload = function() {
    // Delay ensures the layout is ready
    setTimeout(async () => {
        const printPromise = new Promise((resolve) => {
            const beforePrint = () => resolve('print');
            const afterPrint = () => resolve('cancel');
            window.addEventListener('beforeprint', beforePrint, { once: true });
            window.addEventListener('afterprint', afterPrint, { once: true });
        });

        window.print();

        // Wait to detect if cancelled or completed
        const result = await printPromise;

        // If cancelled or printed, close and go back
        if (result === 'cancel' || result === 'print') {
            window.close(); // ⬅️ redirect destination
        }
    }, 300);
};
</script>

</body>
</html>
