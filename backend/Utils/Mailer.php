<?php

namespace Utils;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Dotenv\Dotenv;

class Mailer
{
    private $mailer;

    public function __construct()
    {
        // Load environment variables from the .env file
        $dotenv = Dotenv::createImmutable(__DIR__ . '/..'); // Adjust the path if necessary
        $dotenv->load();

        // Get the configuration from the config/smtp.php file
        $config = require __DIR__ . '/../config/smtp.php';

        $this->mailer = new PHPMailer(true);

        try {
            // Server settings
            $this->mailer->isSMTP();                                      // Use SMTP
            $this->mailer->Host = $config['host'];                         // SMTP server
            $this->mailer->SMTPAuth = true;                               // Enable authentication
            $this->mailer->Username = $config['username'];                // SMTP username
            $this->mailer->Password = $config['password'];                // SMTP password
            $this->mailer->Port = $config['port'];                        // Port
            $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;     // For SSL

            // Sender info
            $this->mailer->setFrom($config['from_email'], $config['from_name']);
        } catch (Exception $e) {
            error_log("Mailer setup error: " . $e->getMessage());
        }
    }

    public function sendVerificationEmail(string $recipientEmail, string $username, string $token): bool
    {
        try {
            $templatePath = __DIR__ . '/../resources/views/emails/email_verification.html';
            $template = file_get_contents($templatePath);

            $verificationLink = "http://localhost:8000/verify-email?token=$token";
            $body = str_replace(['{{username}}', '{{verificationLink}}'], [$username, $verificationLink], $template);

            $this->mailer->addAddress($recipientEmail);
            $this->mailer->isHTML(true);
            $this->mailer->Subject = 'Email Verification';
            $this->mailer->Body = $body;

            $this->mailer->send();
            return true;
        } catch (\Exception $e) {
            error_log("Mailer error: " . $e->getMessage());
            return false;
        }
    }


}
