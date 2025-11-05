@php
    $type = strtolower($incident->report->incident_type ?? '');
    $typeClass = match($type) {
        'fire' => 'text-danger',
        'flood' => 'text-info',
        'earthquake' => 'text-secondary',
        'accident' => 'text-warning',
        default => 'text-success',
    };

    // ✅ Handle both JSON string and array cases
    $docsRaw = $incident->documentation ?? [];
    if (is_string($docsRaw)) {
        $decoded = json_decode(stripslashes($docsRaw), true);
        $docs = is_array($decoded) ? $decoded : [];
    } elseif (is_array($docsRaw)) {
        $docs = $docsRaw;
    } else {
        $docs = [];
    }

    $countDocs = count($docs);
@endphp

<div class="card shadow-sm h-100 border-0 rounded-4 bg-white text-dark">
    <div class="card-body d-flex flex-column justify-content-between">
        <div>
            <h5 class="card-title fw-bold {{ $typeClass }}">
                <i class="bi bi-geo-alt-fill me-1"></i>{{ $incident->report->incident_type ?? '—' }}
            </h5>
            <p class="text-dark-opacity mb-1 small">
                <i class="bi bi-person-circle me-1"></i>Reported by: {{ $incident->report->user->name ?? '—' }}
            </p>
            <p class="text-dark-opacity mb-1 small">
                <i class="bi bi-person-workspace me-1"></i>Responder: {{ $incident->responder->name ?? '—' }}
            </p>
            <p class="text-dark-opacity mb-2 small">
                <i class="bi bi-clock me-1"></i>Reported on: {{ $incident->report->date_reported?->format('M d, Y h:i A') ?? '—' }}
            </p>
            <p class="text-dark-opacity mb-2 small">
                <i class="bi bi-geo-alt me-1"></i>Location: {{ $incident->report->location ?? '—' }}
            </p>
        </div>

        <div class="mb-3">
            <span class="badge bg-success"><i class="bi bi-check2-circle me-1"></i>Resolved</span>
        </div>

        <div class="mt-auto text-end">
            <a href="{{ route('admin.generated.report', $incident->report->id) }}" class="btn btn-primary btn-sm">
                <i class="bi bi-eye-fill me-1"></i>View Detailed Report
            </a>
        </div>
    </div>

    <div class="card-footer bg-white bg-opacity-10 text-white small rounded-bottom-4">
        Documentation: {{ $countDocs }} file(s)
    </div>
</div>
