<?php
require 'connessione.php';

/*
istruzioni di debugging 
 ini_set('display_errors', 1);
 ini_set('display_startup_errors', 1);
 error_reporting(E_ALL);
 mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT); 
 
 

 
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
*/


//funzione per creare un nuovo utente    
function nuovoUtente($nome, $cognome, $username, $password, $mail, $data)
{
    $connessione = connessione_database();

    try {
        //Controlla se l'username o l'email esistono già
        $query = "SELECT * FROM utenti WHERE username = :username OR mail = :mail";
        $stmt = $connessione->prepare($query);
        $stmt->execute([':username' => $username, ':mail' => $mail]);

        if ($stmt->rowCount() > 0) {
            return false; //L'username o l'email esistono già
        } else if (!empty($nome) && !empty($cognome) && !empty($username) && !empty($password) && !empty($mail) && !empty($data)) {
            
            //Inserisci il nuovo utente 
            $query = "INSERT INTO utenti (nome, cognome, username, password, mail, data_nascita, amministratore)
                      VALUES (:nome, :cognome, :username, :password, :mail, :data, 0)";
            $stmt = $connessione->prepare($query);
            $risultato = $stmt->execute([
                ':nome' => $nome,
                ':cognome' => $cognome,
                ':username' => $username,
                ':password' => $password, 
                ':mail' => $mail,
                ':data' => $data
            ]);
            return $risultato; 
        } else {
            return false;
        }
    } catch (PDOException $e) {
        echo "Errore: " . $e->getMessage();
        return false;
    } finally {
        $connessione = null; 
    }
}

//Funzione di controllo utente
function controllaUtente($username, $password)
{
    $conn = connessione_database();

    $query = "SELECT * FROM utenti WHERE username = :username";

    try {
        //Prepara e esegue lo statement
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();

        //Recupera i dati dell'utente
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            //Verifica la password e ritorna i dati sennò messaggio di errore
            if ($password === $row['password']) {
                return $row;
            } else {
                return "Password errata";
            }
        } else {
            return false;
        }
    } catch (PDOException $e) {
        return false;
    }
}



//funzione per ritornare tutti i corsi ordinati per l'id
function listaCorsi()
{
    $connessione = connessione_database();
    $query = "SELECT * FROM Corso ORDER BY id_Corso";
    $return = []; //Array per i risultati

    try {
        //Prepara e esegue la query
        $stmt = $connessione->prepare($query);
        $stmt->execute();

        //Recupera i dati come array associativo
        $return = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        //Gestione dell'errore
    }

    return $return;
}



//funzione per ritornare tutti i corsi presenti sul db 
function listaAdmin()
{
    $connessione = connessione_database();
    $query = "SELECT * FROM Corso";
    $return = []; 

    try {
        //Prepara e esegue la query
        $stmt = $connessione->prepare($query);
        $stmt->execute();

        //Recupera i dati come array associativo
        $return = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        //Gestione dell'errore
    }

    return $return;
}

//Funzione per cancellare un corso dal database in base all'id
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

//Funzione per inserire un nuovo corso all'interno del database
function nuovoCorso($nome, $tipologia, $descrizione, $partecipanti, $orario)
{
    $connessione = connessione_database();
    $query = "INSERT INTO corso (nome, tipologia, descrizione, partecipanti, orario) 
              VALUES (:nome, :tipologia, :descrizione, :partecipanti, :orario)";

    try {
        //Prepara e esegue la query con binding dei parametri
        $stmt = $connessione->prepare($query);
        $stmt->bindParam(':nome', $nome, PDO::PARAM_STR);
        $stmt->bindParam(':tipologia', $tipologia, PDO::PARAM_STR);
        $stmt->bindParam(':descrizione', $descrizione, PDO::PARAM_STR);
        $stmt->bindParam(':partecipanti', $partecipanti, PDO::PARAM_INT);
        $stmt->bindParam(':orario', $orario, PDO::PARAM_STR);
        $stmt->execute();

        return true;
    } catch (PDOException $e) {
        //Restituisce l'errore specifico per il debug
        return $e->getMessage();
    }
}



//Funzione per recuperare e stampare le assistenze presenti nel database
function recuperaAssistenza()
{
    $assistenzaData = []; // Preparo un array per le assistenze
    $connessione = connessione_database();

    //Query per recuperare i dati dalla tabella "assistenza"
    $query = "SELECT id_assistenza, username_ass, mail_ass, assistenza_richiesta FROM assistenza";

    try {
        //Prepara e esegue la query
        $stmt = $connessione->prepare($query);
        $stmt->execute();

        // Recupera i dati in un array associativo
        $assistenzaData = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Errore nella query di assistenza: " . $e->getMessage();
    }

    return $assistenzaData;
}

//Funzione per cancellare assistenze dal database in base all'id
function cancellaAssistenza($id_assistenza)
{
    $conn = connessione_database();

    // Query per eliminare le assistenze dalla tabella assistenza in base all'id
    $query = "DELETE FROM assistenza WHERE id_assistenza = :id_assistenza";

    try {
        //Prepara e esegue lo statement
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':id_assistenza', $id_assistenza, PDO::PARAM_INT);
        $stmt->execute();

        // Controlla se è stata cancellata almeno una riga
        return $stmt->rowCount() > 0;
    } catch (PDOException $e) {
        return false;
    }
}


