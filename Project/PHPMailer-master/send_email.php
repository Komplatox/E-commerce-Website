<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'C:/xampp/htdocs/PhpMailForm/PHPMailer-master/src/Exception.php';
require 'C:/xampp/htdocs/PhpMailForm/PHPMailer-master/src/PHPMailer.php';
require 'C:/xampp/htdocs/PhpMailForm/PHPMailer-master/src/SMTP.php';

$email = isset($_POST['email']) ? $_POST['email'] : null;
$subject = 'Confirmation Email';
$content = 'Click this link to complete your registration: http://localhost/support/PHPMailer-master/register.php';

if (!$email) {
    die("Please provide your email address.");
}

// PHPMailer Configuration
$mail = new PHPMailer(true);

try {
    // SMTP Settings
    $mail->SMTPDebug = 0; // Set debug level (0: off)
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'youremail@gmail.com'; // SMTP username
    $mail->Password = 'yourpassword'; // Gmail app password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port = 465;

    // UTF-8 and Language Settings
    $mail->CharSet = "UTF-8";
    $mail->setLanguage('en');

    // Sender and Recipient Settings
    $mail->setFrom('youremail@gmail.com', 'Sender Name');
    $mail->addAddress($email);  // Set recipient
    $mail->addReplyTo('recipientemail@gmail.com', 'Recipient Name');

    // Content Settings
    $mail->isHTML(true);
    $mail->Subject = $subject;
    $mail->Body = $content;

    // Send Email
    $mail->send();
    
    // Show PHP Alert
    echo "<script>
        alert('Your email has been sent successfully!');
        window.location.href = 'index.php';  // Redirect page
    </script>";
} catch (Exception $e) {
    echo "Message could not be sent. Error: {$mail->ErrorInfo}";
}
?>