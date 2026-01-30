<?php
namespace App\Core;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Mailer
{
    private PHPMailer $mail;

    public function __construct()
    {
        $this->mail = new PHPMailer(true);
        
        $this->mail->isSMTP();
        $this->mail->Host = SMTP_HOST;
        $this->mail->SMTPAuth = true;
        $this->mail->Username = SMTP_USERNAME;
        $this->mail->Password = SMTP_PASSWORD;
        $this->mail->SMTPSecure = SMTP_ENCRYPTION === 'tls' ? PHPMailer::ENCRYPTION_STARTTLS : PHPMailer::ENCRYPTION_SMTPS;
        $this->mail->Port = SMTP_PORT;
        
        $this->mail->setFrom(SMTP_FROM_EMAIL, SMTP_FROM_NAME);
        $this->mail->isHTML(true);
    }

    public function to(string $email, string $name = ''): self
    {
        $this->mail->addAddress($email, $name);
        return $this;
    }

    public function subject(string $subject): self
    {
        $this->mail->Subject = $subject;
        return $this;
    }

    public function body(string $body): self
    {
        $this->mail->Body = $body;
        $this->mail->AltBody = strip_tags($body);
        return $this;
    }

    public function attachment(string $path, string $name = ''): self
    {
        $this->mail->addAttachment($path, $name);
        return $this;
    }

    public function send(): bool
    {
        try {
            return $this->mail->send();
        } catch (Exception $e) {
            if (APP_DEBUG) {
                error_log("Mailer Error: " . $this->mail->ErrorInfo);
            }
            return false;
        }
    }

    public function getError(): string
    {
        return $this->mail->ErrorInfo;
    }
}
