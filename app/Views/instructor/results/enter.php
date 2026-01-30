<?php
/**
 * Instructor Enter Results View
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
<li class="breadcrumb-item"><a href="/instructor/results">Results</a></li>
<li class="breadcrumb-item active">Enter Results</li>
<?php View::endSection(); ?>

<?php View::section('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Enter Results</h4>
        <p class="text-muted mb-0"><?= htmlspecialchars($exam['name']) ?> - <?= $exam['class_code'] ?> <?= $exam['stream_name'] ?></p>
    </div>
    <a href="/instructor/results" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Back
    </a>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-0">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0">Student Results</h5>
                    <span class="badge bg-primary"><?= count($students) ?> Students</span>
                </div>
            </div>
            <div class="card-body">
                <form action="/instructor/results/save" method="POST" id="resultsForm">
                    <?= View::csrf() ?>
                    <input type="hidden" name="exam_id" value="<?= $exam['id'] ?>">
                    
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Student Number</th>
                                    <th>Name</th>
                                    <th style="width: 120px;">Marks (<?= $exam['max_marks'] ?>)</th>
                                    <th>Grade</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($students as $index => $student): ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td><?= $student['student_number'] ?></td>
                                    <td class="fw-semibold"><?= $student['first_name'] ?> <?= $student['last_name'] ?></td>
                                    <td>
                                        <input type="number" 
                                               name="results[<?= $student['id'] ?>]" 
                                               class="form-control form-control-sm marks-input" 
                                               min="0" 
                                               max="<?= $exam['max_marks'] ?>"
                                               step="0.5"
                                               value="<?= $student['result']['marks_obtained'] ?? '' ?>"
                                               data-student-id="<?= $student['id'] ?>">
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary grade-display" id="grade-<?= $student['id'] ?>">
                                            <?= $student['result']['grade'] ?? '-' ?>
                                        </span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="d-flex justify-content-end gap-2 mt-3">
                        <button type="button" class="btn btn-outline-secondary" onclick="clearAll()">Clear All</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-1"></i>Save Results
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-transparent border-0">
                <h5 class="fw-bold mb-0">Exam Details</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm table-borderless">
                    <tr>
                        <td class="text-muted">Subject:</td>
                        <td class="fw-semibold"><?= $exam['subject_name'] ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Type:</td>
                        <td><span class="badge bg-primary"><?= $exam['exam_type_name'] ?></span></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Max Marks:</td>
                        <td class="fw-semibold"><?= $exam['max_marks'] ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Date:</td>
                        <td><?= $exam['exam_date'] ? date('M d, Y', strtotime($exam['exam_date'])) : 'N/A' ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Term:</td>
                        <td><?= $exam['term_name'] ?></td>
                    </tr>
                </table>
            </div>
        </div>
        
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-0">
                <h5 class="fw-bold mb-0">Grading Scale</h5>
            </div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Grade</th>
                            <th>Marks</th>
                            <th>Comment</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($gradingScales as $scale): ?>
                        <tr>
                            <td><span class="badge bg-secondary"><?= $scale['grade'] ?></span></td>
                            <td><?= $scale['min_marks'] ?> - <?= $scale['max_marks'] ?></td>
                            <td class="small"><?= $scale['comment'] ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php View::endSection(); ?>

<?php View::section('scripts'); ?>
<script>
const gradingScales = <?= json_encode($gradingScales) ?>;
const maxMarks = <?= $exam['max_marks'] ?>;

function calculateGrade(marks) {
    if (marks === '' || marks === null) return '-';
    
    const percentage = (marks / maxMarks) * 100;
    
    for (const scale of gradingScales) {
        if (percentage >= scale.min_marks && percentage <= scale.max_marks) {
            return scale.grade;
        }
    }
    return 'F9';
}

function getGradeClass(grade) {
    if (['D1', 'D2', 'A', 'B'].includes(grade)) return 'success';
    if (['C3', 'C4', 'C5', 'C6', 'C', 'D'].includes(grade)) return 'warning';
    if (['P7', 'P8', 'E', 'O'].includes(grade)) return 'info';
    if (['F9', 'F'].includes(grade)) return 'danger';
    return 'secondary';
}

$('.marks-input').on('input', function() {
    const studentId = $(this).data('student-id');
    const marks = $(this).val();
    const grade = calculateGrade(marks);
    const gradeClass = getGradeClass(grade);
    
    $(`#grade-${studentId}`)
        .text(grade)
        .removeClass('bg-secondary bg-success bg-warning bg-info bg-danger')
        .addClass(`bg-${gradeClass}`);
});

function clearAll() {
    if (confirm('Are you sure you want to clear all marks?')) {
        $('.marks-input').val('');
        $('.grade-display').text('-').removeClass('bg-success bg-warning bg-info bg-danger').addClass('bg-secondary');
    }
}

$('#resultsForm').on('submit', function(e) {
    const hasMarks = $('.marks-input').filter(function() {
        return $(this).val() !== '';
    }).length > 0;
    
    if (!hasMarks) {
        e.preventDefault();
        alert('Please enter at least one result.');
        return false;
    }
});
</script>
<?php View::endSection(); ?>
