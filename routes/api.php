<?php
use App\Core\Router;

/** @var Router $router */

// API Routes
$router->group(['prefix' => 'api'], function ($router) {
    
    // Public API
    $router->get('/stats', 'Api\StatsController@index');
    
    // Auth Required APIs
    $router->group(['middleware' => ['AuthMiddleware']], function ($router) {
        
        // Dashboard Stats
        $router->get('/dashboard/stats', 'Api\DashboardController@stats');
        $router->get('/dashboard/charts', 'Api\DashboardController@charts');
        
        // Classes API
        $router->get('/classes', 'Api\ClassController@index');
        $router->get('/classes/{id}', 'Api\ClassController@show');
        $router->get('/classes/{id}/students', 'Api\ClassController@students');
        $router->get('/classes/{id}/subjects', 'Api\ClassController@subjects');
        
        // Students API
        $router->get('/students', 'Api\StudentController@index');
        $router->get('/students/{id}', 'Api\StudentController@show');
        $router->get('/students/{id}/results', 'Api\StudentController@results');
        $router->get('/students/{id}/homework', 'Api\StudentController@homework');
        
        // Subjects API
        $router->get('/subjects', 'Api\SubjectController@index');
        $router->get('/subjects/{id}', 'Api\SubjectController@show');
        $router->get('/subjects/{id}/papers', 'Api\SubjectController@papers');
        
        // Exams API
        $router->get('/exams', 'Api\ExamController@index');
        $router->get('/exams/{id}', 'Api\ExamController@show');
        $router->post('/exams', 'Api\ExamController@store');
        $router->put('/exams/{id}', 'Api\ExamController@update');
        $router->delete('/exams/{id}', 'Api\ExamController@destroy');
        
        // Results API
        $router->get('/results', 'Api\ResultController@index');
        $router->get('/results/exam/{examId}', 'Api\ResultController@byExam');
        $router->get('/results/student/{studentId}', 'Api\ResultController@byStudent');
        $router->post('/results', 'Api\ResultController@store');
        $router->post('/results/bulk', 'Api\ResultController@bulkStore');
        $router->put('/results/{id}', 'Api\ResultController@update');
        
        // Homework API
        $router->get('/homework', 'Api\HomeworkController@index');
        $router->get('/homework/{id}', 'Api\HomeworkController@show');
        $router->post('/homework', 'Api\HomeworkController@store');
        $router->put('/homework/{id}', 'Api\HomeworkController@update');
        $router->delete('/homework/{id}', 'Api\HomeworkController@destroy');
        $router->get('/homework/{id}/submissions', 'Api\HomeworkController@submissions');
        $router->post('/homework/{id}/submit', 'Api\HomeworkController@submit');
        $router->post('/homework/submissions/{id}/grade', 'Api\HomeworkController@grade');
        
        // Notices API
        $router->get('/notices', 'Api\NoticeController@index');
        $router->get('/notices/{id}', 'Api\NoticeController@show');
        $router->post('/notices', 'Api\NoticeController@store');
        $router->put('/notices/{id}', 'Api\NoticeController@update');
        $router->delete('/notices/{id}', 'Api\NoticeController@destroy');
        
        // Reports API
        $router->get('/reports/class/{classStreamId}', 'Api\ReportController@classReport');
        $router->get('/reports/student/{studentId}', 'Api\ReportController@studentReport');
        $router->get('/reports/term/{termId}', 'Api\ReportController@termReport');
        $router->get('/reports/subject/{subjectId}', 'Api\ReportController@subjectReport');
        
        // Grading API
        $router->get('/grading/calculate', 'Api\GradingController@calculate');
        $router->get('/grading/scales/{levelId}', 'Api\GradingController@scales');
        
        // Academic API
        $router->get('/academic-years', 'Api\AcademicController@years');
        $router->get('/terms', 'Api\AcademicController@terms');
        $router->get('/terms/current', 'Api\AcademicController@currentTerm');
        
        // User API
        $router->get('/user', 'Api\UserController@current');
        $router->put('/user', 'Api\UserController@update');
        $router->put('/user/password', 'Api\UserController@updatePassword');
        $router->post('/user/avatar', 'Api\UserController@uploadAvatar');
    });
});
