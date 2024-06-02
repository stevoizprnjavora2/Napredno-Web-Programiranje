<?php
session_start();

require_once 'database.php';

if (!isset($_SESSION['ulogiran'])) {
    header('Location: login.php');
    exit;
}

if (!in_array('postavke_emaila', $_SESSION['moduli'])) {
    header('Location: index.php');
    exit;
}

$currentSettings = [];
$stmt = $pdo->query("SELECT * FROM postavke_emaila WHERE id = 1");
if ($stmt) {
    $currentSettings = $stmt->fetch(PDO::FETCH_ASSOC);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Postavke Emaila</title>
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

        <h2>Email Postavke</h2>
        <form action="emailSettings.php" method="post">
            <div class="form-group">
                <label for="host">SMTP Host:</label>
                <input type="text" id="host" name="host" value="<?php echo htmlspecialchars($currentSettings['email_host'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="port">Port:</label>
                <input type="text" id="port" name="port" value="<?php echo htmlspecialchars($currentSettings['email_port'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($currentSettings['email_username'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" value="<?php echo htmlspecialchars($currentSettings['email_password'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="from_name">From Name:</label>
                <input type="text" id="from_name" name="from_name" value="<?php echo htmlspecialchars($currentSettings['email_from_name'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <button type="submit" class="button">Spremi Promjene</button>
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
