<?php
/**
 * Instructor Result Controller
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
use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\Student;
use App\Models\ClassStream;
use App\Models\GradingScale;
use App\Models\Term;

class ResultController extends Controller
{
    public function index(Request $request): void
    {
        $user = Auth::user();
        $instructor = Instructor::findByUserId($user['id']);
        $term = Term::current();

        $exams = [];
        if ($instructor && $term) {
            $exams = Exam::byInstructor($instructor['id'], $term['id']);
            
            foreach ($exams as &$exam) {
                $exam['results_count'] = Exam::getResultsCount($exam['id']);
                $exam['average'] = ExamResult::classAverage($exam['id']);
            }
        }

        $this->view('instructor.results.index', [
            'title' => 'Results Management - Instructor',
            'user' => $user,
            'exams' => $exams,
            'term' => $term
        ]);
    }

    public function enter(Request $request): void
    {
        $examId = (int)$request->param('examId');
        $exam = Exam::withDetails($examId);

        if (!$exam) {
            $this->redirect('/instructor/results');
            return;
        }

        $students = Student::getByClassStream($exam['class_stream_id']);
        $existingResults = ExamResult::byExam($examId);
        
        $resultsMap = [];
        foreach ($existingResults as $result) {
            $resultsMap[$result['student_id']] = $result;
        }

        foreach ($students as &$student) {
            $student['result'] = $resultsMap[$student['id']] ?? null;
        }

        $classStream = ClassStream::withDetails($exam['class_stream_id']);
        $gradingScales = GradingScale::byLevel($classStream['level_id'] ?? 1);

        $this->view('instructor.results.enter', [
            'title' => 'Enter Results - ' . $exam['name'],
            'user' => Auth::user(),
            'exam' => $exam,
            'students' => $students,
            'gradingScales' => $gradingScales
        ]);
    }

    public function save(Request $request): void
    {
        $user = Auth::user();
        $data = $request->body();
        
        $examId = $data['exam_id'] ?? null;
        $results = $data['results'] ?? [];

        if (!$examId || empty($results)) {
            $this->flash('error', 'Invalid data submitted.');
            $this->back();
            return;
        }

        $exam = Exam::withDetails($examId);
        if (!$exam) {
            $this->flash('error', 'Exam not found.');
            $this->redirect('/instructor/results');
            return;
        }

        $classStream = ClassStream::withDetails($exam['class_stream_id']);
        $levelId = $classStream['level_id'] ?? 1;
        $isOLevel = $classStream['level_code'] === 'O';

        foreach ($results as $studentId => $marks) {
            if ($marks === '' || $marks === null) {
                continue;
            }

            $marks = (float)$marks;
            
            if ($isOLevel) {
                $gradeInfo = GradingScale::calculateOLevelGrade($marks);
            } else {
                $gradeInfo = GradingScale::calculateALevelGrade($marks);
            }

            ExamResult::updateOrCreate($examId, $studentId, [
                'marks_obtained' => $marks,
                'grade' => $gradeInfo['grade'],
                'points' => $gradeInfo['points'],
                'comment' => $gradeInfo['comment'],
                'entered_by' => $user['id']
            ]);
        }

        Exam::update($examId, ['status' => 'completed']);

        $this->flash('success', 'Results saved successfully.');
        $this->redirect('/instructor/results/view/' . $examId);
    }

    public function view(Request $request): void
    {
        $examId = (int)$request->param('examId');
        $exam = Exam::withDetails($examId);

        if (!$exam) {
            $this->redirect('/instructor/results');
            return;
        }

        $results = ExamResult::byExam($examId);
        
        $stats = [
            'average' => ExamResult::classAverage($examId),
            'highest' => ExamResult::classHighest($examId),
            'lowest' => ExamResult::classLowest($examId),
            'total_students' => count($results)
        ];

        $gradeDistribution = [];
        foreach ($results as $result) {
            $grade = $result['grade'] ?? 'N/A';
            $gradeDistribution[$grade] = ($gradeDistribution[$grade] ?? 0) + 1;
        }

        $this->view('instructor.results.view', [
            'title' => 'Results - ' . $exam['name'],
            'user' => Auth::user(),
            'exam' => $exam,
            'results' => $results,
            'stats' => $stats,
            'gradeDistribution' => $gradeDistribution
        ]);
    }
}
