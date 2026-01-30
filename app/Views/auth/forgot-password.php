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
<?php if (($mode ?? 'email') === 'security'): ?>

<p class="text-muted text-center mb-4">
    Security option: answer your secret question to reset your password.
</p>

<?php if (($securityStep ?? 'lookup') === 'lookup'): ?>
<form action="/forgot-password/secret-question" method="POST">
    <?= View::csrf() ?>

    <div class="mb-4">
        <label class="form-label">Email Address</label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
            <input type="email" name="email" class="form-control <?= View::hasError('email') ? 'is-invalid' : '' ?>" placeholder="Enter your email" required>
        </div>
        <?php if ($error = View::error('email')): ?>
        <div class="text-danger small mt-1"><?= $error ?></div>
        <?php endif; ?>
    </div>

    <button type="submit" class="btn btn-primary w-100 mb-3">
        <i class="bi bi-shield-lock me-2"></i>Continue
    </button>

    <div class="alert alert-light border mb-0" role="alert">
        <small class="text-muted">
            If you didn't set a secret question, please use the reset link option or contact Support on
            <a href="mailto:barackdanieljackisa@gmail.com" class="text-decoration-none">barackdanieljackisa@gmail.com</a>.
        </small>
    </div>
</form>

<?php elseif (($securityStep ?? 'lookup') === 'answer'): ?>

<form action="/forgot-password/secret-verify" method="POST">
    <?= View::csrf() ?>

    <input type="hidden" name="email" value="<?= htmlspecialchars($secretEmail ?? '') ?>">

    <div class="mb-3">
        <label class="form-label">Your Secret Question</label>
        <div class="p-3 bg-light rounded">
            <strong><?= htmlspecialchars($secretQuestion ?? '') ?></strong>
        </div>
    </div>

    <div class="mb-4">
        <label class="form-label">Secret Answer</label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-key"></i></span>
            <input type="text" name="secret_answer" class="form-control <?= View::hasError('secret_answer') ? 'is-invalid' : '' ?>" autocomplete="off" placeholder="Enter your answer" required>
        </div>
        <?php if ($error = View::error('secret_answer')): ?>
        <div class="text-danger small mt-1"><?= $error ?></div>
        <?php endif; ?>
    </div>

    <button type="submit" class="btn btn-success w-100">
        <i class="bi bi-check-circle me-2"></i>Verify Answer
    </button>

    <div class="text-center mt-3">
        <a href="/forgot-password?mode=security&restart=1" class="text-decoration-none small">
            <i class="bi bi-arrow-clockwise me-1"></i>Use a different email
        </a>
    </div>
</form>

<?php else: ?>

<form action="/forgot-password/secret-reset" method="POST">
    <?= View::csrf() ?>

    <input type="hidden" name="email" value="<?= htmlspecialchars($secretEmail ?? '') ?>">

    <div class="mb-3">
        <label class="form-label">Email Address</label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
            <input type="email" class="form-control" value="<?= htmlspecialchars($secretEmail ?? '') ?>" disabled>
        </div>
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

    <button type="submit" class="btn btn-primary w-100">
        <i class="bi bi-shield-check me-2"></i>Reset Password
    </button>
</form>

<?php endif; ?>

<hr class="my-4">

<div class="text-center">
    <a href="/forgot-password?mode=email" class="text-decoration-none">
        <i class="bi bi-send me-1"></i>Reset Link Option
    </a>
</div>

<?php else: ?>

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

<div class="text-center">
    <a href="/forgot-password?mode=security" class="text-decoration-none">
        <i class="bi bi-shield-lock me-1"></i>Try Another Way
    </a>
</div>

<?php endif; ?>
<?php View::endSection(); ?>
