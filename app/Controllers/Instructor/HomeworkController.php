<?php
/**
 * Instructor Homework Controller
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
use App\Models\Homework;
use App\Models\HomeworkSubmission;
use App\Models\AcademicYear;

class HomeworkController extends Controller
{
    public function index(Request $request): void
    {
        $user = Auth::user();
        $instructor = Instructor::findByUserId($user['id']);

        $homework = [];
        if ($instructor) {
            $homework = Homework::byInstructor($instructor['id']);
            
            foreach ($homework as &$hw) {
                $hw['submissions_count'] = Homework::getSubmissionCount($hw['id']);
                $hw['is_past_due'] = Homework::isPastDue($hw);
            }
        }

        $this->view('instructor.homework.index', [
            'title' => 'Homework - Instructor',
            'user' => $user,
            'homework' => $homework
        ]);
    }

    public function create(Request $request): void
    {
        $user = Auth::user();
        $instructor = Instructor::findByUserId($user['id']);
        $academicYear = AcademicYear::current();

        $classes = [];
        $subjects = [];
        
        if ($instructor && $academicYear) {
            $classes = Instructor::getClasses($instructor['id'], $academicYear['id']);
            $subjects = Instructor::getSubjects($instructor['id'], $academicYear['id']);
        }

        $this->view('instructor.homework.create', [
            'title' => 'Create Homework - Instructor',
            'user' => $user,
            'classes' => $classes,
            'subjects' => $subjects
        ]);
    }

    public function store(Request $request): void
    {
        $user = Auth::user();
        $instructor = Instructor::findByUserId($user['id']);
        
        $errors = $this->validate($request->body(), [
            'class_stream_id' => 'required|numeric',
            'subject_id' => 'required|numeric',
            'title' => 'required|min:3',
            'due_date' => 'required'
        ]);

        if (!empty($errors)) {
            $_SESSION['_errors'] = $errors;
            $_SESSION['_old'] = $request->body();
            $this->back();
            return;
        }

        $data = $request->body();
        
        $attachment = null;
        $file = $request->file('attachment');
        if ($file && $file['error'] === UPLOAD_ERR_OK) {
            $attachment = $this->uploadFile($file, 'homework', ['pdf', 'doc', 'docx', 'jpg', 'png']);
        }

        Homework::create([
            'instructor_id' => $instructor['id'],
            'class_stream_id' => $data['class_stream_id'],
            'subject_id' => $data['subject_id'],
            'title' => $data['title'],
            'description' => $data['description'] ?? '',
            'attachment' => $attachment,
            'due_date' => $data['due_date'],
            'max_marks' => $data['max_marks'] ?? 100,
            'status' => 'active'
        ]);

        $this->flash('success', 'Homework created successfully.');
        $this->redirect('/instructor/homework');
    }

    public function show(Request $request): void
    {
        $homeworkId = (int)$request->param('id');
        $homework = Homework::withDetails($homeworkId);

        if (!$homework) {
            $this->redirect('/instructor/homework');
            return;
        }

        $submissions = HomeworkSubmission::byHomework($homeworkId);

        $this->view('instructor.homework.show', [
            'title' => $homework['title'] . ' - Homework',
            'user' => Auth::user(),
            'homework' => $homework,
            'submissions' => $submissions
        ]);
    }

    public function edit(Request $request): void
    {
        $homeworkId = (int)$request->param('id');
        $homework = Homework::withDetails($homeworkId);

        if (!$homework) {
            $this->redirect('/instructor/homework');
            return;
        }

        $user = Auth::user();
        $instructor = Instructor::findByUserId($user['id']);
        $academicYear = AcademicYear::current();

        $classes = Instructor::getClasses($instructor['id'], $academicYear['id']);
        $subjects = Instructor::getSubjects($instructor['id'], $academicYear['id']);

        $this->view('instructor.homework.edit', [
            'title' => 'Edit Homework - Instructor',
            'user' => $user,
            'homework' => $homework,
            'classes' => $classes,
            'subjects' => $subjects
        ]);
    }

    public function update(Request $request): void
    {
        $homeworkId = (int)$request->param('id');
        
        $errors = $this->validate($request->body(), [
            'title' => 'required|min:3',
            'due_date' => 'required'
        ]);

        if (!empty($errors)) {
            $_SESSION['_errors'] = $errors;
            $this->back();
            return;
        }

        $data = $request->body();
        $updateData = [
            'class_stream_id' => $data['class_stream_id'],
            'subject_id' => $data['subject_id'],
            'title' => $data['title'],
            'description' => $data['description'] ?? '',
            'due_date' => $data['due_date'],
            'max_marks' => $data['max_marks'] ?? 100,
            'status' => $data['status'] ?? 'active'
        ];

        $file = $request->file('attachment');
        if ($file && $file['error'] === UPLOAD_ERR_OK) {
            $attachment = $this->uploadFile($file, 'homework', ['pdf', 'doc', 'docx', 'jpg', 'png']);
            if ($attachment) {
                $updateData['attachment'] = $attachment;
            }
        }

        Homework::update($homeworkId, $updateData);

        $this->flash('success', 'Homework updated successfully.');
        $this->redirect('/instructor/homework');
    }

    public function destroy(Request $request): void
    {
        $homeworkId = (int)$request->param('id');
        Homework::delete($homeworkId);
        
        $this->flash('success', 'Homework deleted successfully.');
        $this->redirect('/instructor/homework');
    }

    public function submissions(Request $request): void
    {
        $homeworkId = (int)$request->param('id');
        $homework = Homework::withDetails($homeworkId);

        if (!$homework) {
            $this->redirect('/instructor/homework');
            return;
        }

        $submissions = HomeworkSubmission::byHomework($homeworkId);

        $this->view('instructor.homework.submissions', [
            'title' => 'Submissions - ' . $homework['title'],
            'user' => Auth::user(),
            'homework' => $homework,
            'submissions' => $submissions
        ]);
    }

    public function grade(Request $request): void
    {
        $submissionId = (int)$request->param('submissionId');
        $user = Auth::user();
        
        $data = $request->body();
        
        HomeworkSubmission::update($submissionId, [
            'marks_obtained' => $data['marks'] ?? 0,
            'feedback' => $data['feedback'] ?? '',
            'graded_at' => date('Y-m-d H:i:s'),
            'graded_by' => $user['id'],
            'status' => 'graded'
        ]);

        $this->flash('success', 'Submission graded successfully.');
        $this->back();
    }
}
