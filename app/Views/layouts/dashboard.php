<?php
/**
 * Dashboard Layout (Instructor & Student)
 * 
 * @developer   Jackisa Daniel Barack
 * @email       barackdanieljackisa@gmail.com
 * @website     jackisa.com
 * @quote       "One man and God are Majority"
 * @rights      All rights reserved
 */

use App\Core\View;
use App\Core\Auth;

$currentUser = Auth::user();
$role = Auth::role();
$isInstructor = $role === 'instructor';
$dashboardPrefix = $isInstructor ? '/instructor' : '/student';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Dashboard - Jolis SMS' ?></title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- DataTables -->
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet">
    <!-- Toastr -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <!-- Custom Dashboard CSS -->
    <link href="/assets/css/dashboard.css" rel="stylesheet">
    
    <?= View::yield('styles') ?>
</head>
<body class="dashboard-body">
    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <a href="<?= $dashboardPrefix ?>/dashboard" class="sidebar-brand">
                <i class="bi bi-mortarboard-fill"></i>
                <span>Jolis SMS</span>
            </a>
            <button class="sidebar-toggle d-lg-none" id="sidebarClose">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        
        <nav class="sidebar-nav">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/dashboard') !== false ? 'active' : '' ?>" href="<?= $dashboardPrefix ?>/dashboard">
                        <i class="bi bi-speedometer2"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                
                <?php if ($isInstructor): ?>
                <li class="nav-item">
                    <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/classes') !== false ? 'active' : '' ?>" href="<?= $dashboardPrefix ?>/classes">
                        <i class="bi bi-building"></i>
                        <span>Classes</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/students') !== false ? 'active' : '' ?>" href="<?= $dashboardPrefix ?>/students">
                        <i class="bi bi-people"></i>
                        <span>Students</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/subjects') !== false ? 'active' : '' ?>" href="<?= $dashboardPrefix ?>/subjects">
                        <i class="bi bi-book"></i>
                        <span>Subjects</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/exams') !== false ? 'active' : '' ?>" href="<?= $dashboardPrefix ?>/exams">
                        <i class="bi bi-file-earmark-text"></i>
                        <span>Exams</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/results') !== false ? 'active' : '' ?>" href="<?= $dashboardPrefix ?>/results">
                        <i class="bi bi-clipboard-data"></i>
                        <span>Results</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/homework') !== false ? 'active' : '' ?>" href="<?= $dashboardPrefix ?>/homework">
                        <i class="bi bi-journal-check"></i>
                        <span>Homework</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/scripts') !== false ? 'active' : '' ?>" href="<?= $dashboardPrefix ?>/scripts">
                        <i class="bi bi-file-earmark-arrow-up"></i>
                        <span>Exam Scripts</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/reports') !== false ? 'active' : '' ?>" href="<?= $dashboardPrefix ?>/reports">
                        <i class="bi bi-bar-chart"></i>
                        <span>Reports</span>
                    </a>
                </li>
                <?php else: ?>
                <li class="nav-item">
                    <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/results') !== false ? 'active' : '' ?>" href="<?= $dashboardPrefix ?>/results">
                        <i class="bi bi-clipboard-data"></i>
                        <span>My Results</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/homework') !== false ? 'active' : '' ?>" href="<?= $dashboardPrefix ?>/homework">
                        <i class="bi bi-journal-check"></i>
                        <span>Homework</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/scripts') !== false ? 'active' : '' ?>" href="<?= $dashboardPrefix ?>/scripts">
                        <i class="bi bi-file-earmark-arrow-down"></i>
                        <span>Exam Scripts</span>
                    </a>
                </li>
                <?php endif; ?>
                
                <li class="nav-item">
                    <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/notices') !== false ? 'active' : '' ?>" href="<?= $dashboardPrefix ?>/notices">
                        <i class="bi bi-bell"></i>
                        <span>Notices</span>
                    </a>
                </li>
                
                <?php if ($isInstructor): ?>
                <li class="nav-item">
                    <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/contact-messages') !== false ? 'active' : '' ?>" href="<?= $dashboardPrefix ?>/contact-messages">
                        <i class="bi bi-envelope"></i>
                        <span>Contact Messages</span>
                    </a>
                </li>
                <?php endif; ?>
                
                <li class="nav-divider"></li>
                
                <li class="nav-item">
                    <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/profile') !== false ? 'active' : '' ?>" href="<?= $dashboardPrefix ?>/profile">
                        <i class="bi bi-person-circle"></i>
                        <span>Profile</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-danger" href="/logout">
                        <i class="bi bi-box-arrow-left"></i>
                        <span>Logout</span>
                    </a>
                </li>
            </ul>
        </nav>
    </aside>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Navbar -->
        <header class="top-navbar">
            <div class="d-flex align-items-center">
                <button class="btn btn-link sidebar-toggle-btn d-lg-none me-2" id="sidebarToggle">
                    <i class="bi bi-list fs-4"></i>
                </button>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="<?= $dashboardPrefix ?>/dashboard">Home</a></li>
                        <?= View::yield('breadcrumb') ?>
                    </ol>
                </nav>
            </div>
            
            <div class="d-flex align-items-center gap-3">
                <div class="dropdown">
                    <button class="btn btn-link position-relative" data-bs-toggle="dropdown">
                        <i class="bi bi-bell fs-5"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem;">
                            3
                        </span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end notification-dropdown">
                        <h6 class="dropdown-header">Notifications</h6>
                        <a class="dropdown-item" href="#">
                            <small class="text-muted">No new notifications</small>
                        </a>
                    </div>
                </div>
                
                <div class="dropdown">
                    <button class="btn btn-link d-flex align-items-center gap-2 text-decoration-none" data-bs-toggle="dropdown">
                        <?php if (!empty($currentUser['avatar'])): ?>
                            <img src="<?= $currentUser['avatar'] ?>" alt="Avatar" class="rounded-circle" width="36" height="36">
                        <?php else: ?>
                            <div class="avatar-placeholder rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                                <?= strtoupper(substr($currentUser['first_name'], 0, 1)) ?>
                            </div>
                        <?php endif; ?>
                        <div class="d-none d-md-block text-start">
                            <div class="fw-semibold text-dark"><?= $currentUser['first_name'] ?> <?= $currentUser['last_name'] ?></div>
                            <small class="text-muted"><?= ucfirst($role) ?></small>
                        </div>
                        <i class="bi bi-chevron-down text-muted"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="<?= $dashboardPrefix ?>/profile"><i class="bi bi-person me-2"></i>Profile</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="/logout"><i class="bi bi-box-arrow-left me-2"></i>Logout</a></li>
                    </ul>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <div class="page-content">
            <?php 
            $flash_success = $_SESSION['_flash']['success'] ?? null;
            $flash_error = $_SESSION['_flash']['error'] ?? null;
            unset($_SESSION['_flash']['success'], $_SESSION['_flash']['error']);
            ?>
            
            <?php if ($flash_success): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i><?= $flash_success ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>
            
            <?php if ($flash_error): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-circle me-2"></i><?= $flash_error ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>

            <?= View::yield('content') ?>
        </div>

        <!-- Footer -->
        <footer class="dashboard-footer">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <p class="mb-0 text-muted small">
                    &copy; <?= date('Y') ?> Jolis ICT Academy. All rights reserved.
                </p>
                <p class="mb-0 text-muted small">
                    Developed by <a href="https://jackisa.com" class="text-primary text-decoration-none" target="_blank">Jackisa Daniel Barack</a>
                </p>
            </div>
        </footer>
    </div>

    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <!-- Select2 -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Toastr -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <!-- Custom Dashboard JS -->
    <script src="/public/assets/js/dashboard.js"></script>
    
    <?= View::yield('scripts') ?>
</body>
</html>
