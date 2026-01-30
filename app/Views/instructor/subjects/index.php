<?php
/**
 * Instructor Subjects Index View
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
<li class="breadcrumb-item active">Subjects</li>
<?php View::endSection(); ?>

<?php View::section('content'); ?>
<div class="mb-4">
    <h4 class="fw-bold mb-1">My Subjects</h4>
    <p class="text-muted mb-0"><?= $academicYear ? 'Academic Year ' . $academicYear['name'] : '' ?></p>
</div>

<div class="row g-4">
    <?php if (empty($subjects)): ?>
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5">
                <i class="bi bi-book text-muted" style="font-size: 4rem;"></i>
                <h5 class="mt-3">No Subjects Assigned</h5>
                <p class="text-muted">You haven't been assigned to any subjects yet.</p>
            </div>
        </div>
    </div>
    <?php else: ?>
    <?php foreach ($subjects as $subject): ?>
    <div class="col-md-6 col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h5 class="fw-bold mb-1"><?= $subject['name'] ?></h5>
                        <span class="badge bg-secondary"><?= $subject['code'] ?></span>
                    </div>
                    <div class="bg-success bg-opacity-10 text-success rounded-circle p-2">
                        <i class="bi bi-book fs-4"></i>
                    </div>
                </div>
                <p class="text-muted small mb-2">
                    <i class="bi bi-folder me-1"></i><?= $subject['category_name'] ?? 'General' ?>
                </p>
                <p class="text-muted small mb-0">
                    <i class="bi bi-file-earmark me-1"></i><?= count($subject['papers'] ?? []) ?> Paper(s)
                </p>
            </div>
            <div class="card-footer bg-transparent border-0">
                <?php if (!empty($subject['papers'])): ?>
                <div class="d-flex flex-wrap gap-1">
                    <?php foreach ($subject['papers'] as $paper): ?>
                    <span class="badge bg-light text-dark"><?= $paper['name'] ?></span>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
    <?php endif; ?>
</div>
<?php View::endSection(); ?>
