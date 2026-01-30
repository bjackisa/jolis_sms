<?php
/**
 * Instructor Create Homework View
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
<li class="breadcrumb-item"><a href="/instructor/homework">Homework</a></li>
<li class="breadcrumb-item active">Create</li>
<?php View::endSection(); ?>

<?php View::section('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Add Homework</h4>
        <p class="text-muted mb-0">Create a new homework assignment</p>
    </div>
    <a href="/instructor/homework" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Back
    </a>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <form action="/instructor/homework" method="POST" enctype="multipart/form-data">
                    <?= View::csrf() ?>
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Class <span class="text-danger">*</span></label>
                            <select name="class_stream_id" class="form-select" required>
                                <option value="">Select Class</option>
                                <?php foreach ($classes as $class): ?>
                                <option value="<?= $class['class_stream_id'] ?>"><?= $class['class_code'] ?> <?= $class['stream_name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Subject <span class="text-danger">*</span></label>
                            <select name="subject_id" class="form-select" required>
                                <option value="">Select Subject</option>
                                <?php foreach ($subjects as $subject): ?>
                                <option value="<?= $subject['id'] ?>"><?= $subject['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control" placeholder="Homework title" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="4" placeholder="Homework instructions..."></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Due Date <span class="text-danger">*</span></label>
                            <input type="datetime-local" name="due_date" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Maximum Marks</label>
                            <input type="number" name="max_marks" class="form-control" value="100" min="1">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Attachment (Optional)</label>
                            <input type="file" name="attachment" class="form-control" accept=".pdf,.doc,.docx,.jpg,.png">
                            <small class="text-muted">Allowed: PDF, DOC, DOCX, JPG, PNG</small>
                        </div>
                        <div class="col-12">
                            <hr class="my-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg me-1"></i>Create Homework
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php View::endSection(); ?>
