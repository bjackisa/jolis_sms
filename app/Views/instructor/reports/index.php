<?php
/**
 * Instructor Reports Index View
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
<li class="breadcrumb-item active">Reports</li>
<?php View::endSection(); ?>

<?php View::section('content'); ?>
<div class="mb-4">
    <h4 class="fw-bold mb-1">Reports</h4>
    <p class="text-muted mb-0">View class and student performance reports</p>
</div>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-0">
                <h5 class="fw-bold mb-0"><i class="bi bi-building me-2"></i>Class Reports</h5>
            </div>
            <div class="card-body">
                <p class="text-muted">View performance reports for your classes.</p>
                
                <?php if (empty($classes)): ?>
                <p class="text-muted">No classes assigned.</p>
                <?php else: ?>
                <div class="list-group">
                    <?php foreach ($classes as $class): ?>
                    <a href="/instructor/reports/class/<?= $class['class_stream_id'] ?>" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                        <span>
                            <i class="bi bi-building me-2"></i>
                            <?= $class['class_name'] ?> - Stream <?= $class['stream_name'] ?>
                        </span>
                        <i class="bi bi-chevron-right"></i>
                    </a>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-0">
                <h5 class="fw-bold mb-0"><i class="bi bi-bar-chart me-2"></i>Quick Stats</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="bg-primary bg-opacity-10 rounded p-3 text-center">
                            <h3 class="fw-bold text-primary mb-0"><?= count($classes) ?></h3>
                            <small class="text-muted">Classes</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="bg-success bg-opacity-10 rounded p-3 text-center">
                            <h3 class="fw-bold text-success mb-0">-</h3>
                            <small class="text-muted">Avg. Performance</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card border-0 shadow-sm mt-4">
            <div class="card-header bg-transparent border-0">
                <h5 class="fw-bold mb-0"><i class="bi bi-info-circle me-2"></i>Report Types</h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="mb-2">
                        <i class="bi bi-check-circle text-success me-2"></i>
                        <strong>Class Report:</strong> Overall class performance with rankings
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-check-circle text-success me-2"></i>
                        <strong>Student Report:</strong> Individual student performance breakdown
                    </li>
                    <li>
                        <i class="bi bi-check-circle text-success me-2"></i>
                        <strong>Subject Analysis:</strong> Subject-wise performance analysis
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
<?php View::endSection(); ?>
