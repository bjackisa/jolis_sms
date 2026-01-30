<?php
/**
 * About Page View
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
<section class="bg-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center py-5">
            <div class="col-lg-8 mx-auto text-center">
                <h1 class="display-4 fw-bold mb-4">About Us</h1>
                <p class="lead">
                    Learn more about <?= $schoolName ?> and our commitment to excellence in education.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- About Content -->
<section class="py-5">
    <div class="container">
        <div class="row g-5 align-items-center">
            <div class="col-lg-6">
                <img src="https://illustrations.popsy.co/amber/remote-work.svg" alt="About Us" class="img-fluid">
            </div>
            <div class="col-lg-6">
                <h2 class="fw-bold mb-4">Our Story</h2>
                <p class="text-muted">
                    <?= $schoolName ?> is a premier educational institution dedicated to providing 
                    quality secondary education in Uganda. We believe in nurturing young minds 
                    through technology-enhanced learning and comprehensive academic programs.
                </p>
                <p class="text-muted">
                    Our School Management System (SMS) is designed to streamline academic operations, 
                    making it easier for instructors to manage results and for students to track 
                    their academic progress.
                </p>
                <h5 class="fw-bold mt-4 mb-3">Our Mission</h5>
                <p class="text-muted">
                    To provide quality education that empowers students with knowledge, skills, 
                    and values necessary for success in the 21st century.
                </p>
                <h5 class="fw-bold mt-4 mb-3">Our Vision</h5>
                <p class="text-muted">
                    To be a leading center of academic excellence, producing well-rounded individuals 
                    who contribute positively to society.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Academic Structure -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Academic Structure</h2>
            <p class="text-muted">Our comprehensive secondary education program</p>
        </div>
        
        <div class="row g-4">
            <div class="col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="bi bi-mortarboard me-2"></i>O'Level (UCE)</h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">
                            Ordinary Level education covering Senior 1 to Senior 4, culminating in the 
                            Uganda Certificate of Education (UCE) examination set by UNEB.
                        </p>
                        <ul class="list-unstyled">
                            <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Senior 1 (S1)</li>
                            <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Senior 2 (S2)</li>
                            <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Senior 3 (S3)</li>
                            <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Senior 4 (S4)</li>
                        </ul>
                        <p class="small text-muted mb-0">
                            <strong>Grading:</strong> D1 (Distinction) to F9 (Fail)
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="bi bi-award me-2"></i>A'Level (UACE)</h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">
                            Advanced Level education covering Senior 5 and Senior 6, culminating in the 
                            Uganda Advanced Certificate of Education (UACE) examination set by UNEB.
                        </p>
                        <ul class="list-unstyled">
                            <li class="mb-2"><i class="bi bi-check-circle text-primary me-2"></i>Senior 5 (S5)</li>
                            <li class="mb-2"><i class="bi bi-check-circle text-primary me-2"></i>Senior 6 (S6)</li>
                        </ul>
                        <p class="small text-muted mb-0">
                            <strong>Grading:</strong> A (Excellent) to F (Fail)
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Assessment Structure -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Assessment Structure</h2>
            <p class="text-muted">How we evaluate student performance each term</p>
        </div>
        
        <div class="row g-4 justify-content-center">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm text-center h-100">
                    <div class="card-body p-4">
                        <div class="display-4 fw-bold text-primary mb-2">20%</div>
                        <h5 class="fw-bold">Beginning of Term (BOT)</h5>
                        <p class="text-muted mb-0">Assessment at the start of each term</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm text-center h-100">
                    <div class="card-body p-4">
                        <div class="display-4 fw-bold text-warning mb-2">20%</div>
                        <h5 class="fw-bold">Mid Term (MID)</h5>
                        <p class="text-muted mb-0">Assessment at the middle of each term</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm text-center h-100">
                    <div class="card-body p-4">
                        <div class="display-4 fw-bold text-success mb-2">60%</div>
                        <h5 class="fw-bold">End of Term (EOT)</h5>
                        <p class="text-muted mb-0">Final assessment at the end of each term</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php View::endSection(); ?>
