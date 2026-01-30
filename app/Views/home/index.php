<?php
/**
 * Home Page View
 * 
 * @developer   Jackisa Daniel Barack
 * @email       barackdanieljackisa@gmail.com
 * @website     jackisa.com
 * @quote       "One man and God are Majority"
 * @rights      All rights reserved
 */

use App\Core\View;
View::extend('layouts.app');
?>

<?php View::section('content'); ?>
<!-- Hero Section -->
<section class="hero-section bg-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center min-vh-75">
            <div class="col-lg-6 py-5">
                <h1 class="display-4 fw-bold mb-4 animate__animated animate__fadeInUp">
                    Welcome to<br>
                    <span class="text-warning"><?= $schoolName ?></span>
                </h1>
                <p class="lead mb-4 animate__animated animate__fadeInUp animate__delay-1s">
                    <?= $schoolMotto ?>
                </p>
                <p class="mb-4 animate__animated animate__fadeInUp animate__delay-1s">
                    A comprehensive School Management System designed to streamline academic operations, 
                    manage student results, and enhance communication between instructors and students.
                </p>
                <div class="d-flex gap-3 animate__animated animate__fadeInUp animate__delay-2s">
                    <a href="/login" class="btn btn-warning btn-lg px-4">
                        <i class="bi bi-box-arrow-in-right me-2"></i>Sign In
                    </a>
                    <a href="/about" class="btn btn-outline-light btn-lg px-4">
                        Learn More
                    </a>
                </div>
            </div>
            <div class="col-lg-6 text-center py-5 animate__animated animate__fadeInRight">
                <img src="https://illustrations.popsy.co/amber/student-going-to-school.svg" alt="Education" class="img-fluid" style="max-height: 400px;">
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-3 col-6">
                <div class="card border-0 shadow-sm h-100 text-center p-4">
                    <div class="card-body">
                        <div class="display-4 fw-bold text-primary mb-2"><?= $stats['classes'] ?></div>
                        <p class="text-muted mb-0">Classes</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="card border-0 shadow-sm h-100 text-center p-4">
                    <div class="card-body">
                        <div class="display-4 fw-bold text-success mb-2"><?= $stats['students'] ?></div>
                        <p class="text-muted mb-0">Students</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="card border-0 shadow-sm h-100 text-center p-4">
                    <div class="card-body">
                        <div class="display-4 fw-bold text-warning mb-2"><?= $stats['instructors'] ?></div>
                        <p class="text-muted mb-0">Instructors</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="card border-0 shadow-sm h-100 text-center p-4">
                    <div class="card-body">
                        <div class="display-4 fw-bold text-info mb-2"><?= $stats['subjects'] ?></div>
                        <p class="text-muted mb-0">Subjects</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Why Choose Jolis SMS?</h2>
            <p class="text-muted">Comprehensive features designed for modern education management</p>
        </div>
        
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <div class="feature-icon bg-primary bg-opacity-10 text-primary rounded-circle p-3 d-inline-flex mb-3">
                            <i class="bi bi-clipboard-data fs-3"></i>
                        </div>
                        <h5 class="fw-bold">Results Management</h5>
                        <p class="text-muted">
                            Comprehensive exam results tracking with Uganda's O'Level and A'Level grading systems. 
                            BOT, MID, and EOT exams with automatic grade calculation.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <div class="feature-icon bg-success bg-opacity-10 text-success rounded-circle p-3 d-inline-flex mb-3">
                            <i class="bi bi-journal-check fs-3"></i>
                        </div>
                        <h5 class="fw-bold">Homework & Scripts</h5>
                        <p class="text-muted">
                            Easy homework assignment and submission system. Upload exam scripts 
                            for students to access and prepare for their examinations.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <div class="feature-icon bg-warning bg-opacity-10 text-warning rounded-circle p-3 d-inline-flex mb-3">
                            <i class="bi bi-bar-chart fs-3"></i>
                        </div>
                        <h5 class="fw-bold">Analytics & Reports</h5>
                        <p class="text-muted">
                            Visual analytics and detailed reports for tracking student performance. 
                            Class rankings, subject analysis, and progress monitoring.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <div class="feature-icon bg-info bg-opacity-10 text-info rounded-circle p-3 d-inline-flex mb-3">
                            <i class="bi bi-people fs-3"></i>
                        </div>
                        <h5 class="fw-bold">Class Management</h5>
                        <p class="text-muted">
                            Organize students into classes (S1-S6) and streams (A, B, C, D). 
                            Track enrollments and manage academic years efficiently.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <div class="feature-icon bg-danger bg-opacity-10 text-danger rounded-circle p-3 d-inline-flex mb-3">
                            <i class="bi bi-bell fs-3"></i>
                        </div>
                        <h5 class="fw-bold">Notices & Announcements</h5>
                        <p class="text-muted">
                            Keep everyone informed with targeted notices. Send announcements 
                            to specific classes, roles, or the entire school community.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <div class="feature-icon bg-secondary bg-opacity-10 text-secondary rounded-circle p-3 d-inline-flex mb-3">
                            <i class="bi bi-shield-check fs-3"></i>
                        </div>
                        <h5 class="fw-bold">Secure & Modern</h5>
                        <p class="text-muted">
                            Role-based access control with secure authentication. 
                            Modern, responsive interface that works on all devices.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-5 bg-primary text-white">
    <div class="container text-center">
        <h2 class="fw-bold mb-3">Ready to Get Started?</h2>
        <p class="lead mb-4">Sign in to access your dashboard and manage your academic journey.</p>
        <a href="/login" class="btn btn-warning btn-lg px-5">
            <i class="bi bi-box-arrow-in-right me-2"></i>Sign In Now
        </a>
    </div>
</section>
<?php View::endSection(); ?>
