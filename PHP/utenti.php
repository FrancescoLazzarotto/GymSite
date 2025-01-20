<?php
session_start();
include 'funzioni.php';
include_once 'utente.php';


$connessione = connessione_database();

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit();
}


$tabella = '';
$mail = '';
$message_us = '';
$message_psw = '';
$bottone_login = '<a id="login" href="login.php">Login</a>';
$utenti = '';
$bottone_utente = '<a class="loginn" href="registrazione.php">Registrati</a>';

// Condizione per verificare se l'utente è un amministratore
if (isset($_SESSION["amministratore"]) && $_SESSION["amministratore"] == 1) {
    $bottone_login = '<form action="login.php" method="post"><input type="submit" id="loginn" name="logout" value="Logout"></form>';
    $utenti = '<li class=""><a href="areaadmin.php">Area Admin</a></li>';
    $bottone_utente = '';
} elseif (isset($_SESSION["login"])) {
    $username = $_SESSION['login'];
    $tipo = tipoUtente($username);
    $bottone_login = '<form action="login.php" method="post"><input type="submit" id="logout" name="logout" value="Logout"></form>';

    if ($tipo == 'reg') {
        $utenti = '<li class=""><a href="utenti.php">Area Privata</a></li>';
        $bottone_utente = '';
    } else {
        $utenti = '<li class=""><a href="areaadmin.php">Area Admin</a></li>';
        $bottone_utente = '';
    }

    if (isset($_POST['logout'])) {
        session_destroy();
        header("Location: login.php");
        exit();
    }
}

// Recupero i corsi a cui l'utente è iscritto
$corsi = recuperaCorsiUtente($_SESSION['login']);


// Recupero i dati dell'utente
$utenteData = recuperaUtente($_SESSION['login']);
$utente = new Utente(
    $utenteData[0]["nome"],
    $utenteData[0]["cognome"],
    $utenteData[0]["mail"],
    $utenteData[0]["username"],
    $utenteData[0]["password"]
);

// Creazione della tabella con i dati personali dell'utente
$tabella .= '<tr>
                <td>' . $utente->getNome() . '</td>
                <td>' . $utente->getCognome() . '</td>
                <td>' . $utente->getEmail() . '</td>
                <td>' . $utente->getUsername() . '</td>
                
              </tr>';

// Cambiamento username
if (isset($_POST['cambiaUser'])) {
    $nuovoUsername = $_POST['username'];

    if (empty($nuovoUsername)) {
        $message_us = 'Errore, campo mancante';
    } else {
        $esiste = false;
        foreach ($utenteData as $user) {
            if ($nuovoUsername == $user['username']) {
                $esiste = true;
                break;
            }
        }

        if ($esiste) {
            $message_us = '<p>Questo username è già utilizzato</p>';
        } else {
            $mail = $utente->getEmail();
            $res = CambiaUsername($nuovoUsername, $mail);

            if ($res) {
                $message_us = '<p>Cambiamento avvenuto con successo!</p>';
                $_SESSION["login"] = $nuovoUsername; // Aggiorna la sessione
                // Aggiorna i dati dell'utente
                $utenteData = recuperaUtente($nuovoUsername);
                $utente = new Utente(
                    $utenteData[0]["nome"],
                    $utenteData[0]["cognome"],
                    $utenteData[0]["mail"],
                    $utenteData[0]["username"],
                    $utenteData[0]["password"]
                );
                $tabella = '<tr>
                            <td>' . $utente->getNome() . '</td>
                            <td>' . $utente->getCognome() . '</td>
                            <td>' . $utente->getEmail() . '</td>
                            <td>' . $utente->getUsername() . '</td>
                            <td>' . $utente->getInfo()['password'] . '</td>
                          </tr>';
            } else {
                $message_us = "<p>Si è verificato un errore durante il cambiamento dell'utente. Verifica che i dati siano corretti.</p>";
            }
        }
    }
}

