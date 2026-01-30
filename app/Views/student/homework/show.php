<?php
/**
 * Student Homework Show View
 * 
 * @developer   Jackisa Daniel Barack
 * @email       barackdanieljackisa@gmail.com
 * @website     jackisa.com
 * @quote       "One man and God are Majority"
 * @rights      All rights reserved
 */

use App\Core\View;
use App\Models\Homework;
View::extend('layouts.dashboard');

$isPastDue = Homework::isPastDue($homework);
$canSubmit = !$submission && !$isPastDue;
?>

<?php View::section('breadcrumb'); ?>
<li class="breadcrumb-item"><a href="/student/homework">Homework</a></li>
<li class="breadcrumb-item active"><?= htmlspecialchars($homework['title']) ?></li>
<?php View::endSection(); ?>

<?php View::section('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1"><?= htmlspecialchars($homework['title']) ?></h4>
        <p class="text-muted mb-0"><?= $homework['subject_name'] ?></p>
    </div>
    <a href="/student/homework" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Back
    </a>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-transparent border-0">
                <h5 class="fw-bold mb-0">Instructions</h5>
            </div>
            <div class="card-body">
                <?php if ($homework['description']): ?>
                <p><?= nl2br(htmlspecialchars($homework['description'])) ?></p>
                <?php else: ?>
                <p class="text-muted">No instructions provided.</p>
                <?php endif; ?>
                
                <?php if ($homework['attachment']): ?>
                <div class="mt-3">
                    <a href="<?= $homework['attachment'] ?>" class="btn btn-outline-primary" target="_blank">
                        <i class="bi bi-download me-1"></i>Download Attachment
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </div>
        
        <?php if ($canSubmit): ?>
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-0">
                <h5 class="fw-bold mb-0">Submit Your Work</h5>
            </div>
            <div class="card-body">
                <form action="/student/homework/<?= $homework['id'] ?>/submit" method="POST" enctype="multipart/form-data">
                    <?= View::csrf() ?>
                    
                    <div class="mb-3">
                        <label class="form-label">Your Answer</label>
                        <textarea name="submission_text" class="form-control" rows="6" placeholder="Type your answer here..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Attachment (Optional)</label>
                        <input type="file" name="attachment" class="form-control" accept=".pdf,.doc,.docx,.jpg,.png">
                        <small class="text-muted">Allowed: PDF, DOC, DOCX, JPG, PNG</small>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-send me-1"></i>Submit Homework
                    </button>
                </form>
            </div>
        </div>
        <?php elseif ($submission): ?>
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-0">
                <h5 class="fw-bold mb-0">Your Submission</h5>
            </div>
            <div class="card-body">
                <p><strong>Submitted:</strong> <?= date('M d, Y H:i', strtotime($submission['submitted_at'])) ?></p>
                
                <?php if ($submission['submission_text']): ?>
                <div class="bg-light p-3 rounded mb-3">
                    <?= nl2br(htmlspecialchars($submission['submission_text'])) ?>
                </div>
                <?php endif; ?>
                
                <?php if ($submission['attachment']): ?>
                <a href="<?= $submission['attachment'] ?>" class="btn btn-outline-primary btn-sm" target="_blank">
                    <i class="bi bi-download me-1"></i>View Attachment
                </a>
                <?php endif; ?>
                
                <?php if ($submission['status'] === 'graded'): ?>
                <hr>
                <h6 class="fw-bold">Feedback</h6>
                <div class="alert alert-info">
                    <p class="mb-1"><strong>Marks:</strong> <?= $submission['marks_obtained'] ?>/<?= $homework['max_marks'] ?></p>
                    <?php if ($submission['feedback']): ?>
                    <p class="mb-0"><strong>Comment:</strong> <?= htmlspecialchars($submission['feedback']) ?></p>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php else: ?>
        <div class="alert alert-danger">
            <i class="bi bi-exclamation-circle me-2"></i>
            This homework is past due. You can no longer submit.
        </div>
        <?php endif; ?>
    </div>
    
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-0">
                <h5 class="fw-bold mb-0">Details</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm table-borderless">
                    <tr>
                        <td class="text-muted">Subject:</td>
                        <td class="fw-semibold"><?= $homework['subject_name'] ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Class:</td>
                        <td><?= $homework['class_code'] ?> <?= $homework['stream_name'] ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Due Date:</td>
                        <td class="<?= $isPastDue ? 'text-danger' : '' ?>">
                            <?= date('M d, Y H:i', strtotime($homework['due_date'])) ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">Max Marks:</td>
                        <td><?= $homework['max_marks'] ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Instructor:</td>
                        <td><?= $homework['instructor_first_name'] ?> <?= $homework['instructor_last_name'] ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
<?php View::endSection(); ?>
