<?php
/**
 * Student Results Index View
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
<li class="breadcrumb-item active">My Results</li>
<?php View::endSection(); ?>

<?php View::section('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">My Results</h4>
        <p class="text-muted mb-0">
            <?= $term ? $term['name'] . ' - ' . ($term['academic_year_name'] ?? '') : 'No active term' ?>
            <?php if ($enrollment): ?>
            | <?= $enrollment['class_code'] ?> <?= $enrollment['stream_name'] ?>
            <?php endif; ?>
        </p>
    </div>
</div>

<!-- Term Selector -->
<?php if (!empty($terms)): ?>
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <div class="row align-items-center">
            <div class="col-md-3">
                <label class="form-label fw-semibold">Select Term:</label>
            </div>
            <div class="col-md-6">
                <select class="form-select" id="termSelector">
                    <?php foreach ($terms as $t): ?>
                    <option value="<?= $t['id'] ?>" <?= $term && $term['id'] == $t['id'] ? 'selected' : '' ?>>
                        <?= $t['name'] ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Results Table -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-transparent border-0">
        <h5 class="fw-bold mb-0">Term Results Summary</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Subject</th>
                        <th class="text-center">BOT (20%)</th>
                        <th class="text-center">MID (20%)</th>
                        <th class="text-center">EOT (60%)</th>
                        <th class="text-center">Final</th>
                        <th class="text-center">Grade</th>
                        <th>Comment</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($subjectResults)): ?>
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">No results available yet.</td>
                    </tr>
                    <?php else: ?>
                    <?php 
                    $totalFinal = 0;
                    $subjectCount = 0;
                    foreach ($subjectResults as $subject): 
                        $totalFinal += $subject['final_marks'];
                        $subjectCount++;
                    ?>
                    <tr>
                        <td class="fw-semibold"><?= $subject['name'] ?></td>
                        <td class="text-center">
                            <?= $subject['bot'] ? $subject['bot']['marks_obtained'] : '-' ?>
                        </td>
                        <td class="text-center">
                            <?= $subject['mid'] ? $subject['mid']['marks_obtained'] : '-' ?>
                        </td>
                        <td class="text-center">
                            <?= $subject['eot'] ? $subject['eot']['marks_obtained'] : '-' ?>
                        </td>
                        <td class="text-center fw-bold"><?= $subject['final_marks'] ?></td>
                        <td class="text-center">
                            <?php 
                            $gradeClass = 'secondary';
                            $grade = $subject['final_grade'] ?? '-';
                            if (in_array($grade, ['D1', 'D2', 'A', 'B'])) $gradeClass = 'success';
                            elseif (in_array($grade, ['C3', 'C4', 'C5', 'C6', 'C', 'D'])) $gradeClass = 'warning';
                            elseif (in_array($grade, ['P7', 'P8', 'E', 'O'])) $gradeClass = 'info';
                            elseif (in_array($grade, ['F9', 'F'])) $gradeClass = 'danger';
                            ?>
                            <span class="badge bg-<?= $gradeClass ?>"><?= $grade ?></span>
                        </td>
                        <td class="small"><?= $subject['final_comment'] ?? '' ?></td>
                    </tr>
                    <?php endforeach; ?>
                    <tr class="table-primary">
                        <td class="fw-bold">Overall Average</td>
                        <td colspan="3"></td>
                        <td class="text-center fw-bold">
                            <?= $subjectCount > 0 ? round($totalFinal / $subjectCount, 2) : 0 ?>
                        </td>
                        <td colspan="2"></td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Performance Chart -->
<?php if (!empty($subjectResults)): ?>
<div class="row mt-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-0">
                <h5 class="fw-bold mb-0">Performance Chart</h5>
            </div>
            <div class="card-body">
                <canvas id="performanceChart" height="300"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-0">
                <h5 class="fw-bold mb-0">Grade Summary</h5>
            </div>
            <div class="card-body">
                <canvas id="gradeChart" height="250"></canvas>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
<?php View::endSection(); ?>

<?php View::section('scripts'); ?>
<script>
$('#termSelector').on('change', function() {
    window.location.href = '/student/results/term/' + $(this).val();
});

<?php if (!empty($subjectResults)): ?>
const subjects = <?= json_encode(array_column($subjectResults, 'name')) ?>;
const finalMarks = <?= json_encode(array_column($subjectResults, 'final_marks')) ?>;
const grades = <?= json_encode(array_column($subjectResults, 'final_grade')) ?>;

new Chart(document.getElementById('performanceChart'), {
    type: 'bar',
    data: {
        labels: subjects,
        datasets: [{
            label: 'Final Marks',
            data: finalMarks,
            backgroundColor: finalMarks.map(m => {
                if (m >= 80) return '#198754';
                if (m >= 60) return '#ffc107';
                if (m >= 40) return '#0dcaf0';
                return '#dc3545';
            })
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                max: 100
            }
        },
        plugins: {
            legend: {
                display: false
            }
        }
    }
});

const gradeCount = {};
grades.forEach(g => {
    gradeCount[g] = (gradeCount[g] || 0) + 1;
});

new Chart(document.getElementById('gradeChart'), {
    type: 'doughnut',
    data: {
        labels: Object.keys(gradeCount),
        datasets: [{
            data: Object.values(gradeCount),
            backgroundColor: Object.keys(gradeCount).map(g => {
                if (['D1', 'D2', 'A', 'B'].includes(g)) return '#198754';
                if (['C3', 'C4', 'C5', 'C6', 'C', 'D'].includes(g)) return '#ffc107';
                if (['P7', 'P8', 'E', 'O'].includes(g)) return '#0dcaf0';
                return '#dc3545';
            })
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
<?php endif; ?>
</script>
<?php View::endSection(); ?>
