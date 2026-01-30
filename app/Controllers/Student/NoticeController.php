<?php
/**
 * Student Notice Controller
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
use App\Models\Notice;

class NoticeController extends Controller
{
    public function index(Request $request): void
    {
        $user = Auth::user();
        $student = Student::findByUserId($user['id']);

        $notices = [];
        if ($student) {
            $notices = Notice::forStudent($student['id']);
        } else {
            $notices = Notice::forRole('student');
        }

        $this->view('student.notices.index', [
            'title' => 'Notices - Student',
            'user' => $user,
            'notices' => $notices
        ]);
    }

    public function show(Request $request): void
    {
        $noticeId = (int)$request->param('id');
        $notice = Notice::find($noticeId);

        if (!$notice) {
            $this->redirect('/student/notices');
            return;
        }

        $this->view('student.notices.show', [
            'title' => $notice['title'] . ' - Notice',
            'user' => Auth::user(),
            'notice' => $notice
        ]);
    }
}
