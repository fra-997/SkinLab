<?php
session_start();  // Inizia la sessione
session_unset();  // Distrugge tutte le variabili di sessione
session_destroy();  // Distrugge la sessione

// Reindirizza alla pagina di home
header("Location: index.php");
exit();
?>
