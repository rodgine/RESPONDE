@extends('layouts.user')

@section('content')
<div class="content-card">
    <div class="container py-5">
        <h1 class="text-center mb-4 fw-bold text-white">User Dashboard</h1>
        <p class="text-center text-light mb-5">
            Welcome, <strong>{{ auth()->user()->name }}</strong> 
        </p>

        <div class="row g-4 justify-content-center">

            <div class="col-md-3">
                <div class="card text-center shadow-lg border-0" style="background: rgba(255,255,255,0.15); backdrop-filter: blur(8px); color: #fff;">
                    <div class="card-body">
                        <i class="bi bi-file-earmark-text fs-1 mb-2 text-info"></i>
                        <h5 class="card-title fw-semibold">Total Reports</h5>
                        <h2 class="fw-bold mt-2">{{ $totalReports ?? 0 }}</h2>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card text-center shadow-lg border-0" style="background: rgba(255,255,255,0.15); backdrop-filter: blur(8px); color: #fff;">
                    <div class="card-body">
                        <i class="bi bi-hourglass-split fs-1 mb-2 text-warning"></i>
                        <h5 class="card-title fw-semibold">Pending</h5>
                        <h2 class="fw-bold mt-2">{{ $pendingReports ?? 0 }}</h2>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card text-center shadow-lg border-0" style="background: rgba(255,255,255,0.15); backdrop-filter: blur(8px); color: #fff;">
                    <div class="card-body">
                        <i class="bi bi-tools fs-1 mb-2 text-primary"></i>
                        <h5 class="card-title fw-semibold">In Progress</h5>
                        <h2 class="fw-bold mt-2">{{ $inProgressReports ?? 0 }}</h2>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card text-center shadow-lg border-0" style="background: rgba(255,255,255,0.15); backdrop-filter: blur(8px); color: #fff;">
                    <div class="card-body">
                        <i class="bi bi-check-circle fs-1 mb-2 text-success"></i>
                        <h5 class="card-title fw-semibold">Resolved</h5>
                        <h2 class="fw-bold mt-2">{{ $resolvedReports ?? 0 }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
