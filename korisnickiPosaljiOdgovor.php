<?php
session_start();

if (!isset($_SESSION['ulogiran'])) {
    header('Location: login.php');
    exit;
}

require_once 'database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['tiket_id'], $_POST['odgovor'])) {
    $tiketId = $_POST['tiket_id'];
    $odgovor = $_POST['odgovor'];
    $userId = $_SESSION['korisnik_id'];

    $stmt = $pdo->prepare("INSERT INTO konverzacije (tiket_id, korisnik_id, tekst) VALUES (?, ?, ?)");
    if ($stmt->execute([$tiketId, $userId, $odgovor])) {
        header("Location: korisnickiDetaljiTiketa.php?id=" . $tiketId . "&success=1");
    } else {
        header("Location: korisnickiDetaljiTiketa.php?id=" . $tiketId . "&error=1");
    }
} else {
    header("Location: svePrijave.php");
    exit;
}
?>
