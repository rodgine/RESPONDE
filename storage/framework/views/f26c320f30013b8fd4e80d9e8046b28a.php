
<?php $__env->startSection('content'); ?>

<style>

#actionOverlay {
  position: fixed;
  inset: 0;
  display: none;
  align-items: center;
  justify-content: center;
  z-index: 2000;
  background: rgba(0,0,0,0.35);
}
#actionOverlay .card {
  padding: 1.5rem;
  border-radius: .75rem;
  text-align: center;
  background: rgba(255,255,255,0.95);
}
</style>

<div class="content-card">
    <div class="container py-4">

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
            <div>
                <h2 class="fw-bold text-dark mb-0">
                    <i class="bi bi-hdd-stack me-2"></i> Database Backup & Restore
                </h2>
                <small class="text-muted">Manage and secure system database backups with ease.</small>
            </div>
            <button id="backupBtn" class="btn btn-primary mt-2 mt-md-0 shadow-sm">
                <i class="bi bi-cloud-arrow-up me-1"></i> Backup Now
            </button>

            <form id="backupForm" action="<?php echo e(route('admin.backup.run')); ?>" method="POST" style="display:none;">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="confirm_password" id="backup_confirm_password">
            </form>
        </div>

        <!-- Backup Info -->
        <div class="card glass-card border-0 shadow-sm mb-4">
            <div class="card-body">
                
                <!-- Search + Info -->
                <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
                    <h5 class="fw-bold text-dark mb-2 mb-md-0">
                        <i class="bi bi-clock-history me-1"></i> Backup History
                    </h5>
                    <input type="text" id="backupSearch" class="form-control w-25" placeholder="Search filename...">
                </div>

                <p class="text-muted mb-0">
                    The backup process includes all essential database tables and records. 
                    You can download, restore, or delete existing backups from the table below.
                </p>
            </div>
        </div>

        <!-- Backup Table -->
        <div class="card glass-card border-0 shadow-sm">
            <div class="card-body table-responsive">
                <?php $recentLimit = 2; ?>

                <table class="table align-middle table-hover mb-0" id="backupTable">
                    <thead class="table-light border-bottom">
                        <tr>
                            <th>Filename</th>
                            <th>Size</th>
                            <th>Created</th>
                            <th>Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $backups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="fw-semibold"><?php echo e($b->file_name); ?></td>
                            <td><?php echo e(number_format($b->file_size / 1024, 2)); ?> KB</td>
                            <td><?php echo e($b->created_at->format('M d, Y H:i')); ?></td>
                            <td>
                                <?php if($index < $recentLimit): ?>
                                    <span class="badge bg-success px-3 py-2 rounded-pill">
                                        <i class="bi bi-check-circle me-1"></i>Recent
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-warning text-dark px-3 py-2 rounded-pill">
                                        <i class="bi bi-archive me-1"></i>Archived
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="<?php echo e(route('admin.backup.download', $b->id)); ?>" 
                                       class="btn btn-sm btn-outline-success me-1" 
                                       data-bs-toggle="tooltip" title="Download">
                                        <i class="bi bi-download"></i>
                                    </a>

                                    <button class="btn btn-sm btn-outline-primary me-1 restoreBtn" 
                                            data-id="<?php echo e($b->id); ?>" data-bs-toggle="tooltip" title="Restore">
                                        <i class="bi bi-arrow-counterclockwise"></i>
                                    </button>

                                    <button class="btn btn-sm btn-outline-danger deleteBtn" 
                                            data-id="<?php echo e($b->id); ?>" data-bs-toggle="tooltip" title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>

                                <!-- Hidden Forms -->
                                <form id="restoreForm-<?php echo e($b->id); ?>" method="POST" 
                                      action="<?php echo e(route('admin.backup.restore', $b->id)); ?>" style="display:none;">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="confirm_password" id="restore_confirm_password_<?php echo e($b->id); ?>">
                                </form>

                                <form id="deleteForm-<?php echo e($b->id); ?>" method="POST" 
                                      action="<?php echo e(route('admin.backup.delete', $b->id)); ?>" style="display:none;">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <input type="hidden" name="confirm_password" id="delete_confirm_password_<?php echo e($b->id); ?>">
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">
                                <i class="bi bi-info-circle me-1"></i>No backups found.
                            </td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>

                <div class="mt-3">
                    <?php echo e($backups->links('pagination::bootstrap-5')); ?>

                </div>
            </div>
        </div>
    </div>
