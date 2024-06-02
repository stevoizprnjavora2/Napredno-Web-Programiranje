<?php
session_start();
require_once 'database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['korisnik_id'])) {
    $novaLozinka = $_POST['password'];
    $korisnikId = $_SESSION['korisnik_id'];

    $hashiranaLozinka = password_hash($novaLozinka, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("UPDATE korisnici SET lozinka = ? WHERE id = ?");
    $uspjeh = $stmt->execute([$hashiranaLozinka, $korisnikId]);

    if ($uspjeh) {
        echo "Lozinka je uspješno promijenjena.";
    } else {
        echo "Došlo je do greške pri promjeni lozinke.";
    }
} else {
    echo "Nedozvoljen pristup.";
}
?>
