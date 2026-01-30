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
            'schoolPhone' => Setting::get('school_phone', '+256700000000'),
            'schoolAddress' => Setting::get('school_address', 'Kampala, Uganda')
        ]);
    }

    public function sendContact(Request $request): void
    {
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
            
            $this->flash('success', 'Your message has been sent successfully!');
        } catch (\Exception $e) {
            $this->flash('success', 'Your message has been received. We will get back to you soon!');
        }

        $this->redirect('/contact');
    }
}
