<?php
/**
 * Home Controller - Landing Website
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
use App\Core\Mailer;
use App\Models\Setting;
use App\Models\ClassModel;
use App\Models\Student;
use App\Models\Instructor;
use App\Models\Subject;

class HomeController extends Controller
{
    public function index(Request $request): void
    {
        $stats = [
            'classes' => ClassModel::count("status = 'active'"),
            'students' => Student::count(),
            'instructors' => Instructor::count(),
            'subjects' => Subject::count("status = 'active'")
        ];

        $this->view('home.index', [
            'title' => 'Welcome to Jolis SMS',
            'stats' => $stats,
            'schoolName' => Setting::get('school_name', 'Jolis ICT Academy'),
            'schoolMotto' => Setting::get('school_motto', 'Excellence Through Technology')
        ]);
    }

    public function about(Request $request): void
    {
        $this->view('home.about', [
            'title' => 'About Us - Jolis SMS',
            'schoolName' => Setting::get('school_name', 'Jolis ICT Academy'),
            'schoolMotto' => Setting::get('school_motto', 'Excellence Through Technology')
        ]);
    }

    public function contact(Request $request): void
    {
        $this->view('home.contact', [
            'title' => 'Contact Us - Jolis SMS',
            'schoolName' => Setting::get('school_name', 'Jolis ICT Academy'),
            'schoolEmail' => Setting::get('school_email', 'info@jolis.academy'),
            'schoolPhone' => Setting::get('school_phone', '+256702860347'),
            'schoolAddress' => Setting::get('school_address', 'Akright City, Entebbe')
        ]);
    }

    public function sendContact(Request $request): void
    {
        if (!$this->verifyRecaptcha($request)) {
            $_SESSION['_old'] = $request->body();
            $this->back();
            return;
        }

        $errors = $this->validate($request->body(), [
            'name' => 'required|min:2',
            'email' => 'required|email',
            'subject' => 'required|min:5',
            'message' => 'required|min:10'
        ]);

        if (!empty($errors)) {
            $_SESSION['_errors'] = $errors;
            $_SESSION['_old'] = $request->body();
            $this->back();
            return;
        }

        $data = $request->body();
        
        $db = \App\Core\Database::getInstance();
        
        try {
            $db->insert('contact_messages', [
                'name' => $data['name'],
                'email' => $data['email'],
                'subject' => $data['subject'],
                'message' => $data['message'],
                'status' => 'new',
                'ip_address' => $_SERVER['REMOTE_ADDR'] ?? null,
                'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? null
            ]);
            
            try {
                $mailer = new Mailer();
                $mailer->to(Setting::get('school_email', 'info@jolis.academy'))
                       ->subject('Contact Form: ' . $data['subject'])
                       ->body("
                           <h2>New Contact Form Submission</h2>
                           <p><strong>Name:</strong> {$data['name']}</p>
                           <p><strong>Email:</strong> {$data['email']}</p>
                           <p><strong>Subject:</strong> {$data['subject']}</p>
                           <p><strong>Message:</strong></p>
                           <p>{$data['message']}</p>
                       ")
                       ->send();
            } catch (\Exception $e) {
                // Email sending failed, but message is saved in database
            }
            
            $this->flash('success', 'Your message has been sent successfully! We will get back to you soon.');
        } catch (\Exception $e) {
            $this->flash('error', 'Failed to send message. Please try again later.');
        }

        $this->redirect('/contact');
    }
}
