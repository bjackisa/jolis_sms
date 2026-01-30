<?php
/**
 * Login Page View
 * 
 * @developer   Jackisa Daniel Barack
 * @email       barackdanieljackisa@gmail.com
 * @website     jackisa.com
 * @quote       "One man and God are Majority"
 * @rights      All rights reserved
 */

use App\Core\View;
View::extend('layouts.auth');
?>

<?php View::section('content'); ?>
<h4 class="text-center mb-4">Sign In</h4>

<form action="/login" method="POST">
    <?= View::csrf() ?>
    
    <div class="mb-3">
        <label class="form-label">Email Address</label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
            <input type="email" name="email" class="form-control <?= View::hasError('email') ? 'is-invalid' : '' ?>" 
                   value="<?= View::old('email') ?>" placeholder="Enter your email" required>
        </div>
        <?php if ($error = View::error('email')): ?>
        <div class="text-danger small mt-1"><?= $error ?></div>
        <?php endif; ?>
    </div>
    
    <div class="mb-3">
        <label class="form-label">Password</label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-lock"></i></span>
            <input type="password" name="password" class="form-control <?= View::hasError('password') ? 'is-invalid' : '' ?>" 
                   placeholder="Enter your password" required id="password">
            <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                <i class="bi bi-eye"></i>
            </button>
        </div>
        <?php if ($error = View::error('password')): ?>
        <div class="text-danger small mt-1"><?= $error ?></div>
        <?php endif; ?>
    </div>
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="form-check">
            <input type="checkbox" name="remember" class="form-check-input" id="remember">
            <label class="form-check-label" for="remember">Remember me</label>
        </div>
        <a href="/forgot-password" class="text-decoration-none small">Forgot password?</a>
    </div>
    
    <button type="submit" class="btn btn-primary w-100 mb-3">
        <i class="bi bi-box-arrow-in-right me-2"></i>Sign In
    </button>
    
    <div class="text-center">
        <a href="/" class="text-decoration-none small">
            <i class="bi bi-arrow-left me-1"></i>Back to Home
        </a>
    </div>
</form>
<?php View::endSection(); ?>

<?php View::section('scripts'); ?>
<script>
document.getElementById('togglePassword').addEventListener('click', function() {
    const password = document.getElementById('password');
    const icon = this.querySelector('i');
    
    if (password.type === 'password') {
        password.type = 'text';
        icon.classList.remove('bi-eye');
        icon.classList.add('bi-eye-slash');
    } else {
        password.type = 'password';
        icon.classList.remove('bi-eye-slash');
        icon.classList.add('bi-eye');
    }
});
</script>
<?php View::endSection(); ?>
