<?php
/**
 * Instructor Exams Index View
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
<li class="breadcrumb-item active">Exams</li>
<?php View::endSection(); ?>

<?php View::section('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Exams</h4>
        <p class="text-muted mb-0"><?= $term ? $term['name'] : 'No active term' ?></p>
    </div>
    <a href="/instructor/exams/create" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i>Create Exam
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0" id="examsTable">
                <thead class="table-light">
                    <tr>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Class</th>
                        <th>Subject</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($exams)): ?>
                    <?php foreach ($exams as $exam): ?>
                    <tr>
                        <td class="fw-semibold"><?= htmlspecialchars($exam['name']) ?></td>
                        <td><span class="badge bg-primary"><?= $exam['exam_type_code'] ?></span></td>
                        <td><?= $exam['class_code'] ?> <?= $exam['stream_name'] ?></td>
                        <td><?= $exam['subject_name'] ?></td>
                        <td><?= $exam['exam_date'] ? date('M d, Y', strtotime($exam['exam_date'])) : '-' ?></td>
                        <td>
                            <?php 
                            $statusClass = 'secondary';
                            if ($exam['status'] === 'completed') $statusClass = 'success';
                            elseif ($exam['status'] === 'ongoing') $statusClass = 'warning';
                            elseif ($exam['status'] === 'cancelled') $statusClass = 'danger';
                            ?>
                            <span class="badge bg-<?= $statusClass ?>"><?= ucfirst($exam['status']) ?></span>
                        </td>
                        <td class="text-end">
                            <a href="/instructor/exams/<?= $exam['id'] ?>" class="btn btn-sm btn-outline-primary" title="View">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="/instructor/exams/<?= $exam['id'] ?>/edit" class="btn btn-sm btn-outline-secondary" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a href="/instructor/results/enter/<?= $exam['id'] ?>" class="btn btn-sm btn-primary" title="Enter Results">
                                <i class="bi bi-clipboard-data"></i>
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
        order: [[4, 'desc']],
        columnDefs: [
            { orderable: false, searchable: false, targets: [6] }
        ],
        language: {
            emptyTable: 'No exams found.'
        }
    });
});
</script>
<?php View::endSection(); ?>
