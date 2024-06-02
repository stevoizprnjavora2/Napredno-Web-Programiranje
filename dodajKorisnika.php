<?php
session_start();
if (!isset($_SESSION['ulogiran'])) {
    header('Location: login.php');
    exit;
}
require_once 'database.php'; 

$stmtOdjeli = $pdo->query("SELECT id, naziv FROM Odjeli");
$odjeli = $stmtOdjeli->fetchAll();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ime = htmlspecialchars($_POST['ime']);
    $prezime = htmlspecialchars($_POST['prezime']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $username = htmlspecialchars($_POST['username']);
    $lozinka = password_hash($_POST['lozinka'], PASSWORD_DEFAULT); 
    $odjel_ID = $_POST['odjel_ID'];

    $sql = "INSERT INTO Korisnici (ime, prezime, email, korisnicko_ime, lozinka, odjel_ID) VALUES (?, ?, ?, ?, ?, ?)";

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$ime, $prezime, $email, $username, $lozinka, $odjel_ID]);

        $noviKorisnikId = $pdo->lastInsertId();
        
        $modulId1 = 9; 
        $modulId2 = 3; 
        $sqlPrava = "INSERT INTO korisnikmoduli (korisnik_id, modul_id) VALUES (?, ?)";
        $stmtPrava = $pdo->prepare($sqlPrava);
        $stmtPrava->execute([$noviKorisnikId, $modulId1]); 
        $stmtPrava->execute([$noviKorisnikId, $modulId2]); 
        

    } catch(PDOException $e) {
        echo "Greška pri dodavanju korisnika: " . $e->getMessage();
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css"> 
    <title>Dodavanje Novog Korisnika</title>
</head>
<body onload="showConfirmationMessage();">

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

<div class="login-form">
    <h2>Dodaj Novog Korisnika</h2>
    <form action="dodajKorisnika.php" method="POST">
        <div class= "form-group">
            <label for="ime">Ime:</label>
            <input type="text" id="ime" name="ime" required>
        </div>
        <div class="form-group">
            <label for="prezime">Prezime:</label>
            <input type="text" id="prezime" name="prezime" required>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="text" id="email" name="email">
        </div>
        <div class="form-group">
            <label for="username">Korisničko ime:</label>
            <input type="text" id="username" name="username" required>
        </div>
        <div class="form-group">
            <label for="lozinka">Lozinka:</label>
            <input type="password" id="lozinka" name="lozinka" required>
        </div>
        <div class="form-group">
                <label for="odjel_ID">Odjel:</label>
                <select id="odjel_ID" name="odjel_ID">
                    <?php foreach ($odjeli as $odjel): ?>
                        <option value="<?php echo htmlspecialchars($odjel['id']); ?>">
                            <?php echo htmlspecialchars($odjel['naziv']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <input type="submit" value="Dodaj Korisnika" name="dodajKorisnika">
        </form>
        <div id="confirmationMessage" style="color: green; font-size: 16px; margin-top: 20px;"></div>
        <script>
    function showConfirmationMessage() {
        var formSubmitted = <?php echo json_encode(isset($_POST['dodajKorisnika'])); ?>;
        if (formSubmitted) {
            var messageDiv = document.getElementById('confirmationMessage');
            messageDiv.innerText = "Novi korisnik je dodan.";
            setTimeout(function() {
                messageDiv.innerText = '';
            }, 3000);
        }
    }
    </script>
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

