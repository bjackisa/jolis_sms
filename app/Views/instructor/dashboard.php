<?php
/**
 * Instructor Dashboard View
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
<li class="breadcrumb-item active">Dashboard</li>
<?php View::endSection(); ?>

<?php View::section('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Welcome back, <?= $user['first_name'] ?>!</h4>
        <p class="text-muted mb-0">
            <?= $academicYear ? $academicYear['name'] : '' ?> 
            <?= $term ? '- ' . $term['name'] : '' ?>
        </p>
    </div>
</div>

<!-- Stats Cards -->
<div class="row g-4 mb-4">
    <div class="col-lg col-md-4 col-6">
        <div class="card border-0 shadow-sm bg-primary text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="fw-bold mb-0"><?= $stats['classes'] ?></h3>
                        <p class="mb-0 opacity-75">My Classes</p>
                    </div>
                    <div class="opacity-50">
                        <i class="bi bi-building fs-1"></i>
                    </div>
                </div>
            </div>
            <a href="/instructor/classes" class="card-footer bg-transparent border-0 text-white text-decoration-none d-flex justify-content-between align-items-center">
                <span>Manage</span>
                <i class="bi bi-arrow-right"></i>
            </a>
        </div>
    </div>
    <div class="col-lg col-md-4 col-6">
        <div class="card border-0 shadow-sm bg-secondary text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="fw-bold mb-0"><?= $stats['subjects'] ?? 0 ?></h3>
                        <p class="mb-0 opacity-75">My Subjects</p>
                    </div>
                    <div class="opacity-50">
                        <i class="bi bi-book fs-1"></i>
                    </div>
                </div>
            </div>
            <a href="/instructor/subjects" class="card-footer bg-transparent border-0 text-white text-decoration-none d-flex justify-content-between align-items-center">
                <span>Manage</span>
                <i class="bi bi-arrow-right"></i>
            </a>
        </div>
    </div>
    <div class="col-lg col-md-4 col-6">
        <div class="card border-0 shadow-sm bg-success text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="fw-bold mb-0"><?= $stats['students'] ?></h3>
                        <p class="mb-0 opacity-75">Students</p>
                    </div>
                    <div class="opacity-50">
                        <i class="bi bi-people fs-1"></i>
                    </div>
                </div>
            </div>
            <a href="/instructor/students" class="card-footer bg-transparent border-0 text-white text-decoration-none d-flex justify-content-between align-items-center">
                <span>Manage</span>
                <i class="bi bi-arrow-right"></i>
            </a>
        </div>
    </div>
    <div class="col-lg col-md-6 col-6">
        <div class="card border-0 shadow-sm bg-warning text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="fw-bold mb-0"><?= $stats['exams'] ?></h3>
                        <p class="mb-0 opacity-75">Exams</p>
                    </div>
                    <div class="opacity-50">
                        <i class="bi bi-file-earmark-text fs-1"></i>
                    </div>
                </div>
            </div>
            <a href="/instructor/exams" class="card-footer bg-transparent border-0 text-white text-decoration-none d-flex justify-content-between align-items-center">
                <span>View</span>
                <i class="bi bi-arrow-right"></i>
            </a>
        </div>
    </div>
    <div class="col-lg col-md-6 col-6">
        <div class="card border-0 shadow-sm bg-info text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="fw-bold mb-0"><?= $stats['homework'] ?></h3>
                        <p class="mb-0 opacity-75">Homework</p>
                    </div>
                    <div class="opacity-50">
                        <i class="bi bi-journal-check fs-1"></i>
                    </div>
                </div>
            </div>
            <a href="/instructor/homework" class="card-footer bg-transparent border-0 text-white text-decoration-none d-flex justify-content-between align-items-center">
                <span>View</span>
                <i class="bi bi-arrow-right"></i>
            </a>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Quick Actions -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-transparent border-0">
                <h5 class="fw-bold mb-0">Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4 col-6">
                        <a href="/instructor/exams/create" class="btn btn-outline-primary w-100 py-3">
                            <i class="bi bi-plus-circle d-block fs-3 mb-2"></i>
                            Create Exam
                        </a>
                    </div>
                    <div class="col-md-4 col-6">
                        <a href="/instructor/results" class="btn btn-outline-success w-100 py-3">
                            <i class="bi bi-clipboard-data d-block fs-3 mb-2"></i>
                            Enter Results
                        </a>
                    </div>
                    <div class="col-md-4 col-6">
                        <a href="/instructor/homework/create" class="btn btn-outline-warning w-100 py-3">
                            <i class="bi bi-journal-plus d-block fs-3 mb-2"></i>
                            Add Homework
                        </a>
                    </div>
                    <div class="col-md-4 col-6">
                        <a href="/instructor/scripts/upload" class="btn btn-outline-info w-100 py-3">
                            <i class="bi bi-upload d-block fs-3 mb-2"></i>
                            Upload Script
                        </a>
                    </div>
                    <div class="col-md-4 col-6">
                        <a href="/instructor/notices/create" class="btn btn-outline-danger w-100 py-3">
                            <i class="bi bi-megaphone d-block fs-3 mb-2"></i>
                            Post Notice
                        </a>
                    </div>
                    <div class="col-md-4 col-6">
                        <a href="/instructor/reports" class="btn btn-outline-secondary w-100 py-3">
                            <i class="bi bi-bar-chart d-block fs-3 mb-2"></i>
                            View Reports
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- My Classes -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0">My Classes</h5>
                <a href="/instructor/classes" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Class</th>
                                <th>Stream</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($classes)): ?>
                            <tr>
                                <td colspan="3" class="text-center text-muted py-4">No classes assigned yet.</td>
                            </tr>
                            <?php else: ?>
                            <?php foreach (array_slice($classes, 0, 5) as $class): ?>
                            <tr>
                                <td class="fw-semibold"><?= $class['class_name'] ?></td>
                                <td><span class="badge bg-primary"><?= $class['stream_name'] ?></span></td>
                                <td class="text-end">
                                    <a href="/instructor/classes/<?= $class['class_stream_id'] ?>" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Notices -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0"><i class="bi bi-bell me-2"></i>Notices</h5>
                <a href="/instructor/notices" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body">
                <?php if (empty($notices)): ?>
                <p class="text-muted text-center py-4 mb-0">No notices available.</p>
                <?php else: ?>
                <div class="list-group list-group-flush">
                    <?php foreach ($notices as $notice): ?>
                    <div class="list-group-item px-0 border-0">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="mb-1 fw-semibold"><?= htmlspecialchars($notice['title']) ?></h6>
                                <p class="text-muted small mb-1"><?= substr(strip_tags($notice['content']), 0, 80) ?>...</p>
                                <small class="text-muted">
                                    <i class="bi bi-clock me-1"></i>
                                    <?= date('M d, Y', strtotime($notice['published_at'])) ?>
                                </small>
                            </div>
                            <?php if ($notice['priority'] === 'high'): ?>
                            <span class="badge bg-danger">High</span>
                            <?php elseif ($notice['priority'] === 'medium'): ?>
                            <span class="badge bg-warning">Medium</span>
                            <?php else: ?>
                            <span class="badge bg-secondary">Low</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php View::endSection(); ?>

<?php View::section('scripts'); ?>
<script>
$(document).ready(function() {
    // Dashboard specific scripts
});
</script>
<?php View::endSection(); ?>
