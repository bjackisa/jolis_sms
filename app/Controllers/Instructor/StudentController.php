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
use App\Core\Database;
use App\Models\Instructor;
use App\Models\Student;
use App\Models\ExamResult;
use App\Models\AcademicYear;
use App\Models\Term;
use App\Models\User;

class StudentController extends Controller
{
    public function index(Request $request): void
    {
        $user = Auth::user();
        $instructor = Instructor::findByUserId($user['id']);
        $academicYear = AcademicYear::current();

        $students = [];
        $classes = [];
        
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
            'title' => 'My Students - Instructor',
            'user' => $user,
            'students' => $students,
            'classes' => $classes,
            'academicYear' => $academicYear
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

    public function add(Request $request): void
    {
        $user = Auth::user();
        $instructor = Instructor::findByUserId($user['id']);
        $academicYear = AcademicYear::current();
        $term = Term::current();

        if (!$instructor || !$academicYear || !$term) {
            $this->flash('error', 'Unable to add student. Please try again.');
            $this->redirect('/instructor/students');
            return;
        }

        $firstName = trim($request->input('first_name'));
        $lastName = trim($request->input('last_name'));
        $email = trim($request->input('email'));
        $gender = $request->input('gender');
        $classStreamId = (int)$request->input('class_stream_id');
        $dateOfBirth = $request->input('date_of_birth');
        $guardianName = trim($request->input('guardian_name'));
        $guardianPhone = trim($request->input('guardian_phone'));

        if (!$firstName || !$lastName || !$email || !$gender || !$classStreamId) {
            $this->flash('error', 'Please fill in all required fields.');
            $this->redirect('/instructor/students');
            return;
        }

        $db = Database::getInstance();

        $existingUser = User::findBy('email', $email);
        if ($existingUser) {
            $this->flash('error', 'A user with this email already exists.');
            $this->redirect('/instructor/students');
            return;
        }

        $db->beginTransaction();
        try {
            $defaultPassword = password_hash('student123', PASSWORD_BCRYPT, ['cost' => 12]);
            
            $userId = $db->insert('users', [
                'email' => $email,
                'password' => $defaultPassword,
                'role' => 'student',
                'first_name' => $firstName,
                'last_name' => $lastName,
                'status' => 'active'
            ]);

            $studentNumber = 'STU' . date('Y') . str_pad($userId, 5, '0', STR_PAD_LEFT);
            
            $studentId = $db->insert('students', [
                'user_id' => $userId,
                'student_number' => $studentNumber,
                'gender' => $gender,
                'date_of_birth' => $dateOfBirth ?: null,
                'guardian_name' => $guardianName ?: null,
                'guardian_phone' => $guardianPhone ?: null,
                'admission_date' => date('Y-m-d')
            ]);

            $db->insert('student_enrollments', [
                'student_id' => $studentId,
                'class_stream_id' => $classStreamId,
                'academic_year_id' => $academicYear['id'],
                'term_id' => $term['id'],
                'enrollment_date' => date('Y-m-d'),
                'status' => 'active'
            ]);

            $db->commit();
            $this->flash('success', 'Student added successfully! Default password: student123');
        } catch (\Exception $e) {
            $db->rollback();
            $this->flash('error', 'Failed to add student: ' . $e->getMessage());
        }

        $this->redirect('/instructor/students');
    }
}
