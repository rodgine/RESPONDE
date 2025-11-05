

<?php $__env->startSection('content'); ?>
<div class="container py-5">
    <div class="card shadow-sm rounded-4 p-4" id="printableArea">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <img src="<?php echo e(asset('images/mdrrmologo.png')); ?>" alt="MDRRMO Logo" style="height:80px;">
            <h2 class="text-center fw-bold">Incident Reporting System</h2>
            <img src="<?php echo e(asset('images/respondeLogo.png')); ?>" alt="System Logo" style="height:80px;">
        </div>
        <hr>

        
        <h4 class="fw-bold mt-4">I. Incident Details</h4>
        <table class="table table-borderless table-striped">
            <tbody>
                <tr>
                    <th>Type of Incident:</th>
                    <td><?php echo e($report->incident_type); ?></td>
                </tr>
                <tr>
                    <th>Reported By (Citizen):</th>
                    <td><?php echo e($report->user->name ?? '—'); ?></td>
                </tr>
                <tr>
                    <th>Responder:</th>
                    <td><?php echo e($report->incident->responder->name ?? '—'); ?></td>
                </tr>
                <tr>
                    <th>Date Reported:</th>
                    <td><?php echo e($report->date_reported?->format('M d, Y h:i A')); ?></td>
                </tr>
                <tr>
                    <th>Location:</th>
                    <td><?php echo e($report->location); ?></td>
                </tr>
                <tr>
                    <th>Number of Victims:</th>
                    <td><?php echo e($counts['victims']); ?></td>
                </tr>
                <tr>
                    <th>Number of Deaths:</th>
                    <td><?php echo e($counts['deaths']); ?></td>
                </tr>
                <tr>
                    <th>Number of Rescued:</th>
                    <td><?php echo e($counts['rescued']); ?></td>
                </tr>
                <tr>
                    <th>Date Resolved:</th>
                    <td><?php echo e($report->incident->date_resolved?->format('M d, Y h:i A')); ?></td>
                </tr>
            </tbody>
        </table>

        
        <h4 class="fw-bold mt-4">II. Narrative</h4>
        <p><strong>Details:</strong> <?php echo e($report->incident->details ?? '—'); ?></p>
        <p><strong>Action Taken:</strong></p>
        <?php
            $actionItems = preg_split('/[\-\.]\s*/', $report->incident->action_taken ?? '', -1, PREG_SPLIT_NO_EMPTY);
        ?>
        <?php if(count($actionItems)): ?>
            <ul>
                <?php $__currentLoopData = $actionItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e(trim($item)); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        <?php else: ?>
            <p>—</p>
        <?php endif; ?>

        
        <h4 class="fw-bold mt-4">III. Photos Taken</h4>
        <?php
            $landmarks = json_decode($report->landmark_photos ?? '[]', true);
            $proofs = json_decode($report->proof_photos ?? '[]', true);

            // ✅ Handle documentation safely (string or array)
            $docsRaw = $report->incident->documentation ?? [];
            if (is_string($docsRaw)) {
                $decoded = json_decode(stripslashes($docsRaw), true);
                $docs = is_array($decoded) ? $decoded : [];
            } elseif (is_array($docsRaw)) {
                $docs = $docsRaw;
            } else {
                $docs = [];
            }
        ?>

        <div><strong>Landmark Photos:</strong>
            <div class="d-flex flex-wrap gap-2 mt-2">
                <?php $__empty_1 = true; $__currentLoopData = $landmarks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $photo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <img src="<?php echo e(asset($photo)); ?>" 
                         class="img-thumbnail" 
                         style="width:180px;height:180px;object-fit:cover;cursor:pointer;" 
                         onclick="previewImage('<?php echo e(asset($photo)); ?>')">
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <p>No landmark photos available.</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="mt-3"><strong>Proof Photos:</strong>
            <div class="d-flex flex-wrap gap-2 mt-2">
                <?php $__empty_1 = true; $__currentLoopData = $proofs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $photo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <img src="<?php echo e(asset($photo)); ?>" 
                         class="img-thumbnail" 
                         style="width:180px;height:180px;object-fit:cover;cursor:pointer;" 
                         onclick="previewImage('<?php echo e(asset($photo)); ?>')">
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <p>No proof photos available.</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="mt-3"><strong>Documentation:</strong>
            <div class="d-flex flex-wrap gap-2 mt-2">
                <?php $__empty_1 = true; $__currentLoopData = $docs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $doc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <img src="<?php echo e(asset('storage/' . ltrim($doc, '/'))); ?>" 
                         class="img-thumbnail" 
                         style="width:180px;height:180px;object-fit:cover;cursor:pointer;" 
                         onclick="previewImage('<?php echo e(asset('storage/' . ltrim($doc, '/'))); ?>')">
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <p>No documentation photos available.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    
    <div class="text-end mt-4 no-print">
        <small class="text-white d-block mb-2">
            Tip: In the print dialog, disable “Headers and Footers” for a cleaner report.
        </small>

        <div class="text-end mt-4 no-print">
            <button class="btn btn-primary me-2" onclick="openPrintView()">Print</button>
            <a href="<?php echo e(route('admin.completed.incidents')); ?>" class="btn btn-secondary">Back</a>
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
        background: '#fff',
        showCloseButton: true,
        confirmButtonText: 'Close',
        confirmButtonColor: '#800000'
    });
}

function openPrintView() {
    window.open("<?php echo e(route('admin.print.report', $report->id)); ?>", "_blank");
}
</script>


<style>
@media print {
    body {
        background: white !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
        margin: 0;
        padding: 0;
    }

    .card {
        background: white !important;
        box-shadow: none !important;
        border: none !important;
        color: black !important;
    }

    .no-print {
        display: none !important;
    }

    img {
        page-break-inside: avoid;
    }

    @page {
        margin: 1in;
    }
}
</style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\responde_v2\resources\views\admin\generated-report.blade.php ENDPATH**/ ?>