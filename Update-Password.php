<?php
session_start();
require 'database.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password != $confirm_password) {
        echo "Passwords do not match.";
        exit;
    }

    $user_id = $_SESSION['user_id'] ?? null;
    if (!$user_id) {
        echo "No user session found.";
        exit;
    }

    $new_password_hashed = password_hash($new_password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("UPDATE KORISNICI SET lozinka = ? WHERE id = ?");
    $stmt->execute([$new_password_hashed, $user_id]);

    header('Location: login.php');
    exit;
}
