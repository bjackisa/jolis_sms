<?php
/**
 * Student Dashboard View
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
            <?php if ($enrollment): ?>
            <?= $enrollment['class_code'] ?> <?= $enrollment['stream_name'] ?> - 
            <?= $enrollment['academic_year'] ?> <?= $term ? '(' . $term['name'] . ')' : '' ?>
            <?php else: ?>
            Not enrolled in any class
            <?php endif; ?>
        </p>
    </div>
</div>

<!-- Stats Cards -->
<div class="row g-4 mb-4">
    <div class="col-md-3 col-6">
        <div class="card border-0 shadow-sm bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="fw-bold mb-0"><?= $stats['exams_taken'] ?></h3>
                        <p class="mb-0 opacity-75">Exams Taken</p>
                    </div>
                    <div class="opacity-50">
                        <i class="bi bi-file-earmark-text fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="card border-0 shadow-sm bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="fw-bold mb-0"><?= $stats['average_score'] ?>%</h3>
                        <p class="mb-0 opacity-75">Average Score</p>
                    </div>
                    <div class="opacity-50">
                        <i class="bi bi-graph-up fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="card border-0 shadow-sm bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="fw-bold mb-0"><?= $stats['homework_pending'] ?></h3>
                        <p class="mb-0 opacity-75">Pending Homework</p>
                    </div>
                    <div class="opacity-50">
                        <i class="bi bi-journal-check fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="card border-0 shadow-sm bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="fw-bold mb-0"><?= $enrollment ? $enrollment['class_code'] : 'N/A' ?></h3>
                        <p class="mb-0 opacity-75">Current Class</p>
                    </div>
                    <div class="opacity-50">
                        <i class="bi bi-building fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Recent Results -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0"><i class="bi bi-clipboard-data me-2"></i>Recent Results</h5>
                <a href="/student/results" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Subject</th>
                                <th>Exam</th>
                                <th>Marks</th>
                                <th>Grade</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($recentResults)): ?>
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">No results available yet.</td>
                            </tr>
                            <?php else: ?>
                            <?php foreach ($recentResults as $result): ?>
                            <tr>
                                <td class="fw-semibold"><?= $result['subject_name'] ?></td>
                                <td>
                                    <span class="badge bg-secondary"><?= $result['exam_type_code'] ?></span>
                                    <?= $result['exam_name'] ?>
                                </td>
                                <td><?= $result['marks_obtained'] ?>/<?= $result['max_marks'] ?></td>
                                <td>
                                    <?php 
                                    $gradeClass = 'secondary';
                                    if (in_array($result['grade'], ['D1', 'D2', 'A', 'B'])) $gradeClass = 'success';
                                    elseif (in_array($result['grade'], ['C3', 'C4', 'C5', 'C6', 'C', 'D'])) $gradeClass = 'warning';
                                    elseif (in_array($result['grade'], ['P7', 'P8', 'E', 'O'])) $gradeClass = 'info';
                                    elseif (in_array($result['grade'], ['F9', 'F'])) $gradeClass = 'danger';
                                    ?>
                                    <span class="badge bg-<?= $gradeClass ?>"><?= $result['grade'] ?></span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Pending Homework -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0"><i class="bi bi-journal-check me-2"></i>Pending Homework</h5>
                <a href="/student/homework" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Subject</th>
                                <th>Title</th>
                                <th>Due Date</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($pendingHomework)): ?>
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">No pending homework. Great job!</td>
                            </tr>
                            <?php else: ?>
                            <?php foreach (array_slice($pendingHomework, 0, 5) as $hw): ?>
                            <tr>
                                <td class="fw-semibold"><?= $hw['subject_name'] ?></td>
                                <td><?= htmlspecialchars($hw['title']) ?></td>
                                <td>
                                    <?php 
                                    $dueDate = strtotime($hw['due_date']);
                                    $daysLeft = ceil(($dueDate - time()) / 86400);
                                    ?>
                                    <span class="<?= $daysLeft <= 2 ? 'text-danger' : '' ?>">
                                        <?= date('M d, Y', $dueDate) ?>
                                        <?php if ($daysLeft <= 2): ?>
                                        <small>(<?= $daysLeft ?> day<?= $daysLeft != 1 ? 's' : '' ?> left)</small>
                                        <?php endif; ?>
                                    </span>
                                </td>
                                <td class="text-end">
                                    <a href="/student/homework/<?= $hw['id'] ?>" class="btn btn-sm btn-primary">
                                        Submit
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

    <!-- Notices & Quick Links -->
    <div class="col-lg-4">
        <!-- Quick Links -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-transparent border-0">
                <h5 class="fw-bold mb-0">Quick Links</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="/student/results" class="btn btn-outline-primary text-start">
                        <i class="bi bi-clipboard-data me-2"></i>View My Results
                    </a>
                    <a href="/student/homework" class="btn btn-outline-success text-start">
                        <i class="bi bi-journal-check me-2"></i>View Homework
                    </a>
                    <a href="/student/scripts" class="btn btn-outline-warning text-start">
                        <i class="bi bi-file-earmark-arrow-down me-2"></i>Exam Scripts
                    </a>
                    <a href="/student/profile" class="btn btn-outline-info text-start">
                        <i class="bi bi-person-circle me-2"></i>My Profile
                    </a>
                </div>
            </div>
        </div>

        <!-- Notices -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0"><i class="bi bi-bell me-2"></i>Notices</h5>
                <a href="/student/notices" class="btn btn-sm btn-outline-primary">View All</a>
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
