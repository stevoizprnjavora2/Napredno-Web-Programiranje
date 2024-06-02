<?php
session_start();

require_once 'database.php';

if (!isset($_SESSION['ulogiran'])) {
    header('Location: login.php');
    exit;
}

if (isset($_SESSION['odjel_id'])) {
    $odjel_ID = $_SESSION['odjel_id'];
} else {
    $stmt = $pdo->prepare("SELECT odjel_ID FROM korisnici WHERE id = ?");
    $stmt->execute([$_SESSION['korisnik_id']]);
    $odjel_ID = $stmt->fetchColumn();
    $_SESSION['odjel_id'] = $odjel_ID; 
}

$query = "SELECT t.id, k.korisnicko_ime AS izradio, t.datum_kreiranja, t.naslov, t.kategorija, t.prioritet, t.status, a.korisnicko_ime AS preuzeo 
          FROM tiketi t
          JOIN korisnici k ON t.korisnik_id = k.id
          JOIN korisnici a ON t.admin_id = a.id
          WHERE k.odjel_ID = ?  
          ORDER BY t.datum_kreiranja DESC";
$stmt = $pdo->prepare($query);
$stmt->execute([$odjel_ID]);
$tiketi = $stmt->fetchAll(PDO::FETCH_ASSOC);


?>


<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="style.css">
    <title> Nadzor </title>
</head>
<body>
    <div class="header-container">
        <div id="date-time"></div>
        <img src="slike/logo.png" alt="TSŽV Logo" style="height: 100px;"> 
        <div id="weather"></div>
    </div>
    <script src="script.js"></script>    

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
        <h2>Nadzor svih prijava</h2>
        <table class="tickets-table">
            <th>ID</th>
            <th>Username</th>
            <th>Datum Kreiranja</th>
            <th>Naslov</th>
            <th>Kategorija</th>
            <th>Prioritet</th>
            <th>Status</th>
            <th>Administrator</th>
        <tbody>
            <?php foreach ($tiketi as $tiket): ?>
            <tr>
                <td><?php echo htmlspecialchars($tiket['id']); ?></td>
                <td><?php echo htmlspecialchars($tiket['izradio']); ?></td>
                <td><?php echo htmlspecialchars($tiket['datum_kreiranja']); ?></td>
                <td><?php echo htmlspecialchars($tiket['naslov']); ?></td>
                <td><?php echo htmlspecialchars($tiket['kategorija']); ?></td>
                <td><?php echo htmlspecialchars($tiket['prioritet']); ?></td>
                <td><?php echo htmlspecialchars($tiket['status']); ?></td>
                <td><?php echo $tiket['preuzeo'] ? htmlspecialchars($tiket['preuzeo']) : 'Nije preuzeto'; ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
        </table>
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
