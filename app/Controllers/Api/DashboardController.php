<?php
/**
 * API Dashboard Controller
 * 
 * @developer   Jackisa Daniel Barack
 * @email       barackdanieljackisa@gmail.com
 * @website     jackisa.com
 * @quote       "One man and God are Majority"
 * @rights      All rights reserved
 */

namespace App\Controllers\Api;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Auth;
use App\Core\Database;
use App\Models\Instructor;
use App\Models\Student;
use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\Homework;
use App\Models\AcademicYear;
use App\Models\Term;

class DashboardController extends Controller
{
    public function stats(Request $request): void
    {
        $user = Auth::user();
        $role = Auth::role();
        $academicYear = AcademicYear::current();
        $term = Term::current();

        $stats = [];

        if ($role === 'instructor') {
            $instructor = Instructor::findByUserId($user['id']);
            
            if ($instructor && $academicYear) {
                $classes = Instructor::getClasses($instructor['id'], $academicYear['id']);
                $subjects = Instructor::getSubjects($instructor['id'], $academicYear['id']);
                
                $studentCount = 0;
                foreach ($classes as $class) {
                    $studentCount += Student::count("id IN (SELECT student_id FROM student_enrollments WHERE class_stream_id = ? AND status = 'active')", [$class['class_stream_id']]);
                }
                
                $stats = [
                    'classes' => count($classes),
                    'subjects' => count($subjects),
                    'students' => $studentCount,
                    'exams' => $term ? count(Exam::byInstructor($instructor['id'], $term['id'])) : 0,
                    'homework' => count(Homework::byInstructor($instructor['id']))
                ];
            }
        } else {
            $student = Student::findByUserId($user['id']);
            
            if ($student && $term) {
                $results = ExamResult::byStudent($student['id'], $term['id']);
                $totalMarks = 0;
                foreach ($results as $result) {
                    if ($result['marks_obtained'] !== null) {
                        $totalMarks += $result['marks_obtained'];
                    }
                }
                
                $stats = [
                    'exams_taken' => count($results),
                    'average_score' => count($results) > 0 ? round($totalMarks / count($results), 2) : 0,
                    'homework_pending' => 0
                ];
            }
        }

        $this->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    public function charts(Request $request): void
    {
        $user = Auth::user();
        $role = Auth::role();
        $term = Term::current();
        $db = Database::getInstance();

        $chartData = [];

        if ($role === 'instructor') {
            $instructor = Instructor::findByUserId($user['id']);
            
            if ($instructor && $term) {
                $gradeDistribution = $db->fetchAll("
                    SELECT er.grade, COUNT(*) as count
                    FROM exam_results er
                    JOIN exams e ON er.exam_id = e.id
                    JOIN instructor_subjects ins ON e.subject_id = ins.subject_id 
                        AND e.class_stream_id = ins.class_stream_id
                    WHERE ins.instructor_id = ? AND e.term_id = ?
                    GROUP BY er.grade
                    ORDER BY er.grade
                ", [$instructor['id'], $term['id']]);

                $chartData['gradeDistribution'] = $gradeDistribution;

                $examPerformance = $db->fetchAll("
                    SELECT e.name, AVG(er.marks_obtained) as average
                    FROM exams e
                    JOIN exam_results er ON e.id = er.exam_id
                    JOIN instructor_subjects ins ON e.subject_id = ins.subject_id 
                        AND e.class_stream_id = ins.class_stream_id
                    WHERE ins.instructor_id = ? AND e.term_id = ?
                    GROUP BY e.id
                    ORDER BY e.exam_date
                    LIMIT 10
                ", [$instructor['id'], $term['id']]);

                $chartData['examPerformance'] = $examPerformance;
            }
        } else {
            $student = Student::findByUserId($user['id']);
            
            if ($student && $term) {
                $subjectPerformance = $db->fetchAll("
                    SELECT s.name as subject, AVG(er.marks_obtained) as average
                    FROM exam_results er
                    JOIN exams e ON er.exam_id = e.id
                    JOIN subjects s ON e.subject_id = s.id
                    WHERE er.student_id = ? AND e.term_id = ?
                    GROUP BY s.id
                    ORDER BY s.name
                ", [$student['id'], $term['id']]);

                $chartData['subjectPerformance'] = $subjectPerformance;
            }
        }

        $this->json([
            'success' => true,
            'data' => $chartData
        ]);
    }
}
