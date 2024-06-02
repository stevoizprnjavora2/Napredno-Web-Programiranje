<?php
session_start();
require_once 'database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT id, lozinka, odjel_ID, ime, prezime, email FROM korisnici WHERE korisnicko_ime = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['lozinka'])) {
        $_SESSION['ulogiran'] = true;
        $_SESSION['korisnik_id'] = $user['id'];
        $_SESSION['korisnicko_ime'] = $username;
        $_SESSION['ime'] = $user['ime'];
        $_SESSION['prezime'] = $user['prezime'];
        $_SESSION['odjel_id'] = $user['odjel_ID'];
        $_SESSION['email'] = $user['email']; 

        
        $stmtModuli = $pdo->prepare("SELECT m.naziv FROM korisnikmoduli km JOIN moduli m ON km.modul_id = m.id WHERE km.korisnik_id = ?");
        $stmtModuli->execute([$_SESSION['korisnik_id']]);
        $_SESSION['moduli'] = $stmtModuli->fetchAll(PDO::FETCH_COLUMN);

        header("Location: index.php");
        exit;
    } 
    var_dump($user);
    if (!$user) {
        echo "No user found or password mismatch.";
    }
    else {
        header("Location: login.php?error=invalid_credentials");
        exit;
    }
} else {
    header("Location: login.php");
    exit;
}
?>

