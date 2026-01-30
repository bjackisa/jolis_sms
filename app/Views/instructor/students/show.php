<?php
/**
 * Instructor Student Show View
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
<li class="breadcrumb-item"><a href="/instructor/students">Students</a></li>
<li class="breadcrumb-item active"><?= $student['first_name'] ?> <?= $student['last_name'] ?></li>
<?php View::endSection(); ?>

<?php View::section('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1"><?= $student['first_name'] ?> <?= $student['last_name'] ?></h4>
        <p class="text-muted mb-0"><?= $student['student_number'] ?></p>
    </div>
    <a href="/instructor/students" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Back
    </a>
</div>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm text-center">
            <div class="card-body py-4">
                <?php if (!empty($student['avatar'])): ?>
                <img src="<?= $student['avatar'] ?>" alt="Avatar" class="rounded-circle mb-3" width="100" height="100">
                <?php else: ?>
                <div class="avatar-placeholder rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center mb-3" style="width: 100px; height: 100px; font-size: 2.5rem;">
                    <?= strtoupper(substr($student['first_name'], 0, 1)) ?>
                </div>
                <?php endif; ?>
                <h5 class="fw-bold mb-1"><?= $student['first_name'] ?> <?= $student['last_name'] ?></h5>
                <p class="text-muted mb-2"><?= $student['email'] ?></p>
                <span class="badge bg-success">Student</span>
            </div>
        </div>
        
        <div class="card border-0 shadow-sm mt-4">
            <div class="card-header bg-transparent border-0">
                <h6 class="fw-bold mb-0">Student Info</h6>
            </div>
            <div class="card-body">
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <td class="text-muted">Student No:</td>
                        <td class="fw-semibold"><?= $student['student_number'] ?></td>
                    </tr>
                    <?php if ($enrollment): ?>
                    <tr>
                        <td class="text-muted">Class:</td>
                        <td><?= $enrollment['class_code'] ?> <?= $enrollment['stream_name'] ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Year:</td>
                        <td><?= $enrollment['academic_year'] ?></td>
                    </tr>
                    <?php endif; ?>
                    <tr>
                        <td class="text-muted">Gender:</td>
                        <td><?= ucfirst($student['gender'] ?? '-') ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Phone:</td>
                        <td><?= $student['phone'] ?? '-' ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0">Recent Results</h5>
                <span class="badge bg-primary"><?= $term ? $term['name'] : '' ?></span>
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
                            <?php if (empty($results)): ?>
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">No results available.</td>
                            </tr>
                            <?php else: ?>
                            <?php foreach ($results as $result): ?>
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
    </div>
</div>
<?php View::endSection(); ?>
