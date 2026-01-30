<?php
/**
 * Student Homework Controller
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
use App\Models\Homework;
use App\Models\HomeworkSubmission;

class HomeworkController extends Controller
{
    public function index(Request $request): void
    {
        $user = Auth::user();
        $student = Student::findByUserId($user['id']);
        $enrollment = $student ? Student::getCurrentEnrollment($student['id']) : null;

        $homework = [];
        if ($enrollment) {
            $homework = Homework::byClassStream($enrollment['class_stream_id']);
            
            foreach ($homework as &$hw) {
                $hw['submission'] = HomeworkSubmission::getSubmission($hw['id'], $student['id']);
                $hw['is_submitted'] = $hw['submission'] !== null;
                $hw['is_past_due'] = Homework::isPastDue($hw);
            }
        }

        $this->view('student.homework.index', [
            'title' => 'Homework - Student',
            'user' => $user,
            'student' => $student,
            'homework' => $homework
        ]);
    }

    public function show(Request $request): void
    {
        $homeworkId = (int)$request->param('id');
        $homework = Homework::withDetails($homeworkId);

        if (!$homework) {
            $this->redirect('/student/homework');
            return;
        }

        $user = Auth::user();
        $student = Student::findByUserId($user['id']);
        
        $submission = null;
        if ($student) {
            $submission = HomeworkSubmission::getSubmission($homeworkId, $student['id']);
        }

        $this->view('student.homework.show', [
            'title' => $homework['title'] . ' - Homework',
            'user' => $user,
            'student' => $student,
            'homework' => $homework,
            'submission' => $submission
        ]);
    }

    public function submit(Request $request): void
    {
        $homeworkId = (int)$request->param('id');
        $homework = Homework::find($homeworkId);

        if (!$homework) {
            $this->redirect('/student/homework');
            return;
        }

        $user = Auth::user();
        $student = Student::findByUserId($user['id']);

        if (!$student) {
            $this->flash('error', 'Student profile not found.');
            $this->redirect('/student/homework');
            return;
        }

        if (HomeworkSubmission::hasSubmitted($homeworkId, $student['id'])) {
            $this->flash('error', 'You have already submitted this homework.');
            $this->redirect('/student/homework/' . $homeworkId);
            return;
        }

        if (Homework::isPastDue($homework)) {
            $this->flash('error', 'This homework is past due date.');
            $this->redirect('/student/homework/' . $homeworkId);
            return;
        }

        $data = $request->body();
        
        $attachment = null;
        $file = $request->file('attachment');
        if ($file && $file['error'] === UPLOAD_ERR_OK) {
            $attachment = $this->uploadFile($file, 'submissions', ['pdf', 'doc', 'docx', 'jpg', 'png']);
        }

        HomeworkSubmission::create([
            'homework_id' => $homeworkId,
            'student_id' => $student['id'],
            'submission_text' => $data['submission_text'] ?? '',
            'attachment' => $attachment,
            'submitted_at' => date('Y-m-d H:i:s'),
            'status' => 'submitted'
        ]);

        $this->flash('success', 'Homework submitted successfully.');
        $this->redirect('/student/homework/' . $homeworkId);
    }
}