// Cambiamento password
if (isset($_POST['cambiaPsw'])) {
    $nuovaPsw = $_POST['psw'];
    $nuovaPsw1 = $_POST['psw1'];

    if (empty($nuovaPsw) || empty($nuovaPsw1)) {
        $message_psw = '<p>Per favore inserisci entrambe le password</p>';
    } elseif ($nuovaPsw != $nuovaPsw1) {
        $message_psw = '<strong><p>Le password non coincidono</p></strong>';
    } else {
        if ($nuovaPsw == $utente->getInfo()['password']) {
            $message_psw = '<p>La password non può essere uguale a quella precedentemente utilizzata</p>';
        } else {
            $cambiaPsw = cambiaPsw($utente->getUsername(), $nuovaPsw);
            if ($cambiaPsw) {
                $message_psw = '<strong><p>Cambiamento avvenuto con successo!</p></strong>';
                // Aggiorna i dati dell'utente
                $utenteData = recuperaUtente($_SESSION['login']);
                $utente = new Utente(
                    $utenteData[0]["nome"],
                    $utenteData[0]["cognome"],
                    $utenteData[0]["mail"],
                    $utenteData[0]["username"],
                    $nuovaPsw // Aggiorna solo la password
                );
                $tabella = '<tr>
                            <td>' . $utente->getNome() . '</td>
                            <td>' . $utente->getCognome() . '</td>
                            <td>' . $utente->getEmail() . '</td>
                            <td>' . $utente->getUsername() . '</td>
                            <td>' . $utente->getInfo()['password'] . '</td>
                          </tr>';
            } else {
                $message_psw = "<strong><p>Si è verificato un errore durante il cambiamento della password, riprova più tardi o contatta l'amministratore</p></strong>";
            }
        }
    }
}

// Cancellazione account
if (isset($_POST['cancella'])) {
    $username = $_SESSION['login'];

    if (cancellaUtente($username)) {
        session_destroy();
        header("Location: login.php");
        exit();
    } else {
        echo "Impossibile eliminare l'utente";
    }
}

// assistenza 

$richiesteAssistenza = []; // Inizializza l'array
$username = $_SESSION['login'];

try {
    $connessione = connessione_database();
    if (!$connessione) {
        die("Errore di connessione al database.");
    }
    // Connessione al database (assicurati che $connessione sia definito correttamente)
   

    // Prepara la query per ottenere le richieste di assistenza dell'utente
    $sql = "SELECT assistenza_richiesta, risposta FROM assistenza WHERE username_ass = ?";
    $stmt = $connessione->prepare($sql);
    $stmt->execute([$username]); // Esegui la query con il parametro

    // Ottieni i risultati in un array associativo
    $richiesteAssistenza = $stmt->fetchAll(PDO::FETCH_ASSOC);
    

} catch (PDOException $e) {
    // Gestione degli errori
    echo "Errore: " . $e->getMessage();
}



?>

<!DOCTYPE html>
<html lang="it" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta name="keywords" content="Web design, grafica, html, css" />
    <meta name="description" content="sito web di Francesco Lazzarotto" />
    <meta name="author" content="Francesco Lazzarotto" />
    <link rel="shortcut icon" href="immagini/logo.jpg" type="image/jpg">
    <title>Esame Lazzarotto Francesco (Sito Palestra)</title>
    <link rel="stylesheet" href="style.css" type="text/css">
    <style>
/* Stile generale del contenitore */

