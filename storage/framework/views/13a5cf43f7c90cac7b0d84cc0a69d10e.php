

<?php $__env->startSection('content'); ?>
<div class="container py-5">
    
    <div class="card shadow-sm rounded-4 p-4">
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <img src="<?php echo e(asset('images/mdrrmologo.png')); ?>" alt="MDRRMO Logo" style="height:80px;">
            <h2 class="text-center fw-bold">Incident Reporting System</h2>
            <img src="<?php echo e(asset('images/respondeLogo.png')); ?>" alt="System Logo" style="height:80px;">
        </div>

        <hr>

        
        <h4 class="fw-bold mt-4">I. Details of the Incident</h4>
        <table class="table table-borderless table-striped">
            <tbody>
                <tr>
                    <th>Type of Incident:</th>
                    <td><?php echo e($report->incident_type); ?></td>
                </tr>
                <tr>
                    <th>Reported By:</th>
                    <td><?php echo e($report->user->name ?? '—'); ?></td>
                </tr>
                <tr>
                    <th>Date & Time:</th>
                    <td><?php echo e($report->date_reported?->format('M d, Y h:i A')); ?></td>
                </tr>
                <tr>
                    <th>Location:</th>
                    <td><?php echo e(Str::limit($report->location, 100)); ?></td>
                </tr>
            </tbody>
        </table>

        
        <h4 class="fw-bold mt-4">II. Narrative of the Incident</h4>
        <p><strong>Details:</strong> <?php echo e($report->incident->details ?? '—'); ?></p>
        <p><strong>Action Taken:</strong></p>
        <?php
            $actionTaken = $report->incident->action_taken ?? '';
            $actionItems = preg_split('/[\-\.]\s*/', $actionTaken, -1, PREG_SPLIT_NO_EMPTY);
        ?>
        <?php if(count($actionItems)): ?>
            <ul class="ms-3">
                <?php $__currentLoopData = $actionItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e(trim($item)); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        <?php else: ?>
            <p>—</p>
        <?php endif; ?>

        
        <h4 class="fw-bold mt-4">III. Photos Taken</h4>

        <?php
            $landmarks = is_string($report->landmark_photos) ? json_decode($report->landmark_photos, true) : ($report->landmark_photos ?? []);
            $proofs = is_string($report->proof_photos) ? json_decode($report->proof_photos, true) : ($report->proof_photos ?? []);
            $docs = $report->incident ? ($report->incident->documentation ?? []) : [];
        ?>

        <div class="mb-3">
            <strong>Landmark Photos:</strong>
            <div class="d-flex flex-wrap gap-2 mt-2">
                <?php $__currentLoopData = $landmarks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $photo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <img src="<?php echo e(asset($photo)); ?>" class="img-thumbnail" style="width:180px;height:180px;object-fit:cover;cursor:pointer;" onclick="previewImage('<?php echo e(asset($photo)); ?>')">
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        <div class="mb-3">
            <strong>Proof Photos:</strong>
            <div class="d-flex flex-wrap gap-2 mt-2">
                <?php $__currentLoopData = $proofs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $photo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <img src="<?php echo e(asset($photo)); ?>" class="img-thumbnail" style="width:180px;height:180px;object-fit:cover;cursor:pointer;" onclick="previewImage('<?php echo e(asset($photo)); ?>')">
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        <div class="mb-3">
            <strong>Documentation:</strong>
            <div class="d-flex flex-wrap gap-2 mt-2">
                <?php if($docs): ?>
                    <?php $__currentLoopData = $docs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $doc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <img src="<?php echo e(asset('storage/' . $doc)); ?>" 
                            class="img-thumbnail" 
                            style="width:180px;height:180px;object-fit:cover;cursor:pointer;" 
                            onclick="previewImage('<?php echo e(asset('storage/' . $doc)); ?>')">
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                    <p class="text-muted">No documentation uploaded.</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="text-end mt-4">
            <a href="<?php echo e(route('responder.completed')); ?>" class="btn btn-secondary">Back</a>
        </div>
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
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.responder', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\responde_v2\resources\views\responder\generated-report.blade.php ENDPATH**/ ?>