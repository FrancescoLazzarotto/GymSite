<?php

/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/

// require 'cors.php';
include '../includes/funzioni.php';
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: DELETE');
header("Access-Control-Allow-Origin: http://localhost");
header("Access-Control-Allow-Credentials: true");


$data = json_decode(file_get_contents('php://input'), true);
error_log("Dati ricevuti: " . print_r($data, true));


//Controlla che il metodo sia DELETE
if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    session_start();
    //Controlla che l'utente sia autenticato
    $user_id = $_SESSION['id_utente'] ?? null;
    if (!$user_id) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Utente non autenticato']);
        exit();
    }

    $data = json_decode(file_get_contents('php://input'), true);
    $corso_id = $data['id_corso'] ?? null;

    if (!$corso_id || !is_numeric($corso_id)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'ID corso non valido']);
        exit();
    }    

    //Richiama la funzione per cancellare l'iscrizione
    $success = cancellaCorsoUtente($user_id, $corso_id);

    //Risposta in base al risultato della cancellazione
    if ($success === true) {
        echo json_encode(['success' => true, 'message' => 'Disiscrizione avvenuta con successo.']);
    } elseif ($success === "not_subscribed") {
        http_response_code(409);
        echo json_encode(['success' => false, 'message' => 'Non sei iscritto a questo corso, impossibile disiscriversi.']);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Errore nella disiscrizione, riprova più tardi.']);
    }
} else {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Metodo non supportato.']);
}
