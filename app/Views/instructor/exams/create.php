<?php
/**
 * Instructor Create Exam View
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
<li class="breadcrumb-item"><a href="/instructor/exams">Exams</a></li>
<li class="breadcrumb-item active">Create</li>
<?php View::endSection(); ?>

<?php View::section('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Create Exam</h4>
        <p class="text-muted mb-0"><?= $term ? $term['name'] : '' ?></p>
    </div>
    <a href="/instructor/exams" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Back
    </a>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <form action="/instructor/exams" method="POST">
                    <?= View::csrf() ?>
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Exam Type <span class="text-danger">*</span></label>
                            <select name="exam_type_id" class="form-select" required>
                                <option value="">Select Type</option>
                                <?php foreach ($examTypes as $type): ?>
                                <option value="<?= $type['id'] ?>"><?= $type['name'] ?> (<?= $type['code'] ?>)</option>
                                <?php endforeach; ?>
                            </select>
                        </div>
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
                        <div class="col-md-6">
                            <label class="form-label">Exam Date <span class="text-danger">*</span></label>
                            <input type="date" name="exam_date" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Exam Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" placeholder="e.g., Mathematics BOT Exam" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Maximum Marks <span class="text-danger">*</span></label>
                            <input type="number" name="max_marks" class="form-control" value="100" min="1" required>
                        </div>
                        <div class="col-12">
                            <hr class="my-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg me-1"></i>Create Exam
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php View::endSection(); ?>
