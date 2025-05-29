<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>SkinLab</title>
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
  <?php
  session_start();  // Inizia la sessione
  
  // Verifica se c'è un messaggio di successo o errore da mostrare
  $flash = '';
  if (isset($_SESSION['flash_message'])) {
      $flash = $_SESSION['flash_message'];
      unset($_SESSION['flash_message']);
  }
  ?>

  <?php if (!empty($flash)): ?>
    <div class="flash-banner <?php echo (strpos($flash, 'successo') !== false) ? 'success' : 'error'; ?>">
      <?php echo htmlspecialchars($flash); ?>
    </div>
  <?php endif; ?>

  <header>
    <h1><img src="../assets/images/logo.png" alt="SkinLab Logo" style="height: 120px; vertical-align: middle;"> SkinLab</h1>
    <nav>
      <ul>
        <li><a href="index.php">Home</a></li>
        
        <?php if (isset($_SESSION['id'])): ?>
          <?php if ($_SESSION['tipo'] === 'dermatologo'): ?>
            <!-- Bottone per i dermatologi per visualizzare i test -->
            <li><a href="dermatologo_dashboard.php" class="button">Test & Profilo</a></li>
          <?php else: ?>
            <!-- Solo i pazienti possono vedere il test e il blog -->
            <li><a href="paziente_dashboard.php" class="button">Profilo</a></li>
            <li><a href="test.php">Esegui un Test</a></li>
            <li><a href="blog.php">Blog</a></li>
          <?php endif; ?>
          <li><a href="logout.php">Logout</a></li>
        <?php else: ?>
          <li><a href="login.php">Login</a></li>
        <?php endif; ?>
        
      </ul>
    </nav>
  </header>

  <main>
    <section class="hero">
      <h2>Benvenuti in<br>SkinLab</h2>
      <p>Specialisti in Dermatologia</p>
    </section>

    <section class="hero-image">
      <img src="../assets/images/home.jpg" alt="Dashboard SkinLab"/>
    </section>
  </main>

  <!-- CHI SIAMO -->
  <section class="about-section">
    <div class="about-text">
      <h3>Chi siamo</h3>
      <p>SkinLab è il punto di riferimento per chi cerca servizi dermatologici di alta qualità ed accessibili a tutti.
        <br>Ci impegniamo ad offrire un'esperienza professionale e personalizzata per ogni paziente.</p>
    </div>
    <div class="about-images" >
      <img src="../assets/images/dermatologo1.jpeg" alt="Dermatologo 1" style="border-radius: 0px;">
      <img src="../assets/images/dermatologo2.jpeg" alt="Dermatologo 2" style="border-radius: 0px;">
    </div>
  </section>

  <section class="servizi-gallery">
    <div class="gallery-container">
      <img src="../assets/images/mask.jpg" alt="Servizio 1">
      <img src="../assets/images/logo-wbg.png" alt="Servizio 2">
      <img src="../assets/images/skin.jpg" alt="Servizio 3">
    </div>
  </section>
  
  <!-- SERVIZI -->
  <section class="servizi-section">
    <h3>Prova il nostro test gratuito</h3>
    <p>Scopri il tuo tipo di pelle in pochi minuti e ricevi successivamente la tua e-mail personalizzata.</p>
    <a href="test.php" class="servizi-btn">Vai al Test</a>
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
