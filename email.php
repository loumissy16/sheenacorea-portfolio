<?php

declare(strict_types=1);

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

require __DIR__ . '/vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo 'Method not allowed';
    exit;
}

$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$subject = $_POST['subject'] ?? '';
$message = $_POST['message'] ?? '';

if (empty($name) || empty($email) || empty($subject) || empty($message)) {
    http_response_code(400);
    echo 'Please fill in all fields';
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo 'Invalid email address';
    exit;
}

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = 'smtp.hostinger.com';
    $mail->Port = 587;
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Username = 'mail_1@pbodavaodelsur.com';
    $mail->Password = 'It10@2026';
    $mail->setFrom('mail_1@pbodavaodelsur.com', 'Portfolio Contact');
    $mail->addAddress('loumissy16@gmail.com');
    $mail->addReplyTo($email, $name);
    $mail->isHTML(true);
    $mail->Subject = 'Portfolio Contact: ' . $subject;
    $mail->Body = '<p><strong>Name:</strong> ' . htmlspecialchars($name) . '</p>' .
                  '<p><strong>Email:</strong> ' . htmlspecialchars($email) . '</p>' .
                  '<p><strong>Subject:</strong> ' . htmlspecialchars($subject) . '</p>' .
                  '<p><strong>Message:</strong></p><p>' . nl2br(htmlspecialchars($message)) . '</p>';
    $mail->AltBody = "Name: $name\nEmail: $email\nSubject: $subject\nMessage:\n$message";

    if ($mail->send()) {
        http_response_code(200);
        header('Location: success.html');
        exit;
    } else {
        http_response_code(500);
        echo 'Failed to send email';
    }
} catch (Exception $e) {
    http_response_code(500);
    echo 'Error: ' . $e->getMessage();
}
?>