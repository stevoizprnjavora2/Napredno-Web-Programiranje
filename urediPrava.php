<?php
session_start();

if (!isset($_SESSION['ulogiran'])) {
    header('Location: login.php');
    exit;
}
require_once 'database.php';

$korisnikId = isset($_GET['korisnikId']) ? $_GET['korisnikId'] : null; 

$stmtPrava = $pdo->prepare("SELECT modul_id FROM KORISNIKMODULI WHERE korisnik_id = ?");
$stmtPrava->execute([$korisnikId]);
$korisnikovaPrava = $stmtPrava->fetchAll(PDO::FETCH_COLUMN);

$stmtModuli = $pdo->query("SELECT id, naziv FROM MODULI");
$moduli = $stmtModuli->fetchAll();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['snimiPromjene'])) {
    $poslanaPrava = $_POST['prava'] ?? [];

    foreach ($moduli as $modul) {
        if (!array_key_exists($modul['id'], $poslanaPrava) && in_array($modul['id'], $korisnikovaPrava)) {
            $pdo->prepare("DELETE FROM KORISNIKMODULI WHERE korisnik_id = ? AND modul_id = ?")->execute([$korisnikId, $modul['id']]);
        } elseif (array_key_exists($modul['id'], $poslanaPrava)) {
            if (!in_array($modul['id'], $korisnikovaPrava)) {
                $pdo->prepare("INSERT INTO KORISNIKMODULI (korisnik_id, modul_id) VALUES (?, ?)")->execute([$korisnikId, $modul['id']]);
            }
        }
    }

}

$stmtKorisnik = $pdo->prepare("SELECT ime, korisnicko_ime FROM KORISNICI WHERE id = ?");
$stmtKorisnik->execute([$korisnikId]);
$korisnik = $stmtKorisnik->fetch();

?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Uređivanje Prava</title>
    <script>
    function showConfirmationMessage() {
        var formSubmitted = <?php echo json_encode(isset($_POST['snimiPromjene'])); ?>;
        if (formSubmitted) {
            var messageDiv = document.getElementById('confirmationMessage');
            messageDiv.innerText = "Promjene su spremljene.";
            setTimeout(function() {
                messageDiv.innerText = '';
            }, 3000);
        }
    }
    </script>
</head>
<body onload="showConfirmationMessage();">
    <script src="script.js" charset="UTF-8"></script>
    <div class="header-container">
        <div id="date-time"></div>
        <img src="slike/logo.png" alt="TSŽV Logo" style="height: 100px;"> 
        <div id="weather"></div>
    </div>
    
    <div class="navbar">
        <?php if (in_array('mojePrijave', $_SESSION['moduli'])): ?>
            <a href="mojePrijave.php">Moje prijave</a>
        <?php endif; ?>

        <?php if (in_array('prijavaKvara', $_SESSION['moduli'])): ?>
            <a href="prijavaPoteskoce.php">Prijava poteškoće</a>
        <?php endif; ?>

        <?php if (in_array('upute', $_SESSION['moduli'])): ?>
            <a href="novosti.php">Upute</a>
        <?php endif; ?>

        <?php if (in_array('nadzor', $_SESSION['moduli'])): ?>
            <a href="nadzor.php">Nadzor</a>
        <?php endif; ?>

        <?php if (in_array('mojProfil', $_SESSION['moduli'])): ?>
            <a href="mojProfil.php">Moj profil</a>
        <?php endif; ?>

        <?php if (in_array('tiketi', $_SESSION['moduli'])): ?>
            <a href="svePrijave.php">Tiketi</a>
        <?php endif; ?>

        <?php if (in_array('dodajKorisnika', $_SESSION['moduli'])): ?>
            <a href="dodajKorisnika.php">Dodaj Korisnika</a>
        <?php endif; ?>

        <?php if (in_array('odabirKorisnika', $_SESSION['moduli'])): ?>
            <a href="odabirKorisnika.php">Prava pristupa</a>
        <?php endif; ?>

        <?php if (in_array('postavke_emaila', $_SESSION['moduli'])): ?>
            <a href="postavkeEmaila.php">Postavke</a>
        <?php endif; ?>

        <?php if (in_array('UputeAdmin', $_SESSION['moduli'])): ?>
            <a href="UputeAdmin.php">Uredi Upute </a>
        <?php endif; ?>

        <a href="odjava.php">Odjava</a>
</div>
    <div class="ticket-container">
        <h2>Dodjela prava korisniku: <?php echo htmlspecialchars($korisnik['korisnicko_ime']); ?></h2>
        <form method="post">
    <table class="tickets-table">
        <thead>
            <tr>
                <th>Modul</th>
                <th>Pravo Dodano</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($moduli as $modul): ?>
            <tr>
                <td><?php echo htmlspecialchars($modul['naziv']); ?></td>
                <td>
                    <input type="checkbox" name="prava[<?php echo $modul['id']; ?>]"
                        <?php echo in_array($modul['id'], $korisnikovaPrava) ? 'checked' : ''; ?>>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <div id="confirmationMessage" style="color: green; font-size: 16px; margin-top: 20px;"></div>
    <input type="hidden" name="korisnik_id" value="<?php echo $korisnikId; ?>">
    <input type="submit" name="snimiPromjene" value="Snimi Promjene">
</form>

    </div>
    <footer>
        <div class="footer-container">
            <p>&copy; 2024 GTZ. Sva prava pridržana.</p>
            <p>Email: <a href="mailto:info@gtz.com">info@gtz.com</a></p>
            <p><a href="oNama.html">O nama</a> | <a href="kontakt.html">Kontakt</a></p>
        </div>
    </footer>
</body>
</html>