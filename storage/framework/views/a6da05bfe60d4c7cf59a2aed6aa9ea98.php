<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | Responde</title>

    <link rel="icon" type="image/png" href="<?php echo e(asset('images/respondeLogo.png')); ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600;700&display=swap");

        body {
            height: 100vh;
            font-family: "Poppins", sans-serif;
            background: url('<?php echo e(asset('images/md-bg.jpg')); ?>') no-repeat center center/cover;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            overflow: hidden;
            position: relative;
        }

        .overlay {
            position: absolute;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(6px);
            z-index: -1;
        }

        .login-card {
            background: #fff;
            color: #333;
            border-radius: 16px;
            box-shadow: 0 6px 25px rgba(0, 0, 0, 0.25);
            padding: 2.5rem;
            width: 100%;
            max-width: 450px;
            animation: fadeInUp 0.7s ease;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .login-logo {
            text-align: center;
            margin-bottom: 1rem;
        }

        .login-logo img {
            height: 75px;
        }

        .login-logo h3 {
            font-weight: 700;
            margin-top: 0.75rem;
            font-size: 1.6rem;
            color: #222;
        }

        .login-logo p {
            font-size: 0.9rem;
            font-weight: 500;
            color: #666;
            margin-bottom: 1rem;
        }

        .form-label {
            font-weight: 600;
            color: #333;
        }

        .form-control {
            border-radius: 10px;
            padding: 0.7rem 1rem;
            border: 1px solid #ccc;
        }

        .form-control:focus {
            border-color: #ffc107;
            box-shadow: 0 0 0 0.2rem rgba(255,193,7,0.25);
        }

        .btn-login {
            background-color: #ffc107;
            color: #000;
            font-weight: 600;
            border: none;
            border-radius: 10px;
            width: 100%;
            padding: 0.8rem;
            transition: 0.25s;
        }

        .btn-login:hover {
            background-color: #e0a800;
        }

        .text-center a {
            color: #ffc107;
            text-decoration: none;
            font-weight: 500;
        }

        .text-center a:hover {
            text-decoration: underline;
        }

        .footer-note {
            margin-top: 1.5rem;
            text-align: center;
            font-size: 0.85rem;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="overlay"></div>

    <div class="login-card">
        <div class="login-logo">
            <img src="<?php echo e(asset('images/respondeLogo.png')); ?>" alt="Responde Logo">
            <h3>RESPONDE</h3>
            <p><strong>Realtime Emergency System for Prompt, Operation, Notification, Dispatch and Evaluation</strong></p>
        </div>

        <!-- Session Status -->
        <?php if(session('status')): ?>
            <div class="alert alert-success py-2"><?php echo e(session('status')); ?></div>
        <?php endif; ?>

        <form method="POST" action="<?php echo e(route('login')); ?>">
            <?php echo csrf_field(); ?>

            <!-- Email -->
            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input id="email" class="form-control" type="email" name="email" value="<?php echo e(old('email')); ?>" required autofocus>
                <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="text-danger small mt-1"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <!-- Password -->
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input id="password" class="form-control" type="password" name="password" required>
                <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="text-danger small mt-1"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <!-- Remember Me -->
            <div class="mb-3 form-check">
                <input class="form-check-input" type="checkbox" name="remember" id="remember_me">
                <label class="form-check-label" for="remember_me">Remember Me</label>
            </div>

            <!-- Submit -->
            <button type="submit" class="btn btn-login mb-3">
                <i class="bi bi-box-arrow-in-right me-1"></i> Log In
            </button>

            <div class="text-center">
                <p class="mb-0">Don’t have an account?
                    <a href="<?php echo e(route('register')); ?>">Register here</a>
                </p>
            </div>
        </form>

        <div class="footer-note">
            &copy; <?php echo e(date('Y')); ?> Responde System. All rights reserved.
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\responde_v2\resources\views\auth\login.blade.php ENDPATH**/ ?>