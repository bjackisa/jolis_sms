<?php
/**
 * Student Dashboard Controller
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
use App\Models\Student;
use App\Models\ExamResult;
use App\Models\Homework;
use App\Models\HomeworkSubmission;
use App\Models\Notice;
use App\Models\Term;

class DashboardController extends Controller
{
    public function index(Request $request): void
    {
        $user = Auth::user();
        $student = Student::findByUserId($user['id']);
        $enrollment = $student ? Student::getCurrentEnrollment($student['id']) : null;
        $term = Term::current();

        $stats = [
            'subjects' => 0,
            'exams_taken' => 0,
            'homework_pending' => 0,
            'average_score' => 0
        ];

        $recentResults = [];
        $pendingHomework = [];

        if ($student && $enrollment && $term) {
            $results = ExamResult::byStudent($student['id'], $term['id']);
            $stats['exams_taken'] = count($results);
            
            $totalMarks = 0;
            foreach ($results as $result) {
                if ($result['marks_obtained'] !== null) {
                    $totalMarks += $result['marks_obtained'];
                }
            }
            $stats['average_score'] = $stats['exams_taken'] > 0 ? round($totalMarks / $stats['exams_taken'], 2) : 0;
            
            $recentResults = array_slice($results, 0, 5);

            $allHomework = Homework::byClassStream($enrollment['class_stream_id']);
            foreach ($allHomework as $hw) {
                if (!HomeworkSubmission::hasSubmitted($hw['id'], $student['id']) && !Homework::isPastDue($hw)) {
                    $pendingHomework[] = $hw;
                }
            }
            $stats['homework_pending'] = count($pendingHomework);
        }

        $notices = Notice::forRole('student');
        $recentNotices = array_slice($notices, 0, 5);

        $this->view('student.dashboard', [
            'title' => 'Dashboard - Student',
            'user' => $user,
            'student' => $student,
            'enrollment' => $enrollment,
            'term' => $term,
            'stats' => $stats,
            'recentResults' => $recentResults,
            'pendingHomework' => $pendingHomework,
            'notices' => $recentNotices
        ]);
    }
}
