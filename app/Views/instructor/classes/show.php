<?php
/**
 * Instructor Class Show View
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
<li class="breadcrumb-item"><a href="/instructor/classes">Classes</a></li>
<li class="breadcrumb-item active"><?= $classStream['class_code'] ?> <?= $classStream['stream_name'] ?></li>
<?php View::endSection(); ?>

<?php View::section('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1"><?= $classStream['class_name'] ?> - Stream <?= $classStream['stream_name'] ?></h4>
        <p class="text-muted mb-0"><?= $classStream['level_name'] ?> | <?= $classStream['academic_year'] ?></p>
    </div>
    <a href="/instructor/classes" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Back
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
        <h5 class="fw-bold mb-0">Students (<?= count($students) ?>)</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0" id="studentsTable">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Student Number</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($students as $index => $student): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= $student['student_number'] ?></td>
                        <td class="fw-semibold"><?= $student['first_name'] ?> <?= $student['last_name'] ?></td>
                        <td><?= $student['email'] ?></td>
                        <td><?= $student['phone'] ?? '-' ?></td>
                        <td class="text-end">
                            <a href="/instructor/students/<?= $student['id'] ?>" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php View::endSection(); ?>

<?php View::section('scripts'); ?>
<script>
$(document).ready(function() {
    $('#studentsTable').DataTable({
        pageLength: 25
    });
});
</script>
<?php View::endSection(); ?>
