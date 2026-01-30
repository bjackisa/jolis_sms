<?php
/**
 * Instructor Notices Index View
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
<li class="breadcrumb-item active">Notices</li>
<?php View::endSection(); ?>

<?php View::section('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Notices</h4>
        <p class="text-muted mb-0">School announcements and notices</p>
    </div>
    <a href="/instructor/notices/create" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i>Post Notice
    </a>
</div>

<div class="row g-4">
    <?php if (empty($notices)): ?>
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5">
                <i class="bi bi-bell text-muted" style="font-size: 4rem;"></i>
                <h5 class="mt-3">No Notices</h5>
                <p class="text-muted">There are no notices at the moment.</p>
            </div>
        </div>
    </div>
    <?php else: ?>
    <?php foreach ($notices as $notice): ?>
    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <h5 class="fw-bold mb-0"><?= htmlspecialchars($notice['title']) ?></h5>
                    <?php if ($notice['priority'] === 'high'): ?>
                    <span class="badge bg-danger">High</span>
                    <?php elseif ($notice['priority'] === 'medium'): ?>
                    <span class="badge bg-warning">Medium</span>
                    <?php else: ?>
                    <span class="badge bg-secondary">Low</span>
                    <?php endif; ?>
                </div>
                <p class="text-muted mb-3"><?= substr(strip_tags($notice['content']), 0, 150) ?>...</p>
                <div class="d-flex justify-content-between align-items-center">
                    <small class="text-muted">
                        <i class="bi bi-clock me-1"></i>
                        <?= date('M d, Y', strtotime($notice['published_at'])) ?>
                    </small>
                    <span class="badge bg-<?= $notice['target_role'] === 'all' ? 'primary' : ($notice['target_role'] === 'student' ? 'success' : 'info') ?>">
                        <?= ucfirst($notice['target_role']) ?>
                    </span>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0">
                <a href="/instructor/notices/<?= $notice['id'] ?>/edit" class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-pencil me-1"></i>Edit
                </a>
                <form action="/instructor/notices/<?= $notice['id'] ?>/delete" method="POST" class="d-inline" onsubmit="return confirm('Delete this notice?')">
                    <?= View::csrf() ?>
                    <button type="submit" class="btn btn-sm btn-outline-danger">
                        <i class="bi bi-trash"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
    <?php endif; ?>
</div>
<?php View::endSection(); ?>
