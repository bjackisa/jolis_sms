<?php
/**
 * Instructor Exam Controller
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
use App\Models\Exam;
use App\Models\Subject;
use App\Models\ClassStream;
use App\Models\AcademicYear;
use App\Models\Term;

class ExamController extends Controller
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
            }
        }

        $this->view('instructor.exams.index', [
            'title' => 'Exams - Instructor',
            'user' => $user,
            'exams' => $exams,
            'term' => $term
        ]);
    }

    public function create(Request $request): void
    {
        $user = Auth::user();
        $instructor = Instructor::findByUserId($user['id']);
        $academicYear = AcademicYear::current();
        $term = Term::current();

        $classes = [];
        $subjects = [];
        
        if ($instructor && $academicYear) {
            $classes = Instructor::getClasses($instructor['id'], $academicYear['id']);
            $subjects = Instructor::getSubjects($instructor['id'], $academicYear['id']);
        }

        $db = Database::getInstance();
        $examTypes = $db->fetchAll("SELECT * FROM exam_types WHERE is_national = 0 ORDER BY id");

        $this->view('instructor.exams.create', [
            'title' => 'Create Exam - Instructor',
            'user' => $user,
            'classes' => $classes,
            'subjects' => $subjects,
            'examTypes' => $examTypes,
            'term' => $term
        ]);
    }

    public function store(Request $request): void
    {
        $user = Auth::user();
        
        $errors = $this->validate($request->body(), [
            'exam_type_id' => 'required|numeric',
            'class_stream_id' => 'required|numeric',
            'subject_id' => 'required|numeric',
            'name' => 'required|min:3',
            'max_marks' => 'required|numeric',
            'exam_date' => 'required'
        ]);

        if (!empty($errors)) {
            $_SESSION['_errors'] = $errors;
            $_SESSION['_old'] = $request->body();
            $this->back();
            return;
        }

        $term = Term::current();
        if (!$term) {
            $this->flash('error', 'No active term found.');
            $this->back();
            return;
        }

        $data = $request->body();
        
        Exam::create([
            'exam_type_id' => $data['exam_type_id'],
            'term_id' => $term['id'],
            'class_stream_id' => $data['class_stream_id'],
            'subject_id' => $data['subject_id'],
            'paper_id' => $data['paper_id'] ?? null,
            'name' => $data['name'],
            'max_marks' => $data['max_marks'],
            'exam_date' => $data['exam_date'],
            'status' => 'scheduled',
            'created_by' => $user['id']
        ]);

        $this->flash('success', 'Exam created successfully.');
        $this->redirect('/instructor/exams');
    }

    public function show(Request $request): void
    {
        $examId = (int)$request->param('id');
        $exam = Exam::withDetails($examId);

        if (!$exam) {
            $this->redirect('/instructor/exams');
            return;
        }

        $this->view('instructor.exams.show', [
            'title' => $exam['name'] . ' - Exam Details',
            'user' => Auth::user(),
            'exam' => $exam
        ]);
    }

    public function edit(Request $request): void
    {
        $examId = (int)$request->param('id');
        $exam = Exam::withDetails($examId);

        if (!$exam) {
            $this->redirect('/instructor/exams');
            return;
        }

        $user = Auth::user();
        $instructor = Instructor::findByUserId($user['id']);
        $academicYear = AcademicYear::current();

        $classes = Instructor::getClasses($instructor['id'], $academicYear['id']);
        $subjects = Instructor::getSubjects($instructor['id'], $academicYear['id']);

        $db = Database::getInstance();
        $examTypes = $db->fetchAll("SELECT * FROM exam_types WHERE is_national = 0 ORDER BY id");

        $this->view('instructor.exams.edit', [
            'title' => 'Edit Exam - Instructor',
            'user' => $user,
            'exam' => $exam,
            'classes' => $classes,
            'subjects' => $subjects,
            'examTypes' => $examTypes
        ]);
    }

    public function update(Request $request): void
    {
        $examId = (int)$request->param('id');
        
        $errors = $this->validate($request->body(), [
            'name' => 'required|min:3',
            'max_marks' => 'required|numeric',
            'exam_date' => 'required'
        ]);

        if (!empty($errors)) {
            $_SESSION['_errors'] = $errors;
            $this->back();
            return;
        }

        $data = $request->body();
        
        Exam::update($examId, [
            'exam_type_id' => $data['exam_type_id'],
            'class_stream_id' => $data['class_stream_id'],
            'subject_id' => $data['subject_id'],
            'name' => $data['name'],
            'max_marks' => $data['max_marks'],
            'exam_date' => $data['exam_date'],
            'status' => $data['status'] ?? 'scheduled'
        ]);

        $this->flash('success', 'Exam updated successfully.');
        $this->redirect('/instructor/exams');
    }

    public function destroy(Request $request): void
    {
        $examId = (int)$request->param('id');
        Exam::delete($examId);
        
        $this->flash('success', 'Exam deleted successfully.');
        $this->redirect('/instructor/exams');
    }
}
