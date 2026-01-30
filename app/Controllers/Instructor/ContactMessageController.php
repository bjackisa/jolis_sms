<?php
/**
 * Instructor Contact Message Controller
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
use App\Core\Mailer;
use App\Models\ContactMessage;
use App\Models\Instructor;

class ContactMessageController extends Controller
{
    public function index(Request $request): void
    {
        $user = Auth::user();
        $messages = ContactMessage::getAll();

        $this->view('instructor.contact_messages.index', [
            'title' => 'Contact Messages - Instructor',
            'user' => $user,
            'messages' => $messages
        ]);
    }

    public function show(Request $request): void
    {
        $user = Auth::user();
        $messageId = (int)$request->param('id');
        $message = ContactMessage::find($messageId);

        if (!$message) {
            $this->flash('error', 'Message not found.');
            $this->redirect('/instructor/contact-messages');
            return;
        }

        // Mark as read if it's new
        if ($message['status'] === 'new') {
            ContactMessage::markAsRead($messageId);
        }

        $this->view('instructor.contact_messages.show', [
            'title' => 'View Message - Instructor',
            'user' => $user,
            'message' => $message
        ]);
    }

    public function markAsRead(Request $request): void
    {
        $messageId = (int)$request->param('id');
        $message = ContactMessage::find($messageId);

        if (!$message) {
            $this->flash('error', 'Message not found.');
            $this->redirect('/instructor/contact-messages');
            return;
        }

        ContactMessage::markAsRead($messageId);
        $this->flash('success', 'Message marked as read.');
        $this->redirect('/instructor/contact-messages');
    }

    public function delete(Request $request): void
    {
        $messageId = (int)$request->param('id');
        $message = ContactMessage::find($messageId);

        if (!$message) {
            $this->flash('error', 'Message not found.');
            $this->redirect('/instructor/contact-messages');
            return;
        }

        ContactMessage::deleteMessage($messageId);
        $this->flash('success', 'Message deleted successfully.');
        $this->redirect('/instructor/contact-messages');
    }

    public function reply(Request $request): void
    {
        $user = Auth::user();
        $instructor = Instructor::findByUserId($user['id']);
        $messageId = (int)$request->param('id');
        $message = ContactMessage::find($messageId);

        if (!$message) {
            $this->flash('error', 'Message not found.');
            $this->redirect('/instructor/contact-messages');
            return;
        }

        $replyMessage = trim($request->input('reply_message'));

        if (empty($replyMessage)) {
            $this->flash('error', 'Please enter a reply message.');
            $this->redirect('/instructor/contact-messages/' . $messageId);
            return;
        }

        try {
            $instructorName = $user['first_name'] . ' ' . $user['last_name'];
            
            $emailBody = "
                <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
                    <h2 style='color: #0d6efd;'>Response to Your Message to Jolis SMS</h2>
                    <p>Dear {$message['name']},</p>
                    <p>Thank you for contacting us. Here is our response to your inquiry:</p>
                    <div style='background-color: #f8f9fa; padding: 15px; border-left: 4px solid #0d6efd; margin: 20px 0;'>
                        <p><strong>Your Original Message:</strong></p>
                        <p style='color: #6c757d;'>{$message['message']}</p>
                    </div>
                    <div style='background-color: #ffffff; padding: 15px; border: 1px solid #dee2e6; margin: 20px 0;'>
                        <p><strong>Our Response:</strong></p>
                        <p>" . nl2br(htmlspecialchars($replyMessage)) . "</p>
                    </div>
                    <p style='margin-top: 30px;'>Sincerely,</p>
                    <p style='margin: 5px 0;'><strong>{$instructorName}</strong><br>
                    Instructor, Jolis ICT Academy</p>
                    <hr style='border: 0; border-top: 1px solid #dee2e6; margin: 20px 0;'>
                    <p style='color: #6c757d; font-size: 12px;'>
                        Jolis ICT Academy<br>
                        Akright City, Entebbe, Wakiso<br>
                        Phone: +256702860347<br>
                        Email: info@jackisa.com
                    </p>
                </div>
            ";

            $mailer = new Mailer();
            $sent = $mailer->to($message['email'], $message['name'])
                          ->subject('Response to Your Message to Jolis SMS')
                          ->body($emailBody)
                          ->send();

            if ($sent) {
                ContactMessage::markAsReplied($messageId);
                $this->flash('success', 'Reply sent successfully!');
            } else {
                $this->flash('error', 'Failed to send reply. Please try again.');
            }
        } catch (\Exception $e) {
            $this->flash('error', 'Failed to send reply: ' . $e->getMessage());
        }

        $this->redirect('/instructor/contact-messages/' . $messageId);
    }
}
