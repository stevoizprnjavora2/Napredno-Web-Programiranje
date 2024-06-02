<?php
session_start();

if (!isset($_SESSION['ulogiran']) || !isset($_SESSION['moduli']) || !is_array($_SESSION['moduli'])) {
    header('Location: login.php');
    exit;
}

require_once 'database.php';


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" type="text/css" href="style.css">
    <title> Index </title>

    <style>
        .content {
            width: 80%; 
            margin: 0 auto; 
            padding: 20px; 
            background: #fff; 
            box-shadow: 0 2px 4px rgba(0,0,0,0.1); 
            border-radius: 8px; 
            margin-top: 20px; 
            margin-bottom: 20px; 
        }
        .content h1, .content h2 {
            text-align: center;
            color: #333;
        }
        .content p, .content ul {
            color: #666;
            line-height: 1.6;
        }
        .content ul {
            list-style-position: inside;
        }
        .content a {
            color: #0066cc;
        }
    </style>
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
    
<div class="content">
        <h1>Dobrodošli u GTZ Ticketing Sustav</h1>
        <p>GTZ Ticketing Sustav je vaš pouzdan partner u rješavanju svih poteškoća i izazova s kojima se možete susresti u našem radnom okruženju. Naša platforma omogućava brzu i efikasnu komunikaciju između vas i našeg tima za korisničku podršku, osiguravajući da vaše prijave, zahtjevi za javni poziv i sve ostale potrebe budu obrađeni na vrijeme i s maksimalnom pažnjom.</p>
        
        <h2>Kako možemo pomoći?</h2>
        <ul>
            <li><strong>Prijavite poteškoće:</strong> Susrećete se s tehničkim problemom ili imate pitanje vezano uz naše usluge? Jednostavno podnesite tiket i naši stručnjaci će se pobrinuti za vas.</li>
            <li><strong>Zahtjevi za javni poziv:</strong> Imate zahtjev koji zahtijeva javni poziv? Podnesite svoj zahtjev kroz naš sustav i pratite proces odobravanja.</li>
            <li><strong>Praćenje statusa:</strong> U svakom trenutku možete provjeriti status svojih prijava i zahtjeva, te primiti ažuriranja o napretku rješavanja.</li>
        </ul>
        
        <h2>Obratite nam se s povjerenjem</h2>
        <p>Naš tim za korisničku podršku stoji vam na raspolaganju za sve upite i pomoć. Ako imate dodatnih pitanja ili trebate pomoć pri korištenju našeg sustava, slobodno nas kontaktirajte na <a href="mailto:podrska@gtz.hr">podrska@gtz.hr</a>. Obećavamo brz odgovor i rješenje za sve vaše potrebe.</p>
        
        <h2>Zajedno gradimo bolje radno okruženje</h2>
        <p>GTZ Ticketing Sustav je više od platforme za podnošenje zahtjeva - to je alat koji omogućava bolju komunikaciju, bržu reakciju i efikasnije rješavanje problema. Naš cilj je osigurati da se svaki vaš glas čuje i da zajedno gradimo produktivnije i sretnije radno okruženje.</p>
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
