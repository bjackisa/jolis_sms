<?php
/**
 * Instructor Script Controller (Exam Scripts Upload)
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
use App\Models\Term;

class ScriptController extends Controller
{
    public function index(Request $request): void
    {
        $user = Auth::user();
        $instructor = Instructor::findByUserId($user['id']);
        $term = Term::current();

        $db = Database::getInstance();
        $scripts = $db->fetchAll("
            SELECT es.*, e.name as exam_name, s.name as subject_name,
                   c.code as class_code, st.name as stream_name
            FROM exam_scripts es
            JOIN exams e ON es.exam_id = e.id
            JOIN subjects s ON e.subject_id = s.id
            JOIN class_streams cs ON e.class_stream_id = cs.id
            JOIN classes c ON cs.class_id = c.id
            JOIN streams st ON cs.stream_id = st.id
            WHERE es.uploaded_by = ?
            ORDER BY es.created_at DESC
        ", [$user['id']]);

        $this->view('instructor.scripts.index', [
            'title' => 'Exam Scripts - Instructor',
            'user' => $user,
            'scripts' => $scripts
        ]);
    }

    public function create(Request $request): void
    {
        $user = Auth::user();
        $instructor = Instructor::findByUserId($user['id']);
        $term = Term::current();

        $exams = [];
        if ($instructor && $term) {
            $exams = Exam::byInstructor($instructor['id'], $term['id']);
        }

        $this->view('instructor.scripts.create', [
            'title' => 'Upload Exam Script - Instructor',
            'user' => $user,
            'exams' => $exams
        ]);
    }

    public function store(Request $request): void
    {
        $user = Auth::user();
        
        $errors = $this->validate($request->body(), [
            'exam_id' => 'required|numeric',
            'title' => 'required|min:3'
        ]);

        $file = $request->file('file');
        if (!$file || $file['error'] !== UPLOAD_ERR_OK) {
            $errors['file'] = ['Please upload a file.'];
        }

        if (!empty($errors)) {
            $_SESSION['_errors'] = $errors;
            $_SESSION['_old'] = $request->body();
            $this->back();
            return;
        }

        $data = $request->body();
        
        $filePath = $this->uploadFile($file, 'scripts', ['pdf', 'doc', 'docx', 'jpg', 'png']);
        
        if (!$filePath) {
            $this->flash('error', 'Failed to upload file.');
            $this->back();
            return;
        }

        $db = Database::getInstance();
        $db->insert('exam_scripts', [
            'exam_id' => $data['exam_id'],
            'title' => $data['title'],
            'description' => $data['description'] ?? '',
            'file_path' => $filePath,
            'file_type' => pathinfo($file['name'], PATHINFO_EXTENSION),
            'file_size' => $file['size'],
            'uploaded_by' => $user['id']
        ]);

        $this->flash('success', 'Exam script uploaded successfully.');
        $this->redirect('/instructor/scripts');
    }

    public function destroy(Request $request): void
    {
        $scriptId = (int)$request->param('id');
        
        $db = Database::getInstance();
        $script = $db->fetch("SELECT * FROM exam_scripts WHERE id = ?", [$scriptId]);
        
        if ($script) {
            $filePath = PUBLIC_PATH . $script['file_path'];
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            
            $db->delete('exam_scripts', 'id = ?', [$scriptId]);
        }
        
        $this->flash('success', 'Exam script deleted successfully.');
        $this->redirect('/instructor/scripts');
    }
}
