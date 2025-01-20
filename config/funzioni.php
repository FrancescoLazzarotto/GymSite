<?php
/*
istruzioni di debugging 
 ini_set('display_errors', 1);
 ini_set('display_startup_errors', 1);
 error_reporting(E_ALL);
 mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);  
*/

function connessione_database()
{
    $host = "localhost";
    $dbname = "palestralazzarotto";
    $username = "root";
    $password = "";

    try {
        $connessione = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
        $connessione->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $connessione;
    } catch (PDOException $e) {
        echo "Errore di connessione: " . $e->getMessage();
        exit;
    }
}
function debugUserData($userData)
{
    if (is_array($userData)) {
        echo '<pre>'; // Formatta per una visualizzazione migliore
        print_r($userData);
        echo '</pre>';
    } else {
        echo "<p>$userData</p>"; // Messaggio di errore
    }
}

//funzione per creare un nuovo utente    
function nuovoUtente($nome, $cognome, $username, $password, $mail, $data)
{
    $connessione = connessione_database();

    try {
        // Controlla se l'username o l'email esistono già
        $query = "SELECT * FROM utenti WHERE username = :username OR mail = :mail";
        $stmt = $connessione->prepare($query);
        $stmt->execute([':username' => $username, ':mail' => $mail]);

        if ($stmt->rowCount() > 0) {
            return false; // L'username o l'email esistono già
        } else if (!empty($nome) && !empty($cognome) && !empty($username) && !empty($password) && !empty($mail) && !empty($data)) {
            
            // Inserisci il nuovo utente (password in chiaro)
            $query = "INSERT INTO utenti (nome, cognome, username, password, mail, data_nascita, amministratore)
                      VALUES (:nome, :cognome, :username, :password, :mail, :data, 0)";
            $stmt = $connessione->prepare($query);
            $risultato = $stmt->execute([
                ':nome' => $nome,
                ':cognome' => $cognome,
                ':username' => $username,
                ':password' => $password, // salva la password in chiaro
                ':mail' => $mail,
                ':data' => $data
            ]);
            return $risultato; // Restituisci il risultato dell'inserimento
        } else {
            return false; // Dati mancanti
        }
    } catch (PDOException $e) {
        echo "Errore: " . $e->getMessage();
        return false;
    } finally {
        $connessione = null; // Chiude la connessione
    }
}

// Funzione di controllo utente senza hashing
function controllaUtente($username, $password)
{
    $conn = connessione_database();

    $query = "SELECT * FROM utenti WHERE username = :username";

    try {
        // Prepara e esegue lo statement
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();

        // Recupera i dati dell'utente
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            // Verifica la password senza hashing
            if ($password === $row['password']) {
                return $row; // Ritorna i dati dell'utente se la password è corretta
            } else {
                return "Password errata"; // Ritorna un messaggio di errore se la password è errata
            }
        } else {
            return false; // Nessun utente trovato
        }
    } catch (PDOException $e) {
        return false; // Gestione degli errori
    }
}



//funzione per ritornare tutti i corsi ordinati per l'id
function listaCorsi()
{
    $connessione = connessione_database();
    $query = "SELECT * FROM Corso ORDER BY id_Corso";
    $return = []; // Array per i risultati

    try {
        // Prepara e esegue la query
        $stmt = $connessione->prepare($query);
        $stmt->execute();

        // Recupera i dati come array associativo
        $return = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        // Gestione dell'errore (opzionale)
    }

    return $return;
}



//funzione per ritornare tutti i corsi presenti sul db 
function listaAdmin()
{
    $connessione = connessione_database();
    $query = "SELECT * FROM Corso";
    $return = []; // Array per i risultati

    try {
        // Prepara e esegue la query
        $stmt = $connessione->prepare($query);
        $stmt->execute();

        // Recupera i dati come array associativo
        $return = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        // Gestione degli errori (opzionale)
    }

    return $return;
}

// Funzione per cancellare un corso dal database in base all'id
function cancellaCorso($id_corso)
{
    $conn = connessione_database();
    $query = "DELETE FROM Corso WHERE id_corso = :id_corso";

    try {
        // Prepara e esegue la query con binding del parametro
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':id_corso', $id_corso, PDO::PARAM_INT);
        $stmt->execute();

        // Controlla se almeno una riga è stata cancellata
        return $stmt->rowCount() > 0;
    } catch (PDOException $e) {
        return false;
    }
}

// Funzione per inserire un nuovo corso all'interno del database
function nuovoCorso($nome, $tipologia, $descrizione, $partecipanti, $orario)
{
    $connessione = connessione_database();
    $query = "INSERT INTO corso (nome, tipologia, descrizione, partecipanti, orario) 
              VALUES (:nome, :tipologia, :descrizione, :partecipanti, :orario)";

    try {
        // Prepara e esegue la query con binding dei parametri
        $stmt = $connessione->prepare($query);
        $stmt->bindParam(':nome', $nome, PDO::PARAM_STR);
        $stmt->bindParam(':tipologia', $tipologia, PDO::PARAM_STR);
        $stmt->bindParam(':descrizione', $descrizione, PDO::PARAM_STR);
        $stmt->bindParam(':partecipanti', $partecipanti, PDO::PARAM_INT);
        $stmt->bindParam(':orario', $orario, PDO::PARAM_STR);
        $stmt->execute();

        return true;
    } catch (PDOException $e) {
        // Restituisce l'errore specifico per il debug
        return $e->getMessage();
    }
}








