<?php
include '..\includes\db_connect.php';
session_start();

if (!isset($_SESSION["id"]) || $_SESSION["tipo"] !== "paziente") {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION["id"])) {
    $id_utente = $_SESSION["id"];
    $descrizione = $_POST["descrizione"];
    $valutazione = $_POST["valutazione"];

    $stmt = $conn->prepare("INSERT INTO Commenti (id_utente, descrizione, valutazione) VALUES (?, ?, ?)");
    $stmt->bind_param("isi", $id_utente, $descrizione, $valutazione);
    $stmt->execute();

    $messaggio = $stmt->affected_rows > 0 ? "Commento pubblicato!" : "Errore: " . $conn->error;
}

$commenti = $conn->query("SELECT * FROM Commenti ORDER BY data_commento DESC");
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Blog - SkinLab</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<header>
        <h1>SkinLab</h1>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
</header>

<body>
    <div class="container">
        <section class="blog">
            <h2>Blog</h2>

            <?php if (isset($messaggio)) echo "<p>$messaggio</p>"; ?>

            <form method="POST">
                <label>Commento:</label>
                <textarea name="descrizione" required></textarea>
                <label>Valutazione (1-5):</label>
                <input type="number" name="valutazione" min="1" max="5" required>
                <br>
                <button type="submit">Pubblica</button>
            </form>

            <h3>Commenti Recenti</h3>
            <?php while ($row = $commenti->fetch_assoc()) { ?>
                <div class="comment-box">
                    <p><strong>Utente #<?php echo $row['id_utente']; ?></strong>: <?php echo $row['descrizione']; ?> <em>(Voto: <?php echo $row['valutazione']; ?>)</em></p>
                </div>
            <?php } ?>
        </section>
    </div>
</body>
        <footer>
    <div class="footer-container">
      <div class="footer-column">
        <h4>SkinLab</h4>
        <p>La tua clinica dermatologica di fiducia.<br>
           Offriamo trattamenti personalizzati per ogni tipo di pelle.</p>
      </div>
      <div class="footer-column">
        <h4>Contatti</h4>
        <p>📍 Via Garibaldi 100, Roma (RM)<br>
           ☎️ +39 06 123 4567<br>
           📧 info@skinlab.it</p>
      </div>
      <div class="footer-column">
        <h4>Sviluppatrici</h4>
        <p>Gioffredi Francesca<br>
           Iasiuolo Francesca</p>
      </div>
    </div>
    <div class="footer-bottom">
      <p>&copy; 2025 SkinLab. Tutti i diritti riservati.</p>
    </div>
  </footer>

  
</html>