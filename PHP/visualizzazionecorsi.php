<?php
session_start();
include 'funzioni.php';

// Variabili per la gestione dell'interfaccia e dei messaggi
$tabella = '';
$mail = '';
$message_c = '';
$bottone_login = '<a id="login" href="login.php">Login</a>';
$utenti = '';
$bottone_utente = '<a class="loginn" href="registrazione.php">Registrati</a>';

// Condizione per verificare se l'utente è amministratore
if (isset($_SESSION["amministratore"]) && $_SESSION["amministratore"] == 1) {
    $bottone_login = '<form action="login.php" method="post"><input type="submit" id="loginn" name="logout" value="Logout"></form>';
    $utenti = '<li><a href="areaadmin.php">Area Admin</a></li>';
    $bottone_utente = '';
} elseif (isset($_SESSION["login"])) {
    $username = $_SESSION['login'];
    $tipo = tipoUtente($username);
    $bottone_login = '<form action="login.php" method="post"><input type="submit" id="logout" name="logout" value="Logout"></form>';
    $utenti = $tipo == 'reg' ? '<li><a href="utenti.php">Area Privata</a></li>' : '<li><a href="areaadmin.php">Area Admin</a></li>';
    $bottone_utente = '';
    if (isset($_POST['logout'])) {
        session_destroy();
        $bottone_login = '<a id="login" href="login.php">Login</a>';
        $utenti = '';
    }
}

// Se amministratore, recupera lista corsi
if (isset($_SESSION["amministratore"]) && $_SESSION["amministratore"] == 1) {
    $corsi = listaAdmin();
}

// Cancellazione corso
// Cancellazione corso
if (isset($_POST['cancella']) && isset($_POST['id_corso'])) {
    $id_corso = $_POST['id_corso'];
    $risultato = cancellaCorso($id_corso);
    $message_c = $risultato ? '<p>Corso cancellato con successo</p>' : '<p>Errore nella cancellazione</p>';
    $corsi = listaAdmin(); // Aggiorna lista
}


// Generazione tabella corsi
foreach ($corsi as $corso) {
    $tabella .= '<tr>
                    <form action="" method="post">
                        
                        <td>' . $corso['nome'] . '</td>
                        <td>' . $corso['tipologia'] . '</td>
                        <td>' . $corso['partecipanti'] . '</td>
                        <td>' . $corso['orario'] . '</td>
                        <td><a class="detad" href="paginacorsiadmin.php?id=' . $corso['id_corso'] . '">Dettagli</a></td>
                        <td><input type="submit" value="Cancella" name="cancella" /></td>
                    </form>
                </tr>';
}

?>

<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="utf-8">
    <title>Gestione Corsi - Admin</title>
    <link rel="stylesheet" href="style.css" type="text/css">
    <style>
        #table {
            width: 48%;
            border-collapse: collapse;
            margin: 20px 0;
            border-radius: 25px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }

        #table th,
        #table td {
            padding: 10px;
            text-align: center;
            border: 1px solid #000;
            color: #000;
            background-color: #f5f5f5;
        }

        #table th {
            font-weight: bold;
            background-color: #d3d3d3;
            color: #000;
        }

        #table tr:nth-child(even) {
            background-color: #e0e0e0;
        }

        #table tr:hover {
            background-color: #f0f0f0;
        }

        #table td {
            font-weight: bold;
            color: #333;

        }
    </style>
</head>

<body>
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

    <div id="content1">
        <center>
            <br> <br> <br>
            <h1>Gestione Corsi</h1>
            <?php echo $message_c; ?>
            <form action="" method="post">
                <table id="table">
                    <tr>
                        
                        <th>Nome</th>
                        <th>Tipologia</th>
                        <th>Partecipanti</th>
                        <th>Orario</th>
                        <th>Dettagli</th>
                        <th>Elimina</th>
                    </tr>
                    <?php echo $tabella; ?>
                </table>
                
                
            </form>
        </center>
    </div>
    <footer>
        <a href="#inizio">
            <div id="tornasu">Torna su</div>
        </a>
    </footer>
</body>

</html>