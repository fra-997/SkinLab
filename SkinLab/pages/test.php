<?php
include '..\includes\db_connect.php';
session_start();

if (!isset($_SESSION["id"]) || $_SESSION["tipo"] !== "paziente") {
    header("Location: login.php");
    exit();
}

// Seleziona un dermatologo disponibile (puoi usare LIMIT 1 o una logica più complessa)
$result = $conn->query("SELECT id FROM Utenti WHERE tipo = 'dermatologo' LIMIT 1");
$id_dermatologo = $result ? $result->fetch_assoc()['id'] : null;

if ($_SERVER["REQUEST_METHOD"] == "POST" && $id_dermatologo !== null) {
    $id_paziente = $_SESSION["id"];
    $risultati = [];

    // Checkbox multipli
    if (isset($_POST['risultati'])) {
        $risultati = array_merge($risultati, $_POST['risultati']);
    }

    // Radio singoli
    if (isset($_POST['rossori'])) {
        $risultati[] = $_POST['rossori'];
    }
    if (isset($_POST['idratazione'])) {
        $risultati[] = $_POST['idratazione'];
    }
    if (isset($_POST['cosmetici'])) {
        $risultati[] = $_POST['cosmetici'];
    }
    if (isset($_POST['acne'])) {
        $risultati[] = $_POST['acne'];
    }
    if (isset($_POST['allergie'])) {
        $risultati[] = $_POST['allergie'];
    }

    // Unisci tutte le risposte in una stringa
    $risultati = implode(", ", $risultati);

    $stmt = $conn->prepare("INSERT INTO Test_Dermatologici (id_paziente, id_dermatologo, ora_test, risultati) VALUES (?, ?, NOW(), ?)");
    $stmt->bind_param("iis", $id_paziente, $id_dermatologo, $risultati);
    $stmt->execute();

    $messaggio = $stmt->affected_rows > 0 ? "Test inviato con successo!" : "Errore durante l'invio.";
    $_SESSION['flash_message'] = $messaggio;

    header("Location: index.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Test Dermatologico</title>
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
  <section class="test">
    <h2>Test Dermatologico</h2>
    <?php if (isset($messaggio)) echo "<p>$messaggio</p>"; ?>
    <form method="POST">
    <input type="hidden" name="id_paziente" value="<?php echo $_SESSION['username']; ?>">

    <section class="test-form">
        <label>Dopo aver lavato la faccia, come risulta la tua pelle?</label>
        <div class="checkbox-container">
            Secca <input type="checkbox" name="risultati[]" value="Secca">
        </div>
        <div class="checkbox-container">
            Normale <input type="checkbox" name="risultati[]" value="Normale"> 
        </div>
        <div class="checkbox-container">
            Grassa <input type="checkbox" name="risultati[]" value="Grassa"> 
        </div>
        <div class="checkbox-container">
            Mista <input type="checkbox" name="risultati[]" value="Mista"> 
        </div>
    </section>

    <section class="test-form">
        <label>Hai rossori o irritazioni frequenti?</label>
        <div class="radio-container">
            Rossori frequenti <input type="radio" name="rossori" value="Rossori frequenti">
        </div>
        <div class="radio-container">
            No irritazioni <input type="radio" name="rossori" value="No irritazioni">
        </div>
    </section>

    <section class="test-form">
        <label>Come reagisce la tua pelle all'esposizione al sole?</label>
        <div class="checkbox-container">
            Si arrossa facilmente <input type="checkbox" name="risultati[]" value="Si arrossa facilmente">
        </div>
        <div class="checkbox-container">
            Diventa dorata <input type="checkbox" name="risultati[]" value="Diventa dorata">
        </div>
        <div class="checkbox-container">
            Non cambia <input type="checkbox" name="risultati[]" value="Non cambia">
        </div>
    </section>

    <section class="test-form">
        <label>Quanto spesso utilizzi prodotti idratanti sulla pelle?</label>
        <div class="radio-container">
            Ogni giorno <input type="radio" name="idratazione" value="Ogni giorno">
        </div>
        <div class="radio-container">
            Qualche volta alla settimana <input type="radio" name="idratazione" value="Qualche volta alla settimana">
        </div>
        <div class="radio-container">
            Raramente <input type="radio" name="idratazione" value="Raramente">
        </div>
        <div class="radio-container">
            Mai <input type="radio" name="idratazione" value="Mai">
        </div>
    </section>

    <section class="test-form">
        <label>Hai la pelle sensibile ai cosmetici?</label>
        <div class="radio-container">
            Sì, reagisce facilmente <input type="radio" name="cosmetici" value="Sì, reagisce facilmente">
        </div>
        <div class="radio-container">
            No, tollero la maggior parte dei prodotti <input type="radio" name="cosmetici" value="No, tollero la maggior parte dei prodotti">
        </div>
    </section>

    <section class="test-form">
        <label>Hai una tendenza all'acne?</label>
        <div class="radio-container">
            Sì, spesso <input type="radio" name="acne" value="Sì, spesso">
        </div>
        <div class="radio-container">
            A volte <input type="radio" name="acne" value="A volte">
        </div>
        <div class="radio-container">
            No, raramente <input type="radio" name="acne" value="No, raramente">
        </div>
    </section>

    <section class="test-form">
        <label>Come reagisce la tua pelle ai cambiamenti di temperatura?</label>
        <div class="checkbox-container">
            Diventa più secca d'inverno <input type="checkbox" name="risultati[]" value="Diventa più secca d'inverno">
        </div>
        <div class="checkbox-container">
            Diventa più grassa d'estate <input type="checkbox" name="risultati[]" value="Diventa più grassa d'estate">
        </div>
        <div class="checkbox-container">
            Non noto cambiamenti <input type="checkbox" name="risultati[]" value="Non noto cambiamenti">
        </div>
    </section>

    <section class="test-form">
        <label>Hai mai avuto reazioni allergiche ai prodotti per la pelle?</label>
        <div class="radio-container">
            Sì, spesso <input type="radio" name="allergie" value="Sì, spesso">
        </div>
        <div class="radio-container">
            Raramente <input type="radio" name="allergie" value="Raramente">
        </div>
        <div class="radio-container">
            Mai <input type="radio" name="allergie" value="Mai">
        </div>
    </section>

    <section class="test-form">
        <label>La tua pelle ha una tendenza alla desquamazione?</label>
        <div class="checkbox-container">
            Sì, soprattutto d'inverno <input type="checkbox" name="risultati[]" value="Sì, soprattutto d'inverno">
        </div>
        <div class="checkbox-container">
            Solo in certe zone (naso, fronte, mento) <input type="checkbox" name="risultati[]" value="Solo in certe zone (naso, fronte, mento)">
        </div>
        <div class="checkbox-container">
            No, mai <input type="checkbox" name="risultati[]" value="No, mai">
        </div>
    </section>

    <button type="submit">Invia Test</button>



  </form>

  </section>
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
