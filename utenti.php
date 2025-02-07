<?php
session_start();
include 'includes/funzioni.php';
include_once 'class/utente.php';


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
    //se il campo username non viene riempito
    if (empty($nuovoUsername)) {
        $message_us = 'Errore, campo mancante';
    } else {
        $esiste = false;
        // controllo se il nuovo username esiste
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
    //se i campi password non sono riempiti
    if (empty($nuovaPsw) || empty($nuovaPsw1)) {
        $message_psw = '<p>Per favore inserisci entrambe le password</p>';
    // se le password non coincidono
    } elseif ($nuovaPsw != $nuovaPsw1) {
        $message_psw = '<strong><p>Le password non coincidono</p></strong>';
    } else {
        // se la password coincide con quella precedente
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
/*
if (isset($_GET['id']))  {
    $corso_id = $_GET['id'];
    $connessione = connessione_database();

    try {
        $query = "SELECT * FROM Corso WHERE id_Corso = :corso_id";
        $stmt = $connessione->prepare($query);
        $stmt->bindParam(':corso_id', $corso_id, PDO::PARAM_INT);
        $stmt->execute();

        // Se ci sono risultati, estrae i dati del corso
        if ($stmt->rowCount() > 0) {
            $corso = $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            echo "Corso non trovato.";
        }
    } catch (PDOException $e) {
        echo "Errore nella query: " . $e->getMessage();
    } finally {
        $connessione = null; 
    }
} else {
    echo "ID del corso non specificato.";
}

*/
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
    <link rel="stylesheet" href="assets/style.css" type="text/css">
    <style>


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

        #table-corsi tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        #table-corsi tbody tr:hover {
            background-color: #f1f1f1;
        }

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
        .modal {
    display: none; 
    position: fixed;
    z-index: 9999; 
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.6);
    overflow: auto;
    padding-top: 50px; 
    transition: opacity 0.3s ease; 
}


.modal-content {
    background-color: #fff;
    margin: auto;
    margin-top: 10%;
    padding: 30px;
    border-radius: 10px;
    width: 90%; 
    max-width: 500px; 
    text-align: center;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1); 
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    font-size: 16px;
    line-height: 1.5;
    animation: fadeIn 0.5s ease-out; 
}


.close {
    color: #333;
    font-size: 30px;
    font-weight: bold;
    position: relative;
    right: -250px;
    top: 20px;
    cursor: pointer;
    transition: color 0.3s ease;
}

.close:hover {
    color: #e74c3c; 
}


.success {
    color: #155724;
    background-color: #d4edda;
    padding: 15px;
    border-radius: 5px;
    font-size: 18px;
    text-align: center;
    font-weight: bold;
}

.error {
    color: #721c24;
    background-color: #f8d7da;
    padding: 15px;
    border-radius: 5px;
    font-size: 18px;
    text-align: center;
    font-weight: bold;
}



#modal-message {
    font-size: 18px;
    font-weight: bold;
    margin-bottom: 40px;
}


@keyframes fadeIn {
    0% {
        opacity: 0;
        transform: translateY(-20px);
    }
    100% {
        opacity: 1;
        transform: translateY(0);
    }
}


.modal-message-box {
    display: inline-block;
    background-color: #f0f0f0;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
    width: 80%;
    max-width: 500px;
}


@media (max-width: 600px) {
    .modal-content {
        width: 90%;
        padding: 20px;
    }
    .close {
        font-size: 24px;
    }
    #modal-message {
        font-size: 16px;
    }
}

    </style>
</head>

<body>
    <a name="inizio"></a>

  <nav id="navigation" class="menu1">
    <ul>
          <li><a href="index.php">Home </a></li>
                        <li><a href="corsi.php"> Corsi</a></li>
                      <li> <a href="benefici.php"> Benefici </a> </li>   
                        <li><a href="contatti.php">Contatti </a></li>
         <?php echo $utenti ?>
        <li id="logoutpos">
            <?php echo $bottone_login ?>
        </li>
        <li>
            <?php echo $bottone_utente ?>
        </li>
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
             <div id="modal" class="modal">
    <div class="modal-content">
        <span id="close-modal" class="close">&times;</span>
        <p id="modal-message"></p>
    </div>
</div>
       <center>  <h2>I tuoi corsi:</h2> </div> </center>
<table id="table-corsi">
    <thead>
        <tr>
            <th>Corso</th>
            <th>Descrizione</th>
            
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




</body>

</html>