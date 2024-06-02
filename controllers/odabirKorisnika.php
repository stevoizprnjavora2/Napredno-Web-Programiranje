<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Popis Korisnika</title>
</head>
<body>
    <div class="header-container">
        <div id="date-time"></div>
        <h1>TSŽV</h1>
        <div id="weather"></div>
    </div>
    
    <div class="navbar"> 
        <a href="mojProfil.html">Moj profil</a>
        <a href="svePrijave.html">Tiketi</a>
        <a href="odjava.html">Odjava</a>
    </div>
    <div class="ticket-container">
        <h2>Korisnici</h2>
        <table class="tickets-table">
            <tr>
                <th>ID</th>
                <th>Ime</th>
                <th>Prezime</th>
                <th>Username</th>
            </tr>
            <tbody>
            <?php
                ini_set('display_errors', 1);
                ini_set('display_startup_errors', 1);
                error_reporting(E_ALL);
                
                require_once 'database.php';
                try {
                    $query = "SELECT ID, ime, prezime, korisnicko_ime FROM Korisnici";
                    $stmt = $pdo->query($query);

                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['ID']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['ime']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['prezime']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['korisnicko_ime']) . "</td>";
                        echo "</tr>";
                    }
                } catch (PDOException $e) {
                    echo "Greška pri dohvatu podataka: " . $e->getMessage();
                }
                ?>
            </tbody>
        </table>
    </div>
    <script src="script.js"charset="UTF-8"></script>
</body>
</html>


