<?php
/**
 * Instructor Students Index View
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
<li class="breadcrumb-item active">Students</li>
<?php View::endSection(); ?>

<?php View::section('content'); ?>
<div class="mb-4">
    <h4 class="fw-bold mb-1">Students</h4>
    <p class="text-muted mb-0">Students in your classes</p>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0" id="studentsTable">
                <thead class="table-light">
                    <tr>
                        <th>Student No.</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Class</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($students)): ?>
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">No students found.</td>
                    </tr>
                    <?php else: ?>
                    <?php foreach ($students as $student): ?>
                    <tr>
                        <td><?= $student['student_number'] ?></td>
                        <td class="fw-semibold"><?= $student['first_name'] ?> <?= $student['last_name'] ?></td>
                        <td><?= $student['email'] ?></td>
                        <td><span class="badge bg-primary"><?= $student['class_name'] ?? '-' ?></span></td>
                        <td class="text-end">
                            <a href="/instructor/students/<?= $student['id'] ?>" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i> View
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
<?php View::endSection(); ?>

<?php View::section('scripts'); ?>
<script>
$(document).ready(function() {
    $('#studentsTable').DataTable({
        pageLength: 25,
        order: [[1, 'asc']]
    });
});
</script>
<?php View::endSection(); ?>
