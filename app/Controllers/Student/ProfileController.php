<?php
/**
 * Student Profile Controller
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
use App\Models\User;
use App\Models\Student;

class ProfileController extends Controller
{
    public function index(Request $request): void
    {
        $user = Auth::user();
        $student = Student::findByUserId($user['id']);
        $enrollment = $student ? Student::getCurrentEnrollment($student['id']) : null;

        $this->view('student.profile.index', [
            'title' => 'My Profile - Student',
            'user' => $user,
            'student' => $student,
            'enrollment' => $enrollment
        ]);
    }

    public function update(Request $request): void
    {
        $user = Auth::user();
        
        $errors = $this->validate($request->body(), [
            'first_name' => 'required|min:2',
            'last_name' => 'required|min:2',
            'email' => 'required|email'
        ]);

        if (!empty($errors)) {
            $_SESSION['_errors'] = $errors;
            $this->back();
            return;
        }

        $data = $request->body();
        
        $existingUser = User::findByEmail($data['email']);
        if ($existingUser && $existingUser['id'] != $user['id']) {
            $_SESSION['_errors'] = ['email' => ['Email already in use.']];
            $this->back();
            return;
        }

        $updateData = [
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null
        ];

        $file = $request->file('avatar');
        if ($file && $file['error'] === UPLOAD_ERR_OK) {
            $avatar = $this->uploadFile($file, 'avatars', ['jpg', 'jpeg', 'png', 'gif']);
            if ($avatar) {
                $updateData['avatar'] = $avatar;
            }
        }

        User::update($user['id'], $updateData);

        $student = Student::findByUserId($user['id']);
        if ($student) {
            Student::update($student['id'], [
                'address' => $data['address'] ?? null,
                'guardian_name' => $data['guardian_name'] ?? null,
                'guardian_phone' => $data['guardian_phone'] ?? null,
                'guardian_email' => $data['guardian_email'] ?? null
            ]);
        }

        Auth::refresh();

        $this->flash('success', 'Profile updated successfully.');
        $this->redirect('/student/profile');
    }

    public function updatePassword(Request $request): void
    {
        $user = Auth::user();
        
        $errors = $this->validate($request->body(), [
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed'
        ]);

        if (!empty($errors)) {
            $_SESSION['_errors'] = $errors;
            $this->back();
            return;
        }

        $data = $request->body();
        
        $db = Database::getInstance();
        $userRecord = $db->fetch("SELECT password FROM users WHERE id = ?", [$user['id']]);
        
        if (!Auth::verifyPassword($data['current_password'], $userRecord['password'])) {
            $_SESSION['_errors'] = ['current_password' => ['Current password is incorrect.']];
            $this->back();
            return;
        }

        $hashedPassword = Auth::hashPassword($data['password']);
        $db->query("UPDATE users SET password = ? WHERE id = ?", [$hashedPassword, $user['id']]);

        $this->flash('success', 'Password updated successfully.');
        $this->redirect('/student/profile');
    }
}
