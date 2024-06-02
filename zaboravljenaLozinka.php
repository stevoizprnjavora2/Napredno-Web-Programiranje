<?php
session_start();
require_once 'database.php'; 
require 'vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
    $email = $_POST['email'];

    $stmt = $pdo->prepare("SELECT id, email FROM KORISNICI WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if (!$user) {
        echo "No user found with that email.";
        exit;
    }

    $stmt = $pdo->prepare("SELECT * FROM postavke_emaila WHERE id = 1");
    $stmt->execute();
    $emailSettings = $stmt->fetch();

    if (!$emailSettings) {
        echo "Email settings are not configured.";
        exit;
    }

    $code = bin2hex(random_bytes(8)); 

    $stmt = $pdo->prepare("INSERT INTO password_resets (user_id, reset_code) VALUES (?, ?)");
    $stmt->execute([$user['id'], $code]);

    $mail = new PHPMailer\PHPMailer\PHPMailer();
    $mail->isSMTP();
    $mail->Host = $emailSettings['email_host'];
    $mail->SMTPAuth = true;
    $mail->Username = $emailSettings['email_username'];
    $mail->Password = $emailSettings['email_password'];
    $mail->SMTPSecure = 'tls';
    $mail->Port = $emailSettings['email_port'];

    $mail->setFrom($emailSettings['email_username'], $emailSettings['email_from_name']);
    $mail->addAddress($user['email']);

    $mail->isHTML(true);
    $mail->Subject = 'Reset Your Password';
    $mail->Body    = "Here is your password reset code: $code";

    if (!$mail->send()) {
        echo 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo;
    } else {
        header('Location: enter-code.php');
        exit;
    }
} else {
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <link rel="stylesheet" type="text/css" href="style.css">
        <meta charset="UTF-8">
        <title>Forgot Password</title>
    </head>
    <body>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <form action="zaboravljenaLozinka.php" method="post" class="login-form">
            <div>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div>
                <button type="submit">Send Reset Code</button>
            </div>
        </form>
    </body>
    </html>
    <?php
}
?>
