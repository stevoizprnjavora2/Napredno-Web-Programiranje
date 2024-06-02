<?php
session_start();
require_once 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_password = $_POST['new_password'] ?? null;
    $confirm_password = $_POST['confirm_password'] ?? null;

    if ($new_password !== $confirm_password) {
        echo "Drugi upis lozinke, ne odgovara prvom.";
        exit;
    }

    if (!isset($_SESSION['reset_code']) || !isset($_SESSION['user_id'])) {
        echo "Ključ za resetiranje lozinke nije pronađen. Molimo ponovno pokrenite postupak resetiranja lozinke.";
        exit;
    }

    $stmt = $pdo->prepare("SELECT user_id FROM password_resets WHERE reset_code = ?");
    $stmt->execute([$_SESSION['reset_code']]);
    if ($stmt->rowCount() == 0) {
        echo "Netočan reset kod.";
        exit;
    }
    $user_id = $stmt->fetchColumn();

    $new_password_hashed = password_hash($new_password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("UPDATE KORISNICI SET lozinka = ? WHERE id = ?");
    $stmt->execute([$new_password_hashed, $_SESSION['user_id']]);

    unset($_SESSION['reset_code'], $_SESSION['user_id']);

    header('Location: login.php');
    exit;
} else {
    echo "Invalid request method.";
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="style.css">
    <meta charset="UTF-8">
    <title>Reset Password</title>
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
    <form action="resetLozinka.php" method="post" class="login-form">
    
        <div>
            <label for="new_password">New Password:</label>
            <input type="password" id="new_password" name="new_password" required>
        </div>
        <div>
            <label for="confirm_password">Confirm Password:</label>
            <input type="password" id="confirm_password" name="confirm_password" required>
        </div>
        <div>
            <button type="submit">Reset Password</button>
        </div>
    </form>
</body>
</html>
