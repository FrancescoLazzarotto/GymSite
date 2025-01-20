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
        }

        /* Stile dell'intestazione */
        #table-corsi thead tr {
            background-color: #000;
            color: white;
        }

        #table-corsi th,
        #table-corsi td {
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

           
        <div class="account-container">
            <!-- Sezione per cambiare username -->
            <div class="form-section">
                <center>
                    <h2>Cambia Username</h2>
                    <form action="" method="post">
                        <label for="username">Nuovo Username:</label>
                        <input type="text" name="username" id="username" required> <br>
                        <input type="submit" name="cambiaUser" value="Cambia">
                    </form>
                    <p class="feedback"><?php echo $message_us; ?></p>
                </center>
            </div>

            <!-- Sezione per cambiare password -->
            <div class="form-section">
                <center>
                    <h2>Cambia Password</h2>
                    <form action="" method="post">
                        <label for="psw">Nuova Password:</label>
                        <input type="password" name="psw" id="psw" required>
                        <label for="psw1">Conferma Password:</label>
                        <input type="password" name="psw1" id="psw1" required> <br>
                        <input type="submit" name="cambiaPsw" value="Cambia">
                    </form>
                    <p class="feedback"><?php echo $message_psw; ?></p>
                </center>
            </div>

            <!-- Sezione per cancellare account -->
            <div class="form-section">
                <center>
                    <h2>Elimina Account</h2>
                </center>
                <form action="" method="post">
                    <center> <input type="submit" name="cancella" value="Cancella Account"
                            onclick="return confirm('Sei sicuro di voler cancellare il tuo account?');">
                        <center>
                </form>
            </div>
        </div>
    </section>



</body>

</html>