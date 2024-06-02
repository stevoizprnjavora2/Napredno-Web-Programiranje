
<?php

session_start();
if (!isset($_SESSION['moduli'])) {
    $_SESSION['moduli'] = [];
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Popis Korisnika</title>
</head>
<body>

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
        <h2>Korisnici</h2>
        <table class="tickets-table">
            <tr>
                <th>ID</th>
                <th>Ime</th>
                <th>Prezime</th>
                <th>Username</th>
                <th>Email</th>
            </tr>
            <tbody>
            <?php

                if (!isset($_SESSION['ulogiran'])) {
                    header('Location: login.php');
                    exit;
                }

                ini_set('display_errors', 1);
                ini_set('display_startup_errors', 1);
                error_reporting(E_ALL);
                
                require_once 'database.php';
                try {
                    $query = "SELECT ID, ime, prezime, korisnicko_ime, email FROM Korisnici";
                    $stmt = $pdo->query($query);

                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $korisnikId = htmlspecialchars($row['ID']);
                        echo "<tr onclick='urediPrava($korisnikId)'>";
                        echo "<td>" . $korisnikId . "</td>";
                        echo "<td>" . htmlspecialchars($row['ime']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['prezime']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['korisnicko_ime']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                        echo "</tr>";
                    }
                    
                } catch (PDOException $e) {
                    echo "Greška pri dohvatu podataka: " . $e->getMessage();
                }
                ?>
                <script>
                function urediPrava(korisnikId) {
                    window.location.href = 'urediPrava.php?korisnikId=' + korisnikId;
                }
                </script>
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