</div>


<div id="actionOverlay">
  <div class="card">
    <div class="mb-2">
      <div class="spinner-border text-primary" role="status"></div>
    </div>
    <div>
      <strong id="actionOverlayText">Processing...</strong>
      <div class="text-muted small">This may take a few moments.</div>
    </div>
  </div>
</div>


<script>
/* helpers */
function showOverlay(text = 'Processing...') {
    const ov = document.getElementById('actionOverlay');
    document.getElementById('actionOverlayText').innerText = text;
    ov.style.display = 'flex';
}
function hideOverlay() {
    const ov = document.getElementById('actionOverlay');
    ov.style.display = 'none';
}

const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 2200
});

// search
document.getElementById('backupSearch').addEventListener('keyup', function () {
    let searchValue = this.value.toLowerCase();
    document.querySelectorAll('#backupTable tbody tr').forEach(row => {
        row.style.display = row.innerText.toLowerCase().includes(searchValue) ? '' : 'none';
    });
});

// backup button — prompt for password, then submit
document.getElementById('backupBtn').addEventListener('click', () => {
    Swal.fire({
        title: 'Start backup?',
        text: 'Confirm with your admin password to continue.',
        input: 'password',
        inputPlaceholder: 'Admin password',
        showCancelButton: true,
        confirmButtonText: 'Backup',
        preConfirm: (pwd) => {
            if (!pwd) Swal.showValidationMessage('Password required');
            return pwd;
        }
    }).then(result => {
        if (result.isConfirmed) {
            // show spinner
            showOverlay('Creating database backup...');
            // fill hidden confirm_password and submit
            document.getElementById('backup_confirm_password').value = result.value;
            document.getElementById('backupForm').submit();
        }
    });
});

// restore — prompt password then submit corresponding form
document.querySelectorAll('.restoreBtn').forEach(btn => {
    btn.addEventListener('click', () => {
        const id = btn.dataset.id;
        Swal.fire({
            title: 'Restore database?',
            text: 'This will import saved data. Confirm with admin password.',
            input: 'password',
            inputPlaceholder: 'Admin password',
            showCancelButton: true,
            confirmButtonText: 'Restore',
            preConfirm: (pwd) => {
                if (!pwd) Swal.showValidationMessage('Password required');
                return pwd;
            }
        }).then(result => {
            if (result.isConfirmed) {
                showOverlay('Restoring backup...');
                document.getElementById('restore_confirm_password_' + id).value = result.value;
                document.getElementById('restoreForm-' + id).submit();
            }
        });
    });
});

// delete — prompt password then submit
document.querySelectorAll('.deleteBtn').forEach(btn => {
    btn.addEventListener('click', () => {
        const id = btn.dataset.id;
        Swal.fire({
            title: 'Delete backup?',
            text: "This cannot be undone. Confirm with admin password.",
            input: 'password',
            inputPlaceholder: 'Admin password',
            showCancelButton: true,
            confirmButtonText: 'Delete',
            preConfirm: (pwd) => {
                if (!pwd) Swal.showValidationMessage('Password required');
                return pwd;
            }
        }).then(result => {
            if (result.isConfirmed) {
                showOverlay('Deleting backup...');
                document.getElementById('delete_confirm_password_' + id).value = result.value;
                document.getElementById('deleteForm-' + id).submit();
            }
        });
    });
});

// download toast
document.querySelectorAll('.downloadBtn').forEach(btn => {
    btn.addEventListener('click', () => {
        Toast.fire({ icon: 'info', title: 'Download started…' });
        // auto-refresh after a short delay (download starts)
        setTimeout(() => window.location.reload(), 800);
    });
});

/* Flash messages safely */
<?php if(session('success')): ?>
    Toast.fire({ icon: 'success', title: <?php echo json_encode(session('success'), 15, 512) ?> }).then(()=> {
        hideOverlay();
        // auto-refresh after short delay to show new backup in list
        setTimeout(()=> location.reload(), 700);
    });
<?php endif; ?>

<?php if(session('error')): ?>
    Swal.fire({ icon: 'error', title: 'Failed', text: <?php echo json_encode(session('error'), 15, 512) ?> }).then(()=> {
        hideOverlay();
    });
<?php endif; ?>
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\responde_v2\resources\views\admin\backup\index.blade.php ENDPATH**/ ?>