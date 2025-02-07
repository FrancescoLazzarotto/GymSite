<?php
include '../includes/funzioni.php';


header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET');
header("Access-Control-Allow-Origin: http://localhost");
header("Access-Control-Allow-Credentials: true");

//Controlla che il metodo sia GET
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    session_start();

    //Ottiene i parametri dalla query
    $parola_chiave = isset($_GET['parola_chiave']) ? $_GET['parola_chiave'] : '';
    $tipologia = isset($_GET['tipologia']) ? $_GET['tipologia'] : '';
    $posti_disponibili = isset($_GET['posti_disponibili']) && $_GET['posti_disponibili'] == 'true' ? true : false;

    //Ottiene i corsi usando la funzione
    $corsi = getCorsi($parola_chiave, $tipologia, $posti_disponibili);

    //ritorna i corsi in formato JSON
    echo json_encode($corsi);
}