.contentitore {
    padding-top: 10%;
}
.account-container {
    max-width: 973px;
    margin: 20px auto;
    padding: 20px;
    border-radius: 8px;
    
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

.form-section {
    margin-bottom: 20px;
    padding: 15px;
    border: 1px solid #ddd;
    border-radius: 5px;
    background-color: #ffffff;
}

.form-section h3 {
    margin-bottom: 10px;
    color: #333;
}

label {
    display: block;
    margin-bottom: 5px;
    color: #555;
}

input[type="text"],
input[type="password"],
input[type="submit"] {
    width: 50%;
    padding: 10px;
    margin-top: 5px;
    margin-bottom: 15px;
    border: 2px solid #333;
    border-radius: 4px;
    font-size: 16px;
}


input[type="submit"] {
    background-color: #e74c3c;
    width: 20%;
    color: white;
    cursor: pointer;
    border: none !important;
    transition: background-color 0.3s ease;
}

input[type="submit"]:hover {
    background-color: #c0392b;
}

.feedback {
    color: #d9534f;
    font-size: 14px;
}



         #table-corsi {
            width: 60%;
            border-collapse: collapse;
            margin: 20px 0;
            margin-left: 20%;
            font-size: 18px;
            text-align: left;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            border-radius: 8px;
            overflow: hidden;
            padding-left: 20%;
            padding-right: 20%;
            margin-bottom: 5%;
        }

        /* Stile dell'intestazione */
        #table-corsi thead tr {
            background-color: #000;
            color: white;
        }
        
        #table-corsi th, #table-corsi td {
            padding: 12px 15px;
            border-bottom: 1px solid #ddd;
        }

        /* Stile delle righe del corpo della tabella */
        #table-corsi tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        #table-corsi tbody tr:hover {
            background-color: #f1f1f1;
        }

        /* Stile dei bottoni di azione */
        .cancella-corso {
            background-color: #e74c3c;
            color: white;
            border: none;
            padding: 8px 12px;
            cursor: pointer;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }

        .cancella-corso:hover {
            background-color: #c0392b;
        }
        .corsiutente {
            margin-left: 20%;
        }
    </style>
</head>

<body>
    <a name="inizio"></a>

  <nav id="navigation" class="menu1">
    <ul>
        <li><a href="homee.php">Home</a></li>
        <li><a href="corsi.php">Corsi</a></li>
        <li><a href="benefici.php">Benefici</a></li>
        <li><a href="contatti.php">Contatti</a></li>
      <?php echo $utenti; ?>
        <li id="logoutpos"><?php echo $bottone_login; ?></li>
        <li><?php echo $bottone_utente; ?></li>
    </ul>
</nav>

    <section>
        
        <div class="contenitore"> <br> <br> <br> <br> 
           <center> <h1>Welcome <?php echo $utente->getNome(); ?>!</h1>
            <h2>I tuoi dati:</h2> </center>
            <table id="table-corsi">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Cognome</th>
                        <th>Email</th>
                        <th>Username</th>
                        
                    </tr>
                </thead>
                <tbody>
                    <?php echo $tabella; ?>
                </tbody>
            </table>
       <center>  <h2>I tuoi corsi:</h2> </div> </center>
<table id="table-corsi">
    <thead>
        <tr>
            <th>Corso</th>
            <th>Descrizione</th>
            <th>Azioni</th>
        </tr>
    </thead>
    <div class="corsiutente">
    <tbody>
        <?php
        if (empty($corsi)) {
            echo '<tr><td colspan="3">Non sei iscritto a nessun corso.</td></tr>';
        } else {
        foreach ($corsi as $corso) {
            echo '<tr>
                <td>' . htmlspecialchars($corso['nome']) . '</td>
                <td>' . htmlspecialchars($corso['descrizione']) . '</td>
                <td><button class="cancella-corso" data-id="' . htmlspecialchars($corso['id_corso']) . '">Disiscriviti</button></td>
            </tr>';
        } }
        ?>
    </tbody>
</table>
</div> <center>
<h2>Le tue richieste di assistenza</h2> </center>

<table id="table-corsi">
    <thead>
        <tr>
            <th>Richiesta</th>
            <th>Risposta</th>
        </tr>
    </thead>
    <tbody>
        <?php
        
        if (empty($richiesteAssistenza)) {
            echo '<tr><td colspan="2">Non hai ancora inviato richieste di assistenza.</td></tr>';
        } else {
            foreach ($richiesteAssistenza as $richiesta) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($richiesta['assistenza_richiesta']) . '</td>';

                // Mostra la risposta se presente, altrimenti un messaggio di attesa
                if (!empty($richiesta['risposta'])) {
                    echo '<td>' . htmlspecialchars($richiesta['risposta']) . '</td>';
                } else {
                    echo '<td>In attesa di risposta. Si prega di ricontattare se necessario.</td>';
                }
                echo '</tr>';
            }
        }
        ?>
    </tbody>
