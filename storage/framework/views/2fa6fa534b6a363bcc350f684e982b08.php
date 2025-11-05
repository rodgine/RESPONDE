

<?php $__env->startSection('content'); ?>
<div class="content-card">
    <div class="container py-5">
        <h1 class="text-center mb-4 fw-bold text-white">Responder Dashboard</h1>
        <p class="text-center text-light mb-5">
            Welcome back, <strong><?php echo e(auth()->user()->name); ?></strong>
        </p>

        <div class="row g-4 justify-content-center">

            
            <div class="col-md-3">
                <div class="card text-center shadow-lg border-0" 
                    style="background: rgba(255,255,255,0.15); backdrop-filter: blur(8px); color: #fff;">
                    <div class="card-body">
                        <i class="bi bi-people-fill fs-1 mb-2 text-info"></i>
                        <h5 class="card-title fw-semibold">Assigned Incidents</h5>
                        <h2 class="fw-bold mt-2"><?php echo e($assignedReports ?? 0); ?></h2>
                    </div>
                </div>
            </div>

            
            <div class="col-md-3">
                <div class="card text-center shadow-lg border-0" 
                    style="background: rgba(255,255,255,0.15); backdrop-filter: blur(8px); color: #fff;">
                    <div class="card-body">
                        <i class="bi bi-check2-circle fs-1 mb-2 text-success"></i>
                        <h5 class="card-title fw-semibold">Resolved Reports</h5>
                        <h2 class="fw-bold mt-2"><?php echo e($resolvedReports ?? 0); ?></h2>
                    </div>
                </div>
            </div>

            
            <div class="col-md-3">
                <div class="card text-center shadow-lg border-0" 
                    style="background: rgba(255,255,255,0.15); backdrop-filter: blur(8px); color: #fff;">
                    <div class="card-body">
                        <i class="bi bi-hourglass-split fs-1 mb-2 text-warning"></i>
                        <h5 class="card-title fw-semibold">Pending Reports</h5>
                        <h2 class="fw-bold mt-2"><?php echo e($pendingReports ?? 0); ?></h2>
                    </div>
                </div>
            </div>

            
            <div class="col-md-3">
                <div class="card text-center shadow-lg border-0" 
                    style="background: rgba(255,255,255,0.15); backdrop-filter: blur(8px); color: #fff;">
                    <div class="card-body">
                        <i class="bi bi-tools fs-1 mb-2 text-primary"></i>
                        <h5 class="card-title fw-semibold">In Progress</h5>
                        <h2 class="fw-bold mt-2"><?php echo e($inProgressReports ?? 0); ?></h2>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.responder', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\responde_v2\resources\views\responder\dashboard.blade.php ENDPATH**/ ?>