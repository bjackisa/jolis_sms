<?php
/**
 * Student Notices Index View
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
<div class="mb-4">
    <h4 class="fw-bold mb-1">Notices</h4>
    <p class="text-muted mb-0">School announcements and notices</p>
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
        <div class="card border-0 shadow-sm h-100 <?= $notice['is_pinned'] ? 'border-start border-primary border-3' : '' ?>">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <h5 class="fw-bold mb-0">
                        <?php if ($notice['is_pinned']): ?>
                        <i class="bi bi-pin-angle text-primary me-1"></i>
                        <?php endif; ?>
                        <?= htmlspecialchars($notice['title']) ?>
                    </h5>
                    <?php if ($notice['priority'] === 'high'): ?>
                    <span class="badge bg-danger">Important</span>
                    <?php endif; ?>
                </div>
                <p class="text-muted mb-3"><?= nl2br(htmlspecialchars(substr(strip_tags($notice['content']), 0, 200))) ?>...</p>
                <div class="d-flex justify-content-between align-items-center">
                    <small class="text-muted">
                        <i class="bi bi-clock me-1"></i>
                        <?= date('M d, Y', strtotime($notice['published_at'])) ?>
                    </small>
                    <a href="/student/notices/<?= $notice['id'] ?>" class="btn btn-sm btn-outline-primary">
                        Read More
                    </a>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
    <?php endif; ?>
</div>
<?php View::endSection(); ?>
