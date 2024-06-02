<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require 'database.php'; 

$stmt = $pdo->query("SELECT email_username, email_password, email_host FROM postavke_emaila WHERE id = 1");
$settings = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$settings) {
    error_log("Nisu pronađene postavke emaila u bazi podataka.");
    die('Failed to fetch email settings.');
}

$hostname = '{' . $settings['email_host'] . ':993/imap/ssl}INBOX';
$username = $settings['email_username'];
$password = $settings['email_password'];

$inbox = imap_open($hostname, $username, $password) or die('Cannot connect to email: ' . imap_last_error());

$emails = imap_search($inbox, 'UNSEEN');
if (!$emails) {
    echo "Nema novih emailova.<br>";
} else {
    foreach($emails as $email_number) {
        $overview = imap_fetch_overview($inbox, $email_number, 0);
        $htmlMessage = imap_fetchbody($inbox, $email_number, 2);
        $message = getTextFromHtml($htmlMessage); 

        if(preg_match('/#(\d+)/', $overview[0]->subject, $matches)) {
            $ticketId = $matches[1];
            saveReplyToDatabase($pdo, $ticketId, $message);
            imap_setflag_full($inbox, $email_number, "\\Seen");
        }
    }
}
imap_close($inbox);

function getTextFromHtml($html) {
    $dom = new DOMDocument();
    @$dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
    $script = $dom->getElementsByTagName('script');

    while ($script->length > 0) {
        $script->item(0)->parentNode->removeChild($script->item(0));
    }

    $style = $dom->getElementsByTagName('style');
    while ($style->length > 0) {
        $style->item(0)->parentNode->removeChild($style->item(0));
    }

    foreach ($dom->childNodes as $item) {
        if ($item->nodeType == XML_COMMENT_NODE) {
            $item->parentNode->removeChild($item);
        }
    }

    $textContent = $dom->textContent;
    $textContent = quoted_printable_decode($textContent);  
    $textContent = preg_replace('/=\\n/', '', $textContent); 
    $textContent = preg_replace('/\\s+/', ' ', $textContent);

    return trim($textContent);
}



function saveReplyToDatabase($pdo, $ticketId, $message) {
    
    $pattern = '/(.*?)(?:(pon|uto|sri|čet|pet|sub|ned), \d{1,2}\. (sij|velj|ožu|tra|svi|lip|srp|kol|ruj|lis|stu|pro) \d{4}\. u \d{1,2}:\d{2})/s';
    if (preg_match($pattern, $message, $matches)) {
        $message = trim($matches[1]); 
    }

    $message = quoted_printable_decode($message);
    $message = html_entity_decode($message);
    $message = strip_tags($message);
    $message = trim($message);

    $stmt = $pdo->prepare("SELECT korisnik_id FROM tiketi WHERE id = ?");
    $stmt->execute([$ticketId]);
    $korisnikId = $stmt->fetchColumn();

    if ($korisnikId) {
        $stmt = $pdo->prepare("INSERT INTO konverzacije (tiket_id, korisnik_id, tekst) VALUES (?, ?, ?)");
        if ($stmt->execute([$ticketId, $korisnikId, $message])) {
            echo "Uspješno spremljeno u bazu.<br>";
        } else {
            echo "Neuspješno spremanje u bazu.<br>";
        }
    } else {
        echo "Tiket ili korisnik ne postoji za ID: $ticketId";
    }
}



?>
