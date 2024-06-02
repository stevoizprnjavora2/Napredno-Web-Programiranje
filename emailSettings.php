<?php
session_start();

require_once 'database.php';

if (!isset($_SESSION['ulogiran'])) {
    header('Location: login.php');
    exit;
}

if (!in_array('postavke_emaila', $_SESSION['moduli'])) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $host = $_POST['host'] ?? '';
    $port = $_POST['port'] ?? '';
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $from_name = $_POST['from_name'] ?? '';

    $stmtUpdate = $pdo->prepare("UPDATE postavke_emaila SET email_host = ?, email_port = ?, email_username = ?, email_password = ?, email_from_name = ? WHERE id = 1");
    $stmtUpdate->execute([$host, $port, $username, $password, $from_name]);

    header('Location: postavke_emaila.php?success=1');
    exit;
}

header('Location: postavke_emaila.php');
exit;
?>
