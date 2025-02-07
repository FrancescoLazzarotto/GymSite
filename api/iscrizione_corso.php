<?php
/* ini_set('display_errors', 1);
error_reporting(E_ALL); */

// require 'cors.php';
include '../includes/funzioni.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); 
header('Access-Control-Allow-Methods: POST');  
header('Access-Control-Allow-Headers: Content-Type');  

//Accetta solo POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Metodo non consentito']);
    exit();
}

session_start();

//Verifica se l'utente è autenticato
$user_id = $_SESSION['id_utente'] ?? null;
if (!$user_id) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Utente non autenticato']);
    exit();
}

//Recupera i dati JSON
$data = json_decode(file_get_contents('php://input'), true);
$corso_id = $data['id_corso'] ?? null;

//Verifica che id_corso sia un numero intero valido
if (!$corso_id || !is_numeric($corso_id)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'ID corso non valido']);
    exit();
}

//Connessione al database
$conn = connessione_database();
if (!$conn) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Errore di connessione al database']);
    exit();
}

//Verifica se l'utente è già iscritto al corso
$queryCheck = "SELECT COUNT(*) FROM iscrizioni WHERE id_utente = ? AND id_corso = ?";
$stmtCheck = $conn->prepare($queryCheck);
$stmtCheck->execute([$user_id, $corso_id]);

if ($stmtCheck->fetchColumn() > 0) {
    http_response_code(409);
    echo json_encode(['success' => false, 'message' => 'Sei già iscritto a questo corso.']);
    exit();
}

//Esegue l'iscrizione
if (iscriviUtente($user_id, $corso_id)) {
    http_response_code(201);
    echo json_encode(['success' => true, 'message' => 'Iscrizione completata con successo.']);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Errore durante l\'iscrizione.']);
}
