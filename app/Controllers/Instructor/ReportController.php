<?php
/**
 * Instructor Report Controller
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
use App\Models\ClassStream;
use App\Models\ExamResult;
use App\Models\Term;
use App\Models\AcademicYear;

class ReportController extends Controller
{
    public function index(Request $request): void
    {
        $user = Auth::user();
        $instructor = Instructor::findByUserId($user['id']);
        $academicYear = AcademicYear::current();

        $classes = [];
        if ($instructor && $academicYear) {
            $classes = Instructor::getClasses($instructor['id'], $academicYear['id']);
        }

        $this->view('instructor.reports.index', [
            'title' => 'Reports - Instructor',
            'user' => $user,
            'classes' => $classes
        ]);
    }

    public function classReport(Request $request): void
    {
        $classStreamId = (int)$request->param('classStreamId');
        $classStream = ClassStream::withDetails($classStreamId);

        if (!$classStream) {
            $this->redirect('/instructor/reports');
            return;
        }

        $term = Term::current();
        $students = Student::getByClassStream($classStreamId);
        
        $db = Database::getInstance();
        
        foreach ($students as &$student) {
            $results = $db->fetchAll("
                SELECT er.*, e.name as exam_name, et.code as exam_type,
                       s.name as subject_name, s.code as subject_code
                FROM exam_results er
                JOIN exams e ON er.exam_id = e.id
                JOIN exam_types et ON e.exam_type_id = et.id
                JOIN subjects s ON e.subject_id = s.id
                WHERE er.student_id = ? AND e.term_id = ?
                ORDER BY s.name, et.id
            ", [$student['id'], $term['id']]);
            
            $student['results'] = $results;
            
            $totalMarks = 0;
            $totalSubjects = 0;
            foreach ($results as $result) {
                if ($result['marks_obtained'] !== null) {
                    $totalMarks += $result['marks_obtained'];
                    $totalSubjects++;
                }
            }
            $student['average'] = $totalSubjects > 0 ? round($totalMarks / $totalSubjects, 2) : 0;
        }

        usort($students, function($a, $b) {
            return $b['average'] <=> $a['average'];
        });

        $this->view('instructor.reports.class', [
            'title' => 'Class Report - ' . $classStream['class_code'] . ' ' . $classStream['stream_name'],
            'user' => Auth::user(),
            'classStream' => $classStream,
            'students' => $students,
            'term' => $term
        ]);
    }

    public function studentReport(Request $request): void
    {
        $studentId = (int)$request->param('studentId');
        $student = Student::withUser($studentId);

        if (!$student) {
            $this->redirect('/instructor/reports');
            return;
        }

        $enrollment = Student::getCurrentEnrollment($studentId);
        $term = Term::current();
        
        $db = Database::getInstance();
        $results = $db->fetchAll("
            SELECT er.*, e.name as exam_name, e.max_marks, et.code as exam_type, et.name as exam_type_name,
                   s.name as subject_name, s.code as subject_code
            FROM exam_results er
            JOIN exams e ON er.exam_id = e.id
            JOIN exam_types et ON e.exam_type_id = et.id
            JOIN subjects s ON e.subject_id = s.id
            WHERE er.student_id = ? AND e.term_id = ?
            ORDER BY s.name, et.id
        ", [$studentId, $term['id']]);

        $subjectResults = [];
        foreach ($results as $result) {
            $subjectCode = $result['subject_code'];
            if (!isset($subjectResults[$subjectCode])) {
                $subjectResults[$subjectCode] = [
                    'name' => $result['subject_name'],
                    'code' => $subjectCode,
                    'bot' => null,
                    'mid' => null,
                    'eot' => null
                ];
            }
            
            $examType = strtolower($result['exam_type']);
            $subjectResults[$subjectCode][$examType] = $result;
        }

        foreach ($subjectResults as &$subject) {
            $bot = $subject['bot']['marks_obtained'] ?? 0;
            $mid = $subject['mid']['marks_obtained'] ?? 0;
            $eot = $subject['eot']['marks_obtained'] ?? 0;
            
            $subject['final_marks'] = ($bot * 0.20) + ($mid * 0.20) + ($eot * 0.60);
        }

        $this->view('instructor.reports.student', [
            'title' => 'Student Report - ' . $student['first_name'] . ' ' . $student['last_name'],
            'user' => Auth::user(),
            'student' => $student,
            'enrollment' => $enrollment,
            'subjectResults' => $subjectResults,
            'term' => $term
        ]);
    }
}
