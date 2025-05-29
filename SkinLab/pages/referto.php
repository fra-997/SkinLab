<?php
session_start();
include '..\includes\db_connect.php';

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_SESSION["id"]) && $_SESSION["tipo"] === "dermatologo") {
    $id_dermatologo = $_SESSION["id"];
    $id_test = isset($_POST["test_id"]) ? intval($_POST["test_id"]) : 0;
    $descrizione = trim($_POST["descrizione"]);

    if ($id_test <= 0 || empty($descrizione)) {
        die("Dati mancanti o non validi.");
    }

    $query = "INSERT INTO Referti (id_test, id_dermatologo, descrizione) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iis", $id_test, $id_dermatologo, $descrizione);

    if ($stmt->execute()) {
        header("Location: dermatologo_dashboard.php?msg=referto_inviato");
        exit();
    } else {
        echo "Errore durante l'invio del referto: " . $stmt->error;
    }

    $stmt->close();
} else {
    header("Location: login.php");
    exit();
}
