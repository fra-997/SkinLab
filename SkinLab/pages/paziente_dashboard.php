<?php
session_start();
include '..\includes\db_connect.php';
if (!isset($_SESSION["id"]) || $_SESSION["tipo"] !== "paziente") {
    header("Location: login.php");
    exit();
}


$id_paziente = $_SESSION["id"];

// Recupero dati utente
$query = "SELECT nome, cognome, email, cf, telefono FROM Utenti WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id_paziente);
$stmt->execute();
$result = $stmt->get_result();
$utente = $result->fetch_assoc();

// Recupero l'ultimo referto (se esiste)
$query_referto = "
    SELECT R.descrizione, R.data_emissione, TD.data_test 
    FROM Referti R
    JOIN Test_Dermatologici TD ON R.id_test = TD.id
    WHERE TD.id_paziente = ?
    ORDER BY R.data_emissione DESC
    LIMIT 1
";
$stmt_ref = $conn->prepare($query_referto);
$stmt_ref->bind_param("i", $id_paziente);
$stmt_ref->execute();
$res_referto = $stmt_ref->get_result();
$referto = $res_referto->fetch_assoc();

?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Paziente - SkinLab</title>
    <link rel="stylesheet" href="..\assets\css\sections.css">
        <link rel="stylesheet" href="..\assets\css\style.css">
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
        <h1 class="dashboard-header">Benvenuto, <?php echo htmlspecialchars($utente['nome']); ?>!</h1>

        <div class="dashboard-info">
            <p><strong>Nome:</strong> <?php echo htmlspecialchars($utente['nome']); ?></p>
            <p><strong>Cognome:</strong> <?php echo htmlspecialchars($utente['cognome']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($utente['email']); ?></p>
            <p><strong>Codice Fiscale:</strong> <?php echo htmlspecialchars($utente['cf']); ?></p>
            <p><strong>Telefono:</strong> <?php echo htmlspecialchars($utente['telefono']); ?></p>
        </div>

                <h3>Ultimo Referto</h3>
                <?php if ($referto): ?>
                    <div class="report-box">
                        <p><strong>Data Test:</strong> <?php echo $referto['data_test']; ?></p>
                        <p><strong>Data Referto:</strong> <?php echo $referto['data_emissione']; ?></p>
                        <p><strong>Descrizione:</strong> <?php echo nl2br(htmlspecialchars($referto['descrizione'])); ?></p>
                    </div>
                <?php else: ?>
                    <p><em>Nessun referto disponibile al momento.</em></p>
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
