<?php

namespace Utils;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mailer
{
    private $mailer;

    public function __construct()
    {
        $this->mailer = new PHPMailer(true);

        try {
            // Server settings
            $this->mailer->isSMTP();                                      // Use SMTP
            $this->mailer->Host = 'smtp.example.com';                     // SMTP server
            $this->mailer->SMTPAuth = true;                               // Enable authentication
            $this->mailer->Username = 'your-email@example.com';           // SMTP username
            $this->mailer->Password = 'your-email-password';              // SMTP password
            $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;   // Encryption
            $this->mailer->Port = 587;                                    // TCP port

            // Sender info
            $this->mailer->setFrom('your-email@example.com', 'Your App Name');
        } catch (Exception $e) {
            error_log("Mailer setup error: " . $e->getMessage());
        }
    }

    /**
     * Sends a verification email to the user.
     *
     * @param string $recipientEmail The recipient's email address
     * @param string $token The verification token
     * @return bool Whether the email was sent successfully
     */
    public function sendVerificationEmail(string $recipientEmail, string $token): bool
    {
        try {
            $this->mailer->addAddress($recipientEmail); // Add recipient
            $this->mailer->isHTML(true);               // Set email format to HTML
            $this->mailer->Subject = 'Email Verification';

            // Email body
            $verificationLink = "http://localhost:8000/verify-email?token=$token";
            $this->mailer->Body = "
                <h1>Email Verification</h1>
                <p>Thank you for registering! Please verify your email by clicking the link below:</p>
                <a href='$verificationLink'>Verify Email</a>
            ";

            // Send the email
            $this->mailer->send();
            return true;
        } catch (Exception $e) {
            error_log("Mailer error: " . $e->getMessage());
            return false;
        }
    }
}
