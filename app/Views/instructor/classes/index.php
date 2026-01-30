<?php
/**
 * Instructor Classes Index View
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
<li class="breadcrumb-item active">My Classes</li>
<?php View::endSection(); ?>

<?php View::section('content'); ?>
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h5 class="fw-bold mb-1">My Classes</h5>
                <p class="text-muted small mb-0"><?= $academicYear ? 'Academic Year ' . $academicYear['name'] : 'No academic year set' ?></p>
            </div>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-outline-secondary btn-sm" id="exportCsv">
                    <i class="bi bi-download me-1"></i>Export CSV
                </button>
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#assignClassModal">
                    <i class="bi bi-plus-lg me-1"></i>Assign Class
                </button>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle" id="classesTable">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Class</th>
                        <th>Stream</th>
                        <th>Subject</th>
                        <th>Students</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($assignments)): ?>
                    <tr>
                        <td colspan="6" class="text-center py-4">
                            <i class="bi bi-building text-muted d-block" style="font-size: 2rem;"></i>
                            <span class="text-muted">No classes assigned yet. Click "Assign Class" to get started.</span>
                        </td>
                    </tr>
                    <?php else: ?>
                    <?php $i = 1; foreach ($assignments as $assignment): ?>
                    <tr>
                        <td><?= $i++ ?></td>
                        <td><strong><?= htmlspecialchars($assignment['class_name']) ?></strong></td>
                        <td><span class="badge bg-primary"><?= htmlspecialchars($assignment['stream_name']) ?></span></td>
                        <td><?= htmlspecialchars($assignment['subject_name']) ?></td>
                        <td><span class="badge bg-secondary"><?= $assignment['student_count'] ?? 0 ?></span></td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="/instructor/classes/<?= $assignment['class_stream_id'] ?>" class="btn btn-outline-primary" title="View Students">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="/instructor/results/enter/<?= $assignment['class_stream_id'] ?>?subject=<?= $assignment['subject_id'] ?>" class="btn btn-outline-success" title="Enter Results">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <button type="button" class="btn btn-outline-danger" onclick="removeAssignment(<?= $assignment['id'] ?>)" title="Remove">
                                    <i class="bi bi-trash"></i>
                                </button>
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

<!-- Assign Class Modal -->
<div class="modal fade" id="assignClassModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-plus-circle me-2"></i>Assign Class & Subject</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="/instructor/classes/assign" method="POST">
                <?= View::csrf() ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Class <span class="text-danger">*</span></label>
                        <select name="class_stream_id" class="form-select" required>
                            <option value="">Select Class</option>
                            <?php foreach ($allClasses as $class): ?>
                            <option value="<?= $class['id'] ?>"><?= htmlspecialchars($class['class_code'] . ' ' . $class['stream_name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Subject <span class="text-danger">*</span></label>
                        <select name="subject_id" class="form-select" required>
                            <option value="">Select Subject</option>
                            <?php foreach ($allSubjects as $subject): ?>
                            <option value="<?= $subject['id'] ?>"><?= htmlspecialchars($subject['name']) ?> (<?= $subject['level_code'] ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Assign</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php View::endSection(); ?>

<?php View::section('scripts'); ?>
<script>
$(document).ready(function() {
    // Initialize DataTable
    if ($('#classesTable tbody tr').length > 1 || !$('#classesTable tbody tr td[colspan]').length) {
        $('#classesTable').DataTable({
            pageLength: 25,
            order: [[1, 'asc']],
            columnDefs: [
                { orderable: false, targets: [5] }
            ]
        });
    }
    
    // Export CSV
    $('#exportCsv').click(function() {
        let csv = 'Class,Stream,Subject,Students\n';
        $('#classesTable tbody tr').each(function() {
            if (!$(this).find('td[colspan]').length) {
                let row = [];
                row.push($(this).find('td:eq(1)').text().trim());
                row.push($(this).find('td:eq(2)').text().trim());
                row.push($(this).find('td:eq(3)').text().trim());
                row.push($(this).find('td:eq(4)').text().trim());
                csv += row.join(',') + '\n';
            }
        });
        
        let blob = new Blob([csv], { type: 'text/csv' });
        let url = window.URL.createObjectURL(blob);
        let a = document.createElement('a');
        a.href = url;
        a.download = 'my_classes.csv';
        a.click();
    });
});

function removeAssignment(id) {
    if (confirm('Are you sure you want to remove this class assignment?')) {
        fetch('/instructor/classes/remove/' + id, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        }).then(response => response.json())
          .then(data => {
              if (data.success) {
                  location.reload();
              } else {
                  alert(data.message || 'Failed to remove assignment');
              }
          });
    }
}
</script>
<?php View::endSection(); ?>
