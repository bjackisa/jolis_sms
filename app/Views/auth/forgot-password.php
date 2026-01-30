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

<hr class="my-4">

<h5 class="text-center mb-3">Try Another Way</h5>

<?php if (empty($secretQuestion)): ?>
<p class="text-muted text-center mb-3 small">
    If you set a secret question in your profile, you can reset your password here.
</p>

<form action="/forgot-password/secret-question" method="POST">
    <?= View::csrf() ?>

    <div class="mb-3">
        <label class="form-label">Email Address</label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
            <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
        </div>
    </div>

    <button type="submit" class="btn btn-outline-primary w-100">
        <i class="bi bi-question-circle me-2"></i>Show My Secret Question
    </button>
</form>

<div class="alert alert-light border mt-3 mb-0" role="alert">
    <small class="text-muted">
        If you didn't set a secret question, please use the reset link option above or contact Support on
        <a href="mailto:barackdanieljackisa@gmail.com" class="text-decoration-none">barackdanieljackisa@gmail.com</a>.
    </small>
</div>

<?php else: ?>

<form action="/forgot-password/secret-reset" method="POST">
    <?= View::csrf() ?>

    <div class="mb-3">
        <label class="form-label">Email Address</label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($secretEmail ?? '') ?>" required>
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label">Your Secret Question</label>
        <div class="p-3 bg-light rounded">
            <strong><?= htmlspecialchars($secretQuestion) ?></strong>
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label">Secret Answer</label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-key"></i></span>
            <input type="password" name="secret_answer" class="form-control <?= View::hasError('secret_answer') ? 'is-invalid' : '' ?>" placeholder="Enter your answer" required>
        </div>
        <?php if ($error = View::error('secret_answer')): ?>
        <div class="text-danger small mt-1"><?= $error ?></div>
        <?php endif; ?>
    </div>

    <div class="mb-3">
        <label class="form-label">New Password</label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-lock"></i></span>
            <input type="password" name="password" class="form-control <?= View::hasError('password') ? 'is-invalid' : '' ?>" minlength="8" required>
        </div>
        <?php if ($error = View::error('password')): ?>
        <div class="text-danger small mt-1"><?= $error ?></div>
        <?php endif; ?>
    </div>

    <div class="mb-4">
        <label class="form-label">Confirm Password</label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
            <input type="password" name="password_confirmation" class="form-control" required>
        </div>
    </div>

    <button type="submit" class="btn btn-success w-100">
        <i class="bi bi-shield-check me-2"></i>Reset Password
    </button>

    <div class="text-center mt-3">
        <a href="/forgot-password" class="text-decoration-none small">
            <i class="bi bi-arrow-clockwise me-1"></i>Try a different email
        </a>
    </div>
</form>

<?php endif; ?>
<?php View::endSection(); ?>
