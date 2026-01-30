<?php
/**
 * Instructor Homework Index View
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
<li class="breadcrumb-item active">Homework</li>
<?php View::endSection(); ?>

<?php View::section('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Homework</h4>
        <p class="text-muted mb-0">Manage homework assignments</p>
    </div>
    <a href="/instructor/homework/create" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i>Add Homework
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0" id="homeworkTable">
                <thead class="table-light">
                    <tr>
                        <th>Title</th>
                        <th>Class</th>
                        <th>Subject</th>
                        <th>Due Date</th>
                        <th>Submissions</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($homework)): ?>
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">No homework found.</td>
                    </tr>
                    <?php else: ?>
                    <?php foreach ($homework as $hw): ?>
                    <tr>
                        <td class="fw-semibold"><?= htmlspecialchars($hw['title']) ?></td>
                        <td><?= $hw['class_code'] ?> <?= $hw['stream_name'] ?></td>
                        <td><?= $hw['subject_name'] ?></td>
                        <td>
                            <?php if ($hw['is_past_due']): ?>
                            <span class="text-danger"><?= date('M d, Y', strtotime($hw['due_date'])) ?></span>
                            <?php else: ?>
                            <?= date('M d, Y', strtotime($hw['due_date'])) ?>
                            <?php endif; ?>
                        </td>
                        <td><span class="badge bg-info"><?= $hw['submissions_count'] ?></span></td>
                        <td>
                            <?php 
                            $statusClass = $hw['status'] === 'active' ? 'success' : ($hw['status'] === 'closed' ? 'secondary' : 'danger');
                            ?>
                            <span class="badge bg-<?= $statusClass ?>"><?= ucfirst($hw['status']) ?></span>
                        </td>
                        <td class="text-end">
                            <a href="/instructor/homework/<?= $hw['id'] ?>" class="btn btn-sm btn-outline-primary" title="View">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="/instructor/homework/<?= $hw['id'] ?>/submissions" class="btn btn-sm btn-primary" title="Submissions">
                                <i class="bi bi-collection"></i>
                            </a>
                            <a href="/instructor/homework/<?= $hw['id'] ?>/edit" class="btn btn-sm btn-outline-secondary" title="Edit">
                                <i class="bi bi-pencil"></i>
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
    $('#homeworkTable').DataTable({
        pageLength: 25,
        order: [[3, 'desc']]
    });
});
</script>
<?php View::endSection(); ?>
