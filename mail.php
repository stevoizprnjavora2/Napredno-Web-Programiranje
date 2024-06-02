<?php
session_start();
require_once 'database.php'; 
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sendEmailNotification($pdo, $toEmail, $subject, $message) {
    $mail = new PHPMailer(true);

    try {
        
        $stmt = $pdo->query("SELECT * FROM postavke_emaila WHERE id = 1");
        $settings = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$settings) {
            throw new Exception("Nisu pronađene postavke emaila u bazi podataka.");
        }

        $mail->isSMTP();
        $mail->Host = $settings['email_host'];
        $mail->SMTPAuth = true;
        $mail->Username = $settings['email_username'];
        $mail->Password = $settings['email_password'];
        $mail->SMTPSecure = 'tls';
        $mail->Port = $settings['email_port'];

        $mail->setFrom($settings['email_username'], $settings['email_from_name']);
        $mail->addAddress($toEmail);

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $message;

        $mail->send();
        echo 'Email sent successfully';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        error_log("Mailer Error: " . $mail->ErrorInfo);
    }
}

sendEmailNotification($pdo, 'dmitar224@gmail.com', 'Test Email Subject', 'This is a test email body.');

?>
