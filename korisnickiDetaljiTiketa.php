<?php
session_start();

if (!isset($_SESSION['ulogiran'])) {
    header('Location: login.php');
    exit;
}

require_once 'database.php'; 

$tiketId = isset($_GET['id']) ? $_GET['id'] : header("Location: mojePrijave.php");

$stmt = $pdo->prepare("SELECT t.id, k.korisnicko_ime AS izradio, t.datum_kreiranja, t.naslov, t.kategorija, t.prioritet, t.status, a.korisnicko_ime AS preuzeo, t.opis
                       FROM tiketi t
                       LEFT JOIN korisnici k ON t.korisnik_id = k.id
                       LEFT JOIN korisnici a ON t.admin_id = a.id
                       WHERE t.id = ?");
$stmt->execute([$tiketId]);
$tiket = $stmt->fetch(PDO::FETCH_ASSOC);

$stmtOdgovori = $pdo->prepare("SELECT c.tekst AS odgovor, c.datum_vrijeme AS odgovor_datum, u.korisnicko_ime AS korisnik
                               FROM konverzacije c
                               LEFT JOIN korisnici u ON c.korisnik_id = u.id
                               WHERE c.tiket_id = ? 
                               ORDER BY c.datum_vrijeme ASC");
$stmtOdgovori->execute([$tiketId]);
$odgovori = $stmtOdgovori->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Detalji Tiketa</title>
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

    <div class="ticket-container2">
        <h2>Detalji Tiketa</h2>
        <div class="ticket-details">
            <p><strong>ID:</strong> <?php echo htmlspecialchars($tiket['id']); ?></p>
            <p><strong>Izradio:</strong> <?php echo htmlspecialchars($tiket['izradio']); ?></p>
            <p><strong>Datum kreiranja:</strong> <?php echo htmlspecialchars($tiket['datum_kreiranja']); ?></p>
            <p><strong>Naslov:</strong> <?php echo htmlspecialchars($tiket['naslov']); ?></p>
            <p><strong>Vrsta:</strong> <?php echo htmlspecialchars($tiket['kategorija']); ?></p>
            <p><strong>Prioritet:</strong> <?php echo htmlspecialchars($tiket['prioritet']); ?></p>
            <p><strong>Status:</strong> <?php echo htmlspecialchars($tiket['status']); ?></p>
            <p><strong>Preuzeo:</strong> <?php echo $tiket['preuzeo'] ? htmlspecialchars($tiket['preuzeo']) : 'Nije preuzeto'; ?></p>
            <p><strong>Opis:</strong> <?php echo nl2br(htmlspecialchars($tiket['opis'])); ?></p>
        </div>
    
        <input type="hidden" name="tiket_id" value="<?php echo $tiket['id']; ?>">
    </form>
    <br>
    <form action="korisnickiPosaljiOdgovor.php" method="post">
    <input type="hidden" name="tiket_id" value="<?php echo $tiket['id']; ?>">
    <textarea name="odgovor" class="form-group" rows="5" placeholder="Napišite svoj odgovor ovdje..."></textarea>
    <button type="submit" class="button">Pošalji Odgovor</button>
    </form>

    <div class="responses-container">
    <h3>Odgovori:</h3>
    <?php foreach ($odgovori as $odgovor): ?>
        <?php $responseClass = ($odgovor['korisnik'] == 'admin') ? 'admin-response' : 'user-response'; ?>
        <div class="response <?php echo $responseClass; ?>">
            <p><strong><?php echo htmlspecialchars($odgovor['korisnik']); ?>:</strong> <?php echo nl2br(htmlspecialchars($odgovor['odgovor'])); ?></p>
            <p><small><?php echo htmlspecialchars($odgovor['odgovor_datum']); ?></small></p>
        </div>
    <?php endforeach; ?>
    </div>





    </div>
    <br>
    <footer>
        <div class="footer-container">
            <p>&copy; 2024 GTZ. Sva prava pridržana.</p>
            <p>Email: <a href="mailto:info@gtz.com">info@gtz.com</a></p>
            <p><a href="oNama.html">O nama</a> | <a href="kontakt.html">Kontakt</a></p>
        </div>
    </footer>
</body>
</html>
