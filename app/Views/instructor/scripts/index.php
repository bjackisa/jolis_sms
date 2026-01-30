<?php
/**
 * Instructor Exam Scripts Index View
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
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Exam Scripts</h4>
        <p class="text-muted mb-0">Upload and manage exam scripts for students</p>
    </div>
    <a href="/instructor/scripts/upload" class="btn btn-primary">
        <i class="bi bi-upload me-1"></i>Upload Script
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0" id="scriptsTable">
                <thead class="table-light">
                    <tr>
                        <th>Title</th>
                        <th>Exam</th>
                        <th>Subject</th>
                        <th>Class</th>
                        <th>Uploaded</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($scripts)): ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">No scripts uploaded yet.</td>
                    </tr>
                    <?php else: ?>
                    <?php foreach ($scripts as $script): ?>
                    <tr>
                        <td class="fw-semibold"><?= htmlspecialchars($script['title']) ?></td>
                        <td><?= htmlspecialchars($script['exam_name']) ?></td>
                        <td><?= $script['subject_name'] ?></td>
                        <td><?= $script['class_code'] ?> <?= $script['stream_name'] ?></td>
                        <td><?= date('M d, Y', strtotime($script['created_at'])) ?></td>
                        <td class="text-end">
                            <a href="<?= $script['file_path'] ?>" class="btn btn-sm btn-outline-primary" target="_blank" title="Download">
                                <i class="bi bi-download"></i>
                            </a>
                            <form action="/instructor/scripts/<?= $script['id'] ?>/delete" method="POST" class="d-inline" onsubmit="return confirm('Delete this script?')">
                                <?= View::csrf() ?>
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
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
