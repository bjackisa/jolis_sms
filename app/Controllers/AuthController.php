<?php
/**
 * Auth Controller - Authentication
 * 
 * @developer   Jackisa Daniel Barack
 * @email       barackdanieljackisa@gmail.com
 * @website     jackisa.com
 * @quote       "One man and God are Majority"
 * @rights      All rights reserved
 */

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Auth;
use App\Core\Database;
use App\Core\Mailer;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin(Request $request): void
    {
        $this->view('auth.login', [
            'title' => 'Login - Jolis SMS'
        ]);
    }

    public function login(Request $request): void
    {
        $errors = $this->validate($request->body(), [
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);

        if (!empty($errors)) {
            $_SESSION['_errors'] = $errors;
            $_SESSION['_old'] = $request->body();
            $this->back();
            return;
        }

        $email = $request->input('email');
        $password = $request->input('password');
        $remember = $request->input('remember') === 'on';

        if (Auth::attempt($email, $password)) {
            User::updateLastLogin(Auth::id());
            
            if ($remember) {
                $token = Auth::generateToken();
                User::update(Auth::id(), ['remember_token' => $token]);
                setcookie('remember_token', $token, time() + (86400 * 30), '/');
            }

            $intendedUrl = $_SESSION['_intended_url'] ?? null;
            unset($_SESSION['_intended_url']);

            $role = Auth::role();
            
            if ($intendedUrl) {
                $this->redirect($intendedUrl);
            } elseif ($role === 'instructor') {
                $this->redirect('/instructor/dashboard');
            } else {
                $this->redirect('/student/dashboard');
            }
        } else {
            $this->flash('error', 'Invalid email or password.');
            $_SESSION['_old'] = ['email' => $email];
            $this->back();
        }
    }

    public function logout(Request $request): void
    {
        if (isset($_COOKIE['remember_token'])) {
            setcookie('remember_token', '', time() - 3600, '/');
        }
        
        Auth::logout();
        $this->redirect('/login');
    }

    public function showForgotPassword(Request $request): void
    {
        $this->view('auth.forgot-password', [
            'title' => 'Forgot Password - Jolis SMS'
        ]);
    }

    public function forgotPassword(Request $request): void
    {
        $errors = $this->validate($request->body(), [
            'email' => 'required|email'
        ]);

        if (!empty($errors)) {
            $_SESSION['_errors'] = $errors;
            $this->back();
            return;
        }

        $email = $request->input('email');
        $user = User::findByEmail($email);

        if ($user) {
            $token = Auth::generateToken();
            $db = Database::getInstance();
            
            $db->query("DELETE FROM password_resets WHERE email = ?", [$email]);
            $db->insert('password_resets', [
                'email' => $email,
                'token' => $token
            ]);

            $resetLink = APP_URL . '/reset-password/' . $token;
            
            try {
                $mailer = new Mailer();
                $mailer->to($email, $user['first_name'] . ' ' . $user['last_name'])
                       ->subject('Password Reset - Jolis SMS')
                       ->body("
                           <h2>Password Reset Request</h2>
                           <p>Hello {$user['first_name']},</p>
                           <p>You have requested to reset your password. Click the link below to proceed:</p>
                           <p><a href='{$resetLink}'>{$resetLink}</a></p>
                           <p>This link will expire in 1 hour.</p>
                           <p>If you did not request this, please ignore this email.</p>
                       ")
                       ->send();
            } catch (\Exception $e) {
                // Log error but don't expose it
            }
        }

        $this->flash('success', 'If an account exists with that email, you will receive a password reset link.');
        $this->redirect('/forgot-password');
    }

    public function showResetPassword(Request $request): void
    {
        $token = $request->param('token');
        
        $db = Database::getInstance();
        $reset = $db->fetch(
            "SELECT * FROM password_resets WHERE token = ? AND created_at > DATE_SUB(NOW(), INTERVAL 1 HOUR)",
            [$token]
        );

        if (!$reset) {
            $this->flash('error', 'Invalid or expired reset link.');
            $this->redirect('/forgot-password');
            return;
        }

        $this->view('auth.reset-password', [
            'title' => 'Reset Password - Jolis SMS',
            'token' => $token
        ]);
    }

    public function resetPassword(Request $request): void
    {
        $errors = $this->validate($request->body(), [
            'token' => 'required',
            'password' => 'required|min:8|confirmed'
        ]);

        if (!empty($errors)) {
            $_SESSION['_errors'] = $errors;
            $this->back();
            return;
        }

        $token = $request->input('token');
        $password = $request->input('password');

        $db = Database::getInstance();
        $reset = $db->fetch(
            "SELECT * FROM password_resets WHERE token = ? AND created_at > DATE_SUB(NOW(), INTERVAL 1 HOUR)",
            [$token]
        );

        if (!$reset) {
            $this->flash('error', 'Invalid or expired reset link.');
            $this->redirect('/forgot-password');
            return;
        }

        $user = User::findByEmail($reset['email']);
        
        if ($user) {
            $hashedPassword = Auth::hashPassword($password);
            
            $db->query("UPDATE users SET password = ? WHERE id = ?", [$hashedPassword, $user['id']]);
            $db->query("DELETE FROM password_resets WHERE email = ?", [$reset['email']]);

            $this->flash('success', 'Your password has been reset successfully. Please login.');
            $this->redirect('/login');
        } else {
            $this->flash('error', 'User not found.');
            $this->redirect('/forgot-password');
        }
    }
}
