<?php

declare(strict_types=1);

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

require __DIR__ . '/vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = strip_tags(trim($_POST['name'] ?? ''));
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
    $message = trim($_POST['message'] ?? '');

    if (empty($name) || empty($email) || empty($message)) {
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
        $mail->setFrom('mail_1@pbodavaodelsur.com', 'SMTP Demo');
        $mail->addAddress('loumissy16@gmail.com');
        $mail->addReplyTo($email, $name);
        $mail->isHTML(true);
        $mail->Subject = 'SMTP Demo';
        $mail->Body = '<p><strong>Name:</strong> ' . htmlspecialchars($name) . '</p>' .
                      '<p><strong>Email:</strong> ' . htmlspecialchars($email) . '</p>' .
                      '<p><strong>Message:</strong></p><p>' . nl2br(htmlspecialchars($message)) . '</p>';
        $mail->AltBody = 'Name: ' . $name . chr(10) . 'Email: ' . $email . chr(10) . 'Message:' . chr(10) . $message;

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
} else {
    http_response_code(405);
    echo 'Method not allowed';
}
?>