<?php
/**
 * 404 Error Page View
 * 
 * @developer   Jackisa Daniel Barack
 * @email       barackdanieljackisa@gmail.com
 * @website     jackisa.com
 * @quote       "One man and God are Majority"
 * @rights      All rights reserved
 */

use App\Core\View;
View::extend('layouts.app');
?>

<?php View::section('content'); ?>
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 text-center py-5">
                <div class="display-1 fw-bold text-primary mb-4">404</div>
                <h2 class="fw-bold mb-3">Page Not Found</h2>
                <p class="text-muted mb-4">
                    The page you are looking for might have been removed, had its name changed, 
                    or is temporarily unavailable.
                </p>
                <a href="/" class="btn btn-primary px-4">
                    <i class="bi bi-house me-2"></i>Go Home
                </a>
            </div>
        </div>
    </div>
</section>
<?php View::endSection(); ?>
