<?php
// Dati di connessione al database
$servername = "localhost";   // Server del database, di solito localhost
$username = "root";          // Nome utente del database
$password = "";              // Password per il database
$dbname = "skinlab";         // Nome del database

// Crea la connessione
$conn = new mysqli($servername, $username, $password, $dbname);

// Controlla se la connessione è riuscita
if ($conn->connect_error) {
    // In caso di errore nella connessione, mostra il messaggio
    die("Connessione fallita: " . $conn->connect_error);
}
?>