</table>


    <!-- $richiesteAssistenza = [];
$username = $_SESSION['login'];

try {
    // Prepara la query con i parametri
    $sql = "SELECT assistenza_richiesta, risposta FROM assistenza WHERE username = ?";
    $stmt = $connessione->prepare($sql);

    // Esegui la query passando il parametro
    $stmt->execute([$username]);

    // Ottieni i risultati
    $richiesteAssistenza = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    // Gestisci eventuali errori
    echo "Errore: " . $e->getMessage();
}-->


<div class="account-container">
        <!-- Sezione per cambiare username -->
        <div class="form-section">
          <center>  <h2>Cambia Username</h2> 
            <form action="" method="post">
                <label for="username">Nuovo Username:</label>
                <input type="text" name="username" id="username" required> <br>
                <input type="submit" name="cambiaUser" value="Cambia">
            </form>
            <p class="feedback"><?php echo $message_us; ?></p> </center>
    </div>

    <!-- Sezione per cambiare password -->
    <div class="form-section">
        <center> <h2>Cambia Password</h2> 
        <form action="" method="post">
            <label for="psw">Nuova Password:</label>
            <input type="password" name="psw" id="psw" required>
            <label for="psw1">Conferma Password:</label>
            <input type="password" name="psw1" id="psw1" required> <br>
            <input type="submit" name="cambiaPsw" value="Cambia">
        </form>
        <p class="feedback"><?php echo $message_psw; ?></p> </center>
    </div>

    <!-- Sezione per cancellare account -->
    <div class="form-section">
       <center> <h2>Elimina Account</h2> </center> 
        <form action="" method="post">
          <center>  <input type="submit" name="cancella" value="Cancella Account"
                onclick="return confirm('Sei sicuro di voler cancellare il tuo account?');"> <center> 
        </form>
    </div>
</div>
    </section>




    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    
$(document).ready(function() {
    // Intercetta il clic sul pulsante "Disiscriviti" e acquisisce l'ID del corso
    $('.cancella-corso').on('click', function() {
        // Estrai l'ID del corso dall'attributo data-id del bottone cliccato
        const corsoId = $(this).data('id');
        
        console.log("ID del corso selezionato:", corsoId);  // Debug per verificare l'ID del corso

        // Controlla se l'ID del corso è undefined
        if (corsoId === undefined) {
            alert("Errore: l'ID del corso è undefined.");
            return;
        }

        // Invia la richiesta AJAX con l'ID del corso
        $.ajax({
            url: 'cancella_corso.php',
            method: 'POST',
            data: { id_corso: corsoId },  // Passa i dati in forma tradizionale
            success: function(response) {
                console.log("Risposta del server:", response);  // Debug per verificare la risposta
                if (typeof response === 'string') {
                    try {
                        const result = JSON.parse(response); // Parsing della risposta JSON solo se è una stringa
                        handleResponse(result);
                    } catch (e) {
                        console.error("Errore durante il parsing della risposta:", e);
                        alert("Si è verificato un errore durante il caricamento della risposta.");
                    }
                } else {
                    // Se la risposta è già un oggetto
                    handleResponse(response);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('Errore nella richiesta AJAX:', textStatus, errorThrown);
                console.log('Codice di stato:', jqXHR.status);  // Aggiunto per vedere il codice di stato
                console.log('Risposta del server:', jqXHR.responseText); // Stampa la risposta del server in caso di errore
                alert('Si è verificato un errore nella richiesta. Riprova più tardi.');
            }
        });
    });
});

// Funzione per gestire la risposta dal server
function handleResponse(result) {
    if (result.success) {
        alert('Corso cancellato con successo');
        location.reload();  // Ricarica la pagina per aggiornare la lista dei corsi
    } else {
        alert(result.message);
    }
}



</script>



</body>

</html>