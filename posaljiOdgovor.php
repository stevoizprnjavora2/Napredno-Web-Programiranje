<?php
session_start();
require_once 'database.php';
require_once 'send_email.php';  

if (!isset($_SESSION['ulogiran'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['tiket_id'], $_POST['odgovor'])) {
    $tiketId = $_POST['tiket_id'];
    $odgovor = $_POST['odgovor'];
    $userId = $_SESSION['korisnik_id']; 

    $stmt = $pdo->prepare("INSERT INTO konverzacije (tiket_id, korisnik_id, tekst) VALUES (?, ?, ?)");
    if ($stmt->execute([$tiketId, $userId, $odgovor])) {
        $stmt = $pdo->prepare("SELECT k.email, t.naslov FROM korisnici k JOIN tiketi t ON t.korisnik_id = k.id WHERE t.id = ?");
        $stmt->execute([$tiketId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $userEmail = $result['email'];
        $ticketTitle = $result['naslov'];

        if ($userEmail && $ticketTitle) {
            $subject = "Novi odgovor na vas tiket #$tiketId: " . $ticketTitle;
            $message = "Doslo je do novog odgovora na vas tiket <b>" . htmlspecialchars($ticketTitle) . "</b>. Tekst odgovora: <b>" . htmlspecialchars($odgovor) . "</b>";
            sendEmailNotification($pdo, $userEmail, $subject, $message);
        }

        header("Location: detaljiTiketa.php?id=" . $tiketId . "&success=1");
        exit;
    } else {
        header("Location: detaljiTiketa.php?id=" . $tiketId . "&error=1");
        exit;
    }
} else {
    header("Location: svePrijave.php");
    exit;
}

?>
