<?php
/**
 * Instructor Notice Controller
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
use App\Models\Notice;
use App\Models\ClassModel;

class NoticeController extends Controller
{
    public function index(Request $request): void
    {
        $notices = Notice::published();

        $this->view('instructor.notices.index', [
            'title' => 'Notices - Instructor',
            'user' => Auth::user(),
            'notices' => $notices
        ]);
    }

    public function create(Request $request): void
    {
        $classes = ClassModel::allWithLevels();

        $this->view('instructor.notices.create', [
            'title' => 'Create Notice - Instructor',
            'user' => Auth::user(),
            'classes' => $classes
        ]);
    }

    public function store(Request $request): void
    {
        $user = Auth::user();
        
        $errors = $this->validate($request->body(), [
            'title' => 'required|min:3',
            'content' => 'required|min:10'
        ]);

        if (!empty($errors)) {
            $_SESSION['_errors'] = $errors;
            $_SESSION['_old'] = $request->body();
            $this->back();
            return;
        }

        $data = $request->body();
        
        Notice::create([
            'title' => $data['title'],
            'content' => $data['content'],
            'target_role' => $data['target_role'] ?? 'all',
            'target_class_id' => $data['target_class_id'] ?: null,
            'priority' => $data['priority'] ?? 'medium',
            'is_pinned' => isset($data['is_pinned']) ? 1 : 0,
            'published_at' => date('Y-m-d H:i:s'),
            'expires_at' => $data['expires_at'] ?: null,
            'created_by' => $user['id'],
            'status' => 'published'
        ]);

        $this->flash('success', 'Notice created successfully.');
        $this->redirect('/instructor/notices');
    }

    public function edit(Request $request): void
    {
        $noticeId = (int)$request->param('id');
        $notice = Notice::find($noticeId);

        if (!$notice) {
            $this->redirect('/instructor/notices');
            return;
        }

        $classes = ClassModel::allWithLevels();

        $this->view('instructor.notices.edit', [
            'title' => 'Edit Notice - Instructor',
            'user' => Auth::user(),
            'notice' => $notice,
            'classes' => $classes
        ]);
    }

    public function update(Request $request): void
    {
        $noticeId = (int)$request->param('id');
        
        $errors = $this->validate($request->body(), [
            'title' => 'required|min:3',
            'content' => 'required|min:10'
        ]);

        if (!empty($errors)) {
            $_SESSION['_errors'] = $errors;
            $this->back();
            return;
        }

        $data = $request->body();
        
        Notice::update($noticeId, [
            'title' => $data['title'],
            'content' => $data['content'],
            'target_role' => $data['target_role'] ?? 'all',
            'target_class_id' => $data['target_class_id'] ?: null,
            'priority' => $data['priority'] ?? 'medium',
            'is_pinned' => isset($data['is_pinned']) ? 1 : 0,
            'expires_at' => $data['expires_at'] ?: null,
            'status' => $data['status'] ?? 'published'
        ]);

        $this->flash('success', 'Notice updated successfully.');
        $this->redirect('/instructor/notices');
    }

    public function destroy(Request $request): void
    {
        $noticeId = (int)$request->param('id');
        Notice::delete($noticeId);
        
        $this->flash('success', 'Notice deleted successfully.');
        $this->redirect('/instructor/notices');
    }
}