//Funzione per recuperare i dati di un utente
function recuperaUtente($username)
{
    $conn = connessione_database();

    //Query per recuperare i dati dell'utente
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
            return "duplicato";
        } elseif (!empty($username)) {
            //Aggiorna l'username
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
        // Prepara lo statement e sostituisce i placeholder con i valori reali
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
        return false;
    }
}

// Funzione per cancellare un utente dal database
function cancellaUtente($username)
{
    $conn = connessione_database();

    //Imposta PDO in modalità eccezione per il debug
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $query = "DELETE FROM utenti WHERE username = :username";

    try {
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);

        //Debug della query
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
    $tipo = ''; // inizializza la variabile tipo 

    for ($i = 0; $i < sizeof($utente); $i++) {

        if (isset($username)) { // controlla l'utente 
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
        return [];
    }

    $idUtente = $utente['id_utente'];
    $stmt = $connessione->prepare("SELECT * FROM corso WHERE id_corso IN (SELECT id_corso FROM iscrizioni WHERE id_utente = ?)");
    if (!$stmt->execute([$idUtente])) {
        print_r($stmt->errorInfo());
        return [];
    } 
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function iscriviUtente($idUtente, $corsoId)
{
    $connessione = connessione_database(); // Connessione al database

    try {
        // Controllo se l'utente è già iscritto al corso
        $queryCheck = "SELECT COUNT(*) FROM iscrizioni WHERE id_utente = ? AND id_corso = ?";
        $stmtCheck = $connessione->prepare($queryCheck);
        $stmtCheck->execute([$idUtente, $corsoId]);

        if ($stmtCheck->fetchColumn() > 0) {
            return "già iscritto"; 
        }

        //Inserimento iscrizione
        $query = "INSERT INTO Iscrizioni (id_utente, id_corso) VALUES (:id_utente, :id_corso)";
        $stmt = $connessione->prepare($query);
        $stmt->bindParam(':id_utente', $idUtente, PDO::PARAM_INT);
        // $stmt->bindParam(':id_corso', $idCorso, PDO::PARAM_INT);
        $stmt->bindParam(':id_corso', $corsoId, PDO::PARAM_INT);

        $stmt->execute();

        return true;
    } catch (PDOException $e) {
        error_log("Errore durante l'iscrizione: " . $e->getMessage());
        return false; 
    }
}


//funzione per disiscriversi da un corso 
function cancellaCorsoUtente($idUtente, $corsoId)
{
    $connessione = connessione_database();

    if ($connessione === null) {
        return false;
    }

    try {
        //Controllo se l'utente è iscritto al corso
        $stmt_check = $connessione->prepare("SELECT COUNT(*) FROM iscrizioni WHERE id_utente = ? AND id_corso = ?");
        $stmt_check->execute([$idUtente, $corsoId]);
        $iscritto = $stmt_check->fetchColumn();
        //se non iscritto 
        if ($iscritto == 0) {
            return "not_subscribed"; 
        }

        //Se l'utente è iscritto, procedi con la cancellazione
        $stmt_delete = $connessione->prepare("DELETE FROM iscrizioni WHERE id_utente = ? AND id_corso = ?");
        $success = $stmt_delete->execute([$idUtente, $corsoId]);

        return $success;
    } catch (PDOException $e) {
        error_log("Eccezione durante la cancellazione: " . $e->getMessage());
        return false;
    }
}

//funzione per recuperare gli utenti iscritti ai corsi
function utentiIscritti() {
    $conn = connessione_database();
    //query di selezione
    $sql = "SELECT username AS nome_utente, utenti.mail as mail, corso.nome AS corso_nome, corso.orario AS corso_orario 
            FROM utenti
            JOIN iscrizioni ON utenti.id_utente = iscrizioni.id_utente
            JOIN corso ON corso.id_corso = iscrizioni.id_corso";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


//funzione per dare una risposta alle assistenze degli utenti 
function rispondiAssistenza($id_assistenza, $messaggio) {
    try {
        $conn = connessione_database();
        $sql = "UPDATE assistenza SET risposta = :messaggio WHERE id_assistenza = :id_assistenza";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':messaggio', $messaggio, PDO::PARAM_STR);
        $stmt->bindParam(':id_assistenza', $id_assistenza, PDO::PARAM_INT);
        $risultato = $stmt->execute();
        
        return $risultato;
    } catch (PDOException $e) {
        error_log("Errore durante l'inserimento della risposta: " . $e->getMessage());
        return false;
    }
}

//funzione per recuperare i corsi dal db 
function getCorsi($parola_chiave = '', $tipologia = '', $posti_disponibili = false)
{
    $conn = connessione_database();
    $query = "SELECT * FROM corso WHERE nome LIKE :parola_chiave";

    if (!empty($tipologia)) {
        $query .= " AND tipologia = :tipologia";
    }

    if ($posti_disponibili) {
        $query .= " AND partecipanti < 30"; //limite di 30 iscritti per corso
    }

    try {
        $stmt = $conn->prepare($query);
        $stmt->bindValue(':parola_chiave', '%' . $parola_chiave . '%', PDO::PARAM_STR);

        if (!empty($tipologia)) {
            $stmt->bindValue(':tipologia', $tipologia, PDO::PARAM_STR);
        }

        $stmt->execute();
        $corsi = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $corsi;
    } catch (PDOException $e) {
        return ['error' => 'Errore nella query: ' . $e->getMessage()];
    } finally {
        $conn = null;
    }
}