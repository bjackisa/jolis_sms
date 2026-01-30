<?php
/**
 * Main Application Layout
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
    <title><?= $title ?? 'Jolis SMS' ?></title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Animate.css -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="/public/assets/css/style.css" rel="stylesheet">
    
    <?= View::yield('styles') ?>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="/">
                <i class="bi bi-mortarboard-fill me-2"></i>Jolis SMS
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/about">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/contact">Contact</a>
                    </li>
                    <li class="nav-item ms-lg-3">
                        <a class="btn btn-light btn-sm px-4" href="/login">
                            <i class="bi bi-box-arrow-in-right me-1"></i>Sign In
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main style="padding-top: 76px;">
        <?= View::yield('content') ?>
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-light py-5 mt-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4">
                    <h5 class="fw-bold mb-3">
                        <i class="bi bi-mortarboard-fill me-2"></i>Jolis SMS
                    </h5>
                    <p class="text-muted">
                        A comprehensive School Management System for Jolis ICT Academy. 
                        Managing education with excellence and technology.
                    </p>
                </div>
                <div class="col-lg-2">
                    <h6 class="fw-bold mb-3">Quick Links</h6>
                    <ul class="list-unstyled">
                        <li><a href="/" class="text-muted text-decoration-none">Home</a></li>
                        <li><a href="/about" class="text-muted text-decoration-none">About</a></li>
                        <li><a href="/contact" class="text-muted text-decoration-none">Contact</a></li>
                        <li><a href="/login" class="text-muted text-decoration-none">Login</a></li>
                    </ul>
                </div>
                <div class="col-lg-3">
                    <h6 class="fw-bold mb-3">Contact Info</h6>
                    <ul class="list-unstyled text-muted">
                        <li><i class="bi bi-geo-alt me-2"></i>Kampala, Uganda</li>
                        <li><i class="bi bi-telephone me-2"></i>+256 700 000 000</li>
                        <li><i class="bi bi-envelope me-2"></i>info@jolis.academy</li>
                    </ul>
                </div>
                <div class="col-lg-3">
                    <h6 class="fw-bold mb-3">Follow Us</h6>
                    <div class="d-flex gap-3">
                        <a href="#" class="text-muted fs-4"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="text-muted fs-4"><i class="bi bi-twitter-x"></i></a>
                        <a href="#" class="text-muted fs-4"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="text-muted fs-4"><i class="bi bi-linkedin"></i></a>
                    </div>
                </div>
            </div>
            <hr class="my-4 border-secondary">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start">
                    <p class="mb-0 text-muted small">
                        &copy; <?= date('Y') ?> Jolis ICT Academy. All rights reserved.
                    </p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <p class="mb-0 text-muted small">
                        Developed by <a href="https://jackisa.com" class="text-primary text-decoration-none" target="_blank">Jackisa Daniel Barack</a>
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script src="/public/assets/js/app.js"></script>
    
    <?= View::yield('scripts') ?>
</body>
</html>
