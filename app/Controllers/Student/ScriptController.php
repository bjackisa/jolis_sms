<?php
/**
 * Student Script Controller (View/Download Exam Scripts)
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

class ScriptController extends Controller
{
    public function index(Request $request): void
    {
        $user = Auth::user();
        $student = Student::findByUserId($user['id']);
        $enrollment = $student ? Student::getCurrentEnrollment($student['id']) : null;

        $scripts = [];
        if ($enrollment) {
            $db = Database::getInstance();
            $scripts = $db->fetchAll("
                SELECT es.*, e.name as exam_name, s.name as subject_name,
                       c.code as class_code, st.name as stream_name,
                       u.first_name as uploaded_by_first, u.last_name as uploaded_by_last
                FROM exam_scripts es
                JOIN exams e ON es.exam_id = e.id
                JOIN subjects s ON e.subject_id = s.id
                JOIN class_streams cs ON e.class_stream_id = cs.id
                JOIN classes c ON cs.class_id = c.id
                JOIN streams st ON cs.stream_id = st.id
                LEFT JOIN users u ON es.uploaded_by = u.id
                WHERE e.class_stream_id = ?
                ORDER BY es.created_at DESC
            ", [$enrollment['class_stream_id']]);
        }

        $this->view('student.scripts.index', [
            'title' => 'Exam Scripts - Student',
            'user' => $user,
            'scripts' => $scripts
        ]);
    }

    public function download(Request $request): void
    {
        $scriptId = (int)$request->param('id');
        
        $db = Database::getInstance();
        $script = $db->fetch("SELECT * FROM exam_scripts WHERE id = ?", [$scriptId]);

        if (!$script) {
            $this->redirect('/student/scripts');
            return;
        }

        $filePath = PUBLIC_PATH . $script['file_path'];
        
        if (!file_exists($filePath)) {
            $this->flash('error', 'File not found.');
            $this->redirect('/student/scripts');
            return;
        }

        $filename = $script['title'] . '.' . $script['file_type'];
        
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Length: ' . filesize($filePath));
        
        readfile($filePath);
        exit;
    }
}
