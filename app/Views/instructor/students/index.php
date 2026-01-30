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
<li class="breadcrumb-item active">My Students</li>
<?php View::endSection(); ?>

<?php View::section('content'); ?>
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h5 class="fw-bold mb-1">My Students</h5>
                <p class="text-muted small mb-0"><?= $academicYear ? 'Academic Year ' . $academicYear['name'] : 'No academic year set' ?></p>
            </div>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-outline-secondary btn-sm" id="exportCsv">
                    <i class="bi bi-download me-1"></i>Export CSV
                </button>
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addStudentModal">
                    <i class="bi bi-plus-lg me-1"></i>Add Student
                </button>
            </div>
        </div>
    </div>
    <div class="card-body">
        <!-- Filter by Class -->
        <div class="row mb-3">
            <div class="col-md-4">
                <select class="form-select form-select-sm" id="classFilter">
                    <option value="">All Classes</option>
                    <?php foreach ($classes as $class): ?>
                    <option value="<?= htmlspecialchars($class['class_code'] . ' ' . $class['stream_name']) ?>">
                        <?= htmlspecialchars($class['class_code'] . ' ' . $class['stream_name']) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle" id="studentsTable">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Student No.</th>
                        <th>Name</th>
                        <th>Gender</th>
                        <th>Class</th>
                        <th>Contact</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($students)): ?>
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <i class="bi bi-people text-muted d-block" style="font-size: 2rem;"></i>
                            <span class="text-muted">No students found in your classes.</span>
                        </td>
                    </tr>
                    <?php else: ?>
                    <?php $i = 1; foreach ($students as $student): ?>
                    <tr>
                        <td><?= $i++ ?></td>
                        <td><code><?= htmlspecialchars($student['student_number']) ?></code></td>
                        <td><strong><?= htmlspecialchars($student['first_name'] . ' ' . $student['last_name']) ?></strong></td>
                        <td>
                            <?php if (isset($student['gender'])): ?>
                            <span class="badge bg-<?= $student['gender'] === 'male' ? 'info' : 'pink' ?>">
                                <?= ucfirst($student['gender']) ?>
                            </span>
                            <?php else: ?>
                            <span class="text-muted">-</span>
                            <?php endif; ?>
                        </td>
                        <td><span class="badge bg-primary"><?= htmlspecialchars($student['class_name'] ?? '-') ?></span></td>
                        <td class="small"><?= htmlspecialchars($student['email'] ?? '-') ?></td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="/instructor/students/<?= $student['id'] ?>" class="btn btn-outline-primary" title="View Profile">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="/instructor/results?student=<?= $student['id'] ?>" class="btn btn-outline-success" title="View Results">
                                    <i class="bi bi-clipboard-data"></i>
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

<!-- Add Student Modal -->
<div class="modal fade" id="addStudentModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-person-plus me-2"></i>Add New Student</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="/instructor/students/add" method="POST">
                <?= View::csrf() ?>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">First Name <span class="text-danger">*</span></label>
                            <input type="text" name="first_name" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Last Name <span class="text-danger">*</span></label>
                            <input type="text" name="last_name" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Gender <span class="text-danger">*</span></label>
                            <select name="gender" class="form-select" required>
                                <option value="">Select Gender</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Class <span class="text-danger">*</span></label>
                            <select name="class_stream_id" class="form-select" required>
                                <option value="">Select Class</option>
                                <?php foreach ($classes as $class): ?>
                                <option value="<?= $class['class_stream_id'] ?>"><?= htmlspecialchars($class['class_code'] . ' ' . $class['stream_name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Date of Birth</label>
                            <input type="date" name="date_of_birth" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Guardian Name</label>
                            <input type="text" name="guardian_name" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Guardian Phone</label>
                            <input type="tel" name="guardian_phone" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Add Student</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php View::endSection(); ?>

<?php View::section('scripts'); ?>
<script>
$(document).ready(function() {
    var table = null;
    
    if (!$('#studentsTable tbody tr td[colspan]').length) {
        table = $('#studentsTable').DataTable({
            pageLength: 25,
            order: [[2, 'asc']],
            columnDefs: [
                { orderable: false, targets: [6] }
            ]
        });
    }
    
    // Class filter
    $('#classFilter').on('change', function() {
        if (table) {
            table.column(4).search(this.value).draw();
        }
    });
    
    // Export CSV
    $('#exportCsv').click(function() {
        let csv = 'Student No,Name,Gender,Class,Email\n';
        $('#studentsTable tbody tr').each(function() {
            if (!$(this).find('td[colspan]').length) {
                let row = [];
                row.push($(this).find('td:eq(1)').text().trim());
                row.push($(this).find('td:eq(2)').text().trim());
                row.push($(this).find('td:eq(3)').text().trim());
                row.push($(this).find('td:eq(4)').text().trim());
                row.push($(this).find('td:eq(5)').text().trim());
                csv += '"' + row.join('","') + '"\n';
            }
        });
        
        let blob = new Blob([csv], { type: 'text/csv' });
        let url = window.URL.createObjectURL(blob);
        let a = document.createElement('a');
        a.href = url;
        a.download = 'my_students.csv';
        a.click();
    });
});
</script>
<?php View::endSection(); ?>
