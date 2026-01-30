<?php
/**
 * Instructor Profile View
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
<li class="breadcrumb-item active">Profile</li>
<?php View::endSection(); ?>

<?php View::section('content'); ?>
<div class="mb-4">
    <h4 class="fw-bold mb-1">My Profile</h4>
    <p class="text-muted mb-0">Manage your account settings</p>
</div>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm text-center">
            <div class="card-body py-5">
                <?php if (!empty($user['avatar'])): ?>
                <img src="<?= $user['avatar'] ?>" alt="Avatar" class="rounded-circle mb-3" width="120" height="120">
                <?php else: ?>
                <div class="avatar-placeholder rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center mb-3" style="width: 120px; height: 120px; font-size: 3rem;">
                    <?= strtoupper(substr($user['first_name'], 0, 1)) ?>
                </div>
                <?php endif; ?>
                <h5 class="fw-bold mb-1"><?= $user['first_name'] ?> <?= $user['last_name'] ?></h5>
                <p class="text-muted mb-2"><?= $user['email'] ?></p>
                <span class="badge bg-primary">Instructor</span>
                <?php if ($instructor): ?>
                <p class="text-muted small mt-3 mb-0">Employee ID: <?= $instructor['employee_id'] ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-transparent border-0">
                <h5 class="fw-bold mb-0">Personal Information</h5>
            </div>
            <div class="card-body">
                <form action="/instructor/profile" method="POST" enctype="multipart/form-data">
                    <?= View::csrf() ?>
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">First Name</label>
                            <input type="text" name="first_name" class="form-control" value="<?= $user['first_name'] ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Last Name</label>
                            <input type="text" name="last_name" class="form-control" value="<?= $user['last_name'] ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="<?= $user['email'] ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone</label>
                            <input type="text" name="phone" class="form-control" value="<?= $user['phone'] ?? '' ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Qualification</label>
                            <input type="text" name="qualification" class="form-control" value="<?= $instructor['qualification'] ?? '' ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Specialization</label>
                            <input type="text" name="specialization" class="form-control" value="<?= $instructor['specialization'] ?? '' ?>">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Profile Photo</label>
                            <input type="file" name="avatar" class="form-control" accept="image/*">
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg me-1"></i>Update Profile
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-0">
                <h5 class="fw-bold mb-0">Change Password</h5>
            </div>
            <div class="card-body">
                <form action="/instructor/profile/password" method="POST">
                    <?= View::csrf() ?>
                    
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label">Current Password</label>
                            <input type="password" name="current_password" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">New Password</label>
                            <input type="password" name="password" class="form-control" minlength="8" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Confirm New Password</label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-warning">
                                <i class="bi bi-lock me-1"></i>Change Password
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php View::endSection(); ?>
