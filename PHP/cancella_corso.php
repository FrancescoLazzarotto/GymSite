<?php
session_start();
include 'funzioni.php'; // Assicurati che questo sia prima della logica

// Verifica che l'utente sia autenticato
if (!isset($_SESSION['login'])) {
    echo json_encode(['success' => false, 'message' => 'Utente non autenticato']);
    exit();
}

header('Content-Type: application/json');

// Leggi l'ID del corso da $_POST
if (!isset($_POST['id_corso'])) {
    echo json_encode(['success' => false, 'message' => 'ID corso non fornito']);
    exit();
}

$corsoId = $_POST['id_corso'];

// Effettua la cancellazione e restituisci il risultato
if (cancellaCorsoUtente($_SESSION['id_utente'], $corsoId)) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Errore nella cancellazione']);
}
