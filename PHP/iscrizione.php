<?php
require_once 'funzioni.php';

class Iscrizione
{
    private $conn;

    public function __construct()
    {
        $this->conn = connessione_database();
    }

    // Funzione per iscrivere un utente a un corso
    public function create($idUtente, $idCorso)
    {
        try {
            $query = "INSERT INTO Iscrizioni (id_utente, id_corso) VALUES (:id_utente, :id_corso)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_utente', $idUtente, PDO::PARAM_INT);
            $stmt->bindParam(':id_corso', $idCorso, PDO::PARAM_INT);
            $stmt->execute();

            return ['status' => 'success', 'message' => 'Iscrizione completata con successo.'];
        } catch (PDOException $e) {
            return ['status' => 'error', 'message' => 'Errore durante l\'iscrizione: ' . $e->getMessage()];
        }
    }
}

// Endpoint REST per le iscrizioni
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    session_start();

    if (isset($_SESSION['login']) && isset($_POST['id_corso'])) {
        $idUtente = $_SESSION['id_utente']; // Assumendo che l'ID utente sia salvato nella sessione
        $idCorso = $_POST['id_corso'];

        $iscrizione = new Iscrizione();
        $response = $iscrizione->create($idUtente, $idCorso);
    } else {
        $response = ['status' => 'error', 'message' => 'Utente non autenticato o corso non specificato.'];
    }

    // Invio della risposta in formato JSON
    header('Content-Type: application/json');
    echo json_encode($response);
}
