<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Loads PHPMailer automatically

$mail = new PHPMailer(true);

try {
    // SMTP Configuration
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com'; // Use Gmail's SMTP server
    $mail->SMTPAuth = true;
    $mail->Username = 'prebhme@gmail.com'; // Your Gmail email
    $mail->Password = 'hfnn wlvq klsh hyxx'; // Use an App Password (NOT your real password)
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    // Email Headers
    $mail->setFrom('your-email@gmail.com', 'Dog Rescue System');
    $mail->addAddress('rescuer@example.com'); // Recipient email

    // Email Content
    $mail->isHTML(true);
    $mail->Subject = 'Rescue Report Submitted';
    $mail->Body    = '<h2>A new rescue report has been submitted.</h2>';

    // Send Email
    $mail->send();
    echo 'Email sent successfully!';
} catch (Exception $e) {
    echo "Email could not be sent. Error: {$mail->ErrorInfo}";
}
?>
