<?php
session_start();
require_once 'database.php';
require_once 'send_email.php';

if (!isset($_SESSION['ulogiran']) || !isset($_SESSION['korisnik_id'])) {
    header('Location: login.php');
    exit;
}

$adminId = $_SESSION['korisnik_id'];
$tiketId = $_POST['tiket_id'];
$status = $_POST['status'];

if ($tiketId > 0) {
    $sql = "UPDATE tiketi SET admin_id = ?, status = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute([$adminId, $status, $tiketId])) {
        $stmt = $pdo->prepare("SELECT korisnik_id FROM tiketi WHERE id = ?");
        $stmt->execute([$tiketId]);
        $userId = $stmt->fetchColumn();

        if ($userId) {
            $stmt = $pdo->prepare("SELECT email FROM korisnici WHERE id = ?");
            $stmt->execute([$userId]);
            $userEmail = $stmt->fetchColumn();
        
            $stmtTiket = $pdo->prepare("SELECT naslov FROM tiketi WHERE id = ?");
            $stmtTiket->execute([$tiketId]);
            $tiketNaslov = $stmtTiket->fetchColumn();
        
            if ($userEmail) {
                $subject = "Promjena statusa vašeg tiketa";
                $message = "Vaš tiket <b>" . htmlspecialchars($tiketNaslov) . "</b> je sada u statusu <b>" . htmlspecialchars($status) . "</b>. Ovo je automatski generirana poruka.";
                if (sendEmailNotification($pdo, $userEmail, $subject, $message)) {
                    $_SESSION['email_status'] = "Email poslan.";
                } else {
                    $_SESSION['email_status'] = "Email nije poslan.";
                }
            }
        }
        
        header('Location: svePrijave.php?email_status=' . urlencode($_SESSION['email_status']));
        exit;
    } else {
        echo "Došlo je do greške prilikom preuzimanja tiketa.";
    }
} else {
    header('Location: svePrijave.php');
    exit;
}
?>
