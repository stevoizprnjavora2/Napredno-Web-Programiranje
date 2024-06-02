<?php
session_start();
require_once 'database.php';  

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reset_code = $_POST['reset_code'] ?? '';

    $stmt = $pdo->prepare("SELECT user_id FROM password_resets WHERE reset_code = ?");
    $stmt->execute([$reset_code]);
    $resetEntry = $stmt->fetch();

    if ($resetEntry) {
        $_SESSION['user_id'] = $resetEntry['user_id'];  
        $_SESSION['reset_code'] = $reset_code; 
        header('Location: resetLozinka.php'); 
        exit;
    } else {
        echo "Invalid reset code.";
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="style.css">
    <meta charset="UTF-8">
    <title>Invalid Code</title>
</head>
<body>
    <p>Invalid code provided. Please try again.</p>
    <a href="enter-code.php">Try Again</a>
</body>
</html>
