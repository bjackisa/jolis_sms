<?php
/**
 * Student Result Controller
 * 
 * @developer   Jackisa Daniel Barack
 * @email       barackdanieljackisa@gmail.com
 * @website     jackisa.com
 * @quote       "One man and God are Majority"
 * @rights      All rights reserved
 */

namespace App\Controllers\Student;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Auth;
use App\Core\Database;
use App\Models\Student;
use App\Models\ExamResult;
use App\Models\Term;
use App\Models\AcademicYear;
use App\Models\GradingScale;

class ResultController extends Controller
{
    public function index(Request $request): void
    {
        $user = Auth::user();
        $student = Student::findByUserId($user['id']);
        $enrollment = $student ? Student::getCurrentEnrollment($student['id']) : null;
        $term = Term::current();
        $academicYear = AcademicYear::current();

        $terms = [];
        if ($academicYear) {
            $terms = Term::byAcademicYear($academicYear['id']);
        }

        $results = [];
        $subjectResults = [];
        
        if ($student && $term) {
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
            ", [$student['id'], $term['id']]);

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

            $isOLevel = $enrollment && $enrollment['level_code'] === 'O';
            
            foreach ($subjectResults as &$subject) {
                $bot = $subject['bot']['marks_obtained'] ?? 0;
                $mid = $subject['mid']['marks_obtained'] ?? 0;
                $eot = $subject['eot']['marks_obtained'] ?? 0;
                
                $finalMarks = ($bot * 0.20) + ($mid * 0.20) + ($eot * 0.60);
                $subject['final_marks'] = round($finalMarks, 2);
                
                if ($isOLevel) {
                    $gradeInfo = GradingScale::calculateOLevelGrade($finalMarks);
                } else {
                    $gradeInfo = GradingScale::calculateALevelGrade($finalMarks);
                }
                
                $subject['final_grade'] = $gradeInfo['grade'];
                $subject['final_points'] = $gradeInfo['points'];
                $subject['final_comment'] = $gradeInfo['comment'];
            }
        }

        $this->view('student.results.index', [
            'title' => 'My Results - Student',
            'user' => $user,
            'student' => $student,
            'enrollment' => $enrollment,
            'term' => $term,
            'terms' => $terms,
            'subjectResults' => $subjectResults
        ]);
    }

    public function termResults(Request $request): void
    {
        $termId = (int)$request->param('termId');
        $user = Auth::user();
        $student = Student::findByUserId($user['id']);

        if (!$student) {
            $this->redirect('/student/results');
            return;
        }

        $db = Database::getInstance();
        $term = $db->fetch("SELECT t.*, ay.name as academic_year FROM terms t JOIN academic_years ay ON t.academic_year_id = ay.id WHERE t.id = ?", [$termId]);

        if (!$term) {
            $this->redirect('/student/results');
            return;
        }

        $results = ExamResult::byStudent($student['id'], $termId);

        $this->view('student.results.term', [
            'title' => $term['name'] . ' Results - Student',
            'user' => $user,
            'student' => $student,
            'term' => $term,
            'results' => $results
        ]);
    }

    public function subjectResults(Request $request): void
    {
        $subjectId = (int)$request->param('subjectId');
        $user = Auth::user();
        $student = Student::findByUserId($user['id']);

        if (!$student) {
            $this->redirect('/student/results');
            return;
        }

        $db = Database::getInstance();
        $subject = $db->fetch("SELECT * FROM subjects WHERE id = ?", [$subjectId]);

        if (!$subject) {
            $this->redirect('/student/results');
            return;
        }

        $results = $db->fetchAll("
            SELECT er.*, e.name as exam_name, e.max_marks, e.exam_date,
                   et.name as exam_type_name, et.code as exam_type_code,
                   t.name as term_name
            FROM exam_results er
            JOIN exams e ON er.exam_id = e.id
            JOIN exam_types et ON e.exam_type_id = et.id
            JOIN terms t ON e.term_id = t.id
            WHERE er.student_id = ? AND e.subject_id = ?
            ORDER BY e.exam_date DESC
        ", [$student['id'], $subjectId]);

        $this->view('student.results.subject', [
            'title' => $subject['name'] . ' Results - Student',
            'user' => $user,
            'student' => $student,
            'subject' => $subject,
            'results' => $results
        ]);
    }
}
