@extends('layouts.responder')

@section('content')
<div class="content-card">
    <div class="container py-5">
        <h1 class="text-center mb-4 fw-bold text-white">Responder Dashboard</h1>
        <p class="text-center text-light mb-5">
            Welcome back, <strong>{{ auth()->user()->name }}</strong>
        </p>

        <div class="row g-4 justify-content-center">

            {{-- Total Incidents Assigned --}}
            <div class="col-md-3">
                <div class="card text-center shadow-lg border-0" 
                    style="background: rgba(255,255,255,0.15); backdrop-filter: blur(8px); color: #fff;">
                    <div class="card-body">
                        <i class="bi bi-people-fill fs-1 mb-2 text-info"></i>
                        <h5 class="card-title fw-semibold">Assigned Incidents</h5>
                        <h2 class="fw-bold mt-2">{{ $assignedReports ?? 0 }}</h2>
                    </div>
                </div>
            </div>

            {{-- Total Responded --}}
            <div class="col-md-3">
                <div class="card text-center shadow-lg border-0" 
                    style="background: rgba(255,255,255,0.15); backdrop-filter: blur(8px); color: #fff;">
                    <div class="card-body">
                        <i class="bi bi-check2-circle fs-1 mb-2 text-success"></i>
                        <h5 class="card-title fw-semibold">Resolved Reports</h5>
                        <h2 class="fw-bold mt-2">{{ $resolvedReports ?? 0 }}</h2>
                    </div>
                </div>
            </div>

            {{-- Pending --}}
            <div class="col-md-3">
                <div class="card text-center shadow-lg border-0" 
                    style="background: rgba(255,255,255,0.15); backdrop-filter: blur(8px); color: #fff;">
                    <div class="card-body">
                        <i class="bi bi-hourglass-split fs-1 mb-2 text-warning"></i>
                        <h5 class="card-title fw-semibold">Pending Reports</h5>
                        <h2 class="fw-bold mt-2">{{ $pendingReports ?? 0 }}</h2>
                    </div>
                </div>
            </div>

            {{-- In Progress --}}
            <div class="col-md-3">
                <div class="card text-center shadow-lg border-0" 
                    style="background: rgba(255,255,255,0.15); backdrop-filter: blur(8px); color: #fff;">
                    <div class="card-body">
                        <i class="bi bi-tools fs-1 mb-2 text-primary"></i>
                        <h5 class="card-title fw-semibold">In Progress</h5>
                        <h2 class="fw-bold mt-2">{{ $inProgressReports ?? 0 }}</h2>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
