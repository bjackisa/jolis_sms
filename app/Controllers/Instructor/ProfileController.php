<?php
/**
 * Instructor Profile Controller
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
use App\Models\User;
use App\Models\Instructor;
use App\Models\UserSecurity;

class ProfileController extends Controller
{
    private function securityQuestions(): array
    {
        return [
            'What is the name of your first school?',
            'What is your mother\'s maiden name?',
            'What is the name of your first pet?',
            'In what city were you born?',
            'What is your favorite food?',
            'What is your favorite color?',
            'What was the name of your best friend in childhood?',
            'What is the name of the street you grew up on?'
        ];
    }

    public function index(Request $request): void
    {
        $user = Auth::user();
        $instructor = Instructor::findByUserId($user['id']);
        $security = UserSecurity::findByUserId((int)$user['id']);

        $this->view('instructor.profile.index', [
            'title' => 'My Profile - Instructor',
            'user' => $user,
            'instructor' => $instructor,
            'security' => $security,
            'securityQuestions' => $this->securityQuestions()
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

        $instructor = Instructor::findByUserId($user['id']);
        if ($instructor) {
            Instructor::update($instructor['id'], [
                'qualification' => $data['qualification'] ?? null,
                'specialization' => $data['specialization'] ?? null
            ]);
        }

        Auth::refresh();

        $this->flash('success', 'Profile updated successfully.');
        $this->redirect('/instructor/profile');
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
        $this->redirect('/instructor/profile');
    }

    public function updateSecurity(Request $request): void
    {
        $user = Auth::user();

        $errors = $this->validate($request->body(), [
            'secret_question' => 'required',
            'secret_answer' => 'required|min:2'
        ]);

        if (!empty($errors)) {
            $_SESSION['_errors'] = $errors;
            $this->back();
            return;
        }

        $question = trim((string)$request->input('secret_question'));

        if (!in_array($question, $this->securityQuestions(), true)) {
            $_SESSION['_errors'] = ['secret_question' => ['Please select a valid secret question.']];
            $this->back();
            return;
        }

        $answer = strtolower(trim((string)$request->input('secret_answer')));
        $answerHash = password_hash($answer, PASSWORD_BCRYPT, ['cost' => PASSWORD_COST]);

        UserSecurity::upsertForUser((int)$user['id'], $question, $answerHash);

        $this->flash('success', 'Security question updated successfully.');
        $this->redirect('/instructor/profile');
    }
}
