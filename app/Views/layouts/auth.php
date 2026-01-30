<?php
/**
 * Authentication Layout
 * 
 * @developer   Jackisa Daniel Barack
 * @email       barackdanieljackisa@gmail.com
 * @website     jackisa.com
 * @quote       "One man and God are Majority"
 * @rights      All rights reserved
 */

use App\Core\View;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Login - Jolis SMS' ?></title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="/assets/css/auth.css" rel="stylesheet">
</head>
<body class="auth-body">
    <div class="auth-container">
        <div class="auth-card animate__animated animate__fadeIn">
            <div class="auth-header">
                <a href="/" class="auth-logo">
                    <i class="bi bi-mortarboard-fill"></i>
                    <span>Jolis SMS</span>
                </a>
                <p class="text-muted">Jolis ICT Academy</p>
            </div>
            
            <?php 
            $flash_success = $_SESSION['_flash']['success'] ?? null;
            $flash_error = $_SESSION['_flash']['error'] ?? null;
            unset($_SESSION['_flash']['success'], $_SESSION['_flash']['error']);
            ?>
            
            <?php if ($flash_success): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= $flash_success ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>
            
            <?php if ($flash_error): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= $flash_error ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>

            <?= View::yield('content') ?>
            
            <div class="auth-footer">
                <p class="text-muted small mb-0">
                    &copy; <?= date('Y') ?> Jolis ICT Academy. All rights reserved.
                </p>
                <p class="text-muted small mb-0">
                    Developed by <a href="https://jackisa.com" target="_blank">Jackisa Daniel Barack</a>
                </p>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <?= View::yield('scripts') ?>
</body>
</html>
