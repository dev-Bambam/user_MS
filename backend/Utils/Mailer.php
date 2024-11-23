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
            $this->mailer->Host = 'smtp.gmail.com';                     // SMTP server
            $this->mailer->SMTPAuth = true;                               // Enable authentication
            $this->mailer->Username = 'ayogood18@gmail.com';           // SMTP username
            $this->mailer->Password = 'iiiw ulvu jrwh ivyv';              // SMTP password
            $this->mailer->Port = 465;
            $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // For SSL

            // Sender info
            $this->mailer->setFrom('ayogood18@gmail.com', 'PHPMailer');
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

            $this->mailer->Body = <<<EOT
    <html>
    <head>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f8f9fa;
                margin: 0;
                padding: 0;
            }
            .container {
                width: 100%;
                max-width: 600px;
                margin: 0 auto;
                background-color: #ffffff;
                padding: 20px;
                border-radius: 8px;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            }
            h1 {
                color: #4CAF50;
                font-size: 24px;
            }
            p {
                font-size: 16px;
                color: #333;
            }
            a {
                display: inline-block;
                padding: 12px 24px;
                background-color: #4CAF50;
                color: white;
                text-decoration: none;
                font-weight: bold;
                border-radius: 4px;
                margin-top: 10px;
            }
            a:hover {
                background-color: #45a049;
            }
        </style>
    </head>
    <body>
        <div class='container'>
            <h1>Email Verification</h1>
            <p>Thank you for registering! Please verify your email by clicking the link below:</p>
            <a href='$verificationLink'>Verify Email</a>
            <p>If you did not register, please ignore this email.</p>
        </div>
    </body>
    </html>
EOT;

            // Send the email
            $this->mailer->send();
            return true;
        } catch (Exception $e) {
            error_log("Mailer error: " . $e->getMessage());
            return false;
        }
    }
}
