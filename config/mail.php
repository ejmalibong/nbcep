<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php'; // Load PHPMailer if installed via Composer

function sendEmail($to, $subject, $message)
{
    $mail = new PHPMailer(true);

    try {
        // SMTP Configuration
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com'; // Replace with your SMTP server
        $mail->SMTPAuth   = true;
        $mail->Username   = 'nbcsystem2022@gmail.com'; // Your SMTP email
        $mail->Password   = 'dzhrltadxjawfxzd'; // Your SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Use 'ssl' or 'tls'
        $mail->Port       = 587; // Common SMTP ports: 465 (SSL), 587 (TLS)

        // Sender and recipient
        $mail->setFrom('nbcsystem2022@gmail.com', 'NBC DTR Viewer');
        $mail->addAddress($to); // Recipient

        // Email content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = nl2br($message); // Convert new lines to <br>

        // Send email
        if ($mail->send()) {
            return true;
        }
    } catch (Exception $e) {
        error_log("Mail error: " . $mail->ErrorInfo);
        return false;
    }

    return false;
}
