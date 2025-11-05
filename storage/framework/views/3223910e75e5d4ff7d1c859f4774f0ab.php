<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register | Responde</title>

    <link rel="icon" type="image/png" href="<?php echo e(asset('images/respondeLogo.png')); ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600;700&display=swap");

        body {
            min-height: 100vh;
            font-family: "Poppins", sans-serif;
            background: url('<?php echo e(asset('images/md-bg.jpg')); ?>') no-repeat center center/cover;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            overflow-x: hidden;
            overflow-y: auto;
            position: relative;
        }

        .register-card {
            background: #fff;
            color: #333;
            border-radius: 16px;
            box-shadow: 0 6px 25px rgba(0,0,0,0.25);
            padding: 2.5rem;
            width: 100%;
            max-width: 500px;
            margin: 2rem;
            animation: fadeInUp 0.7s ease;
        }

        .overlay {
            position: absolute;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(6px);
            z-index: -1;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .register-logo {
            text-align: center;
            margin-bottom: 1rem;
        }

        .register-logo img {
            height: 75px;
        }

        .register-logo h3 {
            font-weight: 700;
            margin-top: 0.75rem;
            font-size: 1.6rem;
            color: #222;
        }

        .register-logo p {
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

        .btn-register {
            background-color: #ffc107;
            color: #000;
            font-weight: 600;
            border: none;
            border-radius: 10px;
            width: 100%;
            padding: 0.8rem;
            transition: 0.25s;
        }

        .btn-register:hover {
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

        /* Password helper */
        .password-helper {
            font-size: 0.85rem;
            margin-top: 0.4rem;
        }

        .password-helper li {
            list-style: none;
        }

        .password-helper i {
            margin-right: 6px;
        }

        .password-valid {
            color: green;
        }

        .password-invalid {
            color: red;
        }
    </style>
</head>
<body>
    <div class="overlay"></div>

    <div class="register-card">
        <div class="register-logo">
            <img src="<?php echo e(asset('images/respondeLogo.png')); ?>" alt="Responde Logo">
            <h3>RESPONDE</h3>
            <p><strong>Realtime Emergency System for Prompt, Operation, Notification, Dispatch and Evaluation</strong></p>
        </div>

        <?php if(session('status')): ?>
            <div class="alert alert-success py-2"><?php echo e(session('status')); ?></div>
        <?php endif; ?>

        <form method="POST" action="<?php echo e(route('register')); ?>" id="registerForm">
            <?php echo csrf_field(); ?>

            <div class="row mb-3">
                <div class="col-md-6 mb-3 mb-md-0">
                    <label for="name" class="form-label">Full Name</label>
                    <input id="name" class="form-control" type="text" name="name" value="<?php echo e(old('name')); ?>" required autofocus>
                    <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="text-danger small mt-1"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="col-md-6">
                    <label for="username" class="form-label">Username</label>
                    <input id="username" class="form-control" type="text" name="username" value="<?php echo e(old('username')); ?>" required>
                    <?php $__errorArgs = ['username'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="text-danger small mt-1"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input id="email" class="form-control" type="email" name="email" value="<?php echo e(old('email')); ?>" required>
                <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="text-danger small mt-1"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="mb-3">
                <label for="phone_number" class="form-label">Phone Number</label>
                <input 
                    id="phone_number" 
                    class="form-control" 
                    type="tel" 
                    name="phone_number" 
                    value="<?php echo e(old('phone_number')); ?>" 
                    pattern="^\+639\d{9}$"
                    placeholder="e.g. +639123456789"
                    required>
                <small class="text-muted">Format: +639xxxxxxxxx</small>
                <?php $__errorArgs = ['phone_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="text-danger small mt-1"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="row mb-3">
                <!-- Password -->
                <div class="col-md-6 mb-3 mb-md-0">
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

                <!-- Confirm Password -->
                <div class="col-md-6">
                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                    <input id="password_confirmation" class="form-control" type="password" name="password_confirmation" required>
                    <?php $__errorArgs = ['password_confirmation'];
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
                
                <!-- Password Helper Text -->
                <div id="passwordHelper" class="form-text mt-2 text-muted" style="font-size: 0.85rem;">
                    Must contain at least:
                    <span id="pwLength" class="text-danger">8 characters</span>,
                    <span id="pwUpper" class="text-danger">1 uppercase</span>,
                    <span id="pwLower" class="text-danger">1 lowercase</span>,
                    <span id="pwNumber" class="text-danger">1 number</span>,
                    <span id="pwSpecial" class="text-danger">1 special character</span>.
                </div>
            </div>

            <input type="hidden" name="role" value="user">

            <button type="submit" class="btn btn-register mb-3" id="registerBtn" disabled>
                <i class="bi bi-person-plus me-1"></i> Register
            </button>

            <div class="text-center">
                <p class="mb-0">Already have an account?
                    <a href="<?php echo e(route('login')); ?>">Log in here</a>
                </p>
            </div>
        </form>

        <div class="footer-note">
            &copy; <?php echo e(date('Y')); ?> Responde System. All rights reserved.
        </div>
    </div>

    <script>
        const password = document.getElementById('password');
        const confirmPassword = document.getElementById('password_confirmation');
        const registerBtn = document.getElementById('registerBtn');

        const pwChecks = {
            length: document.getElementById('pwLength'),
            upper: document.getElementById('pwUpper'),
            lower: document.getElementById('pwLower'),
            number: document.getElementById('pwNumber'),
            special: document.getElementById('pwSpecial'),
        };

        password.addEventListener('input', validatePassword);
        confirmPassword.addEventListener('input', validatePassword);

        function validatePassword() {
            const val = password.value;

            const rules = {
                length: val.length >= 8,
                upper: /[A-Z]/.test(val),
                lower: /[a-z]/.test(val),
                number: /[0-9]/.test(val),
                special: /[!@#$%^&*(),.?":{}|<>]/.test(val),
            };

            let allValid = true;
            for (const [key, passed] of Object.entries(rules)) {
                pwChecks[key].classList.toggle('text-success', passed);
                pwChecks[key].classList.toggle('text-danger', !passed);
                allValid = allValid && passed;
            }

            const matches = password.value === confirmPassword.value && password.value !== '';
            registerBtn.disabled = !(allValid && matches);
        }
    </script>
</body>
</html><?php /**PATH C:\xampp\htdocs\responde_v2\resources\views\auth\register.blade.php ENDPATH**/ ?>