@extends('layouts.admin')

@section('content')
<div class="content-card bg-transparent">
    <div class="container">
        
        <style>
            .table thead th {
                background: rgba(255, 255, 255, 0.85);
                font-weight: 600;
            }
            .table tbody tr {
                background: rgba(255, 255, 255, 0.05);
                transition: background 0.2s;
            }
            .table tbody tr:hover {
                background: rgba(255, 255, 255, 0.15);
            }
        </style>

        {{-- Header --}}
        <div class="mb-4 text-white">
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                <div>
                    <h2 class="text-white mb-2 d-flex align-items-center">
                        <i class="bi bi-check2-circle me-2 text-success"></i> 
                        Completed Incidents
                    </h2>
                    <small class="text-light-opacity">Overview of all completed incident reports</small>
                </div>
            </div>
        </div>
        
        {{-- Filter Section --}}
        <div class="card shadow-sm rounded-4 border-0 mb-4 p-3 bg-white bg-opacity-10 text-dark">
            <form id="filterForm" method="GET" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label for="incidentType" class="form-label fw-semibold text-white-opacity">Filter by Incident Type</label>
                    <select class="form-select text-dark" name="incident_type" id="incidentType">
                        <option value="" selected disabled>Select incident type</option>
                        @foreach(['Flood', 'Fire', 'Accident', 'Earthquake'] as $type)
                            <option value="{{ $type }}" {{ request('incident_type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label for="dateReported" class="form-label fw-semibold text-white-opacity">Filter by Date</label>
                    <input type="date" name="date_reported" id="dateReported" value="{{ request('date_reported') }}" class="form-control text-dark">
                </div>

                <div class="col-md-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary mt-2 w-50">
                        <i class="bi bi-funnel me-1"></i> Apply
                    </button>
                    <a href="{{ route('admin.completed.incidents') }}" class="btn btn-secondary mt-2 w-50">
                        <i class="bi bi-arrow-clockwise me-1"></i> Reset
                    </a>
                </div>
            </form>
        </div>
        
        {{-- Summary Cards --}}
        <div class="row g-3 mb-4">
            @php
                $types = ['Flood', 'Fire', 'Accident', 'Earthquake'];
                $icons = ['bi-droplet', 'bi-fire', 'bi-car-front-fill', 'bi-geo-alt-fill'];
                $counts = [];
                foreach ($types as $type) {
                    $counts[$type] = $incidents->where('report.incident_type', $type)->count();
                }
            @endphp

            @foreach($types as $index => $type)
            <div class="col-md-3">
                <div class="card shadow-sm rounded-4 border-0 bg-white bg-opacity-10 text-white text-center p-3 hover-scale">
                    <div class="card-body">
                        <i class="bi {{ $icons[$index] }} fs-2 mb-2"></i>
                        <h4 class="fw-bold mb-1">{{ $counts[$type] }}</h4>
                        <p class="mb-0 fw-semibold">{{ $type }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Overview Table --}}
        <div class="card shadow-sm rounded-4 border-0 mb-4 p-3 bg-white bg-opacity-10 text-white">
            <h5 class="fw-bold mb-3 d-flex align-items-center">
                <i class="bi bi-clipboard-data me-2 text-info"></i> Incident Summary Overview
            </h5>

            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle text-white mb-0">
                    <thead class="table-light text-dark">
                        <tr class="text-center">
                            <th>Incident Type</th>
                            <th>Number of Incidents</th>
                            <th>Number of Victims</th>
                            <th>Number of Deaths</th>
                            <th>Number of Rescued</th>
                            <th>Last Date Resolved</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($summary as $row)
                            <tr class="text-center">
                                <td class="fw-semibold">{{ $row['incident_type'] }}</td>
                                <td>{{ $row['count'] }}</td>
                                <td>{{ $row['victims'] }}</td>
                                <td class="text-danger fw-bold">{{ $row['deaths'] }}</td>
                                <td class="text-success fw-bold">{{ $row['rescued'] }}</td>
                                <td>
                                    {{ $row['last_resolved'] 
                                        ? \Carbon\Carbon::parse($row['last_resolved'])->format('M d, Y') 
                                        : '—' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-light-opacity py-3">
                                    <i class="bi bi-info-circle me-1"></i> No completed incidents recorded yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Search Section --}}
        <div class="card shadow-sm rounded-4 border-0 mb-4 p-3 bg-white bg-opacity-10 text-dark">
            <div class="position-relative">
                <div class="input-group">
                    <span class="input-group-text border- text-dark">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" id="incidentSearch" 
                           class="form-control border-start-0 text-dark"
                           placeholder="Search incidents by type, location, user, or responder..."
                           style="height: 48px;">
                    <button type="button" id="clearSearch" class="btn btn-outline-light d-none">
                        <i class="bi bi-x-circle"></i>
                    </button>
                </div>
                <div id="searchSuggestions"
                     class="list-group position-absolute w-100 shadow-sm bg-white bg-opacity-90 mt-1 rounded"
                     style="max-height: 250px; overflow-y: auto; z-index: 1050; display: none;">
                </div>
            </div>
            <small class="text-white-opacity mt-2 d-block">
                <i class="bi bi-lightbulb-fill text-warning me-1"></i>
                Suggestions may show entries that don’t match the currently visible cards because each suggestion is based on a single field of a record.
            </small>
        </div>

        {{-- Incident Cards --}}
        <div id="incidentCards" class="row g-4">
            @foreach($incidents as $incident)
                <div class="col-md-6 col-lg-4 incident-card">
                    @include('admin.partials.incident-card', ['incident' => $incident])
                </div>
            @endforeach
        </div>

        <div id="noResults" class="alert alert-info text-center d-none mt-4">
            <i class="bi bi-info-circle me-2"></i>No incidents found.
        </div>
    </div>
</div>

<style>
/* Hover lift effect for summary and incident cards */
.hover-scale:hover {
    transform: translateY(-4px);
    transition: all 0.2s ease-in-out;
}
/* Text opacity helper */
.text-light-opacity { color: rgba(255,255,255,0.7); }
.text-white-opacity { color: rgba(255,255,255,0.8); }
/* Clear button positioning */
#clearSearch { position: absolute; right: 0; top: 50%; transform: translateY(-50%); }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('incidentSearch');
    const clearBtn = document.getElementById('clearSearch');
    const suggestionsBox = document.getElementById('searchSuggestions');
    const incidentCards = document.getElementById('incidentCards');
    const noResults = document.getElementById('noResults');
    const applyBtn = document.querySelector('#filterForm button[type="submit"]');
    const resetBtn = document.querySelector('#filterForm a.btn-secondary');
    const filterType = document.getElementById('incidentType');
    const filterDate = document.getElementById('dateReported');

    function showClearButton(show) { clearBtn.classList.toggle('d-none', !show); }

    searchInput.addEventListener('input', function() {
        const query = this.value.trim();
        showClearButton(query.length > 0);

        if (!query) {
            suggestionsBox.style.display = 'none';
            fetch(`{{ route('admin.completed-incidents.all') }}`)
                .then(res => res.json())
                .then(data => updateResults(data))
                .catch(err => console.error(err));
            return;
        }

        fetch(`{{ route('admin.completed-incidents.search') }}?q=${encodeURIComponent(query)}`)
            .then(res => res.json())
            .then(data => updateSuggestions(data))
            .catch(err => console.error(err));
    });

    clearBtn.addEventListener('click', () => {
        searchInput.value = '';
        showClearButton(false);
        suggestionsBox.style.display = 'none';
        fetch(`{{ route('admin.completed-incidents.all') }}`)
            .then(res => res.json())
            .then(data => updateResults(data))
            .catch(err => console.error(err));
    });

    searchInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            const query = this.value.trim();
            if (!query) return;
            triggerSearch(query);
        }
    });

    function updateSuggestions(data) {
        suggestionsBox.innerHTML = '';
        const { incident_types = [], locations = [], users = [], responders = [] } = data;

        function createButton(text, icon = 'bi-search') {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'list-group-item list-group-item-action d-flex align-items-center';
            btn.innerHTML = `<i class="bi ${icon} me-2"></i> ${text}`;
            btn.onclick = () => {
                searchInput.value = text.trim();
                suggestionsBox.style.display = 'none';
                triggerSearch(text.trim());
            };
            suggestionsBox.appendChild(btn);
        }

        incident_types.forEach(t => createButton(t, 'bi-geo-alt-fill'));
        locations.forEach(l => createButton(l, 'bi-geo-alt'));
        users.forEach(u => createButton(u, 'bi-person-circle'));
        responders.forEach(r => createButton(r, 'bi-person-workspace'));

        suggestionsBox.style.display = suggestionsBox.children.length ? 'block' : 'none';
    }

    function triggerSearch(query) {
        fetch(`{{ route('admin.completed-incidents.search') }}?q=${encodeURIComponent(query)}`)
            .then(res => res.json())
            .then(data => updateResults(data.results || []))
            .catch(err => console.error(err));
    }

    // ---- FILTER FUNCTION FOR BOTH TABLE & CARDS ----
    applyBtn.addEventListener('click', (e) => {
        e.preventDefault();

        const type = filterType.value;
        const date = filterDate.value ? new Date(filterDate.value).toLocaleDateString('en-US', { month:'short', day:'numeric', year:'numeric' }) : '';

        // Filter table rows
        const tableRows = document.querySelectorAll('table tbody tr');
        tableRows.forEach(row => {
            const rowType = row.cells[0]?.textContent.trim();
            const rowDate = row.cells[5]?.textContent.trim();
            const matchType = !type || rowType === type;
            const matchDate = !date || rowDate === date;
            row.style.display = matchType && matchDate ? '' : 'none';
        });

        // Filter cards
        const cards = document.querySelectorAll('#incidentCards .incident-card');
        let anyVisible = false;
        cards.forEach(card => {
            const cardType = card.querySelector('.card-title')?.textContent.trim() || '';
            const cardDateText = card.querySelector('.bi-clock')?.parentElement?.textContent || '';
            const cardDate = cardDateText.replace('Reported on:','').trim();
            const matchType = !type || cardType.includes(type);
            const matchDate = !date || cardDate === date;
            if(matchType && matchDate){
                card.style.display = '';
                anyVisible = true;
            } else {
                card.style.display = 'none';
            }
        });

        // Show no results if both table and cards have nothing
        if(!anyVisible && [...tableRows].every(r => r.style.display === 'none')){
            noResults.classList.remove('d-none');
        } else {
            noResults.classList.add('d-none');
        }
    });

    // Reset filter button
    resetBtn.addEventListener('click', (e) => {
        e.preventDefault();
        filterType.value = '';
        filterDate.value = '';

        // Reset table
        const tableRows = document.querySelectorAll('table tbody tr');
        tableRows.forEach(row => row.style.display = '');

        // Reset cards
        const cards = document.querySelectorAll('#incidentCards .incident-card');
        cards.forEach(card => card.style.display = '');

        noResults.classList.add('d-none');
    });

    // ---- END FILTER ----

    function updateResults(data) {
        incidentCards.innerHTML = '';
        if (!data.length) {
            noResults.classList.remove('d-none');
            return;
        }
        noResults.classList.add('d-none');

        data.forEach(item => {
            let typeClass = '';
            switch ((item.report.incident_type || '')) {
                case 'Fire': typeClass = 'text-danger'; break;
                case 'Flood': typeClass = 'text-info'; break;
                case 'Earthquake': typeClass = 'text-secondary'; break;
                case 'Accident': typeClass = 'text-warning'; break;
                default: typeClass = 'text-success'; 
            }

            const cardHtml = `
            <div class="col-md-6 col-lg-4 incident-card">
                <div class="card shadow-sm h-100 border-0 rounded-4 bg-white text-dark hover-scale">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div>
                            <h5 class="card-title fw-bold ${typeClass}">
                                <i class="bi bi-geo-alt-fill me-1"></i> ${item.report.incident_type ?? '—'}
                            </h5>
                            <p class="text-dark-opacity mb-1 small">
                                <i class="bi bi-person-circle me-1"></i> Reported by: ${item.report.user?.name ?? '—'}
                            </p>
                            <p class="text-dark-opacity mb-1 small">
                                <i class="bi bi-person-workspace me-1"></i> Responder: ${item.responder?.name ?? '—'}
                            </p>
                            <p class="text-dark-opacity mb-1 small">
                                <i class="bi bi-clock me-1"></i> Reported on: ${item.report.date_reported ?? '—'}
                            </p>
                            <p class="text-dark-opacity mb-2 small">
                                <i class="bi bi-geo-alt me-1"></i> Location: ${item.report.location ?? '—'}
                            </p>
                        </div>
                        <div class="mb-3">
                            <span class="badge bg-success">
                                <i class="bi bi-check2-circle me-1"></i>Resolved
                            </span>
                        </div>
                        <div class="mt-auto text-end">
                            <a href="/admin/generated-report/${item.report.id}" class="btn btn-primary btn-sm">
                                <i class="bi bi-eye-fill me-1"></i>View Detailed Report
                            </a>
                        </div>
                    </div>
                    <div class="card-footer bg-white bg-opacity-10 text-white small rounded-bottom-4">
                        Documentation: ${item.documentation?.length ?? 0} file(s)
                    </div>
                </div>
            </div>`;
            incidentCards.insertAdjacentHTML('beforeend', cardHtml);
        });
    }
});
</script>
@endsection