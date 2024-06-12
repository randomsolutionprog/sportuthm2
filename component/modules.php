<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
// Function to get the current URL
function getCurrentUrl() {
    // Get the protocol (http or https)
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";

    // Get the host (e.g., localhost or www.example.com)
    $host = $_SERVER['HTTP_HOST'];

    // Get the request URI (e.g., /sportuthm2/admin/dashboardAdmin.php)
    $requestUri = $_SERVER['REQUEST_URI'];

    // Construct the full URL
    $currentUrl = $protocol . $host . $requestUri;

    return $currentUrl;
}
// Function to generate a random salt
function generateSalt($length = 16) {
    // Characters allowed in the salt
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $salt = '';
    $max = strlen($chars) - 1;

    // Generate random characters for the salt
    for ($i = 0; $i < $length; $i++) {
        $salt .= $chars[random_int(0, $max)];
    }

    return $salt;
}

// Function to generate a random token
function generateToken() {
    return bin2hex(random_bytes(16)); // Generate a 32-character hexadecimal string
}

// Function to send email
function sendEmail($recipientEmail, $verificationToken, $matrixNo) {
    // Load Composer's autoloader
    require 'vendor/autoload.php';

    // Create a PHPMailer instance
    $mail = new PHPMailer(true);

    try {
        // SMTP configuration
        $mail->isSMTP();
        $mail->Host = 'sandbox.smtp.mailtrap.io'; // Your SMTP server address
        $mail->SMTPAuth = true;
        $mail->Username = '0f70cde2f61404'; // Your SMTP username
        $mail->Password = '1547433075bac0'; // Your SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 2525;

        // Email content
        $mail->setFrom('admin@sportuthm.edu.my', 'Sport UTHM Admin');
        $mail->addAddress($recipientEmail); // Recipient email
        $mail->isHTML(true);
        $mail->Subject = 'Email Verification for Sport UTHM';
        $mail->Body = 'Dear User,<br><br>Please click the following link to verify your email address:<br><br>';
        $mail->Body .= '<a href="http://localhost/sportuthm2/verifyUser_email.php?token=' . $verificationToken . '&matrix=' . $matrixNo . '">Verify Email</a>';
        $mail->AltBody = 'Dear User,\n\nPlease visit the following link to verify your email address:\n\n';
        $mail->AltBody .= 'http://localhost/sportuthm2/verifyUser_email.php?token=' . $verificationToken . '&matrix=' . $matrixNo;

        // Send the email
        $mail->send();
        return true; // Email sent successfully
    } catch (Exception $e) {
        return false; // Error sending email
    }
}
function sendResetEmail($recipientEmail, $verificationToken, $matrixNo) {
    // Load Composer's autoloader
    require 'vendor/autoload.php';

    // Create a PHPMailer instance
    $mail = new PHPMailer(true);

    try {
        // SMTP configuration
        $mail->isSMTP();
        $mail->Host = 'sandbox.smtp.mailtrap.io'; // Your SMTP server address
        $mail->SMTPAuth = true;
        $mail->Username = '0f70cde2f61404'; // Your SMTP username
        $mail->Password = '1547433075bac0'; // Your SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 2525;

        // Email content
        $mail->setFrom('admin@sportuthm.edu.my', 'Sport UTHM Admin');
        $mail->addAddress($recipientEmail); // Recipient email
        $mail->isHTML(true);
        $mail->Subject = 'Email Verification for Sport UTHM';
        $mail->Body = 'Dear User,<br><br>Please click the following link to change your password:<br><br>';
        $mail->Body .= '<a href="http://localhost/sportuthm2/verifyUser_password.php?token=' . $verificationToken . '&matrix=' . $matrixNo . '">Verify Email</a>';
        $mail->AltBody = 'Dear User,\n\nPlease visit the following link to verify your email address:\n\n';
        $mail->AltBody .= 'http://localhost/sportuthm2/verifyUser_password.php?token=' . $verificationToken . '&matrix=' . $matrixNo;

        // Send the email
        $mail->send();
        return true; // Email sent successfully
    } catch (Exception $e) {
        return false; // Error sending email
    }
}
?>