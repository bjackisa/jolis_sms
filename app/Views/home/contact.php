<?php
/**
 * Contact Page View
 * 
 * @developer   Jackisa Daniel Barack
 * @email       barackdanieljackisa@gmail.com
 * @website     jackisa.com
 * @quote       "One man and God are Majority"
 * @rights      All rights reserved
 */

use App\Core\View;
View::extend('layouts.app');

$flash_success = $_SESSION['_flash']['success'] ?? null;
unset($_SESSION['_flash']['success']);
?>

<?php View::section('content'); ?>
<!-- Hero Section -->
<section class="bg-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center py-5">
            <div class="col-lg-8 mx-auto text-center">
                <h1 class="display-4 fw-bold mb-4">Contact Us</h1>
                <p class="lead">
                    Get in touch with <?= $schoolName ?>. We'd love to hear from you.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Contact Content -->
<section class="py-5">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-5">
                <h3 class="fw-bold mb-4">Get In Touch</h3>
                <p class="text-muted mb-4">
                    Have questions about our school or the SMS platform? 
                    Feel free to reach out to us using any of the methods below.
                </p>
                
                <div class="d-flex mb-4">
                    <div class="flex-shrink-0">
                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-3">
                            <i class="bi bi-geo-alt fs-4"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="fw-bold">Address</h6>
                        <p class="text-muted mb-0"><?= $schoolAddress ?></p>
                    </div>
                </div>
                
                <div class="d-flex mb-4">
                    <div class="flex-shrink-0">
                        <div class="bg-success bg-opacity-10 text-success rounded-circle p-3">
                            <i class="bi bi-telephone fs-4"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="fw-bold">Phone</h6>
                        <p class="text-muted mb-0"><?= $schoolPhone ?></p>
                    </div>
                </div>
                
                <div class="d-flex mb-4">
                    <div class="flex-shrink-0">
                        <div class="bg-warning bg-opacity-10 text-warning rounded-circle p-3">
                            <i class="bi bi-envelope fs-4"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="fw-bold">Email</h6>
                        <p class="text-muted mb-0"><?= $schoolEmail ?></p>
                    </div>
                </div>
                
                <div class="d-flex mb-4">
                    <div class="flex-shrink-0">
                        <div class="bg-info bg-opacity-10 text-info rounded-circle p-3">
                            <i class="bi bi-clock fs-4"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="fw-bold">Office Hours</h6>
                        <p class="text-muted mb-0">Monday - Friday: 8:00 AM - 5:00 PM</p>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h4 class="fw-bold mb-4">Send us a Message</h4>
                        
                        <?php if ($flash_success): ?>
                        <div class="alert alert-success">
                            <i class="bi bi-check-circle me-2"></i><?= $flash_success ?>
                        </div>
                        <?php endif; ?>
                        
                        <form action="/contact" method="POST">
                            <?= View::csrf() ?>
                            
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Your Name</label>
                                    <input type="text" name="name" class="form-control <?= View::hasError('name') ? 'is-invalid' : '' ?>" 
                                           value="<?= View::old('name') ?>" required>
                                    <?php if ($error = View::error('name')): ?>
                                    <div class="invalid-feedback"><?= $error ?></div>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Email Address</label>
                                    <input type="email" name="email" class="form-control <?= View::hasError('email') ? 'is-invalid' : '' ?>" 
                                           value="<?= View::old('email') ?>" required>
                                    <?php if ($error = View::error('email')): ?>
                                    <div class="invalid-feedback"><?= $error ?></div>
                                    <?php endif; ?>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Subject</label>
                                    <input type="text" name="subject" class="form-control <?= View::hasError('subject') ? 'is-invalid' : '' ?>" 
                                           value="<?= View::old('subject') ?>" required>
                                    <?php if ($error = View::error('subject')): ?>
                                    <div class="invalid-feedback"><?= $error ?></div>
                                    <?php endif; ?>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Message</label>
                                    <textarea name="message" class="form-control <?= View::hasError('message') ? 'is-invalid' : '' ?>" 
                                              rows="5" required><?= View::old('message') ?></textarea>
                                    <?php if ($error = View::error('message')): ?>
                                    <div class="invalid-feedback"><?= $error ?></div>
                                    <?php endif; ?>
                                </div>

                                <?php if (defined('RECAPTCHA_SITE_KEY') && RECAPTCHA_SITE_KEY !== ''): ?>
                                <div class="col-12">
                                    <div class="g-recaptcha" data-sitekey="<?= htmlspecialchars(RECAPTCHA_SITE_KEY) ?>"></div>
                                    <?php if ($error = View::error('recaptcha')): ?>
                                    <div class="text-danger small mt-2"><?= $error ?></div>
                                    <?php endif; ?>
                                </div>
                                <?php endif; ?>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary px-4">
                                        <i class="bi bi-send me-2"></i>Send Message
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php View::endSection(); ?>