// Funzione per recuperare e stampare le assistenze presenti nel database
function recuperaAssistenza()
{
    $assistenzaData = array(); // Preparo un array per le assistenze
    $connessione = connessione_database();

    // Query per recuperare i dati dalla tabella "assistenza"
    $query = "SELECT id_assistenza, username_ass, mail_ass, assistenza_richiesta FROM assistenza";

    try {
        // Prepara e esegue la query
        $stmt = $connessione->prepare($query);
        $stmt->execute();

        // Recupera i dati in un array associativo
        $assistenzaData = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        // Gestione degli errori (opzionale: aggiungere logging o altro)
    }

    return $assistenzaData;
}

// Funzione per cancellare assistenze dal database in base all'id
function cancellaAssistenza($id_assistenza)
{
    $conn = connessione_database();

    // Query per eliminare le assistenze dalla tabella assistenza in base all'id
    $query = "DELETE FROM assistenza WHERE id_assistenza = :id_assistenza";

    try {
        // Prepara e esegue lo statement
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':id_assistenza', $id_assistenza, PDO::PARAM_INT);
        $stmt->execute();

        // Controlla se è stata cancellata almeno una riga
        return $stmt->rowCount() > 0;
    } catch (PDOException $e) {
        return false;
    }
}

// Funzione per controllare l'esistenza di un utente nel database


// Funzione per recuperare i dati di un utente
function recuperaUtente($username)
{
    $conn = connessione_database();

    // Query per recuperare i dati dell'utente
    $query = "SELECT * FROM utenti WHERE username = :username";

    try {
        // Prepara e esegue lo statement
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();

        // Recupera i dati in un array associativo
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return [];
    }
}

// Funzione per cambiare l'username
function CambiaUsername($username, $mail)
{
    $conn = connessione_database();

    try {
        // Verifica se l'username esiste già
        $query = "SELECT * FROM utenti WHERE username = :username";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return "duplicato"; // Username esiste già
        } elseif (!empty($username)) {
            // Aggiorna l'username
            $query = "UPDATE utenti SET username = :username WHERE mail = :mail";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            $stmt->bindParam(':mail', $mail, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->rowCount() > 0;
        } else {
            return false;
        }
    } catch (PDOException $e) {
        return false;
    }
}



//Funzione per cambiare la password dell'utente
function cambiaPsw($username, $psw)
{
    $conn = connessione_database(); // Connessione al database

    // Query per aggiornare la password dell'utente
    $query = "UPDATE utenti SET password = :psw WHERE username = :username";

    try {
        // Prepara lo statement con PDO e sostituisce i placeholder con i valori reali
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':psw', $psw, PDO::PARAM_STR);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);

        // Esegue lo statement
        $stmt->execute();

        // Controlla se è stata modificata almeno una riga
        if ($stmt->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    } catch (PDOException $e) {
        // Gestione dell'errore (si può aggiungere del logging o altra gestione)
        return false;
    }
}

// Funzione per cancellare un utente dal database
function cancellaUtente($username)
{
    $conn = connessione_database();

    // Imposta PDO in modalità eccezione per il debug
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $query = "DELETE FROM utenti WHERE username = :username";

    try {
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);

        // Debug della query
        echo "Esecuzione query per cancellare l'utente: " . $username;

        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return true;
        } else {
            echo "Nessuna riga trovata per l'username: " . $username;
            return false;
        }
    } catch (PDOException $e) {
        echo "Errore di cancellazione: " . $e->getMessage();
        return false;
    }
}





//funzione per controllare il tipo di utente 
function tipoUtente($username)
{
    //richiamo la funzione recuperaUtente per prelevare i dati 
    $utente = recuperaUtente($username);
    $tipo = ''; // inizializzo la variabile tipo 

    for ($i = 0; $i < sizeof($utente); $i++) {

        if (isset($username)) { // controllo l'utente 
            $tipo = 'reg';
            if ($username == 'Amministratore') {
                $tipo = 'admin';
            }
        } else {
            $tipo = 'utente';
        }

    }
    return $tipo;
}


function recuperaCorsiUtente($username) {
    global $connessione;

    // Recupera l'id_utente dal username
    $stmt = $connessione->prepare("SELECT id_utente FROM utenti WHERE username = ?");
    $stmt->execute([$username]);
    $utente = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$utente) {
        return []; // Utente non trovato
    }

    $idUtente = $utente['id_utente'];
    $stmt = $connessione->prepare("SELECT * FROM corso WHERE id_corso IN (SELECT id_corso FROM iscrizioni WHERE id_utente = ?)");
    if (!$stmt->execute([$idUtente])) {
        print_r($stmt->errorInfo());
        return [];
    } 
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function cancellaCorsoUtente($idUtente, $corsoId) {
    global $connessione;

    // Recupera l'id_utente dal username
    $stmt = $connessione->prepare("SELECT id_utente FROM utenti WHERE username = ?");
    $stmt->execute([$idUtente]);
    $utente = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$utente) {
        return false; // Utente non trovato
    }

    $idUtente = $utente['id_utente'];
    $stmt = $connessione->prepare("DELETE FROM iscrizioni WHERE id_utente = ? AND id_corso = ?");
    if (!$stmt->execute([$idUtente, $corsoId])) {
        print_r($stmt->errorInfo());
        return false;
    }
    return true;
}
