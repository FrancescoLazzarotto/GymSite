<?php
session_start();
include 'funzioni.php';

// Variabili di interfaccia
$tabella = '';
$message_us = '';
$bottone_login = '<a id="login" href="login.php">Login</a>';
$utenti = '';
$bottone_utente = '<a class="loginn" href="registrazione.php">Registrati</a>';

// Controllo se l'utente è un amministratore
if (isset($_SESSION["amministratore"]) && $_SESSION["amministratore"] == 1) {
    $bottone_login = '<form action="login.php" method="post"><input type="submit" id="loginn" name="logout" value="Logout"></form>';
    $utenti = '<li><a href="areaadmin.php">Area Admin</a></li>';
    $bottone_utente = '';
}

// Recupero lista degli utenti iscritti e dei loro corsi
$utenti_corsi = utentiIscritti(); // Funzione che ritorna un array con utenti e corsi associati

// Creazione tabella degli utenti iscritti e corsi
foreach ($utenti_corsi as $utente) {
    $tabella .= '<tr>
                    <td>' . $utente['nome_utente'] . '</td>
                    <td>' . $utente['mail'] . '</td>
                    <td>' . $utente['corso_nome'] . '</td>
                    <td>' . $utente['corso_orario'] . '</td>
                 </tr>';
}
?>

<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="utf-8">
    <title>Utenti Iscritti - Admin</title>
    <link rel="stylesheet" href="style.css" type="text/css">
    <style>
        #table {
            width: 70%;
            border-collapse: collapse;
            margin: 20px 0;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }

        #table th,
        #table td {
            padding: 12px;
            text-align: center;
            border: 1px solid #333;
            background-color: #f5f5f5;
        }

        #table th {
            background-color: #d3d3d3;
            font-weight: bold;
        }

        #table tr:nth-child(even) {
            background-color: #e0e0e0;
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
        <center> <br> <br> <br>
            <h1>Utenti Iscritti e Corsi</h1>
            <table id="table">
                <tr>
                    <th>Nome Utente</th>
                    <th>Email</th>
                    <th>Corso</th>
                    <th>Orario</th>
                </tr>
                <?php echo $tabella; ?>
            </table>
            <?php echo $message_us; ?>
        </center>
    </div>

    <footer>
        <a href="#inizio">
            <div id="tornasu">Torna su</div>
        </a>
    </footer>
</body>

</html>