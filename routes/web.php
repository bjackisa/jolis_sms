<?php
use App\Core\Router;

/** @var Router $router */

// Public Routes (Landing Website)
$router->get('/', 'HomeController@index');
$router->get('/about', 'HomeController@about');
$router->get('/contact', 'HomeController@contact');
$router->post('/contact', 'HomeController@sendContact');

// Authentication Routes
$router->group(['prefix' => '', 'middleware' => ['GuestMiddleware']], function ($router) {
    $router->get('/login', 'AuthController@showLogin');
    $router->post('/login', 'AuthController@login');
    $router->get('/forgot-password', 'AuthController@showForgotPassword');
    $router->post('/forgot-password', 'AuthController@forgotPassword');
    $router->get('/reset-password/{token}', 'AuthController@showResetPassword');
    $router->post('/reset-password', 'AuthController@resetPassword');
});

$router->get('/logout', 'AuthController@logout', ['AuthMiddleware']);

// Instructor Routes
$router->group(['prefix' => 'instructor', 'middleware' => ['InstructorMiddleware']], function ($router) {
    $router->get('/dashboard', 'Instructor\DashboardController@index');
    
    // Classes
    $router->get('/classes', 'Instructor\ClassController@index');
    $router->get('/classes/{id}', 'Instructor\ClassController@show');
    
    // Students
    $router->get('/students', 'Instructor\StudentController@index');
    $router->get('/students/{id}', 'Instructor\StudentController@show');
    
    // Subjects
    $router->get('/subjects', 'Instructor\SubjectController@index');
    
    // Exams
    $router->get('/exams', 'Instructor\ExamController@index');
    $router->get('/exams/create', 'Instructor\ExamController@create');
    $router->post('/exams', 'Instructor\ExamController@store');
    $router->get('/exams/{id}', 'Instructor\ExamController@show');
    $router->get('/exams/{id}/edit', 'Instructor\ExamController@edit');
    $router->post('/exams/{id}', 'Instructor\ExamController@update');
    $router->post('/exams/{id}/delete', 'Instructor\ExamController@destroy');
    
    // Results Entry
    $router->get('/results', 'Instructor\ResultController@index');
    $router->get('/results/enter/{examId}', 'Instructor\ResultController@enter');
    $router->post('/results/save', 'Instructor\ResultController@save');
    $router->get('/results/view/{examId}', 'Instructor\ResultController@view');
    
    // Homework
    $router->get('/homework', 'Instructor\HomeworkController@index');
    $router->get('/homework/create', 'Instructor\HomeworkController@create');
    $router->post('/homework', 'Instructor\HomeworkController@store');
    $router->get('/homework/{id}', 'Instructor\HomeworkController@show');
    $router->get('/homework/{id}/edit', 'Instructor\HomeworkController@edit');
    $router->post('/homework/{id}', 'Instructor\HomeworkController@update');
    $router->post('/homework/{id}/delete', 'Instructor\HomeworkController@destroy');
    $router->get('/homework/{id}/submissions', 'Instructor\HomeworkController@submissions');
    $router->post('/homework/grade/{submissionId}', 'Instructor\HomeworkController@grade');
    
    // Exam Scripts Upload
    $router->get('/scripts', 'Instructor\ScriptController@index');
    $router->get('/scripts/upload', 'Instructor\ScriptController@create');
    $router->post('/scripts', 'Instructor\ScriptController@store');
    $router->post('/scripts/{id}/delete', 'Instructor\ScriptController@destroy');
    
    // Reports
    $router->get('/reports', 'Instructor\ReportController@index');
    $router->get('/reports/class/{classStreamId}', 'Instructor\ReportController@classReport');
    $router->get('/reports/student/{studentId}', 'Instructor\ReportController@studentReport');
    
    // Notices
    $router->get('/notices', 'Instructor\NoticeController@index');
    $router->get('/notices/create', 'Instructor\NoticeController@create');
    $router->post('/notices', 'Instructor\NoticeController@store');
    $router->get('/notices/{id}/edit', 'Instructor\NoticeController@edit');
    $router->post('/notices/{id}', 'Instructor\NoticeController@update');
    $router->post('/notices/{id}/delete', 'Instructor\NoticeController@destroy');
    
    // Profile
    $router->get('/profile', 'Instructor\ProfileController@index');
    $router->post('/profile', 'Instructor\ProfileController@update');
    $router->post('/profile/password', 'Instructor\ProfileController@updatePassword');
});

// Student Routes
$router->group(['prefix' => 'student', 'middleware' => ['StudentMiddleware']], function ($router) {
    $router->get('/dashboard', 'Student\DashboardController@index');
    
    // Results
    $router->get('/results', 'Student\ResultController@index');
    $router->get('/results/term/{termId}', 'Student\ResultController@termResults');
    $router->get('/results/subject/{subjectId}', 'Student\ResultController@subjectResults');
    
    // Homework
    $router->get('/homework', 'Student\HomeworkController@index');
    $router->get('/homework/{id}', 'Student\HomeworkController@show');
    $router->post('/homework/{id}/submit', 'Student\HomeworkController@submit');
    
    // Exam Scripts
    $router->get('/scripts', 'Student\ScriptController@index');
    $router->get('/scripts/{id}', 'Student\ScriptController@download');
    
    // Notices
    $router->get('/notices', 'Student\NoticeController@index');
    $router->get('/notices/{id}', 'Student\NoticeController@show');
    
    // Profile
    $router->get('/profile', 'Student\ProfileController@index');
    $router->post('/profile', 'Student\ProfileController@update');
    $router->post('/profile/password', 'Student\ProfileController@updatePassword');
});
