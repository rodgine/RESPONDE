@extends('layouts.admin')

@section('content')
<div class="content-card">
    <div class="container py-5">
        <h1 class="text-center mb-4 fw-bold text-white">Admin Dashboard</h1>
        <p class="text-center text-light mb-5">
            Welcome back, <strong>{{ auth()->user()->name }}</strong>
        </p>

        <div class="row g-4 justify-content-center">

            {{-- Total Incidents by Concerned Citizens --}}
            <div class="col-12 col-sm-6 col-md-3">
                <a class="text-decoration-none d-block h-100" href="{{ route('admin.incidents') }}">
                    <div class="card text-center shadow-lg border-0 h-100" 
                        style="background: rgba(255,255,255,0.15); backdrop-filter: blur(8px); color: #fff;">
                        <div class="card-body d-flex flex-column justify-content-center align-items-center">
                            <i class="bi bi-people-fill fs-1 mb-2 text-info"></i>
                            <h5 class="card-title fw-semibold">Reports from Citizens</h5>
                            <h2 class="fw-bold mt-2">{{ $citizenReports ?? 0 }}</h2>
                        </div>
                    </div>
                </a>
            </div>

            {{-- Total Users --}}
            <div class="col-12 col-sm-6 col-md-3">
                <a class="text-decoration-none d-block h-100" href="{{ route('admin.users') }}">
                    <div class="card text-center shadow-lg border-0 h-100" 
                        style="background: rgba(255,255,255,0.15); backdrop-filter: blur(8px); color: #fff;">
                        <div class="card-body d-flex flex-column justify-content-center align-items-center">
                            <i class="bi bi-person-badge-fill fs-1 mb-2 text-warning"></i>
                            <h5 class="card-title fw-semibold">Total Users</h5>
                            <h2 class="fw-bold mt-2">{{ $totalUsers ?? 0 }}</h2>
                        </div>
                    </div>
                </a>
            </div>

            {{-- Total Incidents by Responders --}}
            <div class="col-12 col-sm-6 col-md-3">
                <a class="text-decoration-none d-block h-100" href="{{ route('admin.completed.incidents') }}">
                    <div class="card text-center shadow-lg border-0 h-100" 
                        style="background: rgba(255,255,255,0.15); backdrop-filter: blur(8px); color: #fff;">
                        <div class="card-body d-flex flex-column justify-content-center align-items-center">
                            <i class="bi bi-shield-check fs-1 mb-2 text-primary"></i>
                            <h5 class="card-title fw-semibold">Reports from Responders</h5>
                            <h2 class="fw-bold mt-2">{{ $responderReports ?? 0 }}</h2>
                        </div>
                    </div>
                </a>
            </div>

            {{-- Total Reports (Overall) --}}
            <div class="col-12 col-sm-6 col-md-3">
                <a class="text-decoration-none d-block h-100" href="{{ route('admin.completed.incidents') }}">
                    <div class="card text-center shadow-lg border-0 h-100" 
                        style="background: rgba(255,255,255,0.15); backdrop-filter: blur(8px); color: #fff;">
                        <div class="card-body d-flex flex-column justify-content-center align-items-center">
                            <i class="bi bi-bar-chart-fill fs-1 mb-2 text-success"></i>
                            <h5 class="card-title fw-semibold">Total Reports</h5>
                            <h2 class="fw-bold mt-2">{{ $totalReports ?? 0 }}</h2>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection