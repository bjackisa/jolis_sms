<?php
/**
 * Forgot Password Page View
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
<h4 class="text-center mb-4">Forgot Password</h4>
<p class="text-muted text-center mb-4">
    Enter your email address and we'll send you a link to reset your password.
</p>

<form action="/forgot-password" method="POST">
    <?= View::csrf() ?>
    
    <div class="mb-4">
        <label class="form-label">Email Address</label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
            <input type="email" name="email" class="form-control <?= View::hasError('email') ? 'is-invalid' : '' ?>" 
                   placeholder="Enter your email" required>
        </div>
        <?php if ($error = View::error('email')): ?>
        <div class="text-danger small mt-1"><?= $error ?></div>
        <?php endif; ?>
    </div>
    
    <button type="submit" class="btn btn-primary w-100 mb-3">
        <i class="bi bi-send me-2"></i>Send Reset Link
    </button>
    
    <div class="text-center">
        <a href="/login" class="text-decoration-none small">
            <i class="bi bi-arrow-left me-1"></i>Back to Login
        </a>
    </div>
</form>
<?php View::endSection(); ?>
