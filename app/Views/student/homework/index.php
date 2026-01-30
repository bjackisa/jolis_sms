<?php
/**
 * Student Homework Index View
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
<div class="mb-4">
    <h4 class="fw-bold mb-1">Homework</h4>
    <p class="text-muted mb-0">View and submit your homework assignments</p>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0" id="homeworkTable">
                <thead class="table-light">
                    <tr>
                        <th>Subject</th>
                        <th>Title</th>
                        <th>Due Date</th>
                        <th>Status</th>
                        <th>Marks</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($homework)): ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">No homework assigned.</td>
                    </tr>
                    <?php else: ?>
                    <?php foreach ($homework as $hw): ?>
                    <tr>
                        <td class="fw-semibold"><?= $hw['subject_name'] ?></td>
                        <td><?= htmlspecialchars($hw['title']) ?></td>
                        <td>
                            <?php if ($hw['is_past_due'] && !$hw['is_submitted']): ?>
                            <span class="text-danger"><?= date('M d, Y H:i', strtotime($hw['due_date'])) ?></span>
                            <br><small class="text-danger">Past due</small>
                            <?php else: ?>
                            <?= date('M d, Y H:i', strtotime($hw['due_date'])) ?>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($hw['is_submitted']): ?>
                                <?php if ($hw['submission']['status'] === 'graded'): ?>
                                <span class="badge bg-success">Graded</span>
                                <?php else: ?>
                                <span class="badge bg-info">Submitted</span>
                                <?php endif; ?>
                            <?php elseif ($hw['is_past_due']): ?>
                            <span class="badge bg-danger">Missed</span>
                            <?php else: ?>
                            <span class="badge bg-warning">Pending</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($hw['is_submitted'] && $hw['submission']['status'] === 'graded'): ?>
                            <?= $hw['submission']['marks_obtained'] ?>/<?= $hw['max_marks'] ?>
                            <?php else: ?>
                            -
                            <?php endif; ?>
                        </td>
                        <td class="text-end">
                            <a href="/student/homework/<?= $hw['id'] ?>" class="btn btn-sm btn-primary">
                                <?= $hw['is_submitted'] ? 'View' : 'Submit' ?>
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
        order: [[2, 'desc']]
    });
});
</script>
<?php View::endSection(); ?>
