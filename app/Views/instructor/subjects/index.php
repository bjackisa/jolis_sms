<?php
/**
 * Instructor Subjects Index View
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
<li class="breadcrumb-item active">My Subjects</li>
<?php View::endSection(); ?>

<?php View::section('content'); ?>
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h5 class="fw-bold mb-1">My Teaching Assignments</h5>
                <p class="text-muted small mb-0"><?= $academicYear ? 'Academic Year ' . $academicYear['name'] : 'No academic year set' ?></p>
            </div>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-outline-secondary btn-sm" id="exportCsv">
                    <i class="bi bi-download me-1"></i>Export CSV
                </button>
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addSubjectModal">
                    <i class="bi bi-plus-lg me-1"></i>Add Subject
                </button>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle" id="subjectsTable">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Subject</th>
                        <th>Code</th>
                        <th>Level</th>
                        <th>Category</th>
                        <th>Classes</th>
                        <th>Papers</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($assignments)): ?>
                    <tr>
                        <td colspan="8" class="text-center py-4">
                            <i class="bi bi-book text-muted d-block" style="font-size: 2rem;"></i>
                            <span class="text-muted">No subjects assigned yet. Click "Add Subject" to get started.</span>
                        </td>
                    </tr>
                    <?php else: ?>
                    <?php $i = 1; foreach ($assignments as $assignment): ?>
                    <tr>
                        <td><?= $i++ ?></td>
                        <td><strong><?= htmlspecialchars($assignment['subject_name']) ?></strong></td>
                        <td><code><?= htmlspecialchars($assignment['subject_code']) ?></code></td>
                        <td><span class="badge bg-<?= $assignment['level_code'] === 'O' ? 'success' : 'primary' ?>"><?= $assignment['level_code'] ?>-Level</span></td>
                        <td><?= htmlspecialchars($assignment['category_name'] ?? 'General') ?></td>
                        <td>
                            <span class="badge bg-secondary"><?= $assignment['class_count'] ?> class(es)</span>
                        </td>
                        <td><?= $assignment['paper_count'] ?></td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="/instructor/results?subject=<?= $assignment['subject_id'] ?>" class="btn btn-outline-success" title="Enter Results">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <a href="/instructor/homework?subject=<?= $assignment['subject_id'] ?>" class="btn btn-outline-info" title="Homework">
                                    <i class="bi bi-journal-check"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Classes by Subject Summary -->
<?php if (!empty($subjectClasses)): ?>
<div class="card border-0 shadow-sm mt-4">
    <div class="card-header bg-white py-3">
        <h5 class="fw-bold mb-0">Classes by Subject</h5>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <?php foreach ($subjectClasses as $subject): ?>
            <div class="col-md-6 col-lg-4">
                <div class="border rounded p-3">
                    <h6 class="fw-bold mb-2"><?= htmlspecialchars($subject['name']) ?></h6>
                    <div class="d-flex flex-wrap gap-1">
                        <?php foreach ($subject['classes'] as $class): ?>
                        <a href="/instructor/classes/<?= $class['class_stream_id'] ?>" class="badge bg-primary text-decoration-none">
                            <?= htmlspecialchars($class['class_code'] . ' ' . $class['stream_name']) ?>
                        </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Add Subject Modal -->
<div class="modal fade" id="addSubjectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-book me-2"></i>Add Subject Assignment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="/instructor/subjects/assign" method="POST">
                <?= View::csrf() ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Subject <span class="text-danger">*</span></label>
                        <select name="subject_id" class="form-select" required>
                            <option value="">Select Subject</option>
                            <?php foreach ($allSubjects as $subject): ?>
                            <option value="<?= $subject['id'] ?>"><?= htmlspecialchars($subject['name']) ?> (<?= $subject['level_code'] ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Class <span class="text-danger">*</span></label>
                        <select name="class_stream_id" class="form-select" required>
                            <option value="">Select Class</option>
                            <?php foreach ($allClasses as $class): ?>
                            <option value="<?= $class['id'] ?>"><?= htmlspecialchars($class['class_code'] . ' ' . $class['stream_name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Add</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php View::endSection(); ?>

<?php View::section('scripts'); ?>
<script>
$(document).ready(function() {
    if ($('#subjectsTable tbody tr').length > 1 || !$('#subjectsTable tbody tr td[colspan]').length) {
        $('#subjectsTable').DataTable({
            pageLength: 25,
            order: [[1, 'asc']],
            columnDefs: [
                { orderable: false, targets: [7] }
            ]
        });
    }
    
    // Export CSV
    $('#exportCsv').click(function() {
        let csv = 'Subject,Code,Level,Category,Classes,Papers\n';
        $('#subjectsTable tbody tr').each(function() {
            if (!$(this).find('td[colspan]').length) {
                let row = [];
                row.push($(this).find('td:eq(1)').text().trim());
                row.push($(this).find('td:eq(2)').text().trim());
                row.push($(this).find('td:eq(3)').text().trim());
                row.push($(this).find('td:eq(4)').text().trim());
                row.push($(this).find('td:eq(5)').text().trim());
                row.push($(this).find('td:eq(6)').text().trim());
                csv += '"' + row.join('","') + '"\n';
            }
        });
        
        let blob = new Blob([csv], { type: 'text/csv' });
        let url = window.URL.createObjectURL(blob);
        let a = document.createElement('a');
        a.href = url;
        a.download = 'my_subjects.csv';
        a.click();
    });
});
</script>
<?php View::endSection(); ?>
