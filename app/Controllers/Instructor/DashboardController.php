<?php
/**
 * Instructor Dashboard Controller
 * 
 * @developer   Jackisa Daniel Barack
 * @email       barackdanieljackisa@gmail.com
 * @website     jackisa.com
 * @quote       "One man and God are Majority"
 * @rights      All rights reserved
 */

namespace App\Controllers\Instructor;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Auth;
use App\Models\Instructor;
use App\Models\Student;
use App\Models\Exam;
use App\Models\Homework;
use App\Models\Notice;
use App\Models\AcademicYear;
use App\Models\Term;

class DashboardController extends Controller
{
    public function index(Request $request): void
    {
        $user = Auth::user();
        $instructor = Instructor::findByUserId($user['id']);
        $academicYear = AcademicYear::current();
        $term = Term::current();

        $classes = [];
        $subjects = [];
        $stats = [
            'classes' => 0,
            'students' => 0,
            'exams' => 0,
            'homework' => 0
        ];

        if ($instructor && $academicYear) {
            $classes = Instructor::getClasses($instructor['id'], $academicYear['id']);
            $subjects = Instructor::getSubjects($instructor['id'], $academicYear['id']);
            
            $stats['classes'] = count($classes);
            $stats['subjects'] = count($subjects);
            
            $studentCount = 0;
            foreach ($classes as $class) {
                $studentCount += Student::count("id IN (SELECT student_id FROM student_enrollments WHERE class_stream_id = ? AND status = 'active')", [$class['class_stream_id']]);
            }
            $stats['students'] = $studentCount;
            
            if ($term) {
                $exams = Exam::byInstructor($instructor['id'], $term['id']);
                $stats['exams'] = count($exams);
            }
            
            $homework = Homework::byInstructor($instructor['id']);
            $stats['homework'] = count($homework);
        }

        $notices = Notice::forRole('instructor');
        $recentNotices = array_slice($notices, 0, 5);

        $this->view('instructor.dashboard', [
            'title' => 'Dashboard - Instructor',
            'user' => $user,
            'instructor' => $instructor,
            'academicYear' => $academicYear,
            'term' => $term,
            'classes' => $classes,
            'subjects' => $subjects,
            'stats' => $stats,
            'notices' => $recentNotices
        ]);
    }
}
