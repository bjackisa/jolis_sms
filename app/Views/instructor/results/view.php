<?php
/**
 * Instructor View Results View
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
<li class="breadcrumb-item active">View Results</li>
<?php View::endSection(); ?>

<?php View::section('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1"><?= htmlspecialchars($exam['name']) ?></h4>
        <p class="text-muted mb-0"><?= $exam['subject_name'] ?> - <?= $exam['class_code'] ?> <?= $exam['stream_name'] ?></p>
    </div>
    <div class="d-flex gap-2">
        <a href="/instructor/results/enter/<?= $exam['id'] ?>" class="btn btn-primary">
            <i class="bi bi-pencil me-1"></i>Edit Results
        </a>
        <a href="/instructor/results" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Back
        </a>
    </div>
</div>

<!-- Stats -->
<div class="row g-4 mb-4">
    <div class="col-md-3 col-6">
        <div class="card border-0 shadow-sm bg-primary text-white">
            <div class="card-body text-center">
                <h3 class="fw-bold mb-0"><?= $stats['total_students'] ?></h3>
                <p class="mb-0 opacity-75">Total Students</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="card border-0 shadow-sm bg-success text-white">
            <div class="card-body text-center">
                <h3 class="fw-bold mb-0"><?= $stats['average'] ?>%</h3>
                <p class="mb-0 opacity-75">Class Average</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="card border-0 shadow-sm bg-info text-white">
            <div class="card-body text-center">
                <h3 class="fw-bold mb-0"><?= $stats['highest'] ?></h3>
                <p class="mb-0 opacity-75">Highest Mark</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="card border-0 shadow-sm bg-warning text-white">
            <div class="card-body text-center">
                <h3 class="fw-bold mb-0"><?= $stats['lowest'] ?></h3>
                <p class="mb-0 opacity-75">Lowest Mark</p>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-0">
                <h5 class="fw-bold mb-0">Results</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="resultsTable">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Student Number</th>
                                <th>Name</th>
                                <th>Marks</th>
                                <th>Grade</th>
                                <th>Points</th>
                                <th>Comment</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($results as $index => $result): ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td><?= $result['student_number'] ?></td>
                                <td class="fw-semibold"><?= $result['first_name'] ?> <?= $result['last_name'] ?></td>
                                <td><?= $result['marks_obtained'] ?>/<?= $exam['max_marks'] ?></td>
                                <td>
                                    <?php 
                                    $gradeClass = 'secondary';
                                    if (in_array($result['grade'], ['D1', 'D2', 'A', 'B'])) $gradeClass = 'success';
                                    elseif (in_array($result['grade'], ['C3', 'C4', 'C5', 'C6', 'C', 'D'])) $gradeClass = 'warning';
                                    elseif (in_array($result['grade'], ['P7', 'P8', 'E', 'O'])) $gradeClass = 'info';
                                    elseif (in_array($result['grade'], ['F9', 'F'])) $gradeClass = 'danger';
                                    ?>
                                    <span class="badge bg-<?= $gradeClass ?>"><?= $result['grade'] ?></span>
                                </td>
                                <td><?= $result['points'] ?></td>
                                <td class="small"><?= $result['comment'] ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-0">
                <h5 class="fw-bold mb-0">Grade Distribution</h5>
            </div>
            <div class="card-body">
                <canvas id="gradeChart" height="250"></canvas>
            </div>
        </div>
    </div>
</div>
<?php View::endSection(); ?>

<?php View::section('scripts'); ?>
<script>
$(document).ready(function() {
    $('#resultsTable').DataTable({
        pageLength: 25,
        order: [[3, 'desc']]
    });
    
    const gradeData = <?= json_encode($gradeDistribution) ?>;
    const labels = Object.keys(gradeData);
    const data = Object.values(gradeData);
    
    const colors = labels.map(grade => {
        if (['D1', 'D2', 'A', 'B'].includes(grade)) return '#198754';
        if (['C3', 'C4', 'C5', 'C6', 'C', 'D'].includes(grade)) return '#ffc107';
        if (['P7', 'P8', 'E', 'O'].includes(grade)) return '#0dcaf0';
        if (['F9', 'F'].includes(grade)) return '#dc3545';
        return '#6c757d';
    });
    
    new Chart(document.getElementById('gradeChart'), {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                data: data,
                backgroundColor: colors
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
});
</script>
<?php View::endSection(); ?>
