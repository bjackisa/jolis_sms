<?php
/**
 * Instructor Upload Exam Script View
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
<li class="breadcrumb-item"><a href="/instructor/scripts">Exam Scripts</a></li>
<li class="breadcrumb-item active">Upload</li>
<?php View::endSection(); ?>

<?php View::section('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Upload Exam Script</h4>
        <p class="text-muted mb-0">Share exam papers with students</p>
    </div>
    <a href="/instructor/scripts" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Back
    </a>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <form action="/instructor/scripts" method="POST" enctype="multipart/form-data">
                    <?= View::csrf() ?>
                    
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Select Exam <span class="text-danger">*</span></label>
                            <select name="exam_id" class="form-select" required>
                                <option value="">Select an exam</option>
                                <?php foreach ($exams as $exam): ?>
                                <option value="<?= $exam['id'] ?>">
                                    <?= htmlspecialchars($exam['name']) ?> - <?= $exam['class_code'] ?> <?= $exam['stream_name'] ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control" placeholder="e.g., Mathematics Paper 1" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="3" placeholder="Optional description..."></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">File <span class="text-danger">*</span></label>
                            <input type="file" name="file" class="form-control" accept=".pdf,.doc,.docx,.jpg,.png" required>
                            <small class="text-muted">Allowed: PDF, DOC, DOCX, JPG, PNG (Max: 10MB)</small>
                        </div>
                        <div class="col-12">
                            <hr class="my-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-upload me-1"></i>Upload Script
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php View::endSection(); ?>
