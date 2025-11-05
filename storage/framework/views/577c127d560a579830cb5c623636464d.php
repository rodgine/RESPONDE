<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>User Panel | Responde</title> 

    <link rel="icon" type="image/png" href="<?php echo e(asset('images/respondeLogo.png')); ?>">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600;700&display=swap");

        body {
            height: 100vh;
            font-family: "Poppins", sans-serif;
            color: #fff;
            margin: 0;
            overflow-x: hidden;
        }

        body::before {
            content: "";
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('<?php echo e(asset('images/md-bg.jpg')); ?>') no-repeat center center / cover;
            filter: blur(8px) brightness(0.65);
            z-index: -1;
        }

        .navbar {
            box-shadow: 0 2px 6px rgba(0,0,0,0.25);
            padding: 0.75rem 1rem;
            background: rgba(33, 37, 41, 0.9) !important; 
            backdrop-filter: blur(6px);
        }

        .navbar-brand img {
            height: 40px;
        }

        .navbar-brand span {
            font-weight: 600;
            font-size: 1.2rem;
            color: #fff;
        }

        .navbar-nav.center-nav {
            margin: 0 auto;
        }

        .nav-link {
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 6px;
            color: #e9ecef !important;
        }

        .nav-link:hover {
            color: #ffc107 !important;
        }

        .dropdown-menu {
            min-width: 180px;
        }

        .dropdown-item i {
            margin-right: 8px;
        }

        main.container {
            padding-top: 100px;
            min-height: calc(100vh - 70px);
        }

        .content-card {
            background: rgba(255, 255, 255, 0.15);
            border-radius: 12px;
            padding: 20px;
            backdrop-filter: blur(12px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
            color: #fff;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center" href="<?php echo e(route('user.dashboard')); ?>">
            <img src="<?php echo e(asset('images/respondeLogo.png')); ?>" alt="Responde Logo" class="me-2">
            <span>Responde User</span>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#responderNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="responderNavbar">
            <ul class="navbar-nav center-nav">
                <li class="nav-item"><a href="<?php echo e(route('user.dashboard')); ?>" class="nav-link"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
                <li class="nav-item"><a href="<?php echo e(route('user.report')); ?>" class="nav-link"><i class="bi bi-exclamation-triangle"></i> Report Incident</a></li>
                <li class="nav-item"><a href="<?php echo e(route('user.myreports')); ?>" class="nav-link"><i class="bi bi-file-earmark-text"></i> My Reports</a></li>
            </ul>

            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown"
                       role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-person-circle fs-5 me-1"></i>
                        <span><?php echo e(auth()->user()->name ?? 'User'); ?></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                        <li>
                            <a class="dropdown-item" href="<?php echo e(route('user.edit', ['id' => Auth::id()])); ?>">
                                <i class="bi bi-gear"></i> Settings
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form id="logoutForm" method="POST" action="<?php echo e(route('logout')); ?>">
                                <?php echo csrf_field(); ?>
                                <button type="button" id="logoutBtn" class="dropdown-item text-danger">
                                    <i class="bi bi-box-arrow-right"></i> Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<main class="container">
    <div class="content mt-5">
        <?php echo $__env->yieldContent('content'); ?>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.getElementById('logoutBtn').addEventListener('click', function (e) {
    e.preventDefault();

    Swal.fire({
        title: 'Confirm Logout',
        text: "You’re about to end your responder session.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, log me out',
        background: 'rgba(255,255,255,0.95)',
        backdrop: 'rgba(0,0,0,0.4)',
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('logoutForm').submit();
        }
    });
});
</script>

</body>
</html><?php /**PATH C:\xampp\htdocs\responde_v2\resources\views\layouts\user.blade.php ENDPATH**/ ?>