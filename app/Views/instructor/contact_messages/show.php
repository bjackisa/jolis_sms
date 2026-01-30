<?php
/**
 * Instructor Contact Message Show View
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
<li class="breadcrumb-item"><a href="/instructor/contact-messages">Contact Messages</a></li>
<li class="breadcrumb-item active">View Message</li>
<?php View::endSection(); ?>

<?php View::section('content'); ?>
<div class="row g-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0">Message Details</h5>
                    <div class="d-flex gap-2">
                        <?php if ($message['status'] === 'new'): ?>
                        <form action="/instructor/contact-messages/<?= $message['id'] ?>/mark-read" method="POST" class="d-inline">
                            <?= View::csrf() ?>
                            <button type="submit" class="btn btn-success btn-sm">
                                <i class="bi bi-check-circle me-1"></i>Mark as Read
                            </button>
                        </form>
                        <?php endif; ?>
                        <form action="/instructor/contact-messages/<?= $message['id'] ?>/delete" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this message?')">
                            <?= View::csrf() ?>
                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="bi bi-trash me-1"></i>Delete
                            </button>
                        </form>
                        <a href="/instructor/contact-messages" class="btn btn-secondary btn-sm">
                            <i class="bi bi-arrow-left me-1"></i>Back
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="mb-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="text-muted small">From</label>
                            <p class="mb-0 fw-semibold"><?= htmlspecialchars($message['name']) ?></p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Email</label>
                            <p class="mb-0">
                                <a href="mailto:<?= htmlspecialchars($message['email']) ?>" class="text-decoration-none">
                                    <?= htmlspecialchars($message['email']) ?>
                                </a>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Status</label>
                            <p class="mb-0">
                                <?php if ($message['status'] === 'new'): ?>
                                <span class="badge bg-danger">New</span>
                                <?php elseif ($message['status'] === 'read'): ?>
                                <span class="badge bg-warning">Read</span>
                                <?php elseif ($message['status'] === 'replied'): ?>
                                <span class="badge bg-success">Replied</span>
                                <?php else: ?>
                                <span class="badge bg-secondary"><?= ucfirst($message['status']) ?></span>
                                <?php endif; ?>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Date Received</label>
                            <p class="mb-0"><?= date('F d, Y \a\t H:i', strtotime($message['created_at'])) ?></p>
                        </div>
                        <div class="col-12">
                            <label class="text-muted small">Subject</label>
                            <p class="mb-0 fw-semibold"><?= htmlspecialchars($message['subject']) ?></p>
                        </div>
                    </div>
                </div>
                
                <hr>
                
                <div class="mb-4">
                    <label class="text-muted small mb-2">Message</label>
                    <div class="bg-light p-3 rounded">
                        <p class="mb-0" style="white-space: pre-wrap;"><?= htmlspecialchars($message['message']) ?></p>
                    </div>
                </div>

                <?php if ($message['ip_address']): ?>
                <div class="text-muted small">
                    <i class="bi bi-info-circle me-1"></i>
                    Sent from IP: <?= htmlspecialchars($message['ip_address']) ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="fw-bold mb-0"><i class="bi bi-reply me-2"></i>Reply</h5>
            </div>
            <div class="card-body">
                <?php if ($message['status'] === 'replied'): ?>
                <div class="alert alert-success">
                    <i class="bi bi-check-circle me-2"></i>You have already replied to this message.
                </div>
                <?php endif; ?>
                
                <form action="/instructor/contact-messages/<?= $message['id'] ?>/reply" method="POST">
                    <?= View::csrf() ?>
                    <div class="mb-3">
                        <label class="form-label">Your Reply</label>
                        <textarea name="reply_message" class="form-control" rows="8" required placeholder="Type your reply here..."></textarea>
                        <div class="form-text">
                            This will be sent to <?= htmlspecialchars($message['email']) ?>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-send me-2"></i>Send Reply
                    </button>
                </form>
            </div>
        </div>

        <div class="card border-0 shadow-sm mt-3">
            <div class="card-header bg-white py-3">
                <h6 class="fw-bold mb-0"><i class="bi bi-info-circle me-2"></i>Email Preview</h6>
            </div>
            <div class="card-body">
                <p class="small text-muted mb-2"><strong>Subject:</strong></p>
                <p class="small mb-3">Response to Your Message to Jolis SMS</p>
                
                <p class="small text-muted mb-2"><strong>Signature:</strong></p>
                <div class="small">
                    <p class="mb-1">Sincerely,</p>
                    <p class="mb-0"><strong><?= $user['first_name'] . ' ' . $user['last_name'] ?></strong></p>
                    <p class="mb-0">Instructor, Jolis ICT Academy</p>
                </div>
            </div>
        </div>
    </div>
</div>
<?php View::endSection(); ?>
