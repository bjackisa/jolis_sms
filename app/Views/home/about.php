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

<!-- Uganda Education System -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Uganda's Education System</h2>
            <p class="text-muted">Understanding the national curriculum and examination structure</p>
        </div>
        
        <div class="row g-4">
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="bi bi-mortarboard me-2"></i>O'Level (UCE) - Senior 1 to 4</h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">
                            The Uganda Certificate of Education (UCE) is a national examination administered by 
                            the Uganda National Examinations Board (UNEB) at the end of Senior 4.
                        </p>
                        <h6 class="fw-bold mt-3">Core Subjects</h6>
                        <div class="row">
                            <div class="col-6">
                                <ul class="list-unstyled small">
                                    <li class="mb-1"><i class="bi bi-check text-success me-1"></i>English Language</li>
                                    <li class="mb-1"><i class="bi bi-check text-success me-1"></i>Mathematics</li>
                                    <li class="mb-1"><i class="bi bi-check text-success me-1"></i>Physics</li>
                                    <li class="mb-1"><i class="bi bi-check text-success me-1"></i>Chemistry</li>
                                    <li class="mb-1"><i class="bi bi-check text-success me-1"></i>Biology</li>
                                </ul>
                            </div>
                            <div class="col-6">
                                <ul class="list-unstyled small">
                                    <li class="mb-1"><i class="bi bi-check text-success me-1"></i>Geography</li>
                                    <li class="mb-1"><i class="bi bi-check text-success me-1"></i>History</li>
                                    <li class="mb-1"><i class="bi bi-check text-success me-1"></i>CRE/IRE</li>
                                    <li class="mb-1"><i class="bi bi-check text-success me-1"></i>Agriculture</li>
                                    <li class="mb-1"><i class="bi bi-check text-success me-1"></i>Computer Studies</li>
                                </ul>
                            </div>
                        </div>
                        <div class="mt-3 p-2 bg-light rounded">
                            <strong class="small">Grading Scale:</strong>
                            <span class="small text-muted">D1 (80-100%), D2 (75-79%), C3 (70-74%), C4 (65-69%), C5 (60-64%), C6 (55-59%), P7 (50-54%), P8 (45-49%), F9 (0-44%)</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="bi bi-award me-2"></i>A'Level (UACE) - Senior 5 to 6</h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">
                            The Uganda Advanced Certificate of Education (UACE) is taken at the end of Senior 6. 
                            Students specialize in 3-4 principal subjects plus 2 subsidiary subjects.
                        </p>
                        <h6 class="fw-bold mt-3">Subject Combinations</h6>
                        <ul class="list-unstyled small">
                            <li class="mb-2"><i class="bi bi-bookmark text-primary me-1"></i><strong>Sciences:</strong> PCM (Physics, Chemistry, Math), PCB (Physics, Chemistry, Biology), BCM</li>
                            <li class="mb-2"><i class="bi bi-bookmark text-primary me-1"></i><strong>Arts:</strong> HEG (History, Economics, Geography), HED, HEL (Literature)</li>
                            <li class="mb-2"><i class="bi bi-bookmark text-primary me-1"></i><strong>Technical:</strong> MEK (Math, Economics, Entrepreneurship), Computer Science combinations</li>
                        </ul>
                        <h6 class="fw-bold mt-3">Subsidiary Subjects</h6>
                        <p class="small text-muted mb-2">General Paper (compulsory) + one other (ICT, Sub-Math, etc.)</p>
                        <div class="mt-3 p-2 bg-light rounded">
                            <strong class="small">Grading Scale:</strong>
                            <span class="small text-muted">A (80-100%), B (70-79%), C (60-69%), D (50-59%), E (40-49%), O (35-39%), F (0-34%)</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Academic Calendar -->
        <div class="row mt-5">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0"><i class="bi bi-calendar3 me-2"></i>Uganda Academic Calendar</h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-md-4 mb-3 mb-md-0">
                                <div class="p-3 bg-light rounded">
                                    <h6 class="fw-bold text-primary">Term I</h6>
                                    <p class="small text-muted mb-0">February - May</p>
                                    <p class="small text-muted mb-0">(~14 weeks)</p>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3 mb-md-0">
                                <div class="p-3 bg-light rounded">
                                    <h6 class="fw-bold text-success">Term II</h6>
                                    <p class="small text-muted mb-0">June - August</p>
                                    <p class="small text-muted mb-0">(~12 weeks)</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="p-3 bg-light rounded">
                                    <h6 class="fw-bold text-warning">Term III</h6>
                                    <p class="small text-muted mb-0">September - December</p>
                                    <p class="small text-muted mb-0">(~12 weeks + UNEB exams)</p>
                                </div>
                            </div>
                        </div>
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
