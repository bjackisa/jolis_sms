<?php
/**
 * Instructor Student Controller
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
use App\Models\ExamResult;
use App\Models\AcademicYear;
use App\Models\Term;

class StudentController extends Controller
{
    public function index(Request $request): void
    {
        $user = Auth::user();
        $instructor = Instructor::findByUserId($user['id']);
        $academicYear = AcademicYear::current();

        $students = [];
        if ($instructor && $academicYear) {
            $classes = Instructor::getClasses($instructor['id'], $academicYear['id']);
            
            foreach ($classes as $class) {
                $classStudents = Student::getByClassStream($class['class_stream_id']);
                foreach ($classStudents as $student) {
                    $student['class_name'] = $class['class_code'] . ' ' . $class['stream_name'];
                    $students[] = $student;
                }
            }
        }

        $this->view('instructor.students.index', [
            'title' => 'Students - Instructor',
            'user' => $user,
            'students' => $students
        ]);
    }

    public function show(Request $request): void
    {
        $studentId = (int)$request->param('id');
        $student = Student::withUser($studentId);

        if (!$student) {
            $this->redirect('/instructor/students');
            return;
        }

        $enrollment = Student::getCurrentEnrollment($studentId);
        $term = Term::current();
        
        $results = [];
        if ($term) {
            $results = ExamResult::byStudent($studentId, $term['id']);
        }

        $this->view('instructor.students.show', [
            'title' => $student['first_name'] . ' ' . $student['last_name'] . ' - Student Profile',
            'user' => Auth::user(),
            'student' => $student,
            'enrollment' => $enrollment,
            'results' => $results,
            'term' => $term
        ]);
    }
}
