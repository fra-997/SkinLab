<?php
session_start();
include "..\includes\db_connect.php";

$errore = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    $query = "
        SELECT 
            Login.id AS login_id, 
            Login.username, 
            Login.password, 
            Utenti.id AS utente_id, 
            Utenti.nome, 
            Utenti.cognome, 
            Utenti.tipo 
        FROM Login 
        JOIN Utenti ON Login.id_utente = Utenti.id 
        WHERE Login.username = ? AND Login.password = ?
    ";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $user, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($utente = $result->fetch_assoc()) {
        $_SESSION["id"] = $utente["utente_id"];
        $_SESSION["username"] = $utente["username"];
        $_SESSION["tipo"] = $utente["tipo"];

        if ($utente["tipo"] == "paziente") {
            header("Location: paziente_dashboard.php");
        } else {
            header("Location: dermatologo_dashboard.php");
        }
        exit();
    } else {
        $errore = "❌ Credenziali errate.";
    }
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <header>
        <h1>SkinLab</h1>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
            </ul>
        </nav>
    </header>

    <div class="form-container">
        <h2>Login</h2>
        <?php if (!empty($errore)) echo "<p class='message'>$errore</p>"; ?>
        <form method="POST" action="login.php">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Accedi</button>
        </form>
        <p class="message">Non hai un account? <a href="register.php"> Registrati qui</a></p>
    </div>
</body>
</html>
