@extends('layouts.responder')

@section('content')
<div class="content-card">
    <div class="container py-4">
        <h2 class="fw-bold text-dark mb-4">
            <i class="bi bi-check2-circle me-2"></i>Completed Incidents
        </h2>

        @if($reports->isEmpty())
            <div class="alert alert-info text-center">
                <i class="bi bi-info-circle me-2"></i>No completed incidents yet.
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Reference</th>
                            <th>Type</th>
                            <th>Location</th>
                            <th>Date Completed</th>
                            <th>Remarks</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reports as $report)
                        <tr>
                            <td>{{ $report->reference_code }}</td>
                            <td>{{ $report->incident_type }}</td>
                            <td>{{ \Illuminate\Support\Str::limit($report->location, 50, '...') }}</td>
                            <td>{{ $report->updated_at->format('M d, Y h:i A') }}</td>
                            <td><span class="badge bg-success">Resolved</span></td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary"
                                    onclick="generateReport('{{ $report->id }}')">
                                    <i class="bi bi-file-earmark-text"></i> Generate Report
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function generateReport(id) {
    Swal.fire({
        title: 'Generate Report',
        text: 'Are you sure you want to generate a report for this incident?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Generate',
        confirmButtonColor: '#198754',
    }).then((result) => {
        if(result.isConfirmed) {
            window.location.href = `/responder/completed/${id}/report`;
        }
    });
}
</script>
@endsection
