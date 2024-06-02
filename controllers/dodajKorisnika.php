<?php
require_once 'database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ime = htmlspecialchars($_POST['ime']);
    $prezime = htmlspecialchars($_POST['prezime']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $username = htmlspecialchars($_POST['username']);
    $lozinka = password_hash($_POST['lozinka'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO Korisnici (ime, prezime, email, korisnicko_ime, lozinka) VALUES (?, ?, ?, ?, ?)";

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$ime, $prezime, $email, $username, $lozinka]);

        header('Location: uspjesnoDodan.php'); 

    } catch(PDOException $e) {
        echo "Greška pri dodavanju korisnika: " . $e->getMessage();
    }
}
?>
