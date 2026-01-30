<?php
/**
 * Student Exam Scripts Index View
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
<li class="breadcrumb-item active">Exam Scripts</li>
<?php View::endSection(); ?>

<?php View::section('content'); ?>
<div class="mb-4">
    <h4 class="fw-bold mb-1">Exam Scripts</h4>
    <p class="text-muted mb-0">Download exam papers and past papers</p>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0" id="scriptsTable">
                <thead class="table-light">
                    <tr>
                        <th>Title</th>
                        <th>Subject</th>
                        <th>Exam</th>
                        <th>Uploaded By</th>
                        <th>Date</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($scripts)): ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">No exam scripts available.</td>
                    </tr>
                    <?php else: ?>
                    <?php foreach ($scripts as $script): ?>
                    <tr>
                        <td class="fw-semibold"><?= htmlspecialchars($script['title']) ?></td>
                        <td><?= $script['subject_name'] ?></td>
                        <td><?= htmlspecialchars($script['exam_name']) ?></td>
                        <td><?= $script['uploaded_by_first'] ?> <?= $script['uploaded_by_last'] ?></td>
                        <td><?= date('M d, Y', strtotime($script['created_at'])) ?></td>
                        <td class="text-end">
                            <a href="/student/scripts/<?= $script['id'] ?>" class="btn btn-sm btn-primary">
                                <i class="bi bi-download me-1"></i>Download
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
    $('#scriptsTable').DataTable({
        pageLength: 25,
        order: [[4, 'desc']]
    });
});
</script>
<?php View::endSection(); ?>
