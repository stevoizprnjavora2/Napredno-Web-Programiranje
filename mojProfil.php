<?php
session_start();

if (!isset($_SESSION['ulogiran'])) {
    header('Location: login.php');
    exit;
}
require_once 'database.php'; 

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" type="text/css" href="style.css">
    <title> Moj Profil </title>
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
    <div class="profile-container">
        <h2>Korisnički Profil</h2>
        <form action="updateProfile.php" method="post">
            <div class="form-group">
                <label for="ime">Ime:</label>
                <input type="text" id="ime" name="ime" value="<?php echo htmlspecialchars($_SESSION['ime'] ?? ''); ?>" readonly>
            </div>
            <div class="form-group">
                <label for="prezime">Prezime:</label>
                <input type="text" id="prezime" name="prezime" value="<?php echo htmlspecialchars($_SESSION['prezime'] ?? ''); ?>" readonly>
            </div>
            <div class="form-group">
                <label for="username">Korisničko ime:</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($_SESSION['korisnicko_ime'] ?? ''); ?>" readonly>
            </div>
            <div class="form-group">
                <label for="password">Nova lozinka:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <button type="submit" class="button">Promijeni Lozinku</button>
            </div>
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
