<?php
include "../includes/db_connect.php";
session_start();

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST["nome"];
    $cognome = $_POST["cognome"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $data_nascita = $_POST["data_nascita"];
    $telefono = $_POST["telefono"];
    $cf = $_POST["cf"];
    $tipo = $_POST["tipo"];
    $user = $_POST["username"];

    // Verifica che l'username non esista già
    $checkUsername = $conn->prepare("SELECT id FROM Login WHERE username = ?");
    
    // Controllo se la preparazione della query è andata a buon fine
    if ($checkUsername === false) {
        die("Errore nella preparazione della query: " . $conn->error);
    }
    
    $checkUsername->bind_param("s", $user);
    $checkUsername->execute();
    $checkUsername->store_result();

    if ($checkUsername->num_rows > 0) {
        $message = "⚠️ Username già in uso.";
    } else {
        // Verifica che email o cf non siano duplicati
        $checkUtente = $conn->prepare("SELECT id FROM Utenti WHERE email = ? OR cf = ?");
        
        // Controllo se la preparazione della query è andata a buon fine
        if ($checkUtente === false) {
            die("Errore nella preparazione della query: " . $conn->error);
        }
        
        $checkUtente->bind_param("ss", $email, $cf);
        $checkUtente->execute();
        $checkUtente->store_result();

        if ($checkUtente->num_rows > 0) {
            $message = "⚠️ Email o Codice Fiscale già registrati.";
        } else {
            // Tutto ok, procedi con inserimento...
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Inserimento dati utente
            $stmt_utenti = $conn->prepare("INSERT INTO Utenti (nome, cognome, email, tipo, data_nascita, telefono, cf) VALUES (?, ?, ?, ?, ?, ?, ?)");
            
            // Controllo se la preparazione della query è andata a buon fine
            if ($stmt_utenti === false) {
                die("Errore nella preparazione della query: " . $conn->error);
            }
            
            $stmt_utenti->bind_param("sssssss", $nome, $cognome, $email, $tipo, $data_nascita, $telefono, $cf);

            if ($stmt_utenti->execute()) {
                $id_utente = $stmt_utenti->insert_id;

                // Inserimento credenziali di login con password criptata
                $stmt_login = $conn->prepare("INSERT INTO Login (username, password, id_utente) VALUES (?, ?, ?)");
                
                // Controllo se la preparazione della query è andata a buon fine
                if ($stmt_login === false) {
                    die("Errore nella preparazione della query: " . $conn->error);
                }

                $stmt_login->bind_param("ssi", $user, $password, $id_utente);

                if ($stmt_login->execute()) {
                    $message = "✅ Registrazione completata. <a href='login.php'>Vai al login</a>";
                } else {
                    $message = "❌ Errore nella creazione delle credenziali.";
                }

                $stmt_login->close();
            } else {
                $message = "❌ Errore nell'inserimento dei dati utente.";
            }

            $stmt_utenti->close();
        }

        $checkUtente->close();
    }

    $checkUsername->close();
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Registrazione - SkinLab</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <header>
        <h1>SkinLab</h1>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="login.php">Login</a></li>
            </ul>
        </nav>
    </header>

    <div class="form-container">
        <h2>Registrati</h2>
        <form action="register.php" method="post">
            <label for="nome">Nome:</label>
            <input type="text" name="nome" required>

            <label for="cognome">Cognome:</label>
            <input type="text" name="cognome" required>

            <label for="email">Email:</label>
            <input type="email" name="email" required>

            <label for="data_nascita">Data di Nascita:</label>
            <input type="date" name="data_nascita" required>

            <label for="telefono">Telefono:</label>
            <input type="text" name="telefono" required>

            <label for="cf">Codice Fiscale:</label>
            <input type="text" name="cf" required>

            <label for="username">Username:</label>
            <input type="text" name="username" required>

            <label for="password">Password:</label>
            <input type="password" name="password" required>

            <label for="tipo">Tipo Utente:</label>
            <select name="tipo" required>
                <option value="paziente">Paziente</option>
                <option value="dermatologo">Dermatologo</option>
            </select>

            <button type="submit">Registrati</button>
        </form>
        <?php if (!empty($message)) echo "<p class='message'>$message</p>"; ?>
    </div>
</body>
</html>
