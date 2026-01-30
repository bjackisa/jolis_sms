<?php
/**
 * Instructor Results Index View
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
<li class="breadcrumb-item active">Results</li>
<?php View::endSection(); ?>

<?php View::section('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Results Management</h4>
        <p class="text-muted mb-0"><?= $term ? $term['name'] . ' - ' . ($term['academic_year_name'] ?? '') : 'No active term' ?></p>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-transparent border-0">
        <h5 class="fw-bold mb-0">Exams</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0" id="examsTable">
                <thead class="table-light">
                    <tr>
                        <th>Exam Name</th>
                        <th>Type</th>
                        <th>Class</th>
                        <th>Subject</th>
                        <th>Results</th>
                        <th>Average</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($exams)): ?>
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">No exams found.</td>
                    </tr>
                    <?php else: ?>
                    <?php foreach ($exams as $exam): ?>
                    <tr>
                        <td class="fw-semibold"><?= htmlspecialchars($exam['name']) ?></td>
                        <td><span class="badge bg-primary"><?= $exam['exam_type_code'] ?></span></td>
                        <td><?= $exam['class_code'] ?> <?= $exam['stream_name'] ?></td>
                        <td><?= $exam['subject_name'] ?></td>
                        <td>
                            <span class="badge bg-<?= $exam['results_count'] > 0 ? 'success' : 'secondary' ?>">
                                <?= $exam['results_count'] ?> entered
                            </span>
                        </td>
                        <td><?= $exam['average'] ?>%</td>
                        <td class="text-end">
                            <a href="/instructor/results/enter/<?= $exam['id'] ?>" class="btn btn-sm btn-primary" title="Enter Results">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <a href="/instructor/results/view/<?= $exam['id'] ?>" class="btn btn-sm btn-outline-primary" title="View Results">
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
<?php View::endSection(); ?>

<?php View::section('scripts'); ?>
<script>
$(document).ready(function() {
    $('#examsTable').DataTable({
        pageLength: 25,
        order: [[0, 'asc']]
    });
});
</script>
<?php View::endSection(); ?>
