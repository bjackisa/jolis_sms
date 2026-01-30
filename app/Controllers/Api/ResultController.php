<?php
/**
 * API Result Controller
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
use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\Student;
use App\Models\ClassStream;
use App\Models\GradingScale;

class ResultController extends Controller
{
    public function index(Request $request): void
    {
        $termId = $request->query('term_id');
        $classStreamId = $request->query('class_stream_id');
        $subjectId = $request->query('subject_id');

        $filters = [];
        if ($termId) $filters['term_id'] = $termId;
        if ($classStreamId) $filters['class_stream_id'] = $classStreamId;
        if ($subjectId) $filters['subject_id'] = $subjectId;

        $exams = Exam::allWithDetails($filters);

        $this->json([
            'success' => true,
            'data' => $exams
        ]);
    }

    public function byExam(Request $request): void
    {
        $examId = (int)$request->param('examId');
        $results = ExamResult::byExam($examId);

        $this->json([
            'success' => true,
            'data' => $results,
            'stats' => [
                'average' => ExamResult::classAverage($examId),
                'highest' => ExamResult::classHighest($examId),
                'lowest' => ExamResult::classLowest($examId),
                'total' => count($results)
            ]
        ]);
    }

    public function byStudent(Request $request): void
    {
        $studentId = (int)$request->param('studentId');
        $termId = $request->query('term_id');

        $results = ExamResult::byStudent($studentId, $termId);

        $this->json([
            'success' => true,
            'data' => $results
        ]);
    }

    public function store(Request $request): void
    {
        $data = $request->body();
        $user = Auth::user();

        $examId = $data['exam_id'] ?? null;
        $studentId = $data['student_id'] ?? null;
        $marks = $data['marks_obtained'] ?? null;

        if (!$examId || !$studentId || $marks === null) {
            $this->json(['success' => false, 'message' => 'Missing required fields'], 400);
            return;
        }

        $exam = Exam::withDetails($examId);
        if (!$exam) {
            $this->json(['success' => false, 'message' => 'Exam not found'], 404);
            return;
        }

        $classStream = ClassStream::withDetails($exam['class_stream_id']);
        $isOLevel = $classStream['level_code'] === 'O';

        if ($isOLevel) {
            $gradeInfo = GradingScale::calculateOLevelGrade($marks);
        } else {
            $gradeInfo = GradingScale::calculateALevelGrade($marks);
        }

        $resultId = ExamResult::updateOrCreate($examId, $studentId, [
            'marks_obtained' => $marks,
            'grade' => $gradeInfo['grade'],
            'points' => $gradeInfo['points'],
            'comment' => $gradeInfo['comment'],
            'entered_by' => $user['id']
        ]);

        $this->json([
            'success' => true,
            'message' => 'Result saved successfully',
            'data' => [
                'id' => $resultId,
                'grade' => $gradeInfo['grade'],
                'points' => $gradeInfo['points'],
                'comment' => $gradeInfo['comment']
            ]
        ]);
    }

    public function bulkStore(Request $request): void
    {
        $data = $request->body();
        $user = Auth::user();

        $examId = $data['exam_id'] ?? null;
        $results = $data['results'] ?? [];

        if (!$examId || empty($results)) {
            $this->json(['success' => false, 'message' => 'Missing required fields'], 400);
            return;
        }

        $exam = Exam::withDetails($examId);
        if (!$exam) {
            $this->json(['success' => false, 'message' => 'Exam not found'], 404);
            return;
        }

        $classStream = ClassStream::withDetails($exam['class_stream_id']);
        $isOLevel = $classStream['level_code'] === 'O';

        $saved = 0;
        foreach ($results as $studentId => $marks) {
            if ($marks === '' || $marks === null) continue;

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

            $saved++;
        }

        Exam::update($examId, ['status' => 'completed']);

        $this->json([
            'success' => true,
            'message' => "{$saved} results saved successfully"
        ]);
    }

    public function update(Request $request): void
    {
        $resultId = (int)$request->param('id');
        $data = $request->body();
        $user = Auth::user();

        $result = ExamResult::find($resultId);
        if (!$result) {
            $this->json(['success' => false, 'message' => 'Result not found'], 404);
            return;
        }

        $exam = Exam::withDetails($result['exam_id']);
        $classStream = ClassStream::withDetails($exam['class_stream_id']);
        $isOLevel = $classStream['level_code'] === 'O';

        $marks = $data['marks_obtained'];

        if ($isOLevel) {
            $gradeInfo = GradingScale::calculateOLevelGrade($marks);
        } else {
            $gradeInfo = GradingScale::calculateALevelGrade($marks);
        }

        ExamResult::update($resultId, [
            'marks_obtained' => $marks,
            'grade' => $gradeInfo['grade'],
            'points' => $gradeInfo['points'],
            'comment' => $gradeInfo['comment'],
            'entered_by' => $user['id']
        ]);

        $this->json([
            'success' => true,
            'message' => 'Result updated successfully',
            'data' => [
                'grade' => $gradeInfo['grade'],
                'points' => $gradeInfo['points'],
                'comment' => $gradeInfo['comment']
            ]
        ]);
    }
}
