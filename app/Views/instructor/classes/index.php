<?php
/**
 * Instructor Classes Index View
 * 
 * @developer   Jackisa Daniel Barack
 * @email       barackdanieljackisa@gmail.com
 * @website     jackisa.com
 * @quote       "One man and God are Majority"
 * @rights      All rights reserved
 */

use App\Core\View;
View::extend('layouts.dashboard');
?>

<?php View::section('breadcrumb'); ?>
<li class="breadcrumb-item active">Classes</li>
<?php View::endSection(); ?>

<?php View::section('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">My Classes</h4>
        <p class="text-muted mb-0"><?= $academicYear ? 'Academic Year ' . $academicYear['name'] : '' ?></p>
    </div>
</div>

<div class="row g-4">
    <?php if (empty($classes)): ?>
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5">
                <i class="bi bi-building text-muted" style="font-size: 4rem;"></i>
                <h5 class="mt-3">No Classes Assigned</h5>
                <p class="text-muted">You haven't been assigned to any classes yet.</p>
            </div>
        </div>
    </div>
    <?php else: ?>
    <?php foreach ($classes as $class): ?>
    <div class="col-md-6 col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h5 class="fw-bold mb-1"><?= $class['class_name'] ?></h5>
                        <span class="badge bg-primary"><?= $class['stream_name'] ?></span>
                    </div>
                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-2">
                        <i class="bi bi-building fs-4"></i>
                    </div>
                </div>
                <div class="d-flex justify-content-between text-muted small mb-3">
                    <span><i class="bi bi-people me-1"></i><?= $class['student_count'] ?? 0 ?> Students</span>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0">
                <a href="/instructor/classes/<?= $class['class_stream_id'] ?>" class="btn btn-outline-primary w-100">
                    <i class="bi bi-eye me-1"></i>View Class
                </a>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
    <?php endif; ?>
</div>
<?php View::endSection(); ?>
