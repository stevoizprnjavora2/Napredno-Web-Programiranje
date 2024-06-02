<?php
$host = 'localhost';
$dbname = 'ticketingdatabase';
$user = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Ne mogu se povezati s bazom podataka: " . $e->getMessage();
    exit;
}
?>