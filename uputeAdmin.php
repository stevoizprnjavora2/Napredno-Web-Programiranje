<?php
session_start();

if (!isset($_SESSION['ulogiran'])) {
    header('Location: login.php');
    exit;
}

require_once 'database.php';

if (!file_exists('files')) {
    mkdir('files', 0777, true);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['submit']) && $_POST['submit'] == 'uploadFile' && isset($_FILES['file'])) {
        $file = $_FILES['file'];
        $fileBaseName = basename($file['name']);
        $targetDirectory = 'files/';
        $targetFile = $targetDirectory . $fileBaseName;
        $customName = $_POST['nazivDatoteke']; 

        if (!file_exists($targetFile)) {
            if (move_uploaded_file($file['tmp_name'], $targetFile)) {
                echo "Datoteka " . htmlspecialchars($fileBaseName) . " je uspješno učitana.";

                $stmt = $pdo->prepare("INSERT INTO upute (naziv_datoteke, link) VALUES (:naziv_datoteke, :link)");
                $stmt->execute([
                    ':naziv_datoteke' => $customName,
                    ':link' => $targetFile
                ]);

                echo "Podaci o datoteci su uspješno spremljeni u bazu.";
            } else {
                echo "Došlo je do greške prilikom učitavanja datoteke.";
            }
        } else {
            echo "Datoteka već postoji.";
        }
    } elseif (isset($_POST['submit']) && $_POST['submit'] == 'uploadLink' && isset($_POST['nazivLinka'], $_POST['link'])) {
        $nazivLinka = $_POST['nazivLinka'];
        $link = $_POST['link'];

        $stmt = $pdo->prepare("INSERT INTO upute (naziv_datoteke, link) VALUES (:naziv_datoteke, :link)");
        $stmt->execute([
            ':naziv_datoteke' => $nazivLinka,
            ':link' => $link
        ]);
        echo "Link je uspješno spremljen u bazu.";
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete'])) {
    $selectedIds = $_POST['selected_ids'] ?? [];
    foreach ($selectedIds as $id) {
        $stmt = $pdo->prepare("SELECT link FROM upute WHERE id = ?");
        $stmt->execute([$id]);
        $link = $stmt->fetchColumn();

        if (file_exists($link)) {
            unlink($link);
        }

        $stmt = $pdo->prepare("DELETE FROM upute WHERE id = ?");
        $stmt->execute([$id]);
    }
}

$stmt = $pdo->query("SELECT id, naziv_datoteke, link FROM upute");
$upute = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>




<!DOCTYPE html>
<html lang = "en">
    <head>
        <link rel = "stylesheet" type = "text/css" href = "style.css">
        <title> Upute </title>
    </head>
    <body>
        <div class = "header-container">
            <div id = "date-time"></div>
            <img src = "slike/logo.png" alt = "TSŽV Logo" style = "height: 100px;">
            <div id = "weather"></div>
        </div>
        <script src = "script.js"></script>
        
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
    <h2>Datoteka</h2>
    <form action="" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="nazivDatoteke">Naziv datoteke:</label>
            <input type="text" id="nazivDatoteke" name="nazivDatoteke" required>
        </div>
        <div class="form-group">
            <label for="fileUpload">Odaberite datoteku za učitavanje:</label>
            <input type="file" id="fileUpload" name="file" required>
        </div>
        <button type="submit" name="submit" value="uploadFile" class="button">Učitaj Datoteku</button>
    </form>

    <h2>Link</h2>
    <form action="" method="post">
        <div class="form-group">
            <label for="nazivLinka">Naziv:</label>
            <input type="text" id="nazivLinka" name="nazivLinka">
        </div>
        <div class="form-group">
            <label for="link">Link:</label>
            <input type="text" id="link" name="link">
        </div>
        <button type="submit" name="submit" value="uploadLink" class="button">Učitaj Link</button>
    </form>
    <h2>Brisanje</h2>
    <form method="POST" action="">
        <table>
            <tr>
                <th>Select</th>
                <th>Naziv</th>
            </tr>
            <?php foreach ($upute as $uputa): ?>
            <tr>
                <td><input type="checkbox" name="selected_ids[]" value="<?= htmlspecialchars($uputa['id']); ?>"></td>
                <td><?= htmlspecialchars($uputa['naziv_datoteke']); ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
        <button type="submit" name="delete">Obriši odabrane</button>
    </form>

</div>

    </body>
    <footer>
        <div class = "footer-container">
        <p>&copy; 2024 GTZ. Sva prava pridržana.</p>
            <p>Email: <a href="mailto:info@gtz.com">info@gtz.com</a></p>
            <p><a href="oNama.html">O nama</a> | <a href="kontakt.html">Kontakt</a></p>
        </div>
            </footer>
</html>