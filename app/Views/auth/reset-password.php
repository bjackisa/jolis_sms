<?php
/**
 * Reset Password Page View
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
<h4 class="text-center mb-4">Reset Password</h4>
<p class="text-muted text-center mb-4">
    Enter your new password below.
</p>

<form action="/reset-password" method="POST">
    <?= View::csrf() ?>
    <input type="hidden" name="token" value="<?= $token ?>">
    
    <div class="mb-3">
        <label class="form-label">New Password</label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-lock"></i></span>
            <input type="password" name="password" class="form-control <?= View::hasError('password') ? 'is-invalid' : '' ?>" 
                   placeholder="Enter new password" required minlength="8">
        </div>
        <?php if ($error = View::error('password')): ?>
        <div class="text-danger small mt-1"><?= $error ?></div>
        <?php endif; ?>
    </div>
    
    <div class="mb-4">
        <label class="form-label">Confirm Password</label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
            <input type="password" name="password_confirmation" class="form-control" 
                   placeholder="Confirm new password" required>
        </div>
    </div>
    
    <button type="submit" class="btn btn-primary w-100 mb-3">
        <i class="bi bi-check-lg me-2"></i>Reset Password
    </button>
    
    <div class="text-center">
        <a href="/login" class="text-decoration-none small">
            <i class="bi bi-arrow-left me-1"></i>Back to Login
        </a>
    </div>
</form>
<?php View::endSection(); ?>
