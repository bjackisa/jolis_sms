<?php
/**
 * Instructor Contact Messages Index View
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
<li class="breadcrumb-item active">Contact Messages</li>
<?php View::endSection(); ?>

<?php View::section('content'); ?>
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h5 class="fw-bold mb-1">Contact Messages</h5>
                <p class="text-muted small mb-0">Messages submitted through the contact form</p>
            </div>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-outline-secondary btn-sm" id="exportCsv">
                    <i class="bi bi-download me-1"></i>Export CSV
                </button>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle" id="messagesTable">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Subject</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($messages)): ?>
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <i class="bi bi-envelope text-muted d-block" style="font-size: 2rem;"></i>
                            <span class="text-muted">No contact messages yet.</span>
                        </td>
                    </tr>
                    <?php else: ?>
                    <?php $i = 1; foreach ($messages as $message): ?>
                    <tr class="<?= $message['status'] === 'new' ? 'table-primary' : '' ?>">
                        <td><?= $i++ ?></td>
                        <td>
                            <strong><?= htmlspecialchars($message['name']) ?></strong>
                            <?php if ($message['status'] === 'new'): ?>
                            <span class="badge bg-danger ms-1">New</span>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($message['email']) ?></td>
                        <td><?= htmlspecialchars($message['subject']) ?></td>
                        <td>
                            <?php if ($message['status'] === 'new'): ?>
                            <span class="badge bg-danger">New</span>
                            <?php elseif ($message['status'] === 'read'): ?>
                            <span class="badge bg-warning">Read</span>
                            <?php elseif ($message['status'] === 'replied'): ?>
                            <span class="badge bg-success">Replied</span>
                            <?php else: ?>
                            <span class="badge bg-secondary"><?= ucfirst($message['status']) ?></span>
                            <?php endif; ?>
                        </td>
                        <td><?= date('M d, Y H:i', strtotime($message['created_at'])) ?></td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="/instructor/contact-messages/<?= $message['id'] ?>" class="btn btn-outline-primary" title="View">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <?php if ($message['status'] === 'new'): ?>
                                <form action="/instructor/contact-messages/<?= $message['id'] ?>/mark-read" method="POST" class="d-inline">
                                    <?= View::csrf() ?>
                                    <button type="submit" class="btn btn-outline-success" title="Mark as Read">
                                        <i class="bi bi-check-circle"></i>
                                    </button>
                                </form>
                                <?php endif; ?>
                                <form action="/instructor/contact-messages/<?= $message['id'] ?>/delete" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this message?')">
                                    <?= View::csrf() ?>
                                    <button type="submit" class="btn btn-outline-danger" title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php View::endSection(); ?>

<?php View::section('scripts'); ?>
<script>
$(document).ready(function() {
    if ($('#messagesTable tbody tr').length > 1 || !$('#messagesTable tbody tr td[colspan]').length) {
        $('#messagesTable').DataTable({
            pageLength: 25,
            order: [[5, 'desc']],
            columnDefs: [
                { orderable: false, targets: [6] }
            ]
        });
    }
    
    // Export CSV
    $('#exportCsv').click(function() {
        let csv = 'Name,Email,Subject,Status,Date\n';
        $('#messagesTable tbody tr').each(function() {
            if (!$(this).find('td[colspan]').length) {
                let row = [];
                row.push($(this).find('td:eq(1)').text().trim().replace('New', ''));
                row.push($(this).find('td:eq(2)').text().trim());
                row.push($(this).find('td:eq(3)').text().trim());
                row.push($(this).find('td:eq(4)').text().trim());
                row.push($(this).find('td:eq(5)').text().trim());
                csv += '"' + row.join('","') + '"\n';
            }
        });
        
        let blob = new Blob([csv], { type: 'text/csv' });
        let url = window.URL.createObjectURL(blob);
        let a = document.createElement('a');
        a.href = url;
        a.download = 'contact_messages.csv';
        a.click();
    });
});
</script>
<?php View::endSection(); ?>
