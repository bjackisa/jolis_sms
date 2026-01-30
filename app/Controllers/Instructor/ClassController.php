<?php
/**
 * Instructor Class Controller
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
use App\Models\ClassStream;
use App\Models\Student;
use App\Models\AcademicYear;

class ClassController extends Controller
{
    public function index(Request $request): void
    {
        $user = Auth::user();
        $instructor = Instructor::findByUserId($user['id']);
        $academicYear = AcademicYear::current();

        $classes = [];
        if ($instructor && $academicYear) {
            $classes = Instructor::getClasses($instructor['id'], $academicYear['id']);
            
            foreach ($classes as &$class) {
                $class['student_count'] = ClassStream::getStudentCount($class['class_stream_id']);
            }
        }

        $this->view('instructor.classes.index', [
            'title' => 'My Classes - Instructor',
            'user' => $user,
            'classes' => $classes,
            'academicYear' => $academicYear
        ]);
    }

    public function show(Request $request): void
    {
        $classStreamId = (int)$request->param('id');
        $classStream = ClassStream::withDetails($classStreamId);

        if (!$classStream) {
            $this->redirect('/instructor/classes');
            return;
        }

        $students = Student::getByClassStream($classStreamId);

        $this->view('instructor.classes.show', [
            'title' => $classStream['class_code'] . ' ' . $classStream['stream_name'] . ' - Instructor',
            'user' => Auth::user(),
            'classStream' => $classStream,
            'students' => $students
        ]);
    }
}
