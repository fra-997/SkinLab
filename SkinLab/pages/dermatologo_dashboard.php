<?php
session_start();
include '..\includes\db_connect.php';
if (!isset($_SESSION["id"]) || $_SESSION["tipo"] !== "dermatologo") {
    header("Location: login.php");
    exit();
}

$id_dermatologo = $_SESSION["id"];

// Dati del dermatologo
$query = "SELECT nome, cognome, email, telefono FROM Utenti WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id_dermatologo);
$stmt->execute();
$result = $stmt->get_result();
$utente = $result->fetch_assoc();

// Test da verificare (test che NON hanno ancora referto)
$query_test = "
    SELECT TD.id, TD.data_test, TD.risultati, U.nome AS nome_paziente, U.cognome AS cognome_paziente
    FROM Test_Dermatologici TD
    JOIN Utenti U ON TD.id_paziente = U.id
    WHERE TD.id_dermatologo = ? 
    AND NOT EXISTS (
        SELECT 1 FROM Referti R WHERE R.id_test = TD.id
    )
    ORDER BY TD.data_test ASC
";
$stmt_test = $conn->prepare($query_test);
$stmt_test->bind_param("i", $id_dermatologo);
$stmt_test->execute();
$test_result = $stmt_test->get_result();
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Dermatologo - SkinLab</title>
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
    <div class="dashboard-container">
        <h1 class="dashboard-header">Benvenuto Dr. <?php echo htmlspecialchars($utente['nome'],); ?>!</h1>

        <div class="dashboard-info">
            <p><strong>Nome:</strong> <?php echo htmlspecialchars($utente['nome']); ?></p>
            <p><strong>Cognome:</strong> <?php echo htmlspecialchars($utente['cognome']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($utente['email']); ?></p>
            <p><strong>Telefono:</strong> <?php echo htmlspecialchars($utente['telefono']); ?></p>
        </div>

        <h2>Test da Verificare</h2>
        <?php if ($test_result->num_rows > 0): ?>
            <?php while ($test = $test_result->fetch_assoc()): ?>
                <div class="test-box">
                    <h3>Paziente: <?php echo htmlspecialchars($test['nome_paziente'] . ' ' . $test['cognome_paziente']); ?></h3>
                    <p><strong>Data Test:</strong> <?php echo $test['data_test']; ?></p>
                    <p><strong>Risultati:</strong> <?php echo nl2br(htmlspecialchars($test['risultati'])); ?></p>

                    <div class="container">
                        <section class="blog">
                            <h3>Inserisci Referto:</h3>
                            <form action="referto.php" method="POST">
                                <input type="hidden" name="test_id" value="<?php echo $test['id']; ?>">
                                <textarea name="descrizione" required></textarea>
                                <button type="submit" class="dashboard-action-btn">Invia Referto</button>
                            </form>
                        </section>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p><em>Nessun test da verificare al momento.</em></p>
        <?php endif; ?>
    </div>

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
</body>
</html>
