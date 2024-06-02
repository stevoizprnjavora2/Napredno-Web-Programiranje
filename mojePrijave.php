<?php
session_start();

if (!isset($_SESSION['ulogiran']) || !isset($_SESSION['korisnik_id'])) {
    header('Location: login.php');
    exit;
}
require_once 'database.php';

$korisnikId = $_SESSION['korisnik_id'];

$query = "SELECT t.id, t.datum_kreiranja, t.naslov, t.kategorija, t.prioritet, t.status, COALESCE(a.korisnicko_ime, 'Nije preuzeto') as preuzeo
          FROM tiketi t
          LEFT JOIN korisnici a ON t.admin_id = a.id
          WHERE t.korisnik_id = ?
          ORDER BY t.datum_kreiranja DESC";
$stmt = $pdo->prepare($query);
$stmt->execute([$korisnikId]);
$tiketi = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" type="text/css" href="style.css">
    <title> Index </title>
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
    
<script>
function openTicketDetails(ticketId) {
    window.location.href = 'korisnickiDetaljiTiketa.php?id=' + ticketId;
}
</script>

<div class="ticket-container">
    <h2>Moje prijave</h2>
    <table class="tickets-table">
        <tr>
            <th>ID</th>
            <th>Datum</th>
            <th>Naslov</th>
            <th>Vrsta</th>
            <th>Prioritet</th>
            <th>Status</th>
            <th>Preuzeo</th>
        </tr>
        <?php foreach ($tiketi as $tiket): ?>
        <tr onclick="openTicketDetails(<?php echo $tiket['id']; ?>)">
            <td><?php echo htmlspecialchars($tiket['id']); ?></td>
            <td><?php echo htmlspecialchars($tiket['datum_kreiranja']); ?></td>
            <td><?php echo htmlspecialchars($tiket['naslov']); ?></td>
            <td><?php echo htmlspecialchars($tiket['kategorija']); ?></td>
            <td><?php echo htmlspecialchars($tiket['prioritet']); ?></td>
            <td><?php echo htmlspecialchars($tiket['status']); ?></td>
            <td><?php echo htmlspecialchars($tiket['preuzeo']); ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>


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
